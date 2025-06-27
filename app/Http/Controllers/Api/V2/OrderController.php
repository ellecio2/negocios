<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Api\V2\Delivery\PedidosYaController;
use App\Http\Controllers\CategoryController;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CategoryTranslation;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\DeliveryService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\UserHasConversation;
use App\Services\CommissionService;
use App\Utility\NotificationUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller {
    public function store(Request $request, $set_paid = false) {
        $user = User::find(auth()->id());
        $carts = Cart::where('user_id', $user->id)->get();
        $shippingAddress = [];

        if (get_setting('minimum_order_amount_check') == 1) {
            $subtotal = 0;

            foreach ($carts as $cart) {
                $product = Product::find($cart->product_id);
                $subtotal += cart_product_price($cart, $product, false, false) * $cart->quantity;
            }

            if ($subtotal < get_setting('minimum_order_amount')) {
                return $this->failed("You order amount is less then the minimum order amount");
            }
        }

        $address = Address::find($carts->first()->address_id);

        if ($address != null) {
            $shippingAddress['name'] = $user->name;
            $shippingAddress['email'] = $user->email;
            $shippingAddress['address'] = $address->address;
            $shippingAddress['country'] = $address->country;
            $shippingAddress['state'] = $address->state;
            $shippingAddress['city'] = $address->city;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone'] = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $combined_order = CombinedOrder::create([
            'user_id' => $user->id,
            'shipping_address' => json_encode($shippingAddress),
        ]);

        $carts_by_seller_id = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->product->user_id;
            });

        foreach ($carts_by_seller_id as $carts) {
            $order = Order::make([
                'combined_order_id' => $combined_order->id,
                'user_id' => $user->id,
            ]);

            foreach ($carts as $cart) {
                $order->shop_id = $cart->product->user->shop->id;
                $user = User::find($cart->product->user->id);
                $order->category_translation_id =  CategoryTranslation::find($user->category_translation_id)->id;
            }

            $order->shipping_address = $combined_order->shipping_address;
            $order->order_from = 'app';
            $order->payment_type = $request->payment_type;
            $order->payment_status = ($request->payment_type != 'manual_payment_1') ? 'paid' : 'unpaid';
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;

            // Agrupar carritos por tipo de envío
            $groupedCarts = $carts->groupBy('shipping_type');

            foreach ($groupedCarts as $shippingType => $cartsGroup) {
                // Intentar obtener el primer carrito y su estimación de envío
                $firstCart = $cartsGroup->first();
                $estimateShipping = $firstCart ? $firstCart->deliveryEstimate()->where('name', $shippingType)->first() : null;

                if ($shippingType == 'PEDIDOS YA') {
                    // Verificar si se encontró la estimación de envío y si contiene la información necesaria
                    if ($estimateShipping && !empty($estimateShipping->delivery_info)) {
                        $json = json_decode($estimateShipping->delivery_info, true);

                        // Verificar si la decodificación fue exitosa y si los datos necesarios están presentes
                        if ($json && isset($json['deliveryOffers'][0]['deliveryOfferId'], $json['estimateId'])) {
                            $deliveryOfferId = $json['deliveryOffers'][0]['deliveryOfferId'];
                            $estimateId = $json['estimateId'];

                            // Confirmar el envío con PedidosYa y crear el registro de DeliveryService
                            $response = PedidosYaController::confirmShipping($estimateId, $deliveryOfferId);
                            if ($response) {
                                $deliveryService = DeliveryService::create([
                                    'delivery_company' => $shippingType,
                                    'py_info' => json_encode($response)
                                ]);
                            } else {
                                Log::error("Error al confirmar el envío con PedidosYa para el tipo de envío: $shippingType");
                            }
                        } else {
                            Log::error("Datos necesarios no encontrados en la información de entrega para el tipo de envío: $shippingType");
                        }
                    } else {
                        Log::error("Estimación de envío no encontrada para el tipo de envío: $shippingType");
                    }
                } else {
                    // TODO: Implementar proceso de Transporte blanco
                    $deliveryService = DeliveryService::create([
                        'delivery_company' => $shippingType,
                    ]);
                }

                //Order Details Storing
                foreach ($carts as $cart) {
                    $product = Product::find($cart->product_id);
                    $subtotal += cart_product_price($cart, $product, false, false) * $cart->quantity;
                    $tax += cart_product_tax($cart, $product, false) * $cart->quantity;
                    $coupon_discount += $cart->discount;
                    $product_variation = $cart->variation;
                    $product_stock = $product->stocks->first();

                    if ($product->digital != 1 && $cart->quantity > $product_stock->qty) {
                        $order->delete();
                        $combined_order->delete();
                        return response()->json([
                            'combined_order_id' => 0,
                            'result' => false,
                            'message' => translate('The requested quantity is not available for ') . $product->name
                        ]);
                    } elseif ($product->digital != 1) {
                        $product_stock->qty -= $cart->quantity;
                        $product_stock->save();
                    }

                    $order_detail = OrderDetail::create([
                        'order_id' => $order->id,
                        'seller_id' => $product->user_id,
                        'product_id' => $product->id,
                        'variation' => $product_variation,
                        'price' => cart_product_price($cart, $product, false, false) * $cart->quantity,
                        'tax' => cart_product_tax($cart, $product, false) * $cart->quantity,
                        'shipping_type' => $cart->shipping_type,
                        'product_referral_code' => $cart->product_referral_code,
                        'shipping_cost' => $cart->shipping_cost
                    ]);

                    $shipping += $order_detail->shipping_cost;

                    //End of storing shipping cost
                    if (addon_is_activated('club_point')) {
                        $order_detail->earn_point = $product->earn_point;
                    }

                    $order_detail->quantity = $cart->quantity;
                    $order_detail->save();

                    $product->num_of_sale = $product->num_of_sale + $cart->quantity;
                    $product->save();

                    $order->seller_id = $product->user_id;

                    //======== Added By Kiron ==========
                    $order->shipping_type = $cart->shipping_type;

                    if ($cart->shipping_type == 'pickup_point') {
                        $order->pickup_point_id = $cart->pickup_point;
                    }

                    if ($product->added_by == 'seller' && $product->user->seller != null) {
                        $seller = $product->user->seller;
                        $seller->num_of_sale += $cart->quantity;
                        $seller->save();
                    }
                }

            }

            CommissionService::create($order, $subtotal);

            $order->grand_total = $subtotal + $tax + $shipping;

            if ($carts[0]->coupon_code != null) {
                // if (Session::has('club_point')) {
                //     $order->club_point = Session::get('club_point');
                // }
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;
                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = $user->id;
                $coupon_usage->coupon_id = Coupon::where('code', $carts[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }
            $combined_order->grand_total += $order->grand_total;

            if (str_contains($request->payment_type, "manual_payment_")) { // if payment type like  manual_payment_1 or  manual_payment_25 etc)
                $order->manual_payment = 1;
                $order->save();
            }

            $order->save();
        }

        $combined_order->save();

        Cart::where('user_id', auth()->user()->id)->delete();

        return response()->json([
            'combined_order_id' => $combined_order->id,
            'result' => true,
            'message' => translate('Your order has been placed successfully'),
            'order' => $combined_order->load('orders')->load('orders.orderDetails')->load('orders.orderDetails.product')->load('orders.orderDetails.product.thumbnail')
        ]);
    }

    public function order_cancel($id) {
        $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($order && ($order->delivery_status == __('pending') && $order->payment_status == __('unpaid'))) {
            $order->delivery_status = __('cancelled');
            $order->save();
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delivery_status = __('cancelled');
                $orderDetail->save();
                product_restock($orderDetail);
            }
            return $this->success(__('Order has been canceled successfully'));
        } else {
            return $this->failed(__('Something went wrong'));
        }
    }

    public function checkWorkshopAvailability($combined_order_id) {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);
        $isAvailableToWorkshop = false;

        // Get all children categories From Vehículos and Motocicletas
        $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

        $orders = collect();
        $products_to_install = collect();

        $combined_order->orders()->each(function ($order) use ($orders, $availableCategories, &$isAvailableToWorkshop, &$products_to_install) {
            // Check if some product can be installed
            $orderDetails = $order->orderDetails()->get();
            foreach ($orderDetails as $orderDetail) {
                // Get the category name of product
                $productName = $orderDetail->product->category->name ?? null;
                if ($productName && $availableCategories->contains($productName)) {
                    $isAvailableToWorkshop = true;
                    $products_to_install->push([
                        'orderId' => $order->id,
                        'productData' => $orderDetail->product]
                    );
                }
            }

            $orders->push($orderDetails);
        });

        return response()->json([
            'isAvailableToWorkshop' => $isAvailableToWorkshop,
            'productsAvailableToInstall' => $products_to_install
        ]);
    }

    public function checkWorkshopAvailabilityPerOrder($order_id) {
        $order = Order::find($order_id);
        $isAvailableToWorkshop = false;

        // Get all children categories From Vehículos and Motocicletas
        $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

        $orders = collect();
        $products_to_install = collect();

        foreach($order->orderDetails as $orderDetail){
            $productName = $orderDetail->product->category->name ?? null;
            if ($productName && $availableCategories->contains($productName)) {
                $isAvailableToWorkshop = true;
                $products_to_install->push([
                        'orderId' => $order->id,
                        'productData' => $orderDetail->product] 
                );
            }
        }

        return response()->json([
            'isAvailableToWorkshop' => $isAvailableToWorkshop,
            'productsAvailableToInstall' => $products_to_install
        ]);
    }

    public function workshopRequestStatus(){
        return response()->json([
            'userHasOpenedProcess' => UserHasConversation::where('user_id', auth()->id())->exists()
        ]);
    }
}



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
