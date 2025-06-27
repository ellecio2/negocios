<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller {
    public array $availableAreas;
    public Product $product;
    public int $quantity;
    public array $body;
    public array $pedidosYa;
    public array $transporteBlanco;
    public array $transporteBlancoInfo;
    public bool $availableForTransporteBlanco = false;
    private static string $pricing_uri;
    private const HEADERS = [
        "Accept: application/json",
        "System-Key: $2y$10\$Kg.gJFL/SsktHHcYYajgMuAQGo3HOV7zS8pBCuBrP4KNtvXol58qu",
        "Content-Type: application/json"
    ];

    public function __construct($product, $quantity) {
        self::$pricing_uri = config('app.transporte_blanco_pricing_uri');

        $this->quantity = $quantity;
        $this->product = $product;

        $this->availableAreas = [
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

        $this->body = [
            'product' => $this->product,
            'quantity' => $this->quantity,
            'totalWeight' => $this->calcTotalWeight($product, $quantity),
            'totalVolume' => $this->extractVolume($product),
            'sellerAddress' => $this->getSellerAddress(),
            'buyerAddress' => $this->getBuyerAddress(),
            'availableAreas' => $this->availableAreas,
            'isTransferred' => false
        ];

        $this->pedidosYa = PedidosYaController::make($this->body);

        $this->transporteBlanco = [
            'available' => $this->availableForTransporteBlanco()
        ];

        if($this->transporteBlanco['available']) {
            $this->buildTransporteBlancoUsefulData();
        }
    }

    private function extractVolume(): float {
        $choise_options = json_decode($this->product->choice_options);
        $envelope = trim(str_replace('SOBRE', '', $choise_options[0]->values[0]));

        return match ($envelope) {
            'A' => $this->calcVolume(4.7, 8.4, 2.4),
            'B' => $this->calcVolume(5.9, 8.4, 2.4),
            'C' => $this->calcVolume(7, 10.4, 2.4),
            'D' => $this->calcVolume(8.6, 10.4, 2.4),
            'E' => $this->calcVolume(9, 13.3, 2.4),
            'F' => $this->calcVolume(9.4, 13.1, 2.4),
            'G' => $this->calcVolume(10.6, 8.4, 14.1),
            'H' => $this->calcVolume(11.8, 17.5, 2.4),
            'I' => $this->calcVolume(13.7, 18.5, 2.4),
            'J' => $this->calcVolume(7, 6.4, 2.4),
            default => 0.00,
        };

    }

    private function calcTotalWeight(): float {
        return $this->product->weight * $this->quantity;
    }

    private function calcVolume(int|float $lenght, int|float $width, int|float $height) : float {
        $lenght_to_cm = $lenght * 2.54;
        $width_to_cm = $width * 2.54;
        $height_to_cm = $height * 2.54;

        return $lenght_to_cm * $width_to_cm * $height_to_cm;
    }

    private function getSellerAddress(): Address {
        try{
            return $this->product->user->addresses()->where('set_default', 1)->first();
        }catch(\Exception $e){
            return true;
        }
    }

    private function getBuyerAddress(): Address {
        return Auth::user()->addresses()->where('set_default', 1)->first();
    }

    private function availableForTransporteBlanco() {
        // Weight on Pounds
        $totalWeight = $this->product->weight;
        // If the product exceeds the weight or volume limits, it is not eligible for delivery.
        if( $totalWeight > 150.00 ){
            return false;
        }
        return true;
    }
    private function buildTransporteBlancoUsefulData() {
        $buyerAddress = $this->getBuyerAddress();
        // Get the calcs from the API
        $data = [
            'customerId' => Auth::id(),
            'productId' => $this->product->id,
            'quantity' => $this->quantity,
            'address' => $buyerAddress->address,
            'latitude' => $buyerAddress->latitude,
            'longitude' => $buyerAddress->longitude,
            'country' => $buyerAddress->country,
            'state' => $buyerAddress->state,
            'city' => $buyerAddress->city,
            'isTransferred' => false
        ];
        $response = self::makeRequest($data);
        $this->transporteBlanco = [
            'available' => $this->availableForTransporteBlanco(),
            'shippingCostId' => $response['shippingCostId'],
            'delivery' => [
                'starter_price' => $response['starter_price'],
                'ending_price' => $response['ending_price'],
                'estimated_pickup_time' => $response['estimated_pickup_time'],
                'estimated_dropoff_time' => $response['estimated_dropoff_time']
            ],
            'dropOffPoint' => $this->getBuyerAddress()
        ];

    }

    private static function makeRequest($data) {
        $data = json_encode($data);
        $purchase = curl_init( self::$pricing_uri );
        curl_setopt($purchase, CURLOPT_URL, self::$pricing_uri );
        //TODO: cambiar a true
        curl_setopt($purchase, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($purchase, CURLOPT_POST, true);

        curl_setopt($purchase, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($purchase);
        curl_close($purchase);
        return json_decode($resp, true);
    }
}



