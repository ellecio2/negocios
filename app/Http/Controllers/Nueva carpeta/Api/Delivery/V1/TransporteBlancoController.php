<?php

namespace App\Http\Controllers\Api\Delivery\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class TransporteBlancoController extends Controller {
    public static function checkAvailable(Cart $cart){
        return true;
    }

    public static function getDeliveryData(Cart $cart){
        $sellerAddress = Address::where('user_id', $cart->product->user_id )->default()->first();
        $buyerAddress = Address::where('user_id', $cart->user->id)->default()->first();
        // Get the calcs from the API
        $data = [
            'customerId' => $cart->user->id,
            'productId' => $cart->product->id,
            'quantity' => $cart->quantity,
            'address' => $buyerAddress->address,
            'latitude' => $buyerAddress->latitude,
            'longitude' => $buyerAddress->longitude,
            'country' => $buyerAddress->country,
            'state' => $buyerAddress->state,
            'city' => $buyerAddress->city,
            'isTransferred' => false
        ];

        $response = self::makeRequest($data);

        \Log::alert($response);

        $transporteBlanco = [
            'shippingCostId' => $response['shippingCostId'],
            'delivery' => [
                'starter_price' => $response['starter_price'],
                'ending_price' => $response['ending_price'],
                'estimated_pickup_time' => $response['estimated_pickup_time'],
                'estimated_dropoff_time' => $response['estimated_dropoff_time']
            ],
            'dropOffPoint' => $buyerAddress
        ];

        return $transporteBlanco;
    }

    private static function makeRequest($data) {
        $data = json_encode($data);
        $purchase = curl_init( config('app.transporte_blanco_pricing_uri') );
        curl_setopt($purchase, CURLOPT_URL, config('app.transporte_blanco_pricing_uri') );
        //TODO: cambiar a true
        curl_setopt($purchase, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($purchase, CURLOPT_POST, true);

        curl_setopt($purchase, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "System-Key: $2y$10\$Kg.gJFL/SsktHHcYYajgMuAQGo3HOV7zS8pBCuBrP4KNtvXol58qu",
            "Content-Type: application/json"
        ]);
        curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($purchase);
        curl_close($purchase);

        return json_decode($resp, true);
    }
}
