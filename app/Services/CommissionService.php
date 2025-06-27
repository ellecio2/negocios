<?php

namespace App\Services;

use App\Models\BusinessSetting;
use App\Models\CommissionHistory;
use App\Models\Order;

class CommissionService
{
    public static function create(Order $order, float $subtotal)
    {

        $order_detail_ids = $order->orderDetails;
        /*        $new_order = Order::find($order->id);
                dd($order_detail_ids, $new_order->orderDetails);*/

        foreach ($order_detail_ids as $order_detail_id) {

            //dd($subtotal, $order_detail_id, $new_order->orderDetails);
            $vendor_commission = BusinessSetting::where('type', 'vendor_commission')->first()->value;
            $itbs = $order_detail_id->price * (config('app.itbis') / 100);

            $seller_commission = $order_detail_id->price / (1 + ($vendor_commission / 100));
            $seller_earning = $order_detail_id->price - ($order_detail_id->price * ($vendor_commission / 100));
            $re_seller_commission = $seller_commission;
            $admin_commission = $order_detail_id->price - $seller_earning;

            if ($order->order_from == 'pos') {
                $order->orderDetails->load('product')->each(function ($order_detail) use (&$commission, $subtotal, &$re_seller_commission) {
                    $commission = $order_detail->product->taxes()->where('tax_id', 4)->first()->tax;
                    $re_seller_commission += $subtotal * ($commission / 100);
                });
            }

            // Create commission for each order detail

/*            dd([
                'seller_id' => auth()->id(),
                'order_id' => $order->id,
                'order_detail_id' => $order_detail_id->id,
                'admin_commission' => $admin_commission,
                'seller_earning' => $seller_earning,
                're_seller_earning' => 0,
                'itbis' => $itbs
            ]);*/

            CommissionHistory::create([
                'seller_id' => auth()->id(),
                'order_id' => $order->id,
                'order_detail_id' => $order_detail_id->id,
                'admin_commission' => $admin_commission,
                'seller_earning' => $seller_earning,
                're_seller_earning' => 0,
                'itbis' => $itbs
            ]);
        }
    }
}
