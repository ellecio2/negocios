<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController {
    public function apply_coupon_code(Request $request) {
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if ($coupon == null) {
            return response()->json([
                'result' => false,
                'message' => translate('Invalid coupon code!')
            ]);
        }

        $cart_items = Cart::where('user_id', auth()->user()->id)
                         ->where('owner_id', $coupon->user_id)
                         ->with('product')
                         ->get();

        $coupon_discount = 0;
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        $existing_discount = 0;

        if ($cart_items->isEmpty()) {
            return response()->json([
                'result' => false,
                'message' => translate('This coupon is not applicable to your cart products!')
            ]);
        }

        $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

        if (!$in_range) {
            return response()->json([
                'result' => false,
                'message' => translate('Coupon expired!')
            ]);
        }

        $is_used = CouponUsage::where('user_id', auth()->user()->id)
                             ->where('coupon_id', $coupon->id)
                             ->exists();

        if ($is_used) {
            return response()->json([
                'result' => false,
                'message' => translate('You already used this coupon!')
            ]);
        }

              // Calcular subtotal, tax, shipping y descuento existente para todos los productos
              foreach ($cart_items as $cartItem) {
                $item_subtotal = $cartItem->price * $cartItem->amount;
                $subtotal += $item_subtotal;
                
                // Calcular el tax por item basado en el subtotal del item
                $item_tax = ($item_subtotal * $cartItem->tax) / 100;
                $tax += $item_tax;
                
                $shipping += $cartItem->shipping_cost * $cartItem->amount;
                $existing_discount += $cartItem->discount * $cartItem->amount;
            }
            
            $total_before_discount = $subtotal + $tax + $shipping - $existing_discount;
    

        $coupon_details = json_decode($coupon->details);
        $applicable_cart_items = [];

        if ($coupon->type == 'cart_base') {
            $eligible_subtotal = $subtotal - $existing_discount; // Subtotal sin descuento previo
            if ($eligible_subtotal >= $coupon_details->min_buy) {
                if ($coupon->discount_type == 'percent') {
                    $coupon_discount = ($eligible_subtotal * $coupon->discount) / 100;
                    if ($coupon_discount > $coupon_details->max_discount) {
                        $coupon_discount = $coupon_details->max_discount;
                    }
                } elseif ($coupon->discount_type == 'amount') {
                    $coupon_discount = $coupon->discount;
                }
            }
            $applicable_cart_items = $cart_items;
        } elseif ($coupon->type == 'product_base') {
            foreach ($cart_items as $cartItem) {
                foreach ($coupon_details as $coupon_detail) {
                    if ($coupon_detail->product_id == $cartItem->product_id) {
                        $item_subtotal = $cartItem->price * $cartItem->quantity; // Subtotal solo del producto elegible
                        $item_discount = 0;
                        if ($coupon->discount_type == 'percent') {
                            $item_discount = $item_subtotal * ($coupon->discount / 100);
                        } elseif ($coupon->discount_type == 'amount') {
                            $item_discount = $coupon->discount * $cartItem->quantity;
                        }
                        $coupon_discount += $item_discount;
                        $applicable_cart_items[] = [
                            'cart_item' => $cartItem,
                            'discount' => $item_discount
                        ];
                    }
                }
            }
        }

        // Calcular el monto final
        $final_amount = $subtotal + $tax + $shipping - $existing_discount - $coupon_discount;

        if ($coupon_discount > 0 && !empty($applicable_cart_items)) {
            foreach ($applicable_cart_items as $applicable) {
                if ($coupon->type == 'cart_base') {
                    $discount_per_item = $coupon_discount / count($cart_items);
                    $applicable->update([
                        'discount' => $discount_per_item,
                        'coupon_code' => $request->coupon_code,
                        'coupon_applied' => 1
                    ]);
                } elseif ($coupon->type == 'product_base') {
                    $applicable['cart_item']->update([
                        'discount' => $applicable['discount'],
                        'coupon_code' => $request->coupon_code,
                        'coupon_applied' => 1
                    ]);
                }
            }

            return response()->json([
                'result' => true,
                'message' => translate('Coupon Applied'),
                'data' => [
                    'discount' => (double) $coupon_discount,
                    'total_before_discount' => (double) $total_before_discount,
                    'final_amount' => (double) $final_amount,
                    'subtotal' => (double) $subtotal,
                    'tax' => (double) $tax,
                    'shipping' => (double) $shipping,
                    'existing_discount' => (double) $existing_discount
                ],
                'coupon_type' => $coupon->type,
                'coupon_discount_type' => $coupon->discount_type,
                'coupon_discount' => $coupon->discount,
                'coupon_details' => $coupon_details,
            ]);
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('This coupon is not applicable to your cart products!'),
                'data' => [
                    'total_before_discount' => (double) $total_before_discount,
                    'final_amount' => (double) $total_before_discount,
                    'subtotal' => (double) $subtotal,
                    'tax' => (double) $tax,
                    'shipping' => (double) $shipping,
                    'existing_discount' => (double) $existing_discount
                ]
            ]);
        }
    }

    public function remove_coupon_code(Request $request) {
        Cart::where('user_id', auth()->user()->id)->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        $cart_items = Cart::where('user_id', auth()->user()->id)->get();
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        
        foreach ($cart_items as $cartItem) {
            $item_subtotal = $cartItem->price * $cartItem->quantity;
            $subtotal += $item_subtotal;
            
            // Calcular el tax por item basado en el subtotal del item
            $item_tax = ($item_subtotal * $cartItem->tax) / 100;
            $tax += $item_tax;
            
            $shipping += $cartItem->shipping_cost * $cartItem->quantity;
        }
        
        $final_amount = $subtotal + $tax + $shipping;

        return response()->json([
            'result' => true,
            'message' => translate('Coupon Removed'),
            'data' => [
                'final_amount' => (double) $final_amount,
                'subtotal' => (double) $subtotal,
                'tax' => (double) $tax,
                'shipping' => (double) $shipping
            ]
        ]);
    }
}