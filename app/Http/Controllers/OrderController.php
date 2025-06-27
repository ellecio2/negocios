<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V2\Delivery\PedidosYaController;
use App\Mail\InvoiceEmailManager;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CategoryTranslation;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\DeliveryHistory;
use App\Models\DeliveryService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use App\Models\ShippingCost;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Services\CommissionService;
use App\Utility\NotificationUtility;
use App\Utility\SmsUtility;
use Auth;
use CoreComponentRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Mail;
use Session;

class OrderController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy', 'bulk_order_delete');
    }

    // All Orders
    public function all_orders(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';

        $orders = Order::orderBy('id', 'desc');
        $admin_user_id = User::where('user_type', 'admin')->first()->id;


        if (
            Route::currentRouteName() == 'inhouse_orders.index' &&
            Auth::user()->can('view_inhouse_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        } else if (
            Route::currentRouteName() == 'seller_orders.index' &&
            Auth::user()->can('view_seller_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        } else if (
            Route::currentRouteName() == 'pick_up_point.index' &&
            Auth::user()->can('view_pickup_point_orders')
        ) {
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (
                Auth::user()->user_type == 'staff' &&
                Auth::user()->staff->pick_up_point != null
            ) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        } else if (
            Route::currentRouteName() == 'all_orders.index' &&
            Auth::user()->can('view_all_orders')
        ) {
        } else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();

        $order->viewed = 1;
        $order->save();
        return view('backend.sales.show', compact('order', 'delivery_boys'));
    }
    public function store(Request $request) {
        // Search all cart products from the user
        $carts = Cart::where('user_id', Auth::id())->get();
        // Get Delivery address from the cart
        $address = Address::where('id', $carts[0]['address_id'])->first();
    
        if ($address != null) {
            $shippingAddress['name'] = Auth::user()->name;
            $shippingAddress['email'] = Auth::user()->email;
            $shippingAddress['address'] = $address->address;
            $shippingAddress['country'] = $address->country;
            $shippingAddress['state'] = $address->state;
            $shippingAddress['city'] = $address->city;
            $shippingAddress['postal_code'] = $address->postalCode;
            $shippingAddress['phone'] = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }
    
        $combined_order = CombinedOrder::create([
            'user_id' => Auth::id(),
            'shipping_address' => json_encode($shippingAddress),
        ]);
    
        $carts_by_seller_id = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->product->user_id;
            });
    
    
        foreach ($carts_by_seller_id as $carts) {
            $order = Order::make([
                'combined_order_id' => $combined_order->id,
                'user_id' => Auth::id(),
            ]);
    
            foreach ($carts as $cart) {
                $order->shop_id = $cart->product->user->shop->id;
                $user = User::find($cart->product->user->id);
                $order->category_translation_id = CategoryTranslation::find($user->category_translation_id)->id;
            }
    
            $order->shipping_address = $combined_order->shipping_address;
            $order->additional_info = $request->additional_info;
            $order->payment_type = $request->payment_option;
    
            $order->save();
    
            $subtotal = 0;
            $shipping = 0;
            $coupon_discount = 0;
    
            // Agrupar carritos por tipo de envío
            $groupedCarts = $carts->groupBy('shipping_type');
    
            foreach ($groupedCarts as $shippingType => $cartsGroup) {
                // Intentar obtener el primer carrito y su estimación de envío
                $firstCart = $cartsGroup->first();
                $estimateShipping = $firstCart ? $firstCart->deliveryEstimate()->where('name', $shippingType)->first() : null;
                Log::info('Procesando carrito por tipo de envío', [
                    'shippingType' => $shippingType,
                    'hasEstimateShipping' => $estimateShipping ? true : false
                ]);
                
                // Variable para almacenar el servicio de entrega
                $deliveryService = null;
                
                if ($shippingType == 'PEDIDOS YA') {
                    if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
                        $json = json_decode($estimateShipping->delivery_info, true);
                        Log::info('Información de envío PedidosYa', [
                            'delivery_info' => $json,
                            'order_id' => $order->id,
                            'payment_type' => $request->payment_option
                        ]);
                
                        if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
                            $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
                            $estimateId = $json['estimateId'];
                
                            // Solo enviar a Pedidos Ya si NO es transferencia bancaria
                            if ($request->payment_option !== 'Transferencia Bancaria') {
                                try {
                                    $response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);
                                    
                                    Log::info('Respuesta de PedidosYa confirmShipping', [
                                        'response' => $response,
                                        'order_id' => $order->id
                                    ]);
                
                                    $deliveryService = DeliveryService::create([
                                        'delivery_company' => $shippingType,
                                        'py_info' => json_encode($response)
                                    ]);
                
                                    Log::info('DeliveryService creado', [
                                        'delivery_service_id' => $deliveryService->id,
                                        'order_id' => $order->id
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Error al confirmar envío con PedidosYa', [
                                        'error' => $e->getMessage(),
                                        'order_id' => $order->id
                                    ]);
                                }
                            } else {
                                Log::info('Pedido pendiente de pago - No se envía a PedidosYa', [
                                    'order_id' => $order->id,
                                    'payment_type' => $request->payment_option
                                ]);
                
                                // Crear DeliveryService con estado pendiente y guardar los datos necesarios para envío posterior
                                $pendingData = [
                                    'status' => 'pending_payment',
                                    'order_id' => $order->id,
                                    'deliveryOfferId' => $deliveryOfferId,
                                    'estimateId' => $estimateId
                                ];
                                
                                $deliveryService = DeliveryService::create([
                                    'delivery_company' => $shippingType,
                                    'py_info' => json_encode($pendingData)
                                ]);
                            }
                        } else {
                            Log::error('Datos incompletos en delivery_info', [
                                'json' => $json,
                                'order_id' => $order->id
                            ]);
                        }
                    }
                }
    
                //Order Details Storing
                foreach ($cartsGroup as $cart) {
                    $product = $cart->product;
                    $subtotal += cart_product_price($cart, $product, false, false) * $cart['quantity'];
                    $coupon_discount += $cart['discount'];
                    $product_variation = $cart['variation'];
                    $product_stock = $product->stocks->first();
                    if ($product->digital != 1 && $cart['quantity'] > $product_stock->qty) {
                        flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                        $order->delete();
                        return redirect()->route('cart')->send();
                    } elseif ($product->digital != 1) {
                        $product_stock->qty -= $cart['quantity'];
                        $product_stock->save();
                    }
    
                    $order_detail = OrderDetail::make([
                        'order_id' => $order->id,
                        'seller_id' => $product->user_id,
                        'product_id' => $product->id,
                        'variation' => $product_variation,
                        'price' => cart_product_price($cart, $product, false, false) * $cart['quantity'],
                        'tax' => cart_product_tax($cart, $product, false) * $cart['quantity'],
                        'shipping_type' => $cart['shipping_type'],
                        'product_referral_code' => $cart['product_referral_code'],
                        'shipping_cost' => $cart['shipping_cost'],
                        'quantity' => $cart['quantity'],
                        'delivery_service_id' => isset($deliveryService) ? $deliveryService->id : null,
                    ]);
    
                    $shipping = $order_detail->shipping_cost;
    
                    if (addon_is_activated('club_point')) {
                        $order_detail->earn_point = $product->earn_point;
                    }
    
                    $order_detail->save();
    
                    $product->update([
                        'num_of_sale' => DB::raw('num_of_sale + ' . $cart['quantity'])
                    ]);
    
                    $order->seller_id = $product->user_id;
                    $order->shipping_type = $cart['shipping_type'];
    
                    if ($cart['shipping_type'] == 'pickup_point') {
                        $order->pickup_point_id = $cart['pickup_point'];
                    }
    
                    if ($cart['shipping_type'] == 'carrier') {
                        $order->carrier_id = $cart['carrier_id'];
                    }
    
                    if ($product->added_by == 'seller' && $product->user->seller != null) {
                        $seller = $product->user->seller;
                        $seller->num_of_sale += $cart['quantity'];
                        $seller->save();
                    }
    
                    if (addon_is_activated('affiliate_system')) {
                        if ($order_detail->product_referral_code) {
                            $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();
    
                            $affiliateController = new AffiliateController;
                            $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                        }
                    }
                }
            }
            if ($shippingType == 'PEDIDOS YA' && isset($json)) {
                $shipping_cost_id = PedidosYaController::getEstimatedShipping($json) ?? '';
                $order->shipping_cost_id = $shipping_cost_id;
            }
    
            CommissionService::create($order, $subtotal);
    
            $tax = $subtotal * (config('app.itbis') / 100);
    
            $order->grand_total = $subtotal + $tax + $shipping;
    
            if (isset($seller_product[0]) && $seller_product[0]->coupon_code != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;
    
                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }
    
            $combined_order->grand_total += $order->grand_total;
    
            $order->save();
        }
    
        $combined_order->save();
    
        $request->session()->put('combined_order_id', $combined_order->id);
        return $combined_order; 
    }
    // public function store(Request $request)
    // {
    //     // Search all cart products from the user
    //     $carts = Cart::where('user_id', Auth::id())->get();

    //     // Get Delivery address from the cart
    //     $address = Address::where('id', $carts[0]['address_id'])->first();

    //     if ($address != null) {
    //         $shippingAddress['name'] = Auth::user()->name;
    //         $shippingAddress['email'] = Auth::user()->email;
    //         $shippingAddress['address'] = $address->address;
    //         $shippingAddress['country'] = $address->country;
    //         $shippingAddress['state'] = $address->state;
    //         $shippingAddress['city'] = $address->city;
    //         $shippingAddress['postal_code'] = $address->postalCode;
    //         $shippingAddress['phone'] = $address->phone;
    //         if ($address->latitude || $address->longitude) {
    //             $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
    //         }
    //     }

    //     $combined_order = CombinedOrder::create([
    //         'user_id' => Auth::id(),
    //         'shipping_address' => json_encode($shippingAddress),
    //     ]);

    //     $carts_by_seller_id = Cart::where('user_id', Auth::id())
    //         ->with('product')
    //         ->get()
    //         ->groupBy(function ($cart) {
    //             return $cart->product->user_id;
    //         });


    //     foreach ($carts_by_seller_id as $carts) {
    //         $order = Order::make([
    //             'combined_order_id' => $combined_order->id,
    //             'user_id' => Auth::id(),
    //         ]);

    //         foreach ($carts as $cart) {
    //             $order->shop_id = $cart->product->user->shop->id;
    //             $user = User::find($cart->product->user->id);
    //             $order->category_translation_id = CategoryTranslation::find($user->category_translation_id)->id;
    //         }

    //         $order->shipping_address = $combined_order->shipping_address;
    //         $order->additional_info = $request->additional_info;
    //         $order->payment_type = $request->payment_option;

    //         $order->save();

    //         $subtotal = 0;
    //         $shipping = 0;
    //         $coupon_discount = 0;

    //         // Agrupar carritos por tipo de envío
    //         $groupedCarts = $carts->groupBy('shipping_type');

    //         foreach ($groupedCarts as $shippingType => $cartsGroup) {
    //             // Intentar obtener el primer carrito y su estimación de envío
    //             $firstCart = $cartsGroup->first();
    //             $estimateShipping = $firstCart ? $firstCart->deliveryEstimate()->where('name', $shippingType)->first() : null;
    //             Log::info('Procesando carrito por tipo de envío', [
    //                 'shippingType' => $shippingType,
    //                 'hasEstimateShipping' => $estimateShipping ? true : false
    //             ]);
    //             if ($shippingType == 'PEDIDOS YA') {
    //                 if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
    //                     $json = json_decode($estimateShipping->delivery_info, true);
    //                     Log::info('Información de envío PedidosYa', [
    //                         'delivery_info' => $json,
    //                         'order_id' => $order->id,
    //                         'payment_type' => $request->payment_option
    //                     ]);

    //                     if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
    //                         $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
    //                         $estimateId = $json['estimateId'];
    //                         // Guardar en sesión para uso posterior
    //                         Session::put('deliveryOfferId', $deliveryOfferId);
    //                         Session::put('estimateId', $estimateId);
    //                         Session::put('shippingType', $shippingType);


    //                         // Si es transferencia bancaria, solo guardar los datos
    //                         if ($request->payment_option === 'Transferencia Bancaria') {
    //                             Log::info('Guardando información para envío posterior a PedidosYa', [
    //                                 'order_id' => $order->id,
    //                                 'payment_type' => $request->payment_option
    //                             ]);

    //                             // Guardar los datos para uso posterior
    //                             ShippingCost::create([
    //                                 'cart_id' => $firstCart->id,
    //                                 'shipping_company' => 'PEDIDOS YA',
    //                                 'shipping_id' => $estimateId,
    //                                 'delivery_offer_id' => $deliveryOfferId
    //                             ]);
    //                         } else {
    //                             // Para otros métodos de pago, enviar inmediatamente
    //                             try {
    //                                 $response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);

    //                                 Log::info('Respuesta de PedidosYa confirmShipping', [
    //                                     'response' => $response,
    //                                     'order_id' => $order->id
    //                                 ]);

    //                                 $deliveryService = DeliveryService::create([
    //                                     'delivery_company' => $shippingType,
    //                                     'py_info' => json_encode($response)
    //                                 ]);
    //                             } catch (\Exception $e) {
    //                                 Log::error('Error al confirmar envío con PedidosYa', [
    //                                     'error' => $e->getMessage(),
    //                                     'order_id' => $order->id
    //                                 ]);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //             // if ($shippingType == 'PEDIDOS YA') {
    //             //     if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
    //             //         $json = json_decode($estimateShipping->delivery_info, true);
    //             //         Log::info('Información de envío PedidosYa', [
    //             //             'delivery_info' => $json,
    //             //             'order_id' => $order->id,
    //             //             'payment_type' => $request->payment_option
    //             //         ]);

    //             //         if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
    //             //             $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
    //             //             $estimateId = $json['estimateId'];

    //             //             // Guardar en sesión para uso posterior
    //             //             Session::put('deliveryOfferId', $deliveryOfferId);
    //             //             Session::put('estimateId', $estimateId);
    //             //             Session::put('shippingType', $shippingType);

    //             //             // Solo enviar a Pedidos Ya si NO es transferencia bancaria
    //             //             if ($request->payment_option !== 'Transferencia Bancaria') {
    //             //                 try {
    //             //                     $response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);

    //             //                     Log::info('Respuesta de PedidosYa confirmShipping', [
    //             //                         'response' => $response,
    //             //                         'order_id' => $order->id
    //             //                     ]);

    //             //                     $deliveryService = DeliveryService::create([
    //             //                         'delivery_company' => $shippingType,
    //             //                         'py_info' => json_encode($response)
    //             //                     ]);

    //             //                     Log::info('DeliveryService creado', [
    //             //                         'delivery_service_id' => $deliveryService->id,
    //             //                         'order_id' => $order->id
    //             //                     ]);
    //             //                 } catch (\Exception $e) {
    //             //                     Log::error('Error al confirmar envío con PedidosYa', [
    //             //                         'error' => $e->getMessage(),
    //             //                         'order_id' => $order->id
    //             //                     ]);
    //             //                 }
    //             //             } else {
    //             //                 Log::info('Pedido pendiente de pago - No se envía a PedidosYa', [
    //             //                     'order_id' => $order->id,
    //             //                     'payment_type' => $request->payment_option
    //             //                 ]);

    //             //                 // Crear DeliveryService con estado pendiente
    //             //                 $deliveryService = DeliveryService::create([
    //             //                     'delivery_company' => $shippingType,
    //             //                     'py_info' => json_encode([
    //             //                         'status' => 'pending_payment',
    //             //                         'message' => 'Pendiente de confirmación de pago'
    //             //                     ])
    //             //                 ]);
    //             //             }
    //             //         } else {
    //             //             Log::error('Datos incompletos en delivery_info', [
    //             //                 'json' => $json,
    //             //                 'order_id' => $order->id
    //             //             ]);
    //             //         }
    //             //     }
    //             // }

    //             //Order Details Storing
    //             foreach ($cartsGroup as $cart) {
    //                 $product = $cart->product;
    //                 $subtotal += cart_product_price($cart, $product, false, false) * $cart['quantity'];
    //                 $coupon_discount += $cart['discount'];
    //                 $product_variation = $cart['variation'];
    //                 $product_stock = $product->stocks->first();
    //                 if ($product->digital != 1 && $cart['quantity'] > $product_stock->qty) {
    //                     flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
    //                     $order->delete();
    //                     return redirect()->route('cart')->send();
    //                 } elseif ($product->digital != 1) {
    //                     $product_stock->qty -= $cart['quantity'];
    //                     $product_stock->save();
    //                 }

    //                 $order_detail = OrderDetail::make([
    //                     'order_id' => $order->id,
    //                     'seller_id' => $product->user_id,
    //                     'product_id' => $product->id,
    //                     'variation' => $product_variation,
    //                     'price' => cart_product_price($cart, $product, false, false) * $cart['quantity'],
    //                     'tax' => cart_product_tax($cart, $product, false) * $cart['quantity'],
    //                     'shipping_type' => $cart['shipping_type'],
    //                     'product_referral_code' => $cart['product_referral_code'],
    //                     'shipping_cost' => $cart['shipping_cost'],
    //                     'quantity' => $cart['quantity'],
    //                     'delivery_service_id' => isset($deliveryService) ? $deliveryService->id : null,
    //                 ]);

    //                 $shipping = $order_detail->shipping_cost;

    //                 if (addon_is_activated('club_point')) {
    //                     $order_detail->earn_point = $product->earn_point;
    //                 }

    //                 $order_detail->save();

    //                 $product->update([
    //                     'num_of_sale' => DB::raw('num_of_sale + ' . $cart['quantity'])
    //                 ]);

    //                 $order->seller_id = $product->user_id;
    //                 $order->shipping_type = $cart['shipping_type'];

    //                 if ($cart['shipping_type'] == 'pickup_point') {
    //                     $order->pickup_point_id = $cart['pickup_point'];
    //                 }

    //                 if ($cart['shipping_type'] == 'carrier') {
    //                     $order->carrier_id = $cart['carrier_id'];
    //                 }

    //                 if ($product->added_by == 'seller' && $product->user->seller != null) {
    //                     $seller = $product->user->seller;
    //                     $seller->num_of_sale += $cart['quantity'];
    //                     $seller->save();
    //                 }

    //                 if (addon_is_activated('affiliate_system')) {
    //                     if ($order_detail->product_referral_code) {
    //                         $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

    //                         $affiliateController = new AffiliateController;
    //                         $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
    //                     }
    //                 }
    //             }
    //         }
    //         if ($shippingType == 'PEDIDOS YA') {
    //             $shipping_cost_id = PedidosYaController::getEstimatedShipping($json) ?? '';
    //             $order->shipping_cost_id = $shipping_cost_id;
    //         }

    //         CommissionService::create($order, $subtotal);

    //         $tax = $subtotal * (config('app.itbis') / 100);

    //         $order->grand_total = $subtotal + $tax + $shipping;

    //         // dd($seller_product);

    //         // if ($seller_product[0]->coupon_code != null) {
    //         if (isset($seller_product[0]) && $seller_product[0]->coupon_code != null) {
    //             $order->coupon_discount = $coupon_discount;
    //             $order->grand_total -= $coupon_discount;

    //             $coupon_usage = new CouponUsage;
    //             $coupon_usage->user_id = Auth::user()->id;
    //             $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
    //             $coupon_usage->save();
    //         }

    //         $combined_order->grand_total += $order->grand_total;

    //         $order->save();
    //     }

    //     $combined_order->save();
    //     //log::info('order id: ' . $combined_order->id);

    //     $request->session()->put('combined_order_id', $combined_order->id);
    // }
    // public function store(Request $request)
    // {
    //     // Search all cart products from the user
    //     $carts = Cart::where('user_id', Auth::id())->get();

    //     // Get Delivery address from the cart
    //     $address = Address::where('id', $carts[0]['address_id'])->first();

    //     if ($address != null) {
    //         $shippingAddress['name'] = Auth::user()->name;
    //         $shippingAddress['email'] = Auth::user()->email;
    //         $shippingAddress['address'] = $address->address;
    //         $shippingAddress['country'] = $address->country;
    //         $shippingAddress['state'] = $address->state;
    //         $shippingAddress['city'] = $address->city;
    //         $shippingAddress['postal_code'] = $address->postalCode;
    //         $shippingAddress['phone'] = $address->phone;
    //         if ($address->latitude || $address->longitude) {
    //             $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
    //         }
    //     }

    //     $combined_order = CombinedOrder::create([
    //         'user_id' => Auth::id(),
    //         'shipping_address' => json_encode($shippingAddress),
    //     ]);

    //     $carts_by_seller_id = Cart::where('user_id', Auth::id())
    //         ->with('product')
    //         ->get()
    //         ->groupBy(function ($cart) {
    //             return $cart->product->user_id;
    //         });


    //     foreach ($carts_by_seller_id as $carts) {
    //         $order = Order::make([
    //             'combined_order_id' => $combined_order->id,
    //             'user_id' => Auth::id(),
    //         ]);

    //         foreach ($carts as $cart) {
    //             $order->shop_id = $cart->product->user->shop->id;
    //             $user = User::find($cart->product->user->id);
    //             $order->category_translation_id = CategoryTranslation::find($user->category_translation_id)->id;
    //         }

    //         $order->shipping_address = $combined_order->shipping_address;
    //         $order->additional_info = $request->additional_info;
    //         $order->payment_type = $request->payment_option;

    //         $order->save();

    //         $subtotal = 0;
    //         $shipping = 0;
    //         $coupon_discount = 0;

    //         // Agrupar carritos por tipo de envío
    //         $groupedCarts = $carts->groupBy('shipping_type');

    //         foreach ($groupedCarts as $shippingType => $cartsGroup) {
    //             // Intentar obtener el primer carrito y su estimación de envío
    //             $firstCart = $cartsGroup->first();
    //             $estimateShipping = $firstCart ? $firstCart->deliveryEstimate()->where('name', $shippingType)->first() : null;

    //             if ($shippingType == 'PEDIDOS YA') {
    //                 // Verificar si se encontró la estimación de envío y si contiene la información necesaria
    //                 if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
    //                     $json = json_decode($estimateShipping->delivery_info, true);

    //                     // Verificar si la decodificación fue exitosa y si los datos necesarios están presentes
    //                     if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
    //                         $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
    //                         $estimateId = $json['estimateId'];

    //                         //log::info('Estimación de envío1: ' . print_r($json, true));

    //                         Session::put('deliveryOfferId', $deliveryOfferId);
    //                         Session::put('estimateId', $estimateId);
    //                         Session::put('shippingType', $shippingType);

    //                         // Confirmar el envío con PedidosYa y crear el registro de DeliveryService
    //                         //$response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);
    //                         /*$deliveryService = DeliveryService::create([
    //                             'delivery_company' => $shippingType,
    //                             'py_info' => json_encode($json)
    //                         ]);*/

    //                         //log::info('response confirmShipping: ' . json_encode($response));
    //                         /*if ($response) {
    //                             $deliveryService = DeliveryService::create([
    //                                 'delivery_company' => $shippingType,
    //                                 'py_info' => json_encode($response)
    //                             ]);
    //                         } else {
    //                             Log::error("Error al confirmar el envío con PedidosYa para el tipo de envío: $shippingType");
    //                         }*/
    //                     } else {
    //                         Log::error("Datos necesarios no encontrados en la información de entrega para el tipo de envío: $shippingType");
    //                     }
    //                 } else {
    //                     Log::error("Estimación de envío no encontrada para el tipo de envío: $shippingType");
    //                 }
    //             } else {
    //                 // TODO: Implementar proceso de Transporte blanco
    //                 $deliveryService = DeliveryService::create([
    //                     'delivery_company' => $shippingType,
    //                 ]);
    //             }

    //             //Order Details Storing
    //             foreach ($cartsGroup as $cart) {
    //                 $product = $cart->product;
    //                 $subtotal += cart_product_price($cart, $product, false, false) * $cart['quantity'];
    //                 $coupon_discount += $cart['discount'];
    //                 $product_variation = $cart['variation'];
    //                 $product_stock = $product->stocks->first();
    //                 if ($product->digital != 1 && $cart['quantity'] > $product_stock->qty) {
    //                     flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
    //                     $order->delete();
    //                     return redirect()->route('cart')->send();
    //                 } elseif ($product->digital != 1) {
    //                     $product_stock->qty -= $cart['quantity'];
    //                     $product_stock->save();
    //                 }

    //                 $order_detail = OrderDetail::make([
    //                     'order_id' => $order->id,
    //                     'seller_id' => $product->user_id,
    //                     'product_id' => $product->id,
    //                     'variation' => $product_variation,
    //                     'price' => cart_product_price($cart, $product, false, false) * $cart['quantity'],
    //                     'tax' => cart_product_tax($cart, $product, false) * $cart['quantity'],
    //                     'shipping_type' => $cart['shipping_type'],
    //                     'product_referral_code' => $cart['product_referral_code'],
    //                     'shipping_cost' => $cart['shipping_cost'],
    //                     'quantity' => $cart['quantity'],
    //                     'delivery_service_id' => isset($deliveryService) ? $deliveryService->id : null,
    //                 ]);

    //                 $shipping = $order_detail->shipping_cost;

    //                 if (addon_is_activated('club_point')) {
    //                     $order_detail->earn_point = $product->earn_point;
    //                 }

    //                 $order_detail->save();

    //                 $product->update([
    //                     'num_of_sale' => DB::raw('num_of_sale + ' . $cart['quantity'])
    //                 ]);

    //                 $order->seller_id = $product->user_id;
    //                 $order->shipping_type = $cart['shipping_type'];

    //                 if ($cart['shipping_type'] == 'pickup_point') {
    //                     $order->pickup_point_id = $cart['pickup_point'];
    //                 }

    //                 if ($cart['shipping_type'] == 'carrier') {
    //                     $order->carrier_id = $cart['carrier_id'];
    //                 }

    //                 if ($product->added_by == 'seller' && $product->user->seller != null) {
    //                     $seller = $product->user->seller;
    //                     $seller->num_of_sale += $cart['quantity'];
    //                     $seller->save();
    //                 }

    //                 if (addon_is_activated('affiliate_system')) {
    //                     if ($order_detail->product_referral_code) {
    //                         $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

    //                         $affiliateController = new AffiliateController;
    //                         $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
    //                     }
    //                 }

    //             }
    //         }
    //         if ($shippingType == 'PEDIDOS YA') {
    //             $shipping_cost_id = PedidosYaController::getEstimatedShipping($json) ?? '';
    //             $order->shipping_cost_id = $shipping_cost_id;
    //         }

    //         CommissionService::create($order, $subtotal);

    //         $tax = $subtotal * (config('app.itbis') / 100);

    //         $order->grand_total = $subtotal + $tax + $shipping;

    //         // dd($seller_product);

    //         // if ($seller_product[0]->coupon_code != null) {
    //         if (isset($seller_product[0]) && $seller_product[0]->coupon_code != null) {
    //             $order->coupon_discount = $coupon_discount;
    //             $order->grand_total -= $coupon_discount;

    //             $coupon_usage = new CouponUsage;
    //             $coupon_usage->user_id = Auth::user()->id;
    //             $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
    //             $coupon_usage->save();
    //         }

    //         $combined_order->grand_total += $order->grand_total;

    //         $order->save();
    //     }

    //     $combined_order->save();
    //     //log::info('order id: ' . $combined_order->id);

    //     $request->session()->put('combined_order_id', $combined_order->id);
    // }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (Exception $e) {
            }
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }
    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();
    
        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }
    
        $status = 'paid';
    
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
    
        $order->payment_status = $status;
        $order->save();
    
        // Si el pago está completo y era transferencia bancaria, verificar si hay envío pendiente
        if ($order->payment_status == 'paid' && $order->payment_type == 'Transferencia Bancaria') {
            $this->processPendingPedidosYa($order);
        }
    
        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";
    
            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;
    
            NotificationUtility::sendFirebaseNotification($request);
        }
    
        return 1;
    }
    
    /**
     * Procesa y envía pedidos pendientes a Pedidos Ya después de confirmar pago
     */
    private function processPendingPedidosYa($order)
    {
        try {
            // Obtener todos los detalles de orden que tienen servicios de entrega
            $orderDetails = OrderDetail::where('order_id', $order->id)->whereNotNull('delivery_service_id')->get();
            
            foreach ($orderDetails as $orderDetail) {
                // Obtener el servicio de entrega asociado
                $deliveryService = DeliveryService::find($orderDetail->delivery_service_id);
                
                if (!$deliveryService || $deliveryService->delivery_company != 'PEDIDOS YA') {
                    continue;
                }
                
                // Decodificar la información de PedidosYa
                $pyInfo = json_decode($deliveryService->py_info, true);
                
                // Verificar si es un servicio pendiente de pago
                if (!isset($pyInfo['status']) || $pyInfo['status'] != 'pending_payment') {
                    continue;
                }
                
                // Verificar si tenemos la información necesaria
                if (!isset($pyInfo['deliveryOfferId']) || !isset($pyInfo['estimateId'])) {
                    Log::info('Información incompleta en py_info para envío pendiente', [
                        'delivery_service_id' => $deliveryService->id,
                        'order_id' => $order->id,
                        'py_info' => $pyInfo
                    ]);
                    continue;
                }
                
                // Enviar a Pedidos Ya
                $response = PedidosYaController::confirmShipping(
                    $pyInfo['estimateId'], 
                    $pyInfo['deliveryOfferId']
                );
                
                Log::info('Respuesta de PedidosYa después de confirmar pago', [
                    'response' => $response,
                    'order_id' => $order->id,
                    'delivery_service_id' => $deliveryService->id
                ]);
                
                // Actualizar el servicio de entrega
                $deliveryService->py_info = json_encode($response);
                $deliveryService->save();
                
                Log::info('DeliveryService actualizado después de confirmar pago', [
                    'delivery_service_id' => $deliveryService->id,
                    'order_id' => $order->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al procesar envío pendiente con PedidosYa', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
        }
    }

    // public function update_payment_status(Request $request)
    // {
    //     $order = Order::findOrFail($request->order_id);
    //     $order->payment_status_viewed = '0';
    //     $order->save();

    //     if (Auth::user()->user_type == 'seller') {
    //         foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
    //             $orderDetail->payment_status = $request->status;
    //             $orderDetail->save();
    //         }
    //     } else {
    //         foreach ($order->orderDetails as $key => $orderDetail) {
    //             $orderDetail->payment_status = $request->status;
    //             $orderDetail->save();
    //         }
    //     }

    //     $status = 'paid';

    //     foreach ($order->orderDetails as $orderDetail) {
    //         if ($orderDetail->payment_status != 'paid') {
    //             $status = 'unpaid';
    //         }
    //     }

    //     $order->payment_status = $status;
    //     $order->save();
    //     // Si el estado cambia a paid y es una transferencia bancaria
    //     if ($status == 'paid' && $order->payment_type == 'Transferencia Bancaria') {
    //         try {
    //             // Buscar los datos guardados del envío
    //             $shippingCost = ShippingCost::where('order_id', $order->id)
    //                 ->where('shipping_company', 'PEDIDOS YA')
    //                 ->first();

    //             if ($shippingCost) {
    //                 Log::info('Intentando enviar pedido pagado a PedidosYa', [
    //                     'order_id' => $order->id
    //                 ]);

    //                 $response = PedidosYaController::confirmShipping(
    //                     $shippingCost->shipping_id,
    //                     $shippingCost->delivery_offer_id
    //                 );

    //                 $deliveryService = DeliveryService::create([
    //                     'delivery_company' => 'PEDIDOS YA',
    //                     'py_info' => json_encode($response)
    //                 ]);

    //                 // Actualizar order_details con el delivery_service_id
    //                 $order->orderDetails()->update([
    //                     'delivery_service_id' => $deliveryService->id
    //                 ]);

    //                 Log::info('Pedido enviado exitosamente a PedidosYa', [
    //                     'order_id' => $order->id,
    //                     'response' => $response
    //                 ]);
    //             }
    //         } catch (\Exception $e) {
    //             Log::error('Error al enviar pedido a PedidosYa', [
    //                 'error' => $e->getMessage(),
    //                 'order_id' => $order->id
    //             ]);
    //         }
    //     }

    //     /*  if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
    //         calculateCommissionAffilationClubPoint($order);
    //     }*/

    //     //sends Notifications to user
    //     NotificationUtility::sendNotification($order, $request->status);
    //     if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
    //         $request->device_token = $order->user->device_token;
    //         $request->title = "Order updated !";
    //         $status = str_replace("_", "", $order->payment_status);
    //         $request->text = " Your order {$order->code} has been {$status}";

    //         $request->type = "order";
    //         $request->id = $order->id;
    //         $request->user_id = $order->user->id;

    //         NotificationUtility::sendFirebaseNotification($request);
    //     }


    //     /*if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
    //         try {
    //             SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
    //         } catch (\Exception $e) {
    //         }
    //     }*/
    //     return 1;
    // }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (Exception $e) {
                }
            }
        }

        return 1;
    }
}


// namespace App\Http\Controllers;

// use App\Http\Controllers\Api\V2\Delivery\PedidosYaController;
// use App\Models\ShippingCost;
// use App\Mail\InvoiceEmailManager;
// use App\Models\Address;
// use App\Models\Cart;
// use App\Models\CategoryTranslation;
// use App\Models\CombinedOrder;
// use App\Models\Coupon;
// use App\Models\CouponUsage;
// use App\Models\DeliveryHistory;
// use App\Models\DeliveryService;
// use App\Models\Order;
// use App\Models\OrderDetail;
// use App\Models\ProductStock;
// use App\Models\SmsTemplate;
// use App\Models\User;
// use App\Services\CommissionService;
// use App\Utility\NotificationUtility;
// use App\Utility\SmsUtility;
// use Auth;
// use CoreComponentRepository;
// use Exception;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Route;
// use Mail;
// use Session;

// class OrderController extends Controller
// {
//     public function __construct()
//     {
//         // Staff Permission Check
//         $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
//         $this->middleware(['permission:view_order_details'])->only('show');
//         $this->middleware(['permission:delete_order'])->only('destroy', 'bulk_order_delete');
//     }

//     // All Orders
//     public function all_orders(Request $request)
//     {
//         CoreComponentRepository::instantiateShopRepository();

//         $date = $request->date;
//         $sort_search = null;
//         $delivery_status = null;
//         $payment_status = '';

//         $orders = Order::orderBy('id', 'desc');
//         $admin_user_id = User::where('user_type', 'admin')->first()->id;


//         if (
//             Route::currentRouteName() == 'inhouse_orders.index' &&
//             Auth::user()->can('view_inhouse_orders')
//         ) {
//             $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
//         } else if (
//             Route::currentRouteName() == 'seller_orders.index' &&
//             Auth::user()->can('view_seller_orders')
//         ) {
//             $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
//         } else if (
//             Route::currentRouteName() == 'pick_up_point.index' &&
//             Auth::user()->can('view_pickup_point_orders')
//         ) {
//             $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
//             if (
//                 Auth::user()->user_type == 'staff' &&
//                 Auth::user()->staff->pick_up_point != null
//             ) {
//                 $orders->where('shipping_type', 'pickup_point')
//                     ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
//             }
//         } else if (
//             Route::currentRouteName() == 'all_orders.index' &&
//             Auth::user()->can('view_all_orders')
//         ) {
//         } else {
//             abort(403);
//         }

//         if ($request->search) {
//             $sort_search = $request->search;
//             $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
//         }
//         if ($request->payment_status != null) {
//             $orders = $orders->where('payment_status', $request->payment_status);
//             $payment_status = $request->payment_status;
//         }
//         if ($request->delivery_status != null) {
//             $orders = $orders->where('delivery_status', $request->delivery_status);
//             $delivery_status = $request->delivery_status;
//         }
//         if ($date != null) {
//             $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
//                 ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
//         }
//         $orders = $orders->paginate(15);
//         return view('backend.sales.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date'));
//     }

//     public function show($id)
//     {
//         $order = Order::findOrFail(decrypt($id));
//         $order_shipping_address = json_decode($order->shipping_address);
//         $delivery_boys = User::where('city', $order_shipping_address->city)
//             ->where('user_type', 'delivery_boy')
//             ->get();

//         $order->viewed = 1;
//         $order->save();
//         return view('backend.sales.show', compact('order', 'delivery_boys'));
//     }

//     public function store(Request $request)
//     {
//         // Search all cart products from the user
//         $carts = Cart::where('user_id', Auth::id())->get();

//         // Get Delivery address from the cart
//         $address = Address::where('id', $carts[0]['address_id'])->first();

//         if ($address != null) {
//             $shippingAddress['name'] = Auth::user()->name;
//             $shippingAddress['email'] = Auth::user()->email;
//             $shippingAddress['address'] = $address->address;
//             $shippingAddress['country'] = $address->country;
//             $shippingAddress['state'] = $address->state;
//             $shippingAddress['city'] = $address->city;
//             $shippingAddress['postal_code'] = $address->postalCode;
//             $shippingAddress['phone'] = $address->phone;
//             if ($address->latitude || $address->longitude) {
//                 $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
//             }
//         }

//         $combined_order = CombinedOrder::create([
//             'user_id' => Auth::id(),
//             'shipping_address' => json_encode($shippingAddress),
//         ]);

//         $carts_by_seller_id = Cart::where('user_id', Auth::id())
//             ->with('product')
//             ->get()
//             ->groupBy(function ($cart) {
//                 return $cart->product->user_id;
//             });


//         foreach ($carts_by_seller_id as $carts) {
//             $order = Order::make([
//                 'combined_order_id' => $combined_order->id,
//                 'user_id' => Auth::id(),
//             ]);

//             foreach ($carts as $cart) {
//                 $order->shop_id = $cart->product->user->shop->id;
//                 $user = User::find($cart->product->user->id);
//                 $order->category_translation_id = CategoryTranslation::find($user->category_translation_id)->id;
//             }

//             $order->shipping_address = $combined_order->shipping_address;
//             $order->additional_info = $request->additional_info;
//             $order->payment_type = $request->payment_option;

//             $order->save();

//             $subtotal = 0;
//             $shipping = 0;
//             $coupon_discount = 0;

//             // Agrupar carritos por tipo de envío
//             $groupedCarts = $carts->groupBy('shipping_type');

//             foreach ($groupedCarts as $shippingType => $cartsGroup) {
//                 // Intentar obtener el primer carrito y su estimación de envío
//                 $firstCart = $cartsGroup->first();
//                 $estimateShipping = $firstCart ? $firstCart->deliveryEstimate()->where('name', $shippingType)->first() : null;

//                 if ($shippingType == 'PEDIDOS YA') {
//                     // Verificar si se encontró la estimación de envío y si contiene la información necesaria
//                     if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
//                         $json = json_decode($estimateShipping->delivery_info, true);

//                         // Verificar si la decodificación fue exitosa y si los datos necesarios están presentes
//                         if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
//                             $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
//                             $estimateId = $json['estimateId'];

//                             //log::info('Estimación de envío1: ' . print_r($json, true));

//                             Session::put('deliveryOfferId', $deliveryOfferId);
//                             Session::put('estimateId', $estimateId);
//                             Session::put('shippingType', $shippingType);
//                             log::info("alfin apareciste desgraciadoooo3");

//                             // Confirmar el envío con PedidosYa y crear el registro de DeliveryService
//                             //$response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);
//                             /*$deliveryService = DeliveryService::create([
//                                 'delivery_company' => $shippingType,
//                                 'py_info' => json_encode($json)
//                             ]);*/

//                             //log::info('response confirmShipping: ' . json_encode($response));
//                             /*if ($response) {
//                                 $deliveryService = DeliveryService::create([
//                                     'delivery_company' => $shippingType,
//                                     'py_info' => json_encode($response)
//                                 ]);
//                             } else {
//                                 Log::error("Error al confirmar el envío con PedidosYa para el tipo de envío: $shippingType");
//                             }*/
//                         } else {
//                             Log::error("Datos necesarios no encontrados en la información de entrega para el tipo de envío: $shippingType");
//                         }
//                     } else {
//                         Log::error("Estimación de envío no encontrada para el tipo de envío: $shippingType");
//                     }
//                 } else {
//                     // TODO: Implementar proceso de Transporte blanco
//                     $deliveryService = DeliveryService::create([
//                         'delivery_company' => $shippingType,
//                     ]);
//                 }

//                 //Order Details Storing
//                 foreach ($cartsGroup as $cart) {
//                     $product = $cart->product;
//                     $subtotal += cart_product_price($cart, $product, false, false) * $cart['quantity'];
//                     $coupon_discount += $cart['discount'];
//                     $product_variation = $cart['variation'];
//                     $product_stock = $product->stocks->first();
//                     if ($product->digital != 1 && $cart['quantity'] > $product_stock->qty) {
//                         flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
//                         $order->delete();
//                         return redirect()->route('cart')->send();
//                     } elseif ($product->digital != 1) {
//                         $product_stock->qty -= $cart['quantity'];
//                         $product_stock->save();
//                     }

//                     $order_detail = OrderDetail::make([
//                         'order_id' => $order->id,
//                         'seller_id' => $product->user_id,
//                         'product_id' => $product->id,
//                         'variation' => $product_variation,
//                         'price' => cart_product_price($cart, $product, false, false) * $cart['quantity'],
//                         'tax' => cart_product_tax($cart, $product, false) * $cart['quantity'],
//                         'shipping_type' => $cart['shipping_type'],
//                         'product_referral_code' => $cart['product_referral_code'],
//                         'shipping_cost' => $cart['shipping_cost'],
//                         'quantity' => $cart['quantity'],
//                         'delivery_service_id' => isset($deliveryService) ? $deliveryService->id : null,
//                     ]);

//                     $shipping = $order_detail->shipping_cost;

//                     if (addon_is_activated('club_point')) {
//                         $order_detail->earn_point = $product->earn_point;
//                     }

//                     $order_detail->save();

//                     $product->update([
//                         'num_of_sale' => DB::raw('num_of_sale + ' . $cart['quantity'])
//                     ]);

//                     $order->seller_id = $product->user_id;
//                     $order->shipping_type = $cart['shipping_type'];

//                     if ($cart['shipping_type'] == 'pickup_point') {
//                         $order->pickup_point_id = $cart['pickup_point'];
//                     }

//                     if ($cart['shipping_type'] == 'carrier') {
//                         $order->carrier_id = $cart['carrier_id'];
//                     }

//                     if ($product->added_by == 'seller' && $product->user->seller != null) {
//                         $seller = $product->user->seller;
//                         $seller->num_of_sale += $cart['quantity'];
//                         $seller->save();
//                     }

//                     if (addon_is_activated('affiliate_system')) {
//                         if ($order_detail->product_referral_code) {
//                             $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

//                             $affiliateController = new AffiliateController;
//                             $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
//                         }
//                     }
//                 }
//             }
//             if ($shippingType == 'PEDIDOS YA') {
//                 $shipping_cost_id = PedidosYaController::getEstimatedShipping($json) ?? '';
//                 $order->shipping_cost_id = $shipping_cost_id;
//             }

//             CommissionService::create($order, $subtotal);

//             $tax = $subtotal * (config('app.itbis') / 100);

//             $order->grand_total = $subtotal + $tax + $shipping;

//             // dd($seller_product);

//             // if ($seller_product[0]->coupon_code != null) {
//             if (isset($seller_product[0]) && $seller_product[0]->coupon_code != null) {
//                 $order->coupon_discount = $coupon_discount;
//                 $order->grand_total -= $coupon_discount;

//                 $coupon_usage = new CouponUsage;
//                 $coupon_usage->user_id = Auth::user()->id;
//                 $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
//                 $coupon_usage->save();
//             }

//             $combined_order->grand_total += $order->grand_total;

//             $order->save();
//         }

//         $combined_order->save();
//         //log::info('order id: ' . $combined_order->id);

//         $request->session()->put('combined_order_id', $combined_order->id);
//     }

//     public function bulk_order_delete(Request $request)
//     {
//         if ($request->id) {
//             foreach ($request->id as $order_id) {
//                 $this->destroy($order_id);
//             }
//         }

//         return 1;
//     }

//     public function destroy($id)
//     {
//         $order = Order::findOrFail($id);
//         if ($order != null) {
//             foreach ($order->orderDetails as $key => $orderDetail) {
//                 try {

//                     $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
//                     if ($product_stock != null) {
//                         $product_stock->qty += $orderDetail->quantity;
//                         $product_stock->save();
//                     }
//                 } catch (Exception $e) {
//                 }

//                 $orderDetail->delete();
//             }
//             $order->delete();
//             flash(translate('Order has been deleted successfully'))->success();
//         } else {
//             flash(translate('Something went wrong'))->error();
//         }
//         return back();
//     }

//     public function order_details(Request $request)
//     {
//         $order = Order::findOrFail($request->order_id);
//         $order->save();
//         return view('seller.order_details_seller', compact('order'));
//     }

//     public function update_delivery_status(Request $request)
//     {
//         $order = Order::findOrFail($request->order_id);
//         $order->delivery_viewed = '0';
//         $order->delivery_status = $request->status;
//         $order->save();

//         if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
//             $user = User::where('id', $order->user_id)->first();
//             $user->balance += $order->grand_total;
//             $user->save();
//         }

//         if (Auth::user()->user_type == 'seller') {
//             foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
//                 $orderDetail->delivery_status = $request->status;
//                 $orderDetail->save();

//                 if ($request->status == 'cancelled') {
//                     $variant = $orderDetail->variation;
//                     if ($orderDetail->variation == null) {
//                         $variant = '';
//                     }

//                     $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
//                         ->where('variant', $variant)
//                         ->first();

//                     if ($product_stock != null) {
//                         $product_stock->qty += $orderDetail->quantity;
//                         $product_stock->save();
//                     }
//                 }
//             }
//         } else {
//             foreach ($order->orderDetails as $key => $orderDetail) {

//                 $orderDetail->delivery_status = $request->status;
//                 $orderDetail->save();

//                 if ($request->status == 'cancelled') {
//                     $variant = $orderDetail->variation;
//                     if ($orderDetail->variation == null) {
//                         $variant = '';
//                     }

//                     $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
//                         ->where('variant', $variant)
//                         ->first();

//                     if ($product_stock != null) {
//                         $product_stock->qty += $orderDetail->quantity;
//                         $product_stock->save();
//                     }
//                 }

//                 if (addon_is_activated('affiliate_system')) {
//                     if (($request->status == 'delivered' || $request->status == 'cancelled') &&
//                         $orderDetail->product_referral_code
//                     ) {

//                         $no_of_delivered = 0;
//                         $no_of_canceled = 0;

//                         if ($request->status == 'delivered') {
//                             $no_of_delivered = $orderDetail->quantity;
//                         }
//                         if ($request->status == 'cancelled') {
//                             $no_of_canceled = $orderDetail->quantity;
//                         }

//                         $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

//                         $affiliateController = new AffiliateController;
//                         $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
//                     }
//                 }
//             }
//         }
//         if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
//             try {
//                 SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
//             } catch (Exception $e) {
//             }
//         }

//         //sends Notifications to user
//         NotificationUtility::sendNotification($order, $request->status);
//         if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
//             $request->device_token = $order->user->device_token;
//             $request->title = "Order updated !";
//             $status = str_replace("_", "", $order->delivery_status);
//             $request->text = " Your order {$order->code} has been {$status}";

//             $request->type = "order";
//             $request->id = $order->id;
//             $request->user_id = $order->user->id;

//             NotificationUtility::sendFirebaseNotification($request);
//         }


//         if (addon_is_activated('delivery_boy')) {
//             if (Auth::user()->user_type == 'delivery_boy') {
//                 $deliveryBoyController = new DeliveryBoyController;
//                 $deliveryBoyController->store_delivery_history($order);
//             }
//         }

//         return 1;
//     }

//     public function update_tracking_code(Request $request)
//     {
//         $order = Order::findOrFail($request->order_id);
//         $order->tracking_code = $request->tracking_code;
//         $order->save();

//         return 1;
//     }
//     // public function update_payment_status(Request $request)
//     // {
//     //     $order = Order::findOrFail($request->order_id);
//     //     $order->payment_status_viewed = '0';
//     //     $order->save();

//     //     if (Auth::user()->user_type == 'seller') {
//     //         foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
//     //             $orderDetail->payment_status = $request->status;
//     //             $orderDetail->save();
//     //         }
//     //     } else {
//     //         foreach ($order->orderDetails as $key => $orderDetail) {
//     //             $orderDetail->payment_status = $request->status;
//     //             $orderDetail->save();
//     //         }
//     //     }

//     //     $status = 'paid';

//     //     foreach ($order->orderDetails as $orderDetail) {
//     //         if ($orderDetail->payment_status != 'paid') {
//     //             $status = 'unpaid';
//     //         }
//     //     }

//     //     $order->payment_status = $status;
//     //     $order->save();


//     //   /*  if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
//     //         calculateCommissionAffilationClubPoint($order);
//     //     }*/

//     //     //sends Notifications to user
//     //     NotificationUtility::sendNotification($order, $request->status);
//     //     if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
//     //         $request->device_token = $order->user->device_token;
//     //         $request->title = "Order updated !";
//     //         $status = str_replace("_", "", $order->payment_status);
//     //         $request->text = " Your order {$order->code} has been {$status}";

//     //         $request->type = "order";
//     //         $request->id = $order->id;
//     //         $request->user_id = $order->user->id;

//     //         NotificationUtility::sendFirebaseNotification($request);
//     //     }


//     //     /*if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
//     //         try {
//     //             SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
//     //         } catch (\Exception $e) {
//     //         }
//     //     }*/
//     //     return 1;
//     // }

//     public function update_payment_status(Request $request)
//     {
//         Log::info('Iniciando actualización de estado de pago', [
//             'order_id' => $request->order_id,
//             'status' => $request->status
//         ]);

//         $order = Order::findOrFail($request->order_id);
//         $order->payment_status_viewed = '0';
//         $order->save();

//         if (Auth::user()->user_type == 'seller') {
//             foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
//                 $orderDetail->payment_status = $request->status;
//                 $orderDetail->save();
//             }
//         } else {
//             foreach ($order->orderDetails as $key => $orderDetail) {
//                 $orderDetail->payment_status = $request->status;
//                 $orderDetail->save();
//             }
//         }

//         $status = 'paid';

//         foreach ($order->orderDetails as $orderDetail) {
//             if ($orderDetail->payment_status != 'paid') {
//                 $status = 'unpaid';
//             }
//         }

//         $order->payment_status = $status;
//         $order->save();

//         // Si el estado es pagado, enviar a Pedidos Ya independientemente del método de pago
//         if ($status == 'paid') {
//             try {
//                 $orderDetail = $order->orderDetails()->first();
//                 if ($orderDetail) {
//                     $shippingCost = ShippingCost::where('cart_id', $orderDetail->cart_id)
//                         ->where('shipping_company', 'PEDIDOS YA')
//                         ->first();
    
//                     if ($shippingCost) {
//                         // Verificar si ya existe un delivery service para este pedido
//                         $existingDeliveryService = DeliveryService::where('py_info', 'like', '%"shipping_id":"'.$shippingCost->shipping_id.'"%')->first();
    
//                         if (!$existingDeliveryService) {
//                             Log::info('Intentando enviar pedido a Pedidos Ya', [
//                                 'order_id' => $order->id,
//                                 'shipping_id' => $shippingCost->shipping_id
//                             ]);
    
//                             $response = PedidosYaController::confirmShipping(
//                                 $shippingCost->shipping_id,
//                                 $shippingCost->delivery_offer_id
//                             );
    
//                             if (isset($response['code']) && $response['code'] === 'INVALID_STATUS') {
//                                 Log::warning('El envío ya fue procesado en Pedidos Ya', [
//                                     'response' => $response,
//                                     'order_id' => $order->id
//                                 ]);
//                             } else {
//                                 $deliveryService = DeliveryService::create([
//                                     'delivery_company' => 'PEDIDOS YA',
//                                     'py_info' => json_encode($response)
//                                 ]);
    
//                                 $order->orderDetails()->update([
//                                     'delivery_service_id' => $deliveryService->id
//                                 ]);
    
//                                 Log::info('Pedido enviado exitosamente a Pedidos Ya', [
//                                     'order_id' => $order->id,
//                                     'delivery_service_id' => $deliveryService->id
//                                 ]);
//                             }
//                         } else {
//                             Log::info('El pedido ya fue procesado anteriormente en Pedidos Ya', [
//                                 'order_id' => $order->id,
//                                 'existing_delivery_service_id' => $existingDeliveryService->id
//                             ]);
//                         }
//                     }
//                 }
//             } catch (\Exception $e) {
//                 Log::error('Error al enviar pedido a Pedidos Ya', [
//                     'error' => $e->getMessage(),
//                     'order_id' => $order->id
//                 ]);
//             }
//         }
//         // Enviar notificaciones
//         NotificationUtility::sendNotification($order, $request->status);
//         if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
//             $request->device_token = $order->user->device_token;
//             $request->title = "Order updated !";
//             $status = str_replace("_", "", $order->payment_status);
//             $request->text = " Your order {$order->code} has been {$status}";

//             $request->type = "order";
//             $request->id = $order->id;
//             $request->user_id = $order->user->id;

//             NotificationUtility::sendFirebaseNotification($request);
//         }

//         return 1;
//     }
//     // public function update_payment_status(Request $request)
//     // {
//     //     $order = Order::findOrFail($request->order_id);
//     //     $order->payment_status_viewed = '0';
//     //     $order->save();

//     //     if (Auth::user()->user_type == 'seller') {
//     //         foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
//     //             $orderDetail->payment_status = $request->status;
//     //             $orderDetail->save();
//     //         }
//     //     } else {
//     //         foreach ($order->orderDetails as $key => $orderDetail) {
//     //             $orderDetail->payment_status = $request->status;
//     //             $orderDetail->save();
//     //         }
//     //     }

//     //     $status = 'paid';

//     //     foreach ($order->orderDetails as $orderDetail) {
//     //         if ($orderDetail->payment_status != 'paid') {
//     //             $status = 'unpaid';
//     //         }
//     //     }

//     //     $order->payment_status = $status;
//     //     $order->save();


//     //   /*  if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
//     //         calculateCommissionAffilationClubPoint($order);
//     //     }*/

//     //     //sends Notifications to user
//     //     NotificationUtility::sendNotification($order, $request->status);
//     //     if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
//     //         $request->device_token = $order->user->device_token;
//     //         $request->title = "Order updated !";
//     //         $status = str_replace("_", "", $order->payment_status);
//     //         $request->text = " Your order {$order->code} has been {$status}";

//     //         $request->type = "order";
//     //         $request->id = $order->id;
//     //         $request->user_id = $order->user->id;

//     //         NotificationUtility::sendFirebaseNotification($request);
//     //     }


//     //     /*if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
//     //         try {
//     //             SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
//     //         } catch (\Exception $e) {
//     //         }
//     //     }*/
//     //     return 1;
//     // }

//     public function assign_delivery_boy(Request $request)
//     {
//         if (addon_is_activated('delivery_boy')) {

//             $order = Order::findOrFail($request->order_id);
//             $order->assign_delivery_boy = $request->delivery_boy;
//             $order->delivery_history_date = date("Y-m-d H:i:s");
//             $order->save();

//             $delivery_history = DeliveryHistory::where('order_id', $order->id)
//                 ->where('delivery_status', $order->delivery_status)
//                 ->first();

//             if (empty($delivery_history)) {
//                 $delivery_history = new DeliveryHistory;

//                 $delivery_history->order_id = $order->id;
//                 $delivery_history->delivery_status = $order->delivery_status;
//                 $delivery_history->payment_type = $order->payment_type;
//             }
//             $delivery_history->delivery_boy_id = $request->delivery_boy;

//             $delivery_history->save();

//             if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
//                 $array['view'] = 'emails.invoice';
//                 $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
//                 $array['from'] = env('MAIL_FROM_ADDRESS');
//                 $array['order'] = $order;

//                 try {
//                     Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
//                 } catch (Exception $e) {
//                 }
//             }

//             if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
//                 try {
//                     SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
//                 } catch (Exception $e) {
//                 }
//             }
//         }

//         return 1;
//     }
// }


// namespace App\Http\Controllers\Api\V2;

// use App\Http\Controllers\AffiliateController;
// use App\Http\Controllers\Api\V2\Delivery\PedidosYaController;
// use App\Http\Controllers\CategoryController;
// use App\Models\Address;
// use App\Models\Cart;
// use App\Models\CategoryTranslation;
// use App\Models\CombinedOrder;
// use App\Models\Coupon;
// use App\Models\CouponUsage;
// use App\Models\DeliveryService;
// use App\Models\Order;
// use App\Models\OrderDetail;
// use App\Models\Product;
// use App\Models\User;
// use App\Models\UserHasConversation;
// use App\Services\CommissionService;
// use App\Utility\NotificationUtility;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;

// class OrderController extends Controller {
//     public function store(Request $request, $set_paid = false) {
//         $user = User::find(auth()->id());
//         $carts = Cart::where('user_id', $user->id)->get();
//         $shippingAddress = [];

//         if (get_setting('minimum_order_amount_check') == 1) {
//             $subtotal = 0;

//             foreach ($carts as $cart) {
//                 $product = Product::find($cart->product_id);
//                 $subtotal += cart_product_price($cart, $product, false, false) * $cart->quantity;
//             }

//             if ($subtotal < get_setting('minimum_order_amount')) {
//                 return $this->failed("You order amount is less then the minimum order amount");
//             }
//         }

//         $address = Address::find($carts->first()->address_id);

//         if ($address != null) {
//             $shippingAddress['name'] = $user->name;
//             $shippingAddress['email'] = $user->email;
//             $shippingAddress['address'] = $address->address;
//             $shippingAddress['country'] = $address->country;
//             $shippingAddress['state'] = $address->state;
//             $shippingAddress['city'] = $address->city;
//             $shippingAddress['postal_code'] = $address->postal_code;
//             $shippingAddress['phone'] = $address->phone;
//             if ($address->latitude || $address->longitude) {
//                 $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
//             }
//         }

//         $combined_order = CombinedOrder::create([
//             'user_id' => $user->id,
//             'shipping_address' => json_encode($shippingAddress),
//         ]);

//         $carts_by_seller_id = Cart::where('user_id', auth()->id())
//             ->with('product')
//             ->get()
//             ->groupBy(function ($cart) {
//                 return $cart->product->user_id;
//             });

//         foreach ($carts_by_seller_id as $carts) {
//             $order = Order::make([
//                 'combined_order_id' => $combined_order->id,
//                 'user_id' => $user->id,
//             ]);

//             foreach ($carts as $cart) {
//                 $order->shop_id = $cart->product->user->shop->id;
//                 $user = User::find($cart->product->user->id);
//                 $order->category_translation_id =  CategoryTranslation::find($user->category_translation_id)->id;
//             }

//             $order->shipping_address = $combined_order->shipping_address;
//             $order->order_from = 'app';
//             $order->payment_type = $request->payment_type;
//             $order->payment_status = ($request->payment_type != 'manual_payment_1') ? 'paid' : 'unpaid';
//             $order->save();

//             $subtotal = 0;
//             $tax = 0;
//             $shipping = 0;
//             $coupon_discount = 0;

//             // Agrupar carritos por tipo de envío
//             $groupedCarts = $carts->groupBy('shipping_type');

//             foreach ($groupedCarts as $shippingType => $cartsGroup) {
//                 // Intentar obtener el primer carrito y su estimación de envío
//                 $firstCart = $cartsGroup->first();
//                 $estimateShipping = $firstCart ? $firstCart->deliveryEstimate()->where('name', $shippingType)->first() : null;

//                 if ($shippingType == 'PEDIDOS YA') {
//                     log::info("alfin apareciste desgraciado2");
//                     // Verificar si se encontró la estimación de envío y si contiene la información necesaria
//                     if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
//                         $json = json_decode($estimateShipping->delivery_info, true);

//                         // Verificar si la decodificación fue exitosa y si los datos necesarios están presentes
//                         if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
//                             $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
//                             $estimateId = $json['estimateId'];
//                             log::info("alfin apareciste desgraciado1");
//                             // Confirmar el envío con PedidosYa y crear el registro de DeliveryService
//                             $response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);
//                             if ($response) {
//                                 $deliveryService = DeliveryService::create([
//                                     'delivery_company' => $shippingType,
//                                     'py_info' => json_encode($response)
//                                 ]);
//                             } else {
//                                 Log::error("Error al confirmar el envío con PedidosYa para el tipo de envío: $shippingType");
//                             }
//                         } else {
//                             Log::error("Datos necesarios no encontrados en la información de entrega para el tipo de envío: $shippingType");
//                         }
//                     } else {
//                         Log::error("Estimación de envío no encontrada para el tipo de envío: $shippingType");
//                     }
//                 } else {
//                     // TODO: Implementar proceso de Transporte blanco
//                     $deliveryService = DeliveryService::create([
//                         'delivery_company' => $shippingType,
//                     ]);
//                 }

//                 //Order Details Storing
//                 foreach ($carts as $cart) {
//                     $product = Product::find($cart->product_id);
//                     $subtotal += cart_product_price($cart, $product, false, false) * $cart->quantity;
//                     $tax += cart_product_tax($cart, $product, false) * $cart->quantity;
//                     $coupon_discount += $cart->discount;
//                     $product_variation = $cart->variation;
//                     $product_stock = $product->stocks->first();

//                     if ($product->digital != 1 && $cart->quantity > $product_stock->qty) {
//                         $order->delete();
//                         $combined_order->delete();
//                         return response()->json([
//                             'combined_order_id' => 0,
//                             'result' => false,
//                             'message' => translate('The requested quantity is not available for ') . $product->name
//                         ]);
//                     } elseif ($product->digital != 1) {
//                         $product_stock->qty -= $cart->quantity;
//                         $product_stock->save();
//                     }

//                     $order_detail = OrderDetail::create([
//                         'order_id' => $order->id,
//                         'seller_id' => $product->user_id,
//                         'product_id' => $product->id,
//                         'variation' => $product_variation,
//                         'price' => cart_product_price($cart, $product, false, false) * $cart->quantity,
//                         'tax' => cart_product_tax($cart, $product, false) * $cart->quantity,
//                         'shipping_type' => $cart->shipping_type,
//                         'product_referral_code' => $cart->product_referral_code,
//                         'shipping_cost' => $cart->shipping_cost
//                     ]);

//                     $shipping += $order_detail->shipping_cost;

//                     //End of storing shipping cost
//                     if (addon_is_activated('club_point')) {
//                         $order_detail->earn_point = $product->earn_point;
//                     }

//                     $order_detail->quantity = $cart->quantity;
//                     $order_detail->save();

//                     $product->num_of_sale = $product->num_of_sale + $cart->quantity;
//                     $product->save();

//                     $order->seller_id = $product->user_id;

//                     //======== Added By Kiron ==========
//                     $order->shipping_type = $cart->shipping_type;

//                     if ($cart->shipping_type == 'pickup_point') {
//                         $order->pickup_point_id = $cart->pickup_point;
//                     }

//                     if ($product->added_by == 'seller' && $product->user->seller != null) {
//                         $seller = $product->user->seller;
//                         $seller->num_of_sale += $cart->quantity;
//                         $seller->save();
//                     }
//                 }

//             }

//             CommissionService::create($order, $subtotal);

//             $order->grand_total = $subtotal + $tax + $shipping;

//             if ($carts[0]->coupon_code != null) {
//                 // if (Session::has('club_point')) {
//                 //     $order->club_point = Session::get('club_point');
//                 // }
//                 $order->coupon_discount = $coupon_discount;
//                 $order->grand_total -= $coupon_discount;
//                 $coupon_usage = new CouponUsage;
//                 $coupon_usage->user_id = $user->id;
//                 $coupon_usage->coupon_id = Coupon::where('code', $carts[0]->coupon_code)->first()->id;
//                 $coupon_usage->save();
//             }
//             $combined_order->grand_total += $order->grand_total;

//             if (str_contains($request->payment_type, "manual_payment_")) { // if payment type like  manual_payment_1 or  manual_payment_25 etc)
//                 $order->manual_payment = 1;
//                 $order->save();
//             }

//             $order->save();
//         }

//         $combined_order->save();

//         Cart::where('user_id', auth()->user()->id)->delete();

//         return response()->json([
//             'combined_order_id' => $combined_order->id,
//             'result' => true,
//             'message' => translate('Your order has been placed successfully'),
//             'order' => $combined_order->load('orders')->load('orders.orderDetails')->load('orders.orderDetails.product')->load('orders.orderDetails.product.thumbnail')
//         ]);
//     }

//     public function order_cancel($id) {
//         $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->first();
//         if ($order && ($order->delivery_status == __('pending') && $order->payment_status == __('unpaid'))) {
//             $order->delivery_status = __('cancelled');
//             $order->save();
//             foreach ($order->orderDetails as $key => $orderDetail) {
//                 $orderDetail->delivery_status = __('cancelled');
//                 $orderDetail->save();
//                 product_restock($orderDetail);
//             }
//             return $this->success(__('Order has been canceled successfully'));
//         } else {
//             return $this->failed(__('Something went wrong'));
//         }
//     }

//     public function checkWorkshopAvailability($combined_order_id) {
//         $combined_order = CombinedOrder::findOrFail($combined_order_id);
//         $isAvailableToWorkshop = false;

//         // Get all children categories From Vehículos and Motocicletas
//         $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

//         $orders = collect();
//         $products_to_install = collect();

//         $combined_order->orders()->each(function ($order) use ($orders, $availableCategories, &$isAvailableToWorkshop, &$products_to_install) {
//             // Check if some product can be installed
//             $orderDetails = $order->orderDetails()->get();
//             foreach ($orderDetails as $orderDetail) {
//                 // Get the category name of product
//                 $productName = $orderDetail->product->category->name ?? null;
//                 if ($productName && $availableCategories->contains($productName)) {
//                     $isAvailableToWorkshop = true;
//                     $products_to_install->push([
//                         'orderId' => $order->id,
//                         'productData' => $orderDetail->product]
//                     );
//                 }
//             }

//             $orders->push($orderDetails);
//         });

//         return response()->json([
//             'isAvailableToWorkshop' => $isAvailableToWorkshop,
//             'productsAvailableToInstall' => $products_to_install
//         ]);
//     }

//     public function checkWorkshopAvailabilityPerOrder($order_id) {
//         $order = Order::find($order_id);
//         $isAvailableToWorkshop = false;

//         // Get all children categories From Vehículos and Motocicletas
//         $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

//         $orders = collect();
//         $products_to_install = collect();

//         foreach($order->orderDetails as $orderDetail){
//             $productName = $orderDetail->product->category->name ?? null;
//             if ($productName && $availableCategories->contains($productName)) {
//                 $isAvailableToWorkshop = true;
//                 $products_to_install->push([
//                         'orderId' => $order->id,
//                         'productData' => $orderDetail->product]
//                 );
//             }
//         }

//         return response()->json([
//             'isAvailableToWorkshop' => $isAvailableToWorkshop,
//             'productsAvailableToInstall' => $products_to_install
//         ]);
//     }

//     public function workshopRequestStatus(){
//         return response()->json([
//             'userHasOpenedProcess' => UserHasConversation::where('user_id', auth()->id())->exists()
//         ]);
//     }
// }
