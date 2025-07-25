<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\CategoryController;
use App\Http\Requests\Api\V2\Carts\SetDeliveryOptionRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingCost;
use App\Models\Shop;
use App\Utility\CartUtility;
use App\Utility\NagadUtility;
use Illuminate\Http\Request;

class CartController extends Controller {
    public function summary() {
        $items = auth()->user()->carts;
        if ($items->isEmpty()) {
            return response()->json([
                'sub_total' => format_price(0.00),
                'tax' => format_price(0.00),
                'shipping_cost' => format_price(0.00),
                'discount' => format_price(0.00),
                'grand_total' => format_price(0.00),
                'grand_total_value' => 0.00,
                'coupon_code' => "",
                'coupon_applied' => false,
            ]);
        }
    
        $sum = 0.00;
        $shipping = 0;
        $subtotal = 0;
        $tax = 0;
        $shipping_costs_by_shop = [];
    
        foreach ($items->groupBy('shop_id') as $shop_id => $shopCarts) {
            $added_shipping_types = [];
    
            foreach ($shopCarts as $cart) {
                $product = $cart->product;
                
                // Calculate tax for each item quantity
                $item_tax = cart_product_tax($cart, $product, false) * $cart->quantity;
                $tax += $item_tax;
    
                // Calculate subtotal for each item quantity  
                $item_price = cart_product_price($cart, $product, false, false) * $cart->quantity;
                $subtotal += $item_price;
    
                if (!isset($added_shipping_types[$cart->shipping_type])) {
                    if (!isset($shipping_costs_by_shop[$shop_id])) {
                        $shipping_costs_by_shop[$shop_id] = 0;
                    }
                    $shipping_costs_by_shop[$shop_id] += $cart->shipping_cost;
                    $added_shipping_types[$cart->shipping_type] = true;
                }
            }
        }
    
        $shipping = array_sum($shipping_costs_by_shop);
        $discount = $items->sum('discount');
        $sum = ($subtotal + $tax + $shipping) - $discount;
    
        return response()->json([
            'sub_total' => single_price($subtotal),
            'tax' => single_price($tax),
            'shipping_cost' => single_price($shipping),
            'discount' => single_price($discount),
            'grand_total' => single_price($sum),
            'grand_total_value' => convert_price($sum),
            'coupon_code' => $items[0]->coupon_code,
            'coupon_applied' => $items[0]->coupon_applied == 1,
        ]);
    }

    public function count() {
        $items = auth()->user()->carts;
        return response()->json([
            'count' => sizeof($items),
            'status' => true,
        ]);
    }

    public function getList() {
        $owner_ids = Cart::where('user_id', auth()->user()->id)->select('owner_id')->groupBy('owner_id')->pluck('owner_id')->toArray();
        $currency_symbol = currency_symbol();
        $shops = [];
        $sub_total = 0.00;
        $grand_total = 0.00;
        $isAvailableToWorkshop = false;
        $availableCategories = CategoryController::getCategoryWithChildrens(['Vehículos', 'Motocicletas']);

        if (!empty($owner_ids)) {
            foreach ($owner_ids as $owner_id) {
                $shop = array();
                $shop_items_raw_data = Cart::where('user_id', auth()->user()->id)->where('owner_id', $owner_id)->get()->toArray();
                $shop_items_data = array();
                if (!empty($shop_items_raw_data)) {
                    foreach ($shop_items_raw_data as $shop_items_raw_data_item) {
                        $product = Product::where('id', $shop_items_raw_data_item["product_id"])->first();
                        $price = cart_product_price($shop_items_raw_data_item, $product, false, false) * intval($shop_items_raw_data_item["quantity"]);
                        $tax = cart_product_tax($shop_items_raw_data_item, $product, false);
                        $shop_items_data_item["id"] = intval($shop_items_raw_data_item["id"]);
                        $shop_items_data_item["owner_id"] = intval($shop_items_raw_data_item["owner_id"]);
                        $shop_items_data_item["user_id"] = intval($shop_items_raw_data_item["user_id"]);
                        $shop_items_data_item["product_id"] = intval($shop_items_raw_data_item["product_id"]);
                        $shop_items_data_item["product_name"] = $product->getTranslation('name');
                        $shop_items_data_item["slug"] = $product->slug;
                        $shop_items_data_item["weight"] = $product->weight;
                        $shop_items_data_item["auction_product"] = $product->auction_product;
                        $shop_items_data_item["product_thumbnail_image"] = uploaded_asset($product->thumbnail_img);
                        $shop_items_data_item["variation"] = $shop_items_raw_data_item["variation"];
                        $shop_items_data_item["price"] = (float)cart_product_price($shop_items_raw_data_item, $product, false, false);
                        $shop_items_data_item["currency_symbol"] = $currency_symbol;
                        $shop_items_data_item["discount"] = intval($shop_items_raw_data_item["discount"]);
                        $shop_items_data_item["tax"] = (float)cart_product_tax($shop_items_raw_data_item, $product, false);
                        $shop_items_data_item["price"] = single_price($price);
                        $shop_items_data_item["currency_symbol"] = $currency_symbol;
                        $shop_items_data_item["tax"] = single_price($tax);
                        // $shop_items_data_item["tax"] = (float) cart_product_tax($shop_items_raw_data_item, $product, false);
                        $shop_items_data_item["shipping_cost"] = (float)$shop_items_raw_data_item["shipping_cost"];
                        $shop_items_data_item["quantity"] = intval($shop_items_raw_data_item["quantity"]);
                        $shop_items_data_item["lower_limit"] = intval($product->min_qty);
                        $shop_items_data_item["upper_limit"] = intval($product->stocks->first()->qty);
                        $sub_total += $price + $tax;
                        $shop_items_data[] = $shop_items_data_item;
                    }
                }
                $grand_total += $sub_total;
                $shop_data = Shop::where('user_id', $owner_id)->first();
                if ($shop_data) {
		    $shop['id'] = $shop_data->id;
                    $shop['name'] = $shop_data->name;
                    $shop['address'] = $shop_data->address;
                      $shop['phone'] = $shop_data->phone;
                    $shop['country'] = $shop_data->country;
                    $shop['city'] = $shop_data->city;
                     $shop['delivery_pickup_latitude'] = $shop_data->delivery_pickup_latitude;
                     $shop['delivery_pickup_longitude'] = $shop_data->delivery_pickup_longitude;
                    $shop['owner_id'] = (int)$owner_id;
                    $shop['sub_total'] = single_price($sub_total);
                    $shop['cart_items'] = $shop_items_data;
                } else {
		    $shop['id'] = $shop_data->id;
                    $shop['name'] = "Inhouse";
                    $shop['address'] = $shop_data->address;
                    $shop['phone'] = $shop_data->phone;
                    $shop['country'] = $shop_data->country;
                     $shop['city'] = $shop_data->city;
                    $shop['delivery_pickup_latitude'] = $shop_data->delivery_pickup_latitude;
                    $shop['delivery_pickup_longitude'] = $shop_data->delivery_pickup_longitude;
                    $shop['owner_id'] = (int)$owner_id;
                    $shop['sub_total'] = single_price($sub_total);
                    $shop['cart_items'] = $shop_items_data;
                }
                $shops[] = $shop;
                $sub_total = 0.00;
            }
        }

        $carts = Cart::where('user_id', auth()->id())->get();
        $carts->each(function ($cart) use ($availableCategories, &$isAvailableToWorkshop) {
            $productName = $cart->product->category->name ?? null;
            if ($productName && $availableCategories->contains($productName)) {
                $isAvailableToWorkshop = true;
            }
        });

        //dd($shops);
        return response()->json([
            "grand_total" => single_price($grand_total),
            'isAvailableToWorkshop' => $isAvailableToWorkshop,
            "data" =>
                $shops
        ]);
    }

    public function add(Request $request) {
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::findOrFail($request->id);
        if ($check_auction_in_cart && $product->auction_product == 0) {
            return response()->json([
                'result' => false,
                'message' => translate('Remove auction product from cart to add this product.')
            ], 200);
        }
        if ($check_auction_in_cart == false && count($carts) > 0 && $product->auction_product == 1) {
            return response()->json([
                'result' => false,
                'message' => translate('Remove other products from cart to add this auction product.')
            ], 200);
        }
        if ($product->min_qty > $request->quantity) {
            return response()->json([
                'result' => false,
                'message' => translate("Minimum") . " {$product->min_qty} " . translate("item(s) should be ordered")
            ], 200);
        }
        $variant = $request->variant;
        $tax = 0;
        $quantity = $request->quantity;
        $product_stock = $product->stocks->first();
        $cart = Cart::firstOrNew([
            'variation' => $variant,
            'user_id' => auth()->user()->id,
            'product_id' => $request['id']
        ]);
        $variant_string = $variant != null && $variant != "" ? translate("for") . " ($variant)" : "";
        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return response()->json([
                    'result' => false,
                    'message' => translate('This auction product is already added to your cart.')
                ], 200);
            }
            if ($product_stock->qty < $cart->quantity + $request['quantity']) {
                if ($product_stock->qty == 0) {
                    return response()->json([
                        'result' => false,
                        'message' => translate("Stock out")
                    ], 200);
                } else {
                    return response()->json([
                        'result' => false,
                        'message' => translate("Only") . " {$product_stock->qty} " . translate("item(s) are available") . " {$variant_string}"
                    ], 200);
                }
            }
            if ($product->digital == 1 && ($cart->product_id == $product->id)) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Already added this product')
                ]);
            }
            $quantity = $cart->quantity + $request['quantity'];
        }
        $price = CartUtility::get_price($product, $product_stock, $request->quantity);
        $tax = CartUtility::tax_calculation($product, $price);
        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);
        /*if (NagadUtility::create_balance_reference($request->cost_matrix) == false) {
            return response()->json(['result' => false, 'message' => 'Cost matrix error']);
        }*/
        return response()->json([
            'result' => true,
            'message' => translate('Product added to cart successfully')
        ]);
    }

public function changeQuantity(Request $request) {
    $cart = Cart::find($request->id);
    if ($cart != null) {
        $product = Product::find($cart->product_id);
        if ($product->auction_product == 1) {
            return response()->json(['result' => false, 'message' => translate('Cannot modify auction product quantity')], 200);
        }

        $product_stock = \DB::table('product_stocks')
            ->where('product_id', $cart->product_id)
            ->first();  // Primero obtenemos el stock sin filtrar por variante

        if ($product_stock && $product_stock->qty >= $request->quantity) {
            $cart->update([
                'quantity' => $request->quantity
            ]);
            return response()->json(['result' => true, 'message' => translate('Cart updated')], 200);
        }

        return response()->json([
            'result' => false,
            'message' => translate('Only') . ' ' . ($product_stock ? $product_stock->qty : 0) . ' ' . translate('items available')
        ], 200);
    }
    return response()->json(['result' => false, 'message' => translate('Something went wrong')], 200);
}


    public function process(Request $request) {
        $cart_ids = explode(",", $request->cart_ids);
        $cart_quantities = explode(",", $request->cart_quantities);
        if (!empty($cart_ids)) {
            $i = 0;
            foreach ($cart_ids as $cart_id) {
                $cart_item = Cart::where('id', $cart_id)->first();
                $product = Product::where('id', $cart_item->product_id)->first();
                if ($product->min_qty > $cart_quantities[$i]) {
                    return response()->json(['result' => false, 'message' => translate("Minimum") . " {$product->min_qty} " . translate("item(s) should be ordered for") . " {$product->name}"], 200);
                }
                $stock = $cart_item->product->stocks->where('variant', $cart_item->variation)->first()->qty;
                $variant_string = $cart_item->variation != null && $cart_item->variation != "" ? " ($cart_item->variation)" : "";
                if ($stock >= $cart_quantities[$i] || $product->digital == 1) {
                    $cart_item->update([
                        'quantity' => $cart_quantities[$i]
                    ]);
                } else {
                    if ($stock == 0) {
                        return response()->json(['result' => false, 'message' => translate("No item is available for") . " {$product->name}{$variant_string}," . translate("remove this from cart")], 200);
                    } else {
                        return response()->json(['result' => false, 'message' => translate("Only") . " {$stock} " . translate("item(s) are available for") . " {$product->name}{$variant_string}"], 200);
                    }
                }
                $i++;
            }
            return response()->json(['result' => true, 'message' => translate('Cart updated')], 200);
        } else {
            return response()->json(['result' => false, 'message' => translate('Cart is empty')], 200);
        }
    }

    public function destroy($id) {
        Cart::destroy($id);
        return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your cart')], 200);
    }

    public function edit(Request $request, $id) {
        $carts = Cart::where('user_id', $request->user()->id)->get();
        $carts->load('address');

        $carts->each(function ($cart) use ($id) {
            $cart->update([
                'address_id' => $id
            ]);
        });

        return response()->json($carts);
    }

    public function setDeliveryOption(SetDeliveryOptionRequest $request, $cart_id){
        $cart = Cart::find($cart_id);
        $delivery_type = $request->input('type');

        if($delivery_type == "carrier"){
            $cart->update([
                'pickup_point' => null,
                'shipping_type' => strtoupper($request->shippingCompany),
                'shipping_cost' => (float)$request->shippingCost
            ]);

            /*ShippingCost::where('cart_id', $cart_id])
                ->where('id', '!=', $shippingCost->id)
                ->delete();

            $shippingCost->update(['type' => 'SELECTED']);*/
        }

        if($delivery_type == "pickup point"){
            $cart->update([
                'pickup_point' => $request->pickupPointId,
                'shipping_type' => null,
                'shipping_cost' => null
            ]);
        }

        return response()->json($cart);
    }
}
