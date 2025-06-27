<?php

namespace App\Http\Controllers\Api\V2\Delivery;

use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Delivery\MapsController;
use App\Models\Address;
use App\Models\BusinessWorkingHours;
use App\Models\Cart;
use App\Models\DeliveryEstimate;
use App\Models\Order;
use App\Models\ShippingCost;
use App\Models\Shop;
use App\Utility\NotificationUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PedidosYaController extends Controller
{
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

    #private const TOKEN = "7602-042129-8423c69b-84d1-4eda-78dc-d58de236b421";
    private const TOKEN = "7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888";
    private const ESTIMATE_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates";
    private const CONFIRM_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates/{estimateId}/confirm";
    private const PEDIDOS_YA_SCHEDULES = ['9:00', '9:00'];

    private const HEADERS = [
        "Accept: application/json",
        "Authorization: 7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888",
        "Content-Type: application/json",
    ];

    public static function checkAvailability(Collection $carts_per_store)
    {
        $carts_not_available_per_store = [];
        $carts_available_per_store = [];
        $response = [];
        $weight = 0;
        $volume = 0;

        foreach ($carts_per_store as $store_carts) {
            $seller_id = null;

            foreach ($store_carts as $cart) {
                // Direcciones de la tienda y el comprador
                $sellerAddress = $cart->product->user->addresses()->default()->first();
                $buyerAddress = $cart->address;
                $seller_id = $cart->product->user_id;

                // Crea un apartado en la respuesta en caso de no existir aún
                if (!isset($response[$seller_id])) {
                    $response[$seller_id] = [
                        'deliveryInfo' => [
                            'logo' => asset('public/' . $cart->product->user->shop->logo()),
                            'pickupPoint' => "$sellerAddress->address",
                            'dropoffPoint' => "$buyerAddress->address",
                            'city' => $buyerAddress->city,
                        ],
                        'transporteBlanco' => [
                            'logo' => asset('./public/assets/img/cards/logo_transporte_blanco.png'),
                            'productsPackage' => [],
                        ],
                        'PedidosYa' => [
                            'logo' => asset('./public/assets/img/cards/logo_pedidos_ya.png'),
                            'productsPackage' => [],
                        ],
                        'pickupPoint' => $sellerAddress->only(['id', 'address', 'phone'])
                    ];
                }

                if (!isset($carts_not_available_per_store[$seller_id])) {
                    $carts_not_available_per_store[$seller_id] = [];
                }

                if (!isset($carts_available_per_store[$seller_id])) {
                    $carts_available_per_store[$seller_id] = [];
                }

                // Si alguna de las dos direcciones no está en las áreas disponibles, no es elegible para pedidos Ya.
                if (!in_array($sellerAddress->city, self::AVAILABLE_AREAS) || !in_array($buyerAddress->city, self::AVAILABLE_AREAS)) {
                    array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $cart->id);
                    $carts_not_available_per_store[$seller_id][] = $cart;
                    continue;
                } else {
                    // Si las direcciones cumplen el rango maximo de pedidosYa el pedido se marca como no elegible
                    if (!self::checkCoverage($buyerAddress, $sellerAddress)) {
                        $carts_not_available_per_store[$seller_id][] = $cart;
                        array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $cart->id);
                        continue;
                    }
                }
                $vol = 0;
                foreach ($store_carts as $cartV) {
                    $vol += ($cartV->cartVolume() * 16.387) * $cartV->quantity;
                    $weight += ($cartV->cartWeight() / 2.205) * $cartV->quantity;
                }
                //33566.852221
                //68.027210884354
                // Si el peso en Kilogramos es mayor a 8 o el volumen es mayor a 80840 no es elegible para pedidos Ya
                /*
                 * se organiza el volumen y el weight que no estaba tomando los quantity con el foreach
                 */
                //if (($cart->cartWeight() / 2.205) > 8.00 || ($cart->cartVolume() * 16.387) > 80840.00) {
                if ($weight > 8.00 || $vol > 80840.00) {
                    $carts_not_available_per_store[$seller_id][] = $cart;
                    array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $cart->id);
                } else {
                    $carts_available_per_store[$seller_id][] = $cart;
                }
            }
            /*
             * Se toman los productos que pasarón las reglas anteriores
             * Se realiza una suma total de su peso y volumen por tienda
             * */
            foreach ($carts_available_per_store as $seller_id => $carts) {
                foreach ($carts as $cart) {
                    $weight += ($cart->cartWeight() / 2.205) * $cart->quantity;
                    $volume += ($cart->cartVolume() * 16.387) * $cart->quantity;
                }
            }

            while ($weight > 8.00 || $volume > 80840.00) {
                $index_to_remove = 0;
                if ($weight > 8.00 && $volume > 80840.00) {
                    // Si ambos, peso y volumen, son demasiado grandes, encuentre el producto con mayor peso y volumen.
                    $max_size = -1;
                    foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
                        $cart_size = $cart->cartWeight() / 2.205 + $cart->cartVolume() * 16.387;
                        if ($cart_size > $max_size) {
                            $max_size = $cart_size;
                            $index_to_remove = $index;
                        }
                    }
                } elseif ($weight > 8.00) {
                    // Si sólo el peso es demasiado grande, encuentra el producto más pesado.
                    $max_weight = -1;
                    foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
                        $cart_weight = $cart->cartWeight() / 2.205;
                        if ($cart_weight > $max_weight) {
                            $max_weight = $cart_weight;
                            $index_to_remove = $index;
                        }
                    }
                } else {
                    // Si sólo el volumen es demasiado grande, encuentra el producto más voluminoso.
                    $max_volume = -1;
                    foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
                        $cart_volume = $cart->cartVolume() * 16.387;
                        if ($cart_volume > $max_volume) {
                            $max_volume = $cart_volume;
                            $index_to_remove = $index;
                        }
                    }
                }

                // Quitar el producto del arreglo de elegibles y moverlo al de no elegibles TODO ELIMINAR
                //$removed_cart = array_splice($carts_available_per_store[$seller_id], $index_to_remove, 1)[0];
                //$carts_not_available_per_store[$seller_id][] = $removed_cart;
                //array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $removed_cart->id);

                // Recalcular el peso y volumen total
                $weight = 0;
                $volume = 0;
                foreach ($carts_available_per_store[$seller_id] as $cart) {
                    $weight += $cart->cartWeight() / 2.205;
                    $volume += $cart->cartVolume() * 16.387;
                }
            }
            //dd($carts_available_per_store[$seller_id]);
            //Si hay carritos disponibles para pedidosYa se realiza la estimación de envío
            if (!empty($carts_available_per_store[$seller_id])) {
                $res = self::calcDeliveryAvailableTime($carts_available_per_store[$seller_id][0]);
                if ($res['delivery_now_available']) {
                    $body = self::prepareShippingData($carts_available_per_store[$seller_id]);
                } else {
                    $body = self::prepareShippingData($carts_available_per_store[$seller_id], true);
                }
                $py_response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $body);

                foreach ($carts_available_per_store[$seller_id] as $cart) {
                    $deliveryInfoJson = json_encode($py_response);
                    // Verificar si ya existe un registro para este carrito con el mismo nombre
                    $existing_cost = DeliveryEstimate::where('cart_id', $cart->id)
                        ->where('name', 'PEDIDOS YA')
                        ->first();

                    // Si no existe o el costeo anterior ya vencio se crea un nuevo registro
                    if (!$existing_cost) {
                        DeliveryEstimate::create([
                            'name' => 'PEDIDOS YA',
                            'delivery_info' => $deliveryInfoJson,
                            'cart_id' => $cart->id
                        ]);
                    } else {
                        // Decodificar el JSON de la columna 'delivery_info'
                        $json = json_decode($existing_cost->delivery_info, true);
                        // Obtener 'confirmationTimeLimit' y convertirlo a un objeto Carbon
                        $confirmationTimeLimit = Carbon::parse($json['deliveryOffers'][0]['confirmationTimeLimit'])->setTimezone('America/Santo_Domingo');
                        // Si el tiempo de confirmación ya pasó, se actualiza el registro
                        if ($confirmationTimeLimit->isPast()) {
                            $existing_cost->update([
                                'delivery_info' => $deliveryInfoJson
                            ]);
                        }
                    }
                }

                $route = MapsController::getRouteData(
                    $carts_available_per_store[$seller_id][0]->product->user->addresses()->default()->first(),
                    $carts_available_per_store[$seller_id][0]->address
                );

                $added_time = 30 + (int)$route['duration'];

                /*
                 * Si la suma total del peso y volumen superan los 8 Kg o el volumen maximo de 80840
                 * Los productos se marcan como no elegibles para pedidos Ya
                 *
                 * Si la suma total del peso y volumen NO superan los 8 kg y los 80840 cm³ de volumen
                 * El pedido final es elegible para pedidos Ya
                 * */
                if ($weight > 8.00 || $volume > 80840.00) {
                    $response[$seller_id]['transporteBlanco']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
                    $response[$seller_id]['transporteBlanco']['pedidosYa'] = [];
                } else {
                    $response[$seller_id]['PedidosYa']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
                    $response[$seller_id]['PedidosYa']['pricing'] = [
                        'store' => [
                            'isClosed' => !$res['delivery_now_available'],
                            'message' => $res['delivery_now_not_available_message'],
                            'timeLeftToOpen' => $res['delivery_available_on'],
                        ],
                        'delivery' => [
                            'city' => 'Santo Domingo',
                            'initialCost' => $py_response['deliveryOffers'][0]['pricing']['total'],
                            //'endingCost' => $py_response['deliveryOffers'][0]['pricing']['total'] + ($py_response['deliveryOffers'][0]['pricing']['total'] * 0.1),
                            'endingCost' => $py_response['deliveryOffers'][0]['pricing']['total'],
                            'estimatedPickupTime' => Carbon::now('America/Santo_Domingo')->addMinutes(30)->format('h:i a'),
                            'estimatedDeliveryTime' => Carbon::now('America/Santo_Domingo')->addMinutes($added_time)->format('h:i a')
                        ],
                    ];

                    if (!$res['delivery_now_available']) {
                        $deliveryTime = Carbon::parse(json_decode($body, true)['deliveryTime'])->locale('es')->setTimezone('America/Santo_Domingo');
                        $deliveryTime->addMinutes(15);

                        $formattedDeliveryTime = self::formatDate($deliveryTime);

                        $deliveryTime->addMinutes($added_time);
                        $formattedDropoffTime = self::formatDate($deliveryTime, true);

                        $response[$seller_id]['PedidosYa']['pricing']['delivery']['estimatedPickupTime'] = $formattedDeliveryTime;
                        $response[$seller_id]['PedidosYa']['pricing']['delivery']['estimatedDeliveryTime'] = $formattedDropoffTime;
                    }
                }
            } else {
                if ($weight > 8.00 || $volume > 80840.00) {
                    $response[$seller_id]['transporteBlanco']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
                    $response[$seller_id]['transporteBlanco']['pedidosYa'] = [];
                } else {
                    $response[$seller_id]['PedidosYa']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
                    $response[$seller_id]['PedidosYa']['pricing'] = [];
                }
            }


        }

        return response()->json([
            'result' => true,
            'status' => 'success',
            'data' => $response,
        ]);
    }

    public static function checkCoverage(Address $pickup_point, Address $dropoff_point): bool
    {
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
            /* Log::error("Respuesta inesperada de la API: " . print_r($response, true));*/
            return false;
        }

        $statusOk = (int)($response['status'] == 200);

        if ($statusOk) {
            return true;
        }

        return false;
    }

    public static function makeRequest(string $uri, string $data)
    {
        $purchase = self::initializeCurl($uri, $data);
        $resp = self::executeCurl($purchase);

        return json_decode($resp, true);
    }

    private static function initializeCurl(string $uri, string $data)
    {
        $purchase = curl_init($uri);
        curl_setopt($purchase, CURLOPT_URL, $uri);
        curl_setopt($purchase, CURLOPT_POST, true);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($purchase, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);


        /*log::info('Headers pedidos : ' . json_encode(self::HEADERS));*/


        return $purchase;
    }

    private static function executeCurl($purchase)
    {
        $resp = curl_exec($purchase);
        $info = curl_getinfo($purchase);

        if (curl_errno($purchase)) {
            $error_msg = curl_error($purchase);
            Log::error('cURL Error: ' . $error_msg);
        } /*else {
            Log::info('cURL Response: ' . print_r($resp, true));
        }

        Log::info('cURL Info: ' . print_r($info, true));
        $options = curl_getinfo($purchase, CURLINFO_HEADER_OUT);
        Log::info('cURL Request Headers: ' . print_r($options, true));*/

        curl_close($purchase);

        return $resp;
    }

    private static function calcDeliveryAvailableTime(Cart $cart)
    {
        $data = [];
        $estimatedDeliveryTime = Carbon::now('America/Santo_Domingo');
        $shop = Shop::where('user_id', $cart->product->user_id)->first();
        $workingHours = BusinessWorkingHours::where('shop_id', $shop->id)->get()->keyBy('dia_semana');

        $data['delivery_now_available'] = false;
        $data['delivery_now_not_available_message'] = '';

        while (true) {
            $dayOfWeek = $estimatedDeliveryTime->englishDayOfWeek;
            if (isset($workingHours[$dayOfWeek])) {
                $day = $workingHours[$dayOfWeek];
                $openTime = Carbon::parse($day->hora_inicio);
                $closeTime = Carbon::parse($day->hora_fin);

                if ($estimatedDeliveryTime->between($openTime, $closeTime)) {
                    if ($estimatedDeliveryTime->diffInHours($closeTime) < 1) {
                        $estimatedDeliveryTime = $estimatedDeliveryTime->addDay();
                        $data['delivery_now_not_available_message'] = "El negocio está próximo a cerrar, realiza tu pedido el día de mañana";
                        continue;
                    } else {
                        $data['delivery_now_available'] = true;
                        break;
                    }
                } else {
                    $estimatedDeliveryTime = $openTime->copy()->setDate($estimatedDeliveryTime->year, $estimatedDeliveryTime->month, $estimatedDeliveryTime->day);
                    if ($estimatedDeliveryTime->isPast()) {
                        $estimatedDeliveryTime->addDay();
                    }
                    $data['delivery_now_not_available_message'] = "El negocio está cerrado, realiza tu pedido en el horario de apertura";
                    break;
                }
            }
            $estimatedDeliveryTime = $estimatedDeliveryTime->addDay();
        }

        $data['delivery_available_on'] = $estimatedDeliveryTime->format('Y-m-d H:i:s');
        return $data;
    }

    /*private static function buildItems(array|Collection $carts): array
    {
        $items = [];
        $totalPrice = 0.00;
        $isSecure = true;

        foreach ($carts as $cart) {
            $totalPrice += $cart->product->unit_price * $cart->quantity;
        }

        // Check if the product value is > 2000
        $isSecure = !($totalPrice > 2000.00);

        if (!$isSecure) {
            $securePrice = 1999 / count($carts);
        }

        foreach ($carts as $cart) {
            $product = $cart->product;
            $unit_price = $product->unit_price;
            $quantity = $cart->quantity;

            // Parse cart weight to Kg
            $weight = ($cart->cartWeight()) / 2.205;

            // Get cart volume on pulg3
            $volume = $cart->cartVolume() * 16.387;
            $items[] = [
                "value" => ($isSecure) ? $unit_price : $securePrice,
                "description" => $product->name,
                "quantity" => $quantity,
                "volume" => $volume,
                "weight" => $weight
            ];
        }
        return $items;
    }*/

    public static function prepareShippingData(array|Collection $carts, bool $scheduled = false)
    {
        $user = Auth::user();
        //"isTest" => config('app.pedidosya_mode') == 'development',
        $data = [
            "referenceId" => "Client Internal Reference",
            /*"isTest" => true,*/
            "isTest" => config('app.pedidosya_mode') == 'development',
            "notificationMail" => $user->email,
            'items' => self::buildItems($carts),
            'waypoints' => self::buildWaypoints($carts[0]),
        ];

        if ($scheduled) {
            $data['deliveryTime'] = self::getScheduleTime($carts[0]);
        }

        //dd($data, ' PedidosYaController');

        //log::info('pedidos heaader ' . print_r($data, true));
        //log::info('pedidos scheduled ' . print_r($scheduled, true));

        return json_encode($data);
    }

    private static function buildItems(array|Collection $carts): array
    {
        $items = [];
        $totalPrice = 0.00;
        $isSecure = true;

        foreach ($carts as $cart) {
            $totalPrice += $cart->product->unit_price * $cart->quantity;
        }

        // Si el total del precio excede los 2000, aplicar el precio seguro
        $isSecure = !($totalPrice > 2000.00);

        if (!$isSecure) {
            // Calcula el precio seguro basado en la cantidad total de ítems para no exceder 1999
            $secureTotal = 1999.00;
            $totalItems = 0;

            // Contamos la cantidad total de ítems en el carrito
            foreach ($carts as $cart) {
                $totalItems += $cart->quantity;
            }

            // Divide el total seguro entre el número total de ítems
            $secureUnitPrice = $secureTotal / $totalItems;
        }

        foreach ($carts as $cart) {
            $product = $cart->product;
            $unit_price = $product->unit_price;
            $quantity = $cart->quantity;

            // Parse cart weight to Kg
            $weight = ($cart->cartWeight()) / 2.205;

            // Get cart volume on pulg3
            $volume = $cart->cartVolume() * 16.387;

            // Calcular el valor ajustado por ítem y redondearlo
            $adjustedPrice = $isSecure ? $unit_price : $secureUnitPrice;

            $items[] = [
                "value" => $adjustedPrice,
                "description" => $product->name,
                "quantity" => $quantity,
                "volume" => $volume,
                "weight" => $weight
            ];
        }
        return $items;
    }

    private static function buildWaypoints(Cart $cart): array
    {
        $waypoints = [];
        $sellerAddress = $cart->product->user->addresses()->default()->first();
        $buyerAddress = $cart->address;

        $pickup_point = [
            "addressStreet" => $sellerAddress->address,
            "latitude" => $sellerAddress->latitude,
            "longitude" => $sellerAddress->longitude,
            "phone" => $sellerAddress->phone,
            "name" => $cart->product->user->shop->name,
            "city" => $sellerAddress->city,
            "type" => "PICK_UP"
        ];

        $drop_off_point = [
            "addressStreet" => $buyerAddress->address,
            "latitude" => $buyerAddress->latitude,
            "longitude" => $buyerAddress->longitude,
            "phone" => $buyerAddress->phone,
            //TODO: Change this to the user name
            "name" => Auth::user()->name ?? 'username',
            "city" => $buyerAddress->city,
            "type" => "DROP_OFF"
        ];

        $waypoints[] = $pickup_point;
        $waypoints[] = $drop_off_point;

        return $waypoints;
    }

    /* public static function getEstimatedShipping(array $body) {
         $data = self::prepareShippingData($body);

         $cart = Cart::where('user_id', $body['customerId'] ?? Auth::id())
             ->where('product_id', $body['product']->id)
             ->first();


         if (!$cart->shippingCosts()->where('shipping_company', 'PEDIDOS YA')->exists()) {
             $response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);

             $price = $response['deliveryOffers'][0]['pricing']['total'];

             if (($body['product']->unit_price * $body['quantity']) > 2000.00) {
                 $secureDifference = ($body['product']->unit_price * $body['quantity']) - 2000.00;
             }

             $shippingCost = ShippingCost::create([
                 'starter_price' => $price,
                 'ending_price' => $price + ($price * 0.1),
                 'pedidosya_secure_difference' => $secureDifference ?? 0.00,
                 'shipping_id' => $response['estimateId'],
                 'delivery_offer_id' => $response['deliveryOffers'][0]['deliveryOfferId'],
                 'expiration_date' => $response['deliveryOffers'][0]['confirmationTimeLimit'],
                 'cart_id' => $cart->id,
                 'estimated_date' => self::calcDeliveryAvailableTime($body)['delivery_available_on'] ?? null
             ]);

             $response['shippingCostId'] = $shippingCost->id;
         } else {
             $shippingCost = ShippingCost::where('cart_id', $cart->id)->where('shipping_company', 'PEDIDOS YA')->first();
             if ($shippingCost->expiration_date < Carbon::now('America/Santo_Domingo')) {
                 $response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);
                 $price = $response['deliveryOffers'][0]['pricing']['total'];

                 $shippingCost->update([
                     'starter_price' => $price,
                     'ending_price' => $price + ($price * 0.1),
                     'pedidosya_secure_difference' => $secureDifference ?? 0.00,
                     'shipping_id' => $response['estimateId'],
                     'delivery_offer_id' => $response['deliveryOffers'][0]['deliveryOfferId'],
                     'expiration_date' => $response['deliveryOffers'][0]['confirmationTimeLimit'],
                     'estimated_date' => self::calcDeliveryAvailableTime($body)['delivery_available_on'] ?? null,
                 ]);

                 $response['shippingCostId'] = $shippingCost->id;
             } else {
                 $response = [
                     'estimateId' => $shippingCost->shipping_id,
                     'shippingCostId' => $shippingCost->id,
                     'deliveryOffers' => [
                         [
                             'deliveryOfferId' => $shippingCost->delivery_offer_id,
                             'deliveryMode' => 'EXPRESS',
                             'pricing' => [
                                 'total' => $shippingCost->starter_price,
                                 'currency' => 'DOP',
                             ],
                             'confirmationTimeLimit' => $shippingCost->expiration_date
                         ]
                     ]
                 ];
             }
         }

         $route = MapsController::getRouteData($body['sellerAddress'], $body['buyerAddress']);

         return [
             'response' => $response,
             'route' => $route
         ];
     }*/

    private static function getScheduleTime(Cart $cart): string
    {
        $now = Carbon::now('America/Santo_Domingo');
        $shop = Shop::where('user_id', $cart->product->user_id)->first();
        $workingHours = BusinessWorkingHours::where('shop_id', $shop->id)->get()->keyBy('dia_semana');

        // Establecer $scheduleTime a la hora actual
        $scheduleTime = Carbon::now('America/Santo_Domingo');
        $todayWorkingHours = $workingHours[$scheduleTime->englishDayOfWeek] ?? null;

        // Verificar si hoy la tienda está abierta
        if ($todayWorkingHours) {
            $closeTimeToday = Carbon::parse($todayWorkingHours->hora_fin);

            // Si la tienda ya cerró hoy, establecer $scheduleTime para mañana a la hora de apertura + 1 hora
            if ($now->greaterThan($closeTimeToday)) {
                $scheduleTime->addDay();
                $day = $workingHours[$scheduleTime->englishDayOfWeek];
                $scheduleTime->setTimeFromTimeString($day->hora_inicio)->addHour();
            }

            // Si la tienda está abierta, pero el pedido se hace después de la hora de cierre - 1 hora, también mover a mañana
            if ($now->lessThan($closeTimeToday) && $now->diffInHours($closeTimeToday, false) <= 1) {
                $scheduleTime->addDay();
                $day = $workingHours[$scheduleTime->englishDayOfWeek];
                $scheduleTime->setTimeFromTimeString($day->hora_inicio)->addHour();
            }
        }

        // En caso de que la tienda ya haya cerrado
        if (!$todayWorkingHours) {
            // Si hoy la tienda no abre, encontrar el próximo día de apertura y establecer $scheduleTime para ese día a la hora de apertura + 1 hora
            $scheduleTime->addDay();
            while (true) {
                $dayOfWeek = $scheduleTime->englishDayOfWeek;
                if (isset($workingHours[$dayOfWeek])) {
                    $day = $workingHours[$dayOfWeek];
                    $openTime = Carbon::parse($day->hora_inicio);
                    $scheduleTime->setTimeFromTimeString($openTime->format('H:i:s'))->addHour();
                    break;
                }
                $scheduleTime->addDay();
            }
        }

        self::checkPedidosYaWorkingHour($scheduleTime);

        return $scheduleTime->setTimezone('UTC')->toIso8601String();
    }

    private static function checkPedidosYaWorkingHour(Carbon &$scheduleTime)
    {
        $deliveryServiceStart = Carbon::createFromTimeString('07:00:00', 'America/Santo_Domingo');
        $deliveryServiceEnd = Carbon::createFromTimeString('21:00:00', 'America/Santo_Domingo');

        $scheduleTimeMinutes = $scheduleTime->hour * 60 + $scheduleTime->minute;
        $deliveryServiceStartMinutes = $deliveryServiceStart->hour * 60 + $deliveryServiceStart->minute;
        $deliveryServiceEndMinutes = $deliveryServiceEnd->hour * 60 + $deliveryServiceEnd->minute;

        // Verificar si está fuera del rango y ajustar
        if ($scheduleTimeMinutes < $deliveryServiceStartMinutes || $scheduleTimeMinutes > $deliveryServiceEndMinutes) {
            if ($scheduleTimeMinutes < $deliveryServiceStartMinutes) {
                // Ajustar a la hora de inicio del servicio manteniendo la fecha de $scheduleTime
                $scheduleTime->setTime($deliveryServiceStart->hour, $deliveryServiceStart->minute);
            } else {
                $scheduleTime->addDay();
            }
        }
    }

    private static function formatDate(Carbon $deliveryTime, bool $is_dropoff = false): string
    {
        if ($deliveryTime->isTomorrow()) {
            $formattedTime = $is_dropoff ?
                $deliveryTime->format('H:i a') :
                "mañana entre " . $deliveryTime->format('H:i a');
        } elseif ($deliveryTime->isToday()) {
            $formattedTime = $is_dropoff ?
                $deliveryTime->format('H:i a') :
                "hoy entre " . $deliveryTime->format('H:i a');
        } else {
            $formattedTime = $is_dropoff ?
                $deliveryTime->format('H:i a') :
                $deliveryTime->isoFormat('dddd') . " entre " . $deliveryTime->format('H:i a');
        }

        return ucfirst($formattedTime);
    }

    public static function getEstimatedShipping(array $data)
    {

        //Log::info('delivery offer: ' . print_r($data, true));

        $cart = Cart::where('user_id', Auth::id())
            ->first();
        $cartId = $cart->id;
        $price_product = $data['items'][0]['value'];
        $quantity_product = $data['items'][0]['quantity'];

        if (!$cart->shippingCosts()->where('shipping_company', 'PEDIDOS YA')->exists()) {
            #$response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);

            $price = $data['deliveryOffers'][0]['pricing']['total'];

            if (($price_product * $quantity_product) > 2000.00) {
                $secureDifference = ($price_product * $quantity_product) - 2000.00;
            }

            $shippingCost = ShippingCost::create([
                'starter_price' => $price,
                'ending_price' => $price,
                'pedidosya_secure_difference' => $secureDifference ?? 0.00,
                'shipping_id' => $data['estimateId'],
                'delivery_offer_id' => $data['deliveryOffers'][0]['deliveryOfferId'],
                'expiration_date' => $data['deliveryOffers'][0]['confirmationTimeLimit'],
                'cart_id' => $cartId,
                'estimated_date' => self::calcDeliveryAvailableTime($cart)['delivery_available_on'] ?? null
            ]);

            $response = $shippingCost->id;
        } else {
            $shippingCost = ShippingCost::where('cart_id', $cart->id)->where('shipping_company', 'PEDIDOS YA')->first();
            if ($shippingCost->expiration_date < Carbon::now('America/Santo_Domingo')) {
                #$response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);
                $price = $data['deliveryOffers'][0]['pricing']['total'];

                $shippingCost->update([
                    'starter_price' => $price,
                    'ending_price' => $price + ($price * 0.1),
                    'pedidosya_secure_difference' => $secureDifference ?? 0.00,
                    'shipping_id' => $data['estimateId'],
                    'delivery_offer_id' => $data['deliveryOffers'][0]['deliveryOfferId'],
                    'expiration_date' => $data['deliveryOffers'][0]['confirmationTimeLimit'],
                    'estimated_date' => self::calcDeliveryAvailableTime($cart)['delivery_available_on'] ?? null,
                ]);

                $response = $shippingCost->id;
            } else {
                $response = $shippingCost->id;
                /*$response = [
                    'estimateId' => $shippingCost->shipping_id,
                    'shippingCostId' => $shippingCost->id,
                    'deliveryOffers' => [
                        [
                            'deliveryOfferId' => $shippingCost->delivery_offer_id,
                            'deliveryMode' => 'EXPRESS',
                            'pricing' => [
                                'total' => $shippingCost->starter_price,
                                'currency' => 'DOP',
                            ],
                            'confirmationTimeLimit' => $shippingCost->expiration_date
                        ]
                    ]
                ];*/
            }
        }

        /*$route = MapsController::getRouteData($body['sellerAddress'], $body['buyerAddress']);

        return [
            'response' => $response,
            'route' => $route
        ];*/

        return $response;
    }

    public static function confirmShipping($estimateId, $deliveryOfferId)
    {
        $uri = str_replace('{estimateId}', $estimateId, self::CONFIRM_SHIPPING_URI);
        $body = [
            'deliveryOfferId' => $deliveryOfferId
        ];

        //log::info('respuesta pedido 3 ' . $estimateId);
        //log::info('respuesta pedido 4 ' . print_r($deliveryOfferId, true));

        return self::makeRequest($uri, json_encode($body));
    }

    public static function filterCarts()
    {

    }

    public function webhook(Request $request)
    {
        $orders = Order::whereHas('shippingCost', function ($query) use ($request) {
            $query->where('shipping_id', $request->id);
        })
            ->where('payment_status', 'paid')
            ->get();

        //log::info('webhook STATUS: ' . $request->data['status'] . ' - ' . $request->id);

        /*if ($request->data['status'] == 'CONFIRMED') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'pending']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'pending']);
                });
            });
        }*/

        if ($request->data['status'] == 'CONFIRMED') {
            //Log::info('Actualizando estado de entrega a "pending" para la orden ID: ' . $request->id);
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'pending']);
                $order->orderDetails()->each(function ($orderDetail) {
                    //Log::info('Actualizando estado de entrega en OrderDetail ID: ' . $orderDetail->id);
                    $orderDetail->update(['delivery_status' => 'pending']);
                });
                NotificationUtility::sendOrderPlacedNotification($order, 'confirmed');
                $order->delivery_status = 'confirmed';
                event(new OrderStatusUpdated($order));
                broadcast(new OrderStatusUpdated($order))->toOthers();
            });
        }

        if ($request->data['status'] == 'IN_PROGRESS') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'pending']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'pending']);
                });
            });
        }

        if ($request->data['status'] == 'NEAR_PICKUP') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'pending']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'pending']);
                });
            });
        }

        if ($request->data['status'] == 'PICKED_UP') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'picked_up']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'picked_up']);
                });
                NotificationUtility::sendOrderPlacedNotification($order, 'picked_up');
                $order->delivery_status = 'picked_up';
                event(new OrderStatusUpdated($order));
                broadcast(new OrderStatusUpdated($order))->toOthers();
            });
        }

        if ($request->data['status'] == 'NEAR_DROPOFF') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'on_the_way']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'on_the_way']);
                });
                NotificationUtility::sendOrderPlacedNotification($order, 'on_the_way');
                $order->delivery_status = 'on_the_way';
                event(new OrderStatusUpdated($order));
                broadcast(new OrderStatusUpdated($order))->toOthers();
            });
        } 

        if ($request->data['status'] == 'COMPLETED') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'delivered']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'delivered']);
                });
                NotificationUtility::sendOrderPlacedNotification($order, 'delivered');
                $order->delivery_status = 'delivered';
                event(new OrderStatusUpdated($order));
                broadcast(new OrderStatusUpdated($order))->toOthers();
            });
        }

        return response()->json('ACCEPTED');
    }

}


// namespace App\Http\Controllers\Api\V2\Delivery;
// use App\Models\ShippingCost;
// use App\Events\OrderStatusUpdated;
// use App\Http\Controllers\Controller;
// use App\Http\Controllers\Delivery\MapsController;
// use App\Models\Address;
// use App\Models\BusinessWorkingHours;
// use App\Models\Cart;
// use App\Models\DeliveryEstimate;
// use App\Models\Order;

// use App\Models\Shop;
// use App\Utility\NotificationUtility;
// use Carbon\Carbon;
// use Illuminate\Http\Request;
// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Log;

// class PedidosYaController extends Controller
// {
//     private const AVAILABLE_AREAS = [
//         'Santo Domingo',
//         'Santo Domingo Este',
//         'Santo Domingo Norte',
//         'Santo Domingo Oeste',
//         'Distrito Nacional',
//         'Pedro Brand',
//         'Los Alcarrizos',
//         'San Antonio de Guerra',
//         'Boca Chica'
//     ];

//     #private const TOKEN = "7602-042129-8423c69b-84d1-4eda-78dc-d58de236b421";
//     private const TOKEN = "7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888";
//     private const ESTIMATE_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates";
//     private const CONFIRM_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates/{estimateId}/confirm";
//     private const PEDIDOS_YA_SCHEDULES = ['9:00', '9:00'];

//     private const HEADERS = [
//         "Accept: application/json",
//         "Authorization: 7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888",
//         "Content-Type: application/json",
//     ];

//     public static function checkAvailability(Collection $carts_per_store)
//     {
//         $carts_not_available_per_store = [];
//         $carts_available_per_store = [];
//         $response = [];
//         $weight = 0;
//         $volume = 0;

//         foreach ($carts_per_store as $store_carts) {
//             $seller_id = null;

//             foreach ($store_carts as $cart) {
//                 // Direcciones de la tienda y el comprador
//                 $sellerAddress = $cart->product->user->addresses()->default()->first();
//                 $buyerAddress = $cart->address;
//                 $seller_id = $cart->product->user_id;

//                 // Crea un apartado en la respuesta en caso de no existir aún
//                 if (!isset($response[$seller_id])) {
//                     $response[$seller_id] = [
//                         'deliveryInfo' => [
//                             'logo' => asset('public/' . $cart->product->user->shop->logo()),
//                             'pickupPoint' => "$sellerAddress->address",
//                             'dropoffPoint' => "$buyerAddress->address",
//                             'city' => $buyerAddress->city,
//                         ],
//                         'transporteBlanco' => [
//                             'logo' => asset('./public/assets/img/cards/logo_transporte_blanco.png'),
//                             'productsPackage' => [],
//                         ],
//                         'PedidosYa' => [
//                             'logo' => asset('./public/assets/img/cards/logo_pedidos_ya.png'),
//                             'productsPackage' => [],
//                         ],
//                         'pickupPoint' => $sellerAddress->only(['id', 'address', 'phone'])
//                     ];
//                 }

//                 if (!isset($carts_not_available_per_store[$seller_id])) {
//                     $carts_not_available_per_store[$seller_id] = [];
//                 }

//                 if (!isset($carts_available_per_store[$seller_id])) {
//                     $carts_available_per_store[$seller_id] = [];
//                 }

//                 // Si alguna de las dos direcciones no está en las áreas disponibles, no es elegible para pedidos Ya.
//                 if (!in_array($sellerAddress->city, self::AVAILABLE_AREAS) || !in_array($buyerAddress->city, self::AVAILABLE_AREAS)) {
//                     array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $cart->id);
//                     $carts_not_available_per_store[$seller_id][] = $cart;
//                     continue;
//                 } else {
//                     // Si las direcciones cumplen el rango maximo de pedidosYa el pedido se marca como no elegible
//                     if (!self::checkCoverage($buyerAddress, $sellerAddress)) {
//                         $carts_not_available_per_store[$seller_id][] = $cart;
//                         array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $cart->id);
//                         continue;
//                     }
//                 }
//                 $vol = 0;
//                 foreach ($store_carts as $cartV) {
//                     $vol += ($cartV->cartVolume() * 16.387) * $cartV->quantity;
//                     $weight += ($cartV->cartWeight() / 2.205) * $cartV->quantity;
//                 }
//                 //33566.852221
//                 //68.027210884354
//                 // Si el peso en Kilogramos es mayor a 8 o el volumen es mayor a 80840 no es elegible para pedidos Ya
//                 /*
//                  * se organiza el volumen y el weight que no estaba tomando los quantity con el foreach
//                  */
//                 //if (($cart->cartWeight() / 2.205) > 8.00 || ($cart->cartVolume() * 16.387) > 80840.00) {
//                 if ($weight > 8.00 || $vol > 80840.00) {
//                     $carts_not_available_per_store[$seller_id][] = $cart;
//                     array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $cart->id);
//                 } else {
//                     $carts_available_per_store[$seller_id][] = $cart;
//                 }
//             }
//             /*
//              * Se toman los productos que pasarón las reglas anteriores
//              * Se realiza una suma total de su peso y volumen por tienda
//              * */
//             foreach ($carts_available_per_store as $seller_id => $carts) {
//                 foreach ($carts as $cart) {
//                     $weight += ($cart->cartWeight() / 2.205) * $cart->quantity;
//                     $volume += ($cart->cartVolume() * 16.387) * $cart->quantity;
//                 }
//             }

//             while ($weight > 8.00 || $volume > 80840.00) {
//                 $index_to_remove = 0;
//                 if ($weight > 8.00 && $volume > 80840.00) {
//                     // Si ambos, peso y volumen, son demasiado grandes, encuentre el producto con mayor peso y volumen.
//                     $max_size = -1;
//                     foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
//                         $cart_size = $cart->cartWeight() / 2.205 + $cart->cartVolume() * 16.387;
//                         if ($cart_size > $max_size) {
//                             $max_size = $cart_size;
//                             $index_to_remove = $index;
//                         }
//                     }
//                 } elseif ($weight > 8.00) {
//                     // Si sólo el peso es demasiado grande, encuentra el producto más pesado.
//                     $max_weight = -1;
//                     foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
//                         $cart_weight = $cart->cartWeight() / 2.205;
//                         if ($cart_weight > $max_weight) {
//                             $max_weight = $cart_weight;
//                             $index_to_remove = $index;
//                         }
//                     }
//                 } else {
//                     // Si sólo el volumen es demasiado grande, encuentra el producto más voluminoso.
//                     $max_volume = -1;
//                     foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
//                         $cart_volume = $cart->cartVolume() * 16.387;
//                         if ($cart_volume > $max_volume) {
//                             $max_volume = $cart_volume;
//                             $index_to_remove = $index;
//                         }
//                     }
//                 }

//                 // Quitar el producto del arreglo de elegibles y moverlo al de no elegibles TODO ELIMINAR
//                 //$removed_cart = array_splice($carts_available_per_store[$seller_id], $index_to_remove, 1)[0];
//                 //$carts_not_available_per_store[$seller_id][] = $removed_cart;
//                 //array_push($response[$seller_id]['transporteBlanco']['productsPackage'], $removed_cart->id);

//                 // Recalcular el peso y volumen total
//                 $weight = 0;
//                 $volume = 0;
//                 foreach ($carts_available_per_store[$seller_id] as $cart) {
//                     $weight += $cart->cartWeight() / 2.205;
//                     $volume += $cart->cartVolume() * 16.387;
//                 }
//             }
//             //dd($carts_available_per_store[$seller_id]);
//             //Si hay carritos disponibles para pedidosYa se realiza la estimación de envío
//             if (!empty($carts_available_per_store[$seller_id])) {
//                 $res = self::calcDeliveryAvailableTime($carts_available_per_store[$seller_id][0]);
//                 if ($res['delivery_now_available']) {
//                     $body = self::prepareShippingData($carts_available_per_store[$seller_id]);
//                 } else {
//                     $body = self::prepareShippingData($carts_available_per_store[$seller_id], true);
//                 }
//                 $py_response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $body);

//                 foreach ($carts_available_per_store[$seller_id] as $cart) {
//                     $deliveryInfoJson = json_encode($py_response);
//                     // Verificar si ya existe un registro para este carrito con el mismo nombre
//                     $existing_cost = DeliveryEstimate::where('cart_id', $cart->id)
//                         ->where('name', 'PEDIDOS YA')
//                         ->first();

//                     // Si no existe o el costeo anterior ya vencio se crea un nuevo registro
//                     if (!$existing_cost) {
//                         DeliveryEstimate::create([
//                             'name' => 'PEDIDOS YA',
//                             'delivery_info' => $deliveryInfoJson,
//                             'cart_id' => $cart->id
//                         ]);
//                     } else {
//                         // Decodificar el JSON de la columna 'delivery_info'
//                         $json = json_decode($existing_cost->delivery_info, true);
//                         // Obtener 'confirmationTimeLimit' y convertirlo a un objeto Carbon
//                         $confirmationTimeLimit = Carbon::parse($json['deliveryOffers'][0]['confirmationTimeLimit'])->setTimezone('America/Santo_Domingo');
//                         // Si el tiempo de confirmación ya pasó, se actualiza el registro
//                         if ($confirmationTimeLimit->isPast()) {
//                             $existing_cost->update([
//                                 'delivery_info' => $deliveryInfoJson
//                             ]);
//                         }
//                     }
//                 }

//                 $route = MapsController::getRouteData(
//                     $carts_available_per_store[$seller_id][0]->product->user->addresses()->default()->first(),
//                     $carts_available_per_store[$seller_id][0]->address
//                 );

//                 $added_time = 30 + (int)$route['duration'];

//                 /*
//                  * Si la suma total del peso y volumen superan los 8 Kg o el volumen maximo de 80840
//                  * Los productos se marcan como no elegibles para pedidos Ya
//                  *
//                  * Si la suma total del peso y volumen NO superan los 8 kg y los 80840 cm³ de volumen
//                  * El pedido final es elegible para pedidos Ya
//                  * */
//                 if ($weight > 8.00 || $volume > 80840.00) {
//                     $response[$seller_id]['transporteBlanco']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
//                     $response[$seller_id]['transporteBlanco']['pedidosYa'] = [];
//                 } else {
//                     $response[$seller_id]['PedidosYa']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
//                     $response[$seller_id]['PedidosYa']['pricing'] = [
//                         'store' => [
//                             'isClosed' => !$res['delivery_now_available'],
//                             'message' => $res['delivery_now_not_available_message'],
//                             'timeLeftToOpen' => $res['delivery_available_on'],
//                         ],
//                         'delivery' => [
//                             'city' => 'Santo Domingo',
//                             'initialCost' => $py_response['deliveryOffers'][0]['pricing']['total'],
//                             //'endingCost' => $py_response['deliveryOffers'][0]['pricing']['total'] + ($py_response['deliveryOffers'][0]['pricing']['total'] * 0.1),
//                             'endingCost' => $py_response['deliveryOffers'][0]['pricing']['total'],
//                             'estimatedPickupTime' => Carbon::now('America/Santo_Domingo')->addMinutes(30)->format('h:i a'),
//                             'estimatedDeliveryTime' => Carbon::now('America/Santo_Domingo')->addMinutes($added_time)->format('h:i a')
//                         ],
//                     ];

//                     if (!$res['delivery_now_available']) {
//                         $deliveryTime = Carbon::parse(json_decode($body, true)['deliveryTime'])->locale('es')->setTimezone('America/Santo_Domingo');
//                         $deliveryTime->addMinutes(15);

//                         $formattedDeliveryTime = self::formatDate($deliveryTime);

//                         $deliveryTime->addMinutes($added_time);
//                         $formattedDropoffTime = self::formatDate($deliveryTime, true);

//                         $response[$seller_id]['PedidosYa']['pricing']['delivery']['estimatedPickupTime'] = $formattedDeliveryTime;
//                         $response[$seller_id]['PedidosYa']['pricing']['delivery']['estimatedDeliveryTime'] = $formattedDropoffTime;
//                     }
//                 }
//             } else {
//                 if ($weight > 8.00 || $volume > 80840.00) {
//                     $response[$seller_id]['transporteBlanco']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
//                     $response[$seller_id]['transporteBlanco']['pedidosYa'] = [];
//                 } else {
//                     $response[$seller_id]['PedidosYa']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
//                     $response[$seller_id]['PedidosYa']['pricing'] = [];
//                 }
//             }


//         }

//         return response()->json([
//             'result' => true,
//             'status' => 'success',
//             'data' => $response,
//         ]);
//     }

//     public static function checkCoverage(Address $pickup_point, Address $dropoff_point): bool
//     {
//         $body = [
//             "waypoints" => [
//                 [
//                     "addressStreet" => $pickup_point->address,
//                     "city" => $pickup_point->city,
//                     "latitude" => $pickup_point->latitude,
//                     "longitude" => $pickup_point->longitude,
//                     "type" => "PICK_UP"
//                 ],
//                 [
//                     "addressStreet" => $dropoff_point->address,
//                     "city" => $dropoff_point->city,
//                     "latitude" => $dropoff_point->latitude,
//                     "longitude" => $dropoff_point->longitude,
//                     "type" => "DROP_OFF"
//                 ]
//             ]
//         ];

//         $response = self::makeRequest('https://courier-api.pedidosya.com/v3/estimates/coverage', json_encode($body));

//         if (!isset($response['status']) || !is_numeric($response['status'])) {
//             /* Log::error("Respuesta inesperada de la API: " . print_r($response, true));*/
//             return false;
//         }

//         $statusOk = (int)($response['status'] == 200);

//         if ($statusOk) {
//             return true;
//         }

//         return false;
//     }

//     public static function makeRequest(string $uri, string $data)
//     {
//         $purchase = self::initializeCurl($uri, $data);
//         $resp = self::executeCurl($purchase);

//         return json_decode($resp, true);
//     }

//     private static function initializeCurl(string $uri, string $data)
//     {
//         $purchase = curl_init($uri);
//         curl_setopt($purchase, CURLOPT_URL, $uri);
//         curl_setopt($purchase, CURLOPT_POST, true);
//         curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($purchase, CURLOPT_HTTPHEADER, self::HEADERS);
//         curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);


//         /*log::info('Headers pedidos : ' . json_encode(self::HEADERS));*/


//         return $purchase;
//     }

//     private static function executeCurl($purchase)
//     {
//         $resp = curl_exec($purchase);
//         $info = curl_getinfo($purchase);

//         if (curl_errno($purchase)) {
//             $error_msg = curl_error($purchase);
//             Log::error('cURL Error: ' . $error_msg);
//         } /*else {
//             Log::info('cURL Response: ' . print_r($resp, true));
//         }

//         Log::info('cURL Info: ' . print_r($info, true));
//         $options = curl_getinfo($purchase, CURLINFO_HEADER_OUT);
//         Log::info('cURL Request Headers: ' . print_r($options, true));*/

//         curl_close($purchase);

//         return $resp;
//     }

//     private static function calcDeliveryAvailableTime(Cart $cart)
//     {
//         $data = [];
//         $estimatedDeliveryTime = Carbon::now('America/Santo_Domingo');
//         $shop = Shop::where('user_id', $cart->product->user_id)->first();
//         $workingHours = BusinessWorkingHours::where('shop_id', $shop->id)->get()->keyBy('dia_semana');

//         $data['delivery_now_available'] = false;
//         $data['delivery_now_not_available_message'] = '';

//         while (true) {
//             $dayOfWeek = $estimatedDeliveryTime->englishDayOfWeek;
//             if (isset($workingHours[$dayOfWeek])) {
//                 $day = $workingHours[$dayOfWeek];
//                 $openTime = Carbon::parse($day->hora_inicio);
//                 $closeTime = Carbon::parse($day->hora_fin);

//                 if ($estimatedDeliveryTime->between($openTime, $closeTime)) {
//                     if ($estimatedDeliveryTime->diffInHours($closeTime) < 1) {
//                         $estimatedDeliveryTime = $estimatedDeliveryTime->addDay();
//                         $data['delivery_now_not_available_message'] = "El negocio está próximo a cerrar, realiza tu pedido el día de mañana";
//                         continue;
//                     } else {
//                         $data['delivery_now_available'] = true;
//                         break;
//                     }
//                 } else {
//                     $estimatedDeliveryTime = $openTime->copy()->setDate($estimatedDeliveryTime->year, $estimatedDeliveryTime->month, $estimatedDeliveryTime->day);
//                     if ($estimatedDeliveryTime->isPast()) {
//                         $estimatedDeliveryTime->addDay();
//                     }
//                     $data['delivery_now_not_available_message'] = "El negocio está cerrado, realiza tu pedido en el horario de apertura";
//                     break;
//                 }
//             }
//             $estimatedDeliveryTime = $estimatedDeliveryTime->addDay();
//         }

//         $data['delivery_available_on'] = $estimatedDeliveryTime->format('Y-m-d H:i:s');
//         return $data;
//     }

//     /*private static function buildItems(array|Collection $carts): array
//     {
//         $items = [];
//         $totalPrice = 0.00;
//         $isSecure = true;

//         foreach ($carts as $cart) {
//             $totalPrice += $cart->product->unit_price * $cart->quantity;
//         }

//         // Check if the product value is > 2000
//         $isSecure = !($totalPrice > 2000.00);

//         if (!$isSecure) {
//             $securePrice = 1999 / count($carts);
//         }

//         foreach ($carts as $cart) {
//             $product = $cart->product;
//             $unit_price = $product->unit_price;
//             $quantity = $cart->quantity;

//             // Parse cart weight to Kg
//             $weight = ($cart->cartWeight()) / 2.205;

//             // Get cart volume on pulg3
//             $volume = $cart->cartVolume() * 16.387;
//             $items[] = [
//                 "value" => ($isSecure) ? $unit_price : $securePrice,
//                 "description" => $product->name,
//                 "quantity" => $quantity,
//                 "volume" => $volume,
//                 "weight" => $weight
//             ];
//         }
//         return $items;
//     }*/

//     public static function prepareShippingData(array|Collection $carts, bool $scheduled = false)
//     {
//         $user = Auth::user();
//         //"isTest" => config('app.pedidosya_mode') == 'development',
//         $data = [
//             "referenceId" => "Client Internal Reference",
//             /*"isTest" => true,*/
//             "isTest" => config('app.pedidosya_mode') == 'development',
//             "notificationMail" => $user->email,
//             'items' => self::buildItems($carts),
//             'waypoints' => self::buildWaypoints($carts[0]),
//         ];

//         if ($scheduled) {
//             $data['deliveryTime'] = self::getScheduleTime($carts[0]);
//         }

//         //dd($data, ' PedidosYaController');

//         //log::info('pedidos heaader ' . print_r($data, true));
//         //log::info('pedidos scheduled ' . print_r($scheduled, true));

//         return json_encode($data);
//     }

//     private static function buildItems(array|Collection $carts): array
//     {
//         $items = [];
//         $totalPrice = 0.00;
//         $isSecure = true;

//         foreach ($carts as $cart) {
//             $totalPrice += $cart->product->unit_price * $cart->quantity;
//         }

//         // Si el total del precio excede los 2000, aplicar el precio seguro
//         $isSecure = !($totalPrice > 2000.00);

//         if (!$isSecure) {
//             // Calcula el precio seguro basado en la cantidad total de ítems para no exceder 1999
//             $secureTotal = 1999.00;
//             $totalItems = 0;

//             // Contamos la cantidad total de ítems en el carrito
//             foreach ($carts as $cart) {
//                 $totalItems += $cart->quantity;
//             }

//             // Divide el total seguro entre el número total de ítems
//             $secureUnitPrice = $secureTotal / $totalItems;
//         }

//         foreach ($carts as $cart) {
//             $product = $cart->product;
//             $unit_price = $product->unit_price;
//             $quantity = $cart->quantity;

//             // Parse cart weight to Kg
//             $weight = ($cart->cartWeight()) / 2.205;

//             // Get cart volume on pulg3
//             $volume = $cart->cartVolume() * 16.387;

//             // Calcular el valor ajustado por ítem y redondearlo
//             $adjustedPrice = $isSecure ? $unit_price : $secureUnitPrice;

//             $items[] = [
//                 "value" => $adjustedPrice,
//                 "description" => $product->name,
//                 "quantity" => $quantity,
//                 "volume" => $volume,
//                 "weight" => $weight
//             ];
//         }
//         return $items;
//     }

//     private static function buildWaypoints(Cart $cart): array
//     {
//         $waypoints = [];
//         $sellerAddress = $cart->product->user->addresses()->default()->first();
//         $buyerAddress = $cart->address;

//         $pickup_point = [
//             "addressStreet" => $sellerAddress->address,
//             "latitude" => $sellerAddress->latitude,
//             "longitude" => $sellerAddress->longitude,
//             "phone" => $sellerAddress->phone,
//             "name" => $cart->product->user->shop->name,
//             "city" => $sellerAddress->city,
//             "type" => "PICK_UP"
//         ];

//         $drop_off_point = [
//             "addressStreet" => $buyerAddress->address,
//             "latitude" => $buyerAddress->latitude,
//             "longitude" => $buyerAddress->longitude,
//             "phone" => $buyerAddress->phone,
//             //TODO: Change this to the user name
//             "name" => Auth::user()->name ?? 'username',
//             "city" => $buyerAddress->city,
//             "type" => "DROP_OFF"
//         ];

//         $waypoints[] = $pickup_point;
//         $waypoints[] = $drop_off_point;

//         return $waypoints;
//     }

//     /* public static function getEstimatedShipping(array $body) {
//          $data = self::prepareShippingData($body);

//          $cart = Cart::where('user_id', $body['customerId'] ?? Auth::id())
//              ->where('product_id', $body['product']->id)
//              ->first();


//          if (!$cart->shippingCosts()->where('shipping_company', 'PEDIDOS YA')->exists()) {
//              $response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);

//              $price = $response['deliveryOffers'][0]['pricing']['total'];

//              if (($body['product']->unit_price * $body['quantity']) > 2000.00) {
//                  $secureDifference = ($body['product']->unit_price * $body['quantity']) - 2000.00;
//              }

//              $shippingCost = ShippingCost::create([
//                  'starter_price' => $price,
//                  'ending_price' => $price + ($price * 0.1),
//                  'pedidosya_secure_difference' => $secureDifference ?? 0.00,
//                  'shipping_id' => $response['estimateId'],
//                  'delivery_offer_id' => $response['deliveryOffers'][0]['deliveryOfferId'],
//                  'expiration_date' => $response['deliveryOffers'][0]['confirmationTimeLimit'],
//                  'cart_id' => $cart->id,
//                  'estimated_date' => self::calcDeliveryAvailableTime($body)['delivery_available_on'] ?? null
//              ]);

//              $response['shippingCostId'] = $shippingCost->id;
//          } else {
//              $shippingCost = ShippingCost::where('cart_id', $cart->id)->where('shipping_company', 'PEDIDOS YA')->first();
//              if ($shippingCost->expiration_date < Carbon::now('America/Santo_Domingo')) {
//                  $response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);
//                  $price = $response['deliveryOffers'][0]['pricing']['total'];

//                  $shippingCost->update([
//                      'starter_price' => $price,
//                      'ending_price' => $price + ($price * 0.1),
//                      'pedidosya_secure_difference' => $secureDifference ?? 0.00,
//                      'shipping_id' => $response['estimateId'],
//                      'delivery_offer_id' => $response['deliveryOffers'][0]['deliveryOfferId'],
//                      'expiration_date' => $response['deliveryOffers'][0]['confirmationTimeLimit'],
//                      'estimated_date' => self::calcDeliveryAvailableTime($body)['delivery_available_on'] ?? null,
//                  ]);

//                  $response['shippingCostId'] = $shippingCost->id;
//              } else {
//                  $response = [
//                      'estimateId' => $shippingCost->shipping_id,
//                      'shippingCostId' => $shippingCost->id,
//                      'deliveryOffers' => [
//                          [
//                              'deliveryOfferId' => $shippingCost->delivery_offer_id,
//                              'deliveryMode' => 'EXPRESS',
//                              'pricing' => [
//                                  'total' => $shippingCost->starter_price,
//                                  'currency' => 'DOP',
//                              ],
//                              'confirmationTimeLimit' => $shippingCost->expiration_date
//                          ]
//                      ]
//                  ];
//              }
//          }

//          $route = MapsController::getRouteData($body['sellerAddress'], $body['buyerAddress']);

//          return [
//              'response' => $response,
//              'route' => $route
//          ];
//      }*/

//     private static function getScheduleTime(Cart $cart): string
//     {
//         $now = Carbon::now('America/Santo_Domingo');
//         $shop = Shop::where('user_id', $cart->product->user_id)->first();
//         $workingHours = BusinessWorkingHours::where('shop_id', $shop->id)->get()->keyBy('dia_semana');

//         // Establecer $scheduleTime a la hora actual
//         $scheduleTime = Carbon::now('America/Santo_Domingo');
//         $todayWorkingHours = $workingHours[$scheduleTime->englishDayOfWeek] ?? null;

//         // Verificar si hoy la tienda está abierta
//         if ($todayWorkingHours) {
//             $closeTimeToday = Carbon::parse($todayWorkingHours->hora_fin);

//             // Si la tienda ya cerró hoy, establecer $scheduleTime para mañana a la hora de apertura + 1 hora
//             if ($now->greaterThan($closeTimeToday)) {
//                 $scheduleTime->addDay();
//                 $day = $workingHours[$scheduleTime->englishDayOfWeek];
//                 $scheduleTime->setTimeFromTimeString($day->hora_inicio)->addHour();
//             }

//             // Si la tienda está abierta, pero el pedido se hace después de la hora de cierre - 1 hora, también mover a mañana
//             if ($now->lessThan($closeTimeToday) && $now->diffInHours($closeTimeToday, false) <= 1) {
//                 $scheduleTime->addDay();
//                 $day = $workingHours[$scheduleTime->englishDayOfWeek];
//                 $scheduleTime->setTimeFromTimeString($day->hora_inicio)->addHour();
//             }
//         }

//         // En caso de que la tienda ya haya cerrado
//         if (!$todayWorkingHours) {
//             // Si hoy la tienda no abre, encontrar el próximo día de apertura y establecer $scheduleTime para ese día a la hora de apertura + 1 hora
//             $scheduleTime->addDay();
//             while (true) {
//                 $dayOfWeek = $scheduleTime->englishDayOfWeek;
//                 if (isset($workingHours[$dayOfWeek])) {
//                     $day = $workingHours[$dayOfWeek];
//                     $openTime = Carbon::parse($day->hora_inicio);
//                     $scheduleTime->setTimeFromTimeString($openTime->format('H:i:s'))->addHour();
//                     break;
//                 }
//                 $scheduleTime->addDay();
//             }
//         }

//         self::checkPedidosYaWorkingHour($scheduleTime);

//         return $scheduleTime->setTimezone('UTC')->toIso8601String();
//     }

//     private static function checkPedidosYaWorkingHour(Carbon &$scheduleTime)
//     {
//         $deliveryServiceStart = Carbon::createFromTimeString('07:00:00', 'America/Santo_Domingo');
//         $deliveryServiceEnd = Carbon::createFromTimeString('21:00:00', 'America/Santo_Domingo');

//         $scheduleTimeMinutes = $scheduleTime->hour * 60 + $scheduleTime->minute;
//         $deliveryServiceStartMinutes = $deliveryServiceStart->hour * 60 + $deliveryServiceStart->minute;
//         $deliveryServiceEndMinutes = $deliveryServiceEnd->hour * 60 + $deliveryServiceEnd->minute;

//         // Verificar si está fuera del rango y ajustar
//         if ($scheduleTimeMinutes < $deliveryServiceStartMinutes || $scheduleTimeMinutes > $deliveryServiceEndMinutes) {
//             if ($scheduleTimeMinutes < $deliveryServiceStartMinutes) {
//                 // Ajustar a la hora de inicio del servicio manteniendo la fecha de $scheduleTime
//                 $scheduleTime->setTime($deliveryServiceStart->hour, $deliveryServiceStart->minute);
//             } else {
//                 $scheduleTime->addDay();
//             }
//         }
//     }

//     private static function formatDate(Carbon $deliveryTime, bool $is_dropoff = false): string
//     {
//         if ($deliveryTime->isTomorrow()) {
//             $formattedTime = $is_dropoff ?
//                 $deliveryTime->format('H:i a') :
//                 "mañana entre " . $deliveryTime->format('H:i a');
//         } elseif ($deliveryTime->isToday()) {
//             $formattedTime = $is_dropoff ?
//                 $deliveryTime->format('H:i a') :
//                 "hoy entre " . $deliveryTime->format('H:i a');
//         } else {
//             $formattedTime = $is_dropoff ?
//                 $deliveryTime->format('H:i a') :
//                 $deliveryTime->isoFormat('dddd') . " entre " . $deliveryTime->format('H:i a');
//         }

//         return ucfirst($formattedTime);
//     }

//     public static function getEstimatedShipping(array $data)
//     {

//         //Log::info('delivery offer: ' . print_r($data, true));

//         $cart = Cart::where('user_id', Auth::id())
//             ->first();
//         $cartId = $cart->id;
//         $price_product = $data['items'][0]['value'];
//         $quantity_product = $data['items'][0]['quantity'];

//         if (!$cart->shippingCosts()->where('shipping_company', 'PEDIDOS YA')->exists()) {
//             #$response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);

//             $price = $data['deliveryOffers'][0]['pricing']['total'];

//             if (($price_product * $quantity_product) > 2000.00) {
//                 $secureDifference = ($price_product * $quantity_product) - 2000.00;
//             }

//             $shippingCost = ShippingCost::create([
//                 'starter_price' => $price,
//                 'ending_price' => $price,
//                 'pedidosya_secure_difference' => $secureDifference ?? 0.00,
//                 'shipping_id' => $data['estimateId'],
//                 'delivery_offer_id' => $data['deliveryOffers'][0]['deliveryOfferId'],
//                 'expiration_date' => $data['deliveryOffers'][0]['confirmationTimeLimit'],
//                 'cart_id' => $cartId,
//                 'estimated_date' => self::calcDeliveryAvailableTime($cart)['delivery_available_on'] ?? null
//             ]);

//             $response = $shippingCost->id;
//         } else {
//             $shippingCost = ShippingCost::where('cart_id', $cart->id)->where('shipping_company', 'PEDIDOS YA')->first();
//             if ($shippingCost->expiration_date < Carbon::now('America/Santo_Domingo')) {
//                 #$response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $data);
//                 $price = $data['deliveryOffers'][0]['pricing']['total'];

//                 $shippingCost->update([
//                     'starter_price' => $price,
//                     'ending_price' => $price + ($price * 0.1),
//                     'pedidosya_secure_difference' => $secureDifference ?? 0.00,
//                     'shipping_id' => $data['estimateId'],
//                     'delivery_offer_id' => $data['deliveryOffers'][0]['deliveryOfferId'],
//                     'expiration_date' => $data['deliveryOffers'][0]['confirmationTimeLimit'],
//                     'estimated_date' => self::calcDeliveryAvailableTime($cart)['delivery_available_on'] ?? null,
//                 ]);

//                 $response = $shippingCost->id;
//             } else {
//                 $response = $shippingCost->id;
//                 /*$response = [
//                     'estimateId' => $shippingCost->shipping_id,
//                     'shippingCostId' => $shippingCost->id,
//                     'deliveryOffers' => [
//                         [
//                             'deliveryOfferId' => $shippingCost->delivery_offer_id,
//                             'deliveryMode' => 'EXPRESS',
//                             'pricing' => [
//                                 'total' => $shippingCost->starter_price,
//                                 'currency' => 'DOP',
//                             ],
//                             'confirmationTimeLimit' => $shippingCost->expiration_date
//                         ]
//                     ]
//                 ];*/
//             }
//         }

//         /*$route = MapsController::getRouteData($body['sellerAddress'], $body['buyerAddress']);

//         return [
//             'response' => $response,
//             'route' => $route
//         ];*/

//         return $response;
//     }

//     public static function confirmShipping($estimateId, $deliveryOfferId)
//     {
//         $uri = str_replace('{estimateId}', $estimateId, self::CONFIRM_SHIPPING_URI);
//         $body = [
//             'deliveryOfferId' => $deliveryOfferId
//         ];

//         log::info('respuesta pedido 3 ' . $estimateId);
//         log::info('respuesta pedido 4 ' . print_r($deliveryOfferId, true));

//         return self::makeRequest($uri, json_encode($body));
//     }

// //     public static function confirmShipping($estimateId, $deliveryOfferId)
// // {
// //     Log::info('confirmShipping llamado desde:', [
// //         'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10),
// //         'estimateId' => $estimateId,
// //         'deliveryOfferId' => $deliveryOfferId
// //     ]);
    
    
// //     if (!$estimateId || !$deliveryOfferId) {
// //         Log::error('Faltan parámetros requeridos en confirmShipping', [
// //             'estimateId' => $estimateId,
// //             'deliveryOfferId' => $deliveryOfferId
// //         ]);
// //         return null;
// //     }

// //     $uri = str_replace('{estimateId}', $estimateId, self::CONFIRM_SHIPPING_URI);
// //     $body = [
// //         'deliveryOfferId' => $deliveryOfferId
// //     ];

// //     Log::info('Enviando confirmación a PedidosYa confirm', [
// //         'estimateId' => $estimateId,
// //         'deliveryOfferId' => $deliveryOfferId
// //     ]);

// //     return self::makeRequest($uri, json_encode($body));
// // }

//     public static function filterCarts()
//     {

//     }

//     public function webhook(Request $request)
//     {
//         $orders = Order::whereHas('shippingCost', function ($query) use ($request) {
//             $query->where('shipping_id', $request->id);
//         })
//             ->where('payment_status', 'paid')
//             ->get();

//         //log::info('webhook STATUS: ' . $request->data['status'] . ' - ' . $request->id);

//         /*if ($request->data['status'] == 'CONFIRMED') {
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'pending']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     $orderDetail->update(['delivery_status' => 'pending']);
//                 });
//             });
//         }*/

//         if ($request->data['status'] == 'CONFIRMED') {
//             //Log::info('Actualizando estado de entrega a "pending" para la orden ID: ' . $request->id);
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'pending']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     //Log::info('Actualizando estado de entrega en OrderDetail ID: ' . $orderDetail->id);
//                     $orderDetail->update(['delivery_status' => 'pending']);
//                 });
//                 NotificationUtility::sendOrderPlacedNotification($order, 'confirmed');
//                 $order->delivery_status = 'confirmed';
//                 event(new OrderStatusUpdated($order));
//                 broadcast(new OrderStatusUpdated($order))->toOthers();
//             });
//         }

//         if ($request->data['status'] == 'IN_PROGRESS') {
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'pending']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     $orderDetail->update(['delivery_status' => 'pending']);
//                 });
//             });
//         }

//         if ($request->data['status'] == 'NEAR_PICKUP') {
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'pending']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     $orderDetail->update(['delivery_status' => 'pending']);
//                 });
//             });
//         }

//         if ($request->data['status'] == 'PICKED_UP') {
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'picked_up']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     $orderDetail->update(['delivery_status' => 'picked_up']);
//                 });
//                 NotificationUtility::sendOrderPlacedNotification($order, 'picked_up');
//                 $order->delivery_status = 'picked_up';
//                 event(new OrderStatusUpdated($order));
//                 broadcast(new OrderStatusUpdated($order))->toOthers();
//             });
//         }

//         if ($request->data['status'] == 'NEAR_DROPOFF') {
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'on_the_way']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     $orderDetail->update(['delivery_status' => 'on_the_way']);
//                 });
//                 NotificationUtility::sendOrderPlacedNotification($order, 'on_the_way');
//                 $order->delivery_status = 'on_the_way';
//                 event(new OrderStatusUpdated($order));
//                 broadcast(new OrderStatusUpdated($order))->toOthers();
//             });
//         }

//         if ($request->data['status'] == 'COMPLETED') {
//             $orders->each(function ($order) {
//                 $order->update(['delivery_status' => 'delivered']);
//                 $order->orderDetails()->each(function ($orderDetail) {
//                     $orderDetail->update(['delivery_status' => 'delivered']);
//                 });
//                 NotificationUtility::sendOrderPlacedNotification($order, 'delivered');
//                 $order->delivery_status = 'delivered';
//                 event(new OrderStatusUpdated($order));
//                 broadcast(new OrderStatusUpdated($order))->toOthers();
//             });
//         }

//         return response()->json('ACCEPTED');
//     }

// }
