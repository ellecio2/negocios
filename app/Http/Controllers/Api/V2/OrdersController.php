<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller {
    public function index(Request $request){
        $combinedOrders = CombinedOrder::latest('id')->where('user_id', $request->user()->id)->get();

        if($request->orderDetails){
            $combinedOrders->load('orders')->load('orders.orderDetails');
        }

        return response()->json($combinedOrders);
    }

//     public function show($id){
//         $combined_order = CombinedOrder::find($id);
//         return response()->json($combined_order->load('orders')->load('orders.orderDetails')->load('orders.orderDetails.product')->load('orders.orderDetails.product.thumbnail'));
//     }

public function showByCode($code)
{
    $order = \App\Models\Order::where('code', $code)
        ->with([
            'orderDetails',
            'orderDetails.product',
            'orderDetails.product.thumbnail',
            'user',
            'shop',
            'pickup_point'

        ])
        ->first();

    if (!$order) {
        return response()->json([
            'message' => 'Order not found',
        ], 404);
    }

    return response()->json($order);
}

public function showByTrackingCode($code)
{
    $order = \App\Models\Order::where('tracking_code', $code)
        ->with([
            'orderDetails',
            'orderDetails.product',
            'orderDetails.product.thumbnail',
            'user',
            'shop',
            'pickup_point'

        ])
        ->first();

    if (!$order) {
        return response()->json([
            'message' => 'Order not found',
        ], 404);
    }

    return response()->json($order);
}


}