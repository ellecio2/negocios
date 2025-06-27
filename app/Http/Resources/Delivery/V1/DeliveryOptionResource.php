<?php

namespace App\Http\Resources\Delivery\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Api\Delivery\V1\PedidosYaController;
use App\Http\Controllers\Api\Delivery\V1\TransporteBlancoController;
use Illuminate\Support\Carbon;

class DeliveryOptionResource extends JsonResource {
    public function toArray($request) {
        $cart = $this->resource;

        $pedidosYaAvailable = PedidosYaController::checkAvailable($cart);
        $transporteBlancoAvailable = TransporteBlancoController::checkAvailable($cart);

        $pedidosYaData = PedidosYaController::getDeliveryData($cart);
        $transporteBlanco = TransporteBlancoController::getDeliveryData($cart);
        $transporteBlancoWhitPedidosYaAvailable = PedidosYaController::checkAvailable($cart);

        return [
            'cart_id' => $cart->id,
            'product_id' => $cart->product->id,
            'deliveryOptions' => [
                'pedidosYa' => $pedidosYaAvailable ? [
                    'pricing' => $pedidosYaData['delivery']['starter_price'] ?? null,
                    'pricingRange' => $pedidosYaData['delivery']['ending_price'] ?? null,
                    'distance' => $pedidosYaData['route']['distance'] ?? null,
                    'routeDurationInMinutes' => $pedidosYaData['route']['duration'] ?? null,
                    'shopClosed' => isset($pedidosYaData['storeAvailability']) && !$pedidosYaData['storeAvailability']['delivery_now_available'],
                    'shopClosedMessage' => $pedidosYaData['storeAvailability']['delivery_now_not_available_message'] ?? null,
                    'shopOpenendAt' => isset($pedidosYaData['storeAvailability']) ? Carbon::parse($pedidosYaData['storeAvailability']['delivery_available_on']) : null,
                ] : $pedidosYaAvailable,
                'transporteBlanco' => $transporteBlancoAvailable ? [
                    'pricing' => $transporteBlanco['delivery']['starter_price'] ?? 0.0,
                    'pricingRange' => $transporteBlanco['delivery']['starter_price'] + ($transporteBlanco['delivery']['starter_price'] * 0.25),
                    'estimatePickupTime' => $transporteBlanco['delivery']['estimated_pickup_time'] ?? null,
                    'estimateDropOffTime' => $transporteBlanco['delivery']['estimated_dropoff_time'] ?? null
                ] : $transporteBlancoAvailable,
                'transporteBlancoWhitPedidosYa' => $transporteBlancoWhitPedidosYaAvailable,
            ],
        ];
    }
}
