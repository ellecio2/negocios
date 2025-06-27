<?php

namespace App\Http\Controllers\Api\V2\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BusinessDateNonWorking;
use App\Models\Cart;
use App\Models\DeliveryEstimate;
use App\Models\ShippingCost;
use App\Models\TransporteBlancoCategoria;
use App\Models\TransporteBlancoPueblo;
use App\Models\TransporteBlancoZona;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransporteBlancoController extends Controller {

    // Direccion de las oficinas en santo domingo de transporte blanco
    private const TB_ADDRESS_SD_RD = [
        'address' => 'Av. Los Beibolistas, Esq. Jafres',
        'city' => 'Santo Domingo',
        'state' => 'Santo Domingo',
        'latitude' => 18.48402381193991,
        'longitude' => -69.982946712279,
        'phone' => '+8093791670',
    ];
    private const MAX_VOLUME = (19 * 12 * 12) / 1728;
    private const ESTIMATE_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates";

    public static function checkAvailability($sorted_carts) {

        // Direccion de entrega
        $dropoff_address = Cart::where('user_id', Auth::id())->first()->address;

        foreach ($sorted_carts as $key => $store) {
            // Id del seller del cual se están procesando sus carritos


            // Se obtienen los carritos no disponibles para Pedidos Ya
            $transporte_blanco_carts = self::filterCarts($store);

            // Se obtienen los carritos disponibles para Pedidos Ya
            $pedidosya_carts = self::filterCarts($store, 'py');

            // Se agregan los costos de los carritos de Transporte Blanco
            if($transporte_blanco_carts->isNotEmpty()){
                $sorted_carts = self::costCarts($transporte_blanco_carts, $dropoff_address, $sorted_carts);
            }

            if($pedidosya_carts->isNotEmpty()){
                $is_available = self::checkTransferableAvailablity($pedidosya_carts->first()->address);

                if($is_available){
                    // Se agregan los costos de transporte blanco a los carritos para ser llevados a las oficinas por pedidos ya
                    $sorted_carts = self::costTransferableCarts($pedidosya_carts, $dropoff_address, $sorted_carts);
                }else{
                    $sorted_carts = self::costNonTransferableCarts($pedidosya_carts, $dropoff_address, $sorted_carts);
                }
            }
        }

        return $sorted_carts;
    }

    private static function filterCarts(array $array, string $type = 'tb') {
        $key = ($type == 'tb') ? 'transporteBlanco' : 'PedidosYa';

        return collect($array[$key]['productsPackage'])->map(function ($cart_id) {
            return Cart::find($cart_id);
        });
    }

    private static function checkTransferableAvailablity(Address $pickup_address){
        $dropoff_address = Address::make(self::TB_ADDRESS_SD_RD);
        return PedidosYaController::checkCoverage($pickup_address, $dropoff_address);
    }

    private static function costCarts(Collection $carts, Address $dropoff_address, array $sorted_carts): array {
        $result = self::processCarts($carts, $dropoff_address);

        $sorted_carts[$result['seller']->id]['transporteBlanco']['pricing'] = [
            'delivery' => [
                'initialCost' => $result['price'],
                'endingCost' => $result['price'] + ($result['price'] * 0.1),
                'estimatedPickupTime' => self::estimatePickupTime($result['seller']->shop->id)['formated_date'],
                'estimatedDeliveryTime' => self::estimateDropOffTime(self::estimatePickupTime($result['seller']->shop->id)['date'], false)['formated_date']
            ],
        ];

        return $sorted_carts;
    }

    private static function costTransferableCarts(Collection $carts, Address $dropoff_address, array $sorted_carts): array {
        $result = self::processCarts($carts, $dropoff_address);

        $body = PedidosYaController::prepareShippingData($carts);
        $py_response = PedidosYaController::makeRequest(self::ESTIMATE_SHIPPING_URI, $body);

        $py_price = $py_response['deliveryOffers'][0]['pricing']['total'];

        $total_price = $result['price'] + $py_price;

        $sorted_carts[$result['seller']->id]['PedidosYa']['pricing']['transporteBlanco'] = [
            'transferable' => true,
            'initialCost' => $total_price,
            'endingCost' => $total_price + ($total_price* 0.1),
            'estimatedPickupTime' => self::estimatePickupTime($result['seller']->shop->id)['formated_date'],
            'estimatedDeliveryTime' => self::estimateDropOffTime(self::estimatePickupTime($result['seller']->shop->id)['date'], false)['formated_date']
        ];

        // TODO: Aqui inicia proceso de Transporte Blanco

        return $sorted_carts;
    }

    private static function processCarts(Collection $carts, Address $dropoff_address): array {
        $price = 0;
        $totalWeight = 0;
        $totalVolume = 0;
        $seller = $carts->first()->product->user;

        // Se calcula el total de peso y volumen de los productos a enviar
        foreach ($carts as $cart) {
            $totalWeight += $cart->cartWeight();
            $totalVolume += $cart->cartVolume();
        }

        // Volumen de cm3 a ft3
        $totalVolume = $totalVolume / 28316.8466;

        $sizes = [
            'totalVolume' => $totalVolume,
            'totalWeight' => $totalWeight
        ];

        // Buscar pueblo, zona o categoria de Transporte Blanco
        $is_town = self::searchTown($dropoff_address);
        if (!$is_town) {
            $is_zone = self::searchZone($dropoff_address);
            if (!$is_zone) {
                $is_category = self::searchCategory($dropoff_address);
                if ($is_category) {
                    $price = self::calcCosts('category', $is_category, $sizes);
                }
            } else {
                $price = self::calcCosts('zone', $is_zone, $sizes);
            }
        } else {
            $price = self::calcCosts('town', $is_town, $sizes);
        }

        if (isset($is_category) && !$is_category) {
            // TODO: No existe una zona u costeo para mostrar
        }

        return [
            'seller' => $seller,
            'price' => $price
        ];
    }

    private static function costNonTransferableCarts(Collection $carts, Address $dropoff_address, array $sorted_carts): array {
        $result = self::processCarts($carts, $dropoff_address);

        $sorted_carts[$result['seller']->id]['PedidosYa']['pricing']['transporteBlanco'] = [
            'transferable' => false,
            'initialCost' => $result['price'],
            'endingCost' => $result['price'] + ($result['price'] * 0.1),
            'estimatedPickupTime' => self::estimatePickupTime($result['seller']->shop->id)['formated_date'],
            'estimatedDeliveryTime' => self::estimateDropOffTime(self::estimatePickupTime($result['seller']->shop->id)['date'], false)['formated_date']
        ];


        //TODO: Habilitar PY + TB
        /*foreach ($carts as $cart) {
            $deliveryInfoJson = json_encode([
                'transferable' => false,
                'initialCost' => $result['price'],
                'endingCost' => $result['price'] + ($result['price'] * 0.1),
                'estimatedPickupTime' => self::estimatePickupTime($result['seller']->shop->id)['formated_date'],
                'estimatedDeliveryTime' => self::estimateDropOffTime(self::estimatePickupTime($result['seller']->shop->id)['date'], false)['formated_date']
            ]);

            // Verificar si ya existe un registro para este carrito con el mismo nombre
            $existing_cost = DeliveryEstimate::where('cart_id', $cart->id)
                ->where('name', 'TB + PY')
                ->first();

            // Si no existe o el costeo anterior ya vencio se crea un nuevo registro
            if (!$existing_cost) {
                DeliveryEstimate::create([
                    'name' => 'TB + PY',
                    'delivery_info' => $deliveryInfoJson,
                    'cart_id' => $cart->id
                ]);
            }else{
                // Decodificar el JSON de la columna 'delivery_info'
                $json = json_decode($existing_cost->delivery_info, true);
                // Obtener 'confirmationTimeLimit' y convertirlo a un objeto Carbon
                $confirmationTimeLimit = Carbon::parse($json['deliveryOffers'][0]['confirmationTimeLimit'])->setTimezone('America/Santo_Domingo');
                // Si el tiempo de confirmación ya pasó, se actualiza el registro
                if($confirmationTimeLimit->isPast()){
                    $existing_cost->update([
                        'delivery_info' => $deliveryInfoJson
                    ]);
                }
            }
        }*/

        return $sorted_carts;
    }

    private static function searchTown(Address $dropoff_address) {
        return TransporteBlancoPueblo::where('nombre', strtoupper($dropoff_address->city))
            ->orWhere(function ($query) use ($dropoff_address) {
                $query->where('latitud', $dropoff_address->latitude)
                    ->where('longitud', $dropoff_address->longitude);
            })->first();
    }

    private static function searchZone(Address $dropoff_address) {
        return TransporteBlancoZona::where('nombre', 'like', '%' . $dropoff_address->state . '%')->first();
    }

    private static function searchCategory(Address $dropoff_address) {
        return TransporteBlancoCategoria::where('nombre', 'like', '%' . strtoupper($dropoff_address->city) . '%')
            ->orWhere('nombre', 'like', '%' . strtoupper($dropoff_address->state) . '%')
            ->orWhere('nombre', 'like', '%' . strtoupper($dropoff_address->country) . '%')
            ->first();
    }

    private static function calcCosts(string $type, TransporteBlancoCategoria|TransporteBlancoPueblo|TransporteBlancoZona $model, array $sizes): float {
        if ($type == 'town') {
            return self::calcExtraPrice($model->zona->categoria->precio, $sizes);
        }

        if ($type == 'zone') {
            return self::calcExtraPrice($model->categoria->precio, $sizes);
        }

        if ($type == 'category') {
            return self::calcExtraPrice($model->precio, $sizes);
        }
    }

    private static function calcExtraPrice(float $price, array $sizes): float {
        $weight = $sizes['totalWeight'];
        $volume = $sizes['totalVolume'];

        if ($weight > 40.00) {
            $totalWeightExceeded = $weight - 40.00;
            $extraWeightPrice = $totalWeightExceeded * 10.00;
            $price += $extraWeightPrice;
        }

        if ($volume > self::MAX_VOLUME) {
            $volumeExceeded = $volume - self::MAX_VOLUME;
            $extraVolumePrice = $volumeExceeded * 30.00;
            $price += $extraVolumePrice;
        }

        return $price;
    }

    private static function estimatePickupTime(int $shop_id): array {
        // Se obtienen los días no laborables
        $not_working_days = BusinessDateNonWorking::where('shop_id', $shop_id)->get();

        // Se crea una nueva fecha y se le agrega un día para su recoleccion
        $date = Carbon::today()->timezone('America/Santo_Domingo')->addDay();

        // Repite el ciclo mientras $date sea fin de semana o un dia no laborable
        do {
            // regresa true si $date es igual a un dia no laborable
            $isNonWorkingDay = $not_working_days->contains(function ($value) use ($date) {
                return $date->eq(Carbon::parse($value->fecha_no_laborable));
            });

            // Si $date es fin de semana agrega un dia
            if ($date->isWeekend() || $isNonWorkingDay) {
                $date->addDay();
            }

        } while ($date->isWeekend() || $isNonWorkingDay);

        // Extraemos los datos para parsear la fecha
        $date->locale('es');
        $day_name = ucfirst($date->dayName);
        $day_number = $date->day;
        $month_name = $date->monthName;
        $year = $date->year;

        return [
            'date' => $date,
            'formated_date' => "$day_name, $day_number de $month_name del $year"
        ];
    }

    private static function estimateDropOffTime($pickupTime, $isTown = true, $town = null, $isLocal = false) {
        // Tomar la fecha estimada de entrega a la paquetera
        $date = Carbon::createFromDate($pickupTime)->timezone('America/Santo_Domingo');
        // Si es una ciudad, calcular el tiempo de entrega a fecha actual + 5 días files
        // Si es un pueblo, calcular el tiempo de entrega al proximal día de disponibilidad
        if ($isLocal) $isTown = true;
        if ($isTown) {
            $isLocal ?
                $available_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] :
                $available_days = array_map('ucfirst', json_decode($town->dias_de_entrega));
            // Si el día de recogida es un día de entrega, agrega un día
            if (in_array($date->englishDayOfWeek, $available_days)) {
                $date->addDay();
            }
            // Calcula el próximo día de entrega
            while (!in_array($date->englishDayOfWeek, $available_days)) {
                $date->addDay();
            }
        } else {
            $date->addRealDays(5);
            while ($date->isWeekend()) {
                $date->addDay();
            }
        }

        // Extraemos los datos para parsear la fecha
        $date->locale('es');
        $day_name = ucfirst($date->dayName);
        $day_number = $date->day;
        $month_name = $date->monthName;
        $year = $date->year;

        return [
            'date' => $date,
            'formated_date' => "$day_name, $day_number de $month_name del $year"
        ];
    }

    private static function createShippingCost($pedidosYa, $cart, $body, $price = 0.00) {
        $price = self::calcExtraPrice($cart->product->weight, self::extractVolume($cart->product), $price);

        if (($body['product']->unit_price * $body['quantity']) > 2000.00) {
            $secureDifference = ($body['product']->unit_price * $body['quantity']) - 2000.00;
        }

        $shippingCost = ShippingCost::make([
            'starter_price' => $price,
            'ending_price' => $price + ($price * 0.1),
            'pedidosya_secure_difference' => $secureDifference ?? 0.00,
            'cart_id' => $cart->id,
            'shipping_company' => 'Transporte Blanco'
        ]);

        if ($body['isTransferred']) {
            $isSaved = $cart->shippingCosts()
                ->where('shipping_company', 'TRANSPORTE BLANCO')
                ->exists();
            if ($isSaved) {
                $oldShippingCost = $cart->shippingCosts()
                    ->where('shipping_company', 'TRANSPORTE BLANCO')
                    ->where('require_transfer', true)
                    ->first();
                if ($oldShippingCost->expiration_date < Carbon::now('America/Santo_Domingo')) {
                    $response = PedidosYaController::make($body);
                    $shippingCost->update([
                        'shipping_id' => $response['delivery']['estimateId'],
                        'delivery_offer_id' => $response['delivery']['deliveryOfferId'],
                        'pedidosya_secure_difference' => $secureDifference ?? 0.00,
                        'expiration_date' => $response['delivery']['confirmationTimeLimit'],
                        'require_transfer' => $body['isTransferred']
                    ]);
                }
                $shippingCost = $oldShippingCost;
            } else {
                $shippingCost->fill([
                    'shipping_id' => $pedidosYa['delivery']['estimateId'],
                    'delivery_offer_id' => $pedidosYa['delivery']['deliveryOfferId'],
                    'pedidosya_secure_difference' => $secureDifference ?? 0.00,
                    'expiration_date' => $pedidosYa['delivery']['confirmationTimeLimit'],
                    'require_transfer' => $body['isTransferred']
                ]);
                $shippingCost->save();
            }
        } else {
            if ($cart->shippingCosts()->where('shipping_company', 'TRANSPORTE BLANCO')->exists()) {
                $cart->shippingCosts()
                    ->where('shipping_company', 'TRANSPORTE BLANCO')
                    ->first()
                    ->delete();
            }
            $shippingCost->save();
        }

        self::$shippingCost = $shippingCost;
    }

}
