<?php

namespace App\Http\Controllers\Api\Delivery\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Delivery\PedidosYaController as PedidosYaUtility;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class PedidosYaController extends Controller {

    private const AVAILABLE_AREAS = [
        'Santo Domingo',
        'Santo Domingo Este',
        'Santo Domingo Norte',
        'Santo Domingo Oeste',
        'Distrito Nacional',
        'Pedro Brand',
        'Los Alcarrizos',
        'San Antonio de Guerra',
        'Boca Chica'
    ];

    private const TOKEN = "7602-192007-1d7b6584-3874-482a-6aa4-ae3bdfc5a447";
    private const ESTIMATE_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates";
    private const CONFIRM_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates/{estimateId}/confirm";

    private const HEADERS = [
        "Accept: application/json",
        "Authorization: Bearer " . self::TOKEN,
        "Content-Type: application/json",
    ];

    public static function checkAvailable(Cart $cart) {
        $sellerAddress = Address::where('user_id', $cart->product->user_id )->default()->first();
        $buyerAddress = Address::where('user_id', $cart->user->id)->default()->first();

        //Weight on Kg
        $weight = $cart->cartWeight() / 2.205;

        // If the product exceeds the weight or volume limits, it is not eligible for delivery.
        if($weight > 20.00 || $cart->cartVolume() > 80840.00){
            return false;
        }

        // If the address of the seller or the buyer is not in the available areas, it is not eligible for delivery.
        if(!in_array($sellerAddress->city, self::AVAILABLE_AREAS) || !in_array($buyerAddress->city, self::AVAILABLE_AREAS)){
            return false;
        }

        return true;
    }

    public static function getDeliveryData(Cart $cart) {

        $sellerAddress = Address::where('user_id', $cart->product->user_id)->default()->first();
        $buyerAddress = Address::where('user_id', $cart->user->id)->default()->first();

        $body = [
            'product' => $cart->product,
            'quantity' => $cart->quantity,
            'totalWeight' => $cart->cartWeight() / 2.205,
            'totalVolume' => $cart->cartVolume(),
            'sellerAddress' => $sellerAddress,
            'buyerAddress' => $buyerAddress,
            'availableAreas' => self::AVAILABLE_AREAS,
            'isTransferred' => false
        ];

        $data = \App\Http\Controllers\Delivery\PedidosYaController::make($body);

        return $data;
    }

    public function checkCoverage(): bool {
        $carts = Cart::where('user_id', auth()->id())->get();
        $pickup_point = auth()->user()->addresses()->default()->first();

        foreach ($carts as $cart) {
            $dropoff_point = $cart->user->addresses()->default()->first();

            $body = [
                "waypoints" => [
                    [
                        "addressStreet" => $pickup_point->address,
                        "city" => $pickup_point->city,
                        "latitude" => $pickup_point->latitude,
                        "longitude" => $pickup_point->longitude,
                        "type" => "PICK_UP"
                    ],
                    [
                        "addressStreet" => $dropoff_point->address,
                        "city" => $dropoff_point->city,
                        "latitude" => $dropoff_point->latitude,
                        "longitude" => $dropoff_point->longitude,
                        "type" => "DROP_OFF"
                    ]
                ]
            ];

            $response = self::makeRequest('https://courier-api.pedidosya.com/v3/estimates/coverage', json_encode($body));

            if (!isset($response['status']) || !is_numeric($response['status'])) {
                Log::error("Respuesta inesperada de la API: " . print_r($response, true));
                $isAvailable = false;
            }

            $statusOk = (int)($response['status'] == 200);

            if ($statusOk) {
                $isAvailable = true;
            }

        }

        return $isAvailable;
    }

    private static function makeRequest(string $uri, string $data) {
        $purchase = self::initializeCurl($uri, $data);
        $resp = self::executeCurl($purchase);

        Log::warning($resp);
        return json_decode($resp, true);
    }

    private static function initializeCurl(string $uri, string $data) {
        $purchase = curl_init($uri);
        curl_setopt($purchase, CURLOPT_URL, $uri);
        curl_setopt($purchase, CURLOPT_POST, true);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($purchase, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);

        return $purchase;
    }

    private static function executeCurl($purchase) {
        $resp = curl_exec($purchase);
        $info = curl_getinfo($purchase);

        if (curl_errno($purchase)) {
            $error_msg = curl_error($purchase);
            Log::error('cURL Error: ' . $error_msg);
        }

        curl_close($purchase);

        return $resp;
    }

    private static function buildItems(Cart $cart) : array {
        $product = $cart->product;
        $unit_price = $product->unit_price;
        $quantity = $cart->quantity;

        // Check if the product value is > 2000
        $isSecure = !(($unit_price * $quantity) > 2000.00);
        // Parse cart weight to Kg
        $weight = $cart->cartWeight() / 2.205;
        // Get cart volume
        $volume = $cart->cartVolume();

        return [
            "value" => ($isSecure) ? $unit_price : 100.00,
            "description" => $product->name,
            "quantity" => $quantity,
            "volume" => $volume,
            "weight" => $weight
        ];
    }


}
