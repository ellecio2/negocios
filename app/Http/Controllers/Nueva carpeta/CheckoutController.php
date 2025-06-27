<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V2\Delivery\PedidosYaController;
use App\Http\Controllers\NfcVouchersController;
use App\Mail\InvoiceEmailManager;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\NfcVoucher;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Utility\NotificationUtility;
use Auth;
use Illuminate\Http\Request;
use Mail;
use Session;
use Illuminate\Support\Facades\Log;
use App\Models\DeliveryService;


class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Minumum order amount check...
        if ($request->payment_option != null) {
            // Guardar la orden y obtener el objeto combined_order
            $combined_order = (new OrderController)->store($request);
            $combined_order_id = $combined_order->id;

            // Actualizar la sesión con el ID correcto
            $request->session()->put('combined_order_id', $combined_order_id);
            $request->session()->put('payment_type', 'cart_payment');

            $data['combined_order_id'] = $combined_order_id;
            $request->session()->put('payment_data', $data);

            Log::info('Combined order ID después de store: ' . $combined_order_id);

            // Resto del código igual
            if ($combined_order_id != null) {
                $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
                Log::info('Verificando decorador: ' . $decorator);

                if (class_exists($decorator)) {
                    Log::info('Usando decorador: ' . $decorator);
                    return (new $decorator)->pay($request);
                } else {
                    Log::info('Usando makeManualPaymet');
                    return $this->makeManualPaymet($request);
                }
            } else {
                Log::error('No se pudo obtener combined_order_id después de store');
                flash(translate('Error al procesar su orden.'))->error();
                return redirect()->route('home');
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }

    public function makeManualPaymet(Request $request)
{
    try {
        Log::info('Ejecutando makeManualPaymet');
        $combined_order_id = $request->session()->get('combined_order_id');
        Log::info('Combined order ID en makeManualPaymet: ' . $combined_order_id);
        
        if (!$combined_order_id) {
            Log::error('No hay combined_order_id en la sesión');
            flash(translate('No se pudo encontrar la información de su orden.'))->error();
            return redirect()->route('home');
        }
        
        $combined_order = CombinedOrder::with('orders')->findOrFail($combined_order_id);

        $manual_payment_data = [
            'name' => $request->payment_option,
            'amount' => $combined_order->grand_total,
            'trx_id' => $request->trx_id,
            'photo' => $request->photo
        ];

        $orderIds = $combined_order->orders->pluck('id');

        // Actualizar el estado de pago de las órdenes
        Order::whereIn('id', $orderIds)->update([
            'manual_payment' => true,
            'manual_payment_data' => json_encode($manual_payment_data),
        ]);

        $orders = Order::whereIn('id', $orderIds)->get();
        $order = $orders->first(); // Solo para notificación

        NotificationUtility::sendOrderPlacedNotification($order, 'Pendiente por verificar');
        
        // Asegurarse de que la sesión tenga el combined_order_id para orderConfirmed
        Session::put('combined_order_id', $combined_order_id);

        flash(translate('Su orden ha sido realizada exitosamente. Por favor, envíe información de pago desde el historial de compras'))->success();

        Log::info('Redireccionando a checkout.order_confirmed desde makeManualPaymet');
        
        // Usar return para asegurarse de que la ejecución termine aquí
        return redirect()->route('checkout.order_confirmed');
    } catch (\Exception $e) {
        Log::error('Error en makeManualPaymet: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        flash(translate('Error al procesar su orden: ') . $e->getMessage())->error();
        return redirect()->route('home');
    }
}

    public function checkout_done($combined_order_id, $payment)
    {
        try {
            $combined_order = CombinedOrder::with('orders')->findOrFail($combined_order_id);
            $orders = $combined_order->orders;

            // Logging para debug
            \Log::info('Processing checkout_done for order: ' . $combined_order_id);

            $response = PedidosYaController::confirmShipping(
                session()->get('estimateId'),
                session()->get('deliveryOfferId')
            );

            $ncfData = NfcVouchersController::updateNfcOrder();
            $deliveryService = DeliveryService::create([
                'delivery_company' => session()->get('shippingType'),
                'py_info' => json_encode($response)
            ]);
            $deliveryService = DeliveryService::create([
                'delivery_company' => session()->get('shippingType'),
                'py_info' => json_encode($response)
            ]);

            foreach ($orders as $order) {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_details' => $payment,
                    'ncf_id' => $ncfData['ncf_id'],
                    'nro_ncf' => $ncfData['nro_ncf']
                ]);

                $order->orderDetails()->each(function ($orderDetail) use ($deliveryService) {
                    $orderDetail->update([
                        'payment_status' => 'paid',
                        'delivery_service_id' => $deliveryService->id
                    ]);
                });
            }

            Session::put('combined_order_id', $combined_order_id);

            // Logging exitoso
            \Log::info('Checkout completed successfully for order: ' . $combined_order_id);

            return redirect()->route('checkout.order_confirmed')->with('success', 'Order processed successfully');
        } catch (\Exception $e) {
            // Log el error
            \Log::error('Error in checkout_done: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('home')->with('error', 'There was an error processing your order.');
        }
    }

    public function getShippingInfo()
    {
        return view('frontend.shipping_info', [
            'addresses' => Address::ownedByCurrentUser()->get()
        ]);
    }

    public function storeDeliveryInfo(Request $request)
    {
        $carts = Cart::with(['address', 'product'])->ownedByCurrentUser()->get();
        $shipping_info = $carts->first()->address;

        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        $added_shipping_costs = [];

        foreach ($carts->groupBy('shop_id') as $shop_id => $shopCarts) {
            $added_shipping_types = [];

            foreach ($shopCarts as $cart) {
                $product = $cart->product;

                $tax += cart_product_tax($cart, $product, false) * $cart->quantity;
                $subtotal += cart_product_price($cart, $product, false, false) * $cart->quantity;


                if (!isset($added_shipping_types[$cart->shipping_type])) {
                    if (!isset($shipping_costs_by_shop[$shop_id])) {
                        $shipping_costs_by_shop[$shop_id] = 0;
                    }
                    $shipping_costs_by_shop[$shop_id] += $cart->shipping_cost;

                    // Marcar el costo de envío para este tipo como ya agregado para esta tienda
                    $added_shipping_types[$cart->shipping_type] = true;
                }
            }
        }

        // Sumar todos los costos de envío por tienda al total de envío
        $shipping = array_sum($shipping_costs_by_shop);

        $total = $subtotal + $tax + $shipping;

        return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));
    }

    public function applyCouponCode(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = array();

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->get();

                    $coupon_discount = 0;

                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }

                    if ($coupon_discount > 0) {
                        Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1
                                ]
                            );
                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        $carts = Cart::ownedByCurrentUser()->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html' => $returnHTML));
    }

    public function removeCouponCode(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
            ->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function applyClubPoint(Request $request)
    {
        if (addon_is_activated('club_point')) {

            $point = $request->point;

            if (Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            } else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function removeClubPoint(Request $request)
    {
        $request->session()->forget('club_point');
        return back();
    }

    public function orderConfirmed()
{
    try {
        $combined_order_id = Session::get('combined_order_id');
        Log::info('Ejecutando orderConfirmed para combined_order_id: ' . $combined_order_id);
        
        if (!$combined_order_id) {
            Log::error('No se encontró combined_order_id en la sesión en orderConfirmed');
            flash(translate('No se pudo encontrar la información de su orden.'))->error();
            return redirect()->route('home');
        }

        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        $isAvailableToWorkshop = false;

        // Elimina del cart las compras que se realizaron
        Cart::where('user_id', $combined_order->user_id)
            ->delete();

        // Get all children categories From Vehículos and Motocicletas
        $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

        // Get all children categories From Vehículos and Motocicletas
        $orders = collect();
        $products_to_install = collect();

        $combined_order->orders()->each(function ($order) use ($orders, $availableCategories, &$isAvailableToWorkshop, &$products_to_install) {
            // Check if some product can be installed
            $orderDetails = $order->orderDetails()->get();

            try {
                $array['view'] = 'emails.invoice';
                $array['subject'] = 'Tu orden ha sido creada - ' . $order->code;
                $array['from'] = env('MAIL_USERNAME');
                $array['order'] = $order;

                Mail::to(User::find($order->user_id)->email)
                    ->bcc($order->shop->user->email)
                    ->send(new InvoiceEmailManager($array));
            } catch (\Exception $e) {
                Log::error('Error al enviar email de confirmación: ' . $e->getMessage());
                // No interrumpir el flujo por un error de correo
            }

            foreach ($orderDetails as $orderDetail) {
                // Get the category name of product
                $productName = $orderDetail->product->category->name ?? null;
                if ($productName && $availableCategories->contains($productName)) {
                    $isAvailableToWorkshop = true;
                    $products_to_install->push([
                        'order_id' => $order->id,
                        'product' => $orderDetail->product
                    ]);
                    break;
                }
            }

            $orders->push($orderDetails);
        });

        Log::info('orderConfirmed ejecutado correctamente. Mostrando vista de confirmación.');
        return view('frontend.order_confirmed', compact('combined_order', 'orders', 'isAvailableToWorkshop', 'products_to_install'));
    } catch (\Exception $e) {
        Log::error('Error en orderConfirmed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        flash(translate('Error al mostrar la información de su orden.'))->error();
        return redirect()->route('home');
    }
}
    // public function orderConfirmed()
    // {
    //     try {

    //         $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

    //         $isAvailableToWorkshop = false;

    //         //elimina del cart las compra que se realizo
    //         Cart::where('user_id', $combined_order->user_id)
    //             ->delete();

    //         // Get all children categories From Vehículos and Motocicletas
    //         $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

    //         // Get all children categories From Vehículos and Motocicletas
    //         $orders = collect();
    //         $products_to_install = collect();

    //         $combined_order->orders()->each(function ($order) use ($orders, $availableCategories, &$isAvailableToWorkshop, &$products_to_install) {
    //             // Check if some product can be installed
    //             $orderDetails = $order->orderDetails()->get();

    //             $array['view'] = 'emails.invoice';
    //             $array['subject'] = 'Tu orden ah sido creada - ' . $order->code;
    //             $array['from'] = env('MAIL_USERNAME');
    //             $array['order'] = $order;

    //             Mail::to(User::find($order->user_id)->email)->bcc($order->shop->user->email)->send(new InvoiceEmailManager($array));

    //             foreach ($orderDetails as $orderDetail) {
    //                 // Get the category name of product
    //                 $productName = $orderDetail->product->category->name ?? null;
    //                 if ($productName && $availableCategories->contains($productName)) {
    //                     $isAvailableToWorkshop = true;
    //                     $products_to_install->push(
    //                         [
    //                             'order_id' => $order->id,
    //                             'product' => $orderDetail->product
    //                         ]
    //                     );
    //                     break;
    //                 }
    //             }

    //             $orders->push($orderDetails);
    //         });

    //         return view('frontend.order_confirmed', compact('combined_order', 'orders', 'isAvailableToWorkshop', 'products_to_install'));
    //     } catch (\Exception $e) {
    //         \Log::error('Error in orderConfirmed: ' . $e->getMessage());
    //         return redirect()->route('home')->with('error', 'There was an error displaying your order.');
    //     }
    // }
}
