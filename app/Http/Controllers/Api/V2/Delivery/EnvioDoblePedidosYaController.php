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
use App\Models\ShippingCompany;
use App\Models\ShippingCost;
use App\Models\Shop;
use App\Utility\NotificationUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnvioDoblePedidosYaController extends Controller
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
    // "Authorization: 7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888",
    private const HEADERS = [
        "Accept: application/json",
        "Authorization: 7424-311502-acb0b8c5-1d7b-4db8-61b9-6a2a8ecf70b8",
        "Content-Type: application/json",
    ];
    public static function checkAvailability(Collection $carts_per_store, ?int $transportCompanyId = null)
    {
        $carts_not_available_per_store = [];
        $carts_available_per_store = [];
        $response = [];
        $weight = 0;
        $volume = 0;

        foreach ($carts_per_store as $store_carts) {
            $seller_id = null;

            foreach ($store_carts as $cart) {
                $seller_id = $cart->product->user_id;
                log::info('Procesando carrito para el vendedor: ' . $seller_id);
                // Direcciones de la tienda y la transportadora
                $sellerAddress = $cart->product->user->addresses()->default()->first();

                // Aquí obtenemos la dirección de la transportadora en lugar de la dirección del comprador
                // $transportAddress = self::getTransportadoraAddress($cart);
                // Para usar la transportadora por defecto
                // $transportAddress = self::getTransportadoraAddress($carts_available_per_store[$seller_id][0]);
                $transportAddress = self::getTransportadoraAddress($cart, $transportCompanyId);
                // Si no hay dirección de transportadora configurada, no podemos continuar con este carrito
                if (!$transportAddress) {
                    Log::error('No se encontró dirección de transportadora para el carrito', [
                        'cart_id' => $cart->id
                    ]);
                    continue;
                }

                // $seller_id = $cart->product->user_id;

                // Crea un apartado en la respuesta en caso de no existir aún
                if (!isset($response[$seller_id])) {
                    $response[$seller_id] = [
                        'deliveryInfo' => [
                            'logo' => asset('public/' . $cart->product->user->shop->logo()),
                            'pickupPoint' => "$sellerAddress->address",
                            'dropoffPoint' => "$transportAddress->address",
                            'city' => $transportAddress->city,
                        ],
                        'PedidosYa' => [
                            'logo' => asset('./public/assets/img/cards/logo_pedidos_ya.png'),
                            'productsPackage' => [],
                        ],
                        'pickupPoint' => $sellerAddress->only(['id', 'address', 'phone']),
                        'transportPoint' => [
                            'id' => $transportAddress->id ?? null,
                            'name' => $transportAddress->name ?? null,
                            'address' => $transportAddress->address ?? null,
                            'city' => $transportAddress->city ?? null,
                            'longitude' => $transportAddress->longitude ?? null,
                            'latitude' => $transportAddress->latitude ?? null,
                            'phone' => $transportAddress->phone ?? null,
                        ]
                        // 'transportPoint' => $transportAddress->only(['id', 'address', 'phone'])
                    ];
                }

                if (!isset($carts_not_available_per_store[$seller_id])) {
                    $carts_not_available_per_store[$seller_id] = [];
                }

                if (!isset($carts_available_per_store[$seller_id])) {
                    $carts_available_per_store[$seller_id] = [];
                }

                // Si alguna de las dos direcciones no está en las áreas disponibles, no es elegible para pedidos Ya.
                // if (!in_array($sellerAddress->city, self::AVAILABLE_AREAS) || !in_array($transportAddress->city, self::AVAILABLE_AREAS)) {
                //     $carts_not_available_per_store[$seller_id][] = $cart;
                //     continue;
                // } else {
                //     // Si las direcciones cumplen el rango maximo de pedidosYa el pedido se marca como no elegible
                //     if (!self::checkCoverage($transportAddress, $sellerAddress)) {
                //         $carts_not_available_per_store[$seller_id][] = $cart;
                //         continue;
                //     }
                // }
                $weight = 0;
                $vol = 0;
                foreach ($store_carts as $cartV) {
                    $vol += ($cartV->cartVolume() * 16.387) * $cartV->quantity;
                    $weight += ($cartV->cartWeight() / 2.205) * $cartV->quantity;
                }

                // Si el peso en Kilogramos es mayor a 8 o el volumen es mayor a 80840 no es elegible para pedidos Ya
                // if ($weight > 8.00 || $vol > 80840.00) {
                //     $carts_not_available_per_store[$seller_id][] = $cart;
                // } else {
                $carts_available_per_store[$seller_id][] = $cart;
                // }
            }

            // Se toman los productos que pasaron las reglas anteriores
            // Se realiza una suma total de su peso y volumen por tienda
            foreach ($carts_available_per_store as $seller_id => $carts) {
                foreach ($carts as $cart) {
                    $weight += ($cart->cartWeight() / 2.205) * $cart->quantity;
                    $volume += ($cart->cartVolume() * 16.387) * $cart->quantity;
                }
            }
            if ($weight > 20.0) {
                return response()->json([
                    'result' => false,
                    'status' => 'error',
                    'message' => 'El peso total excede el máximo permitido por PedidosYa (20 kg).'
                ], 400);
            }
            if ($volume > 80840.0) {
                return response()->json([
                    'result' => false,
                    'status' => 'error',
                    'message' => 'El volumen total excede el máximo permitido por PedidosYa.'
                ], 400);
            }
            log::info('Peso total: ' . $weight . ' kg, Volumen total: ' . $volume . ' cm³');
            // while ($weight > 8.00 || $volume > 80840.00) {
            //     $index_to_remove = 0;
            //     if ($weight > 8.00 && $volume > 80840.00) {
            //         // Si ambos, peso y volumen, son demasiado grandes, encuentra el producto con mayor peso y volumen.
            //         $max_size = -1;
            //         foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
            //             $cart_size = $cart->cartWeight() / 2.205 + $cart->cartVolume() * 16.387;
            //             if ($cart_size > $max_size) {
            //                 $max_size = $cart_size;
            //                 $index_to_remove = $index;
            //             }
            //         }
            //     } elseif ($weight > 8.00) {
            //         // Si sólo el peso es demasiado grande, encuentra el producto más pesado.
            //         $max_weight = -1;
            //         foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
            //             $cart_weight = $cart->cartWeight() / 2.205;
            //             if ($cart_weight > $max_weight) {
            //                 $max_weight = $cart_weight;
            //                 $index_to_remove = $index;
            //             }
            //         }
            //     } else {
            //         // Si sólo el volumen es demasiado grande, encuentra el producto más voluminoso.
            //         $max_volume = -1;
            //         foreach ($carts_available_per_store[$seller_id] as $index => $cart) {
            //             $cart_volume = $cart->cartVolume() * 16.387;
            //             if ($cart_volume > $max_volume) {
            //                 $max_volume = $cart_volume;
            //                 $index_to_remove = $index;
            //             }
            //         }
            //     }

            //     // Recalcular el peso y volumen total
            //     $weight = 0;
            //     $volume = 0;
            //     foreach ($carts_available_per_store[$seller_id] as $cart) {
            //         $weight += $cart->cartWeight() / 2.205;
            //         $volume += $cart->cartVolume() * 16.387;
            //     }
            // }

            Log::info('Preparando datos para envío doble a transportadora');

            // Si hay carritos disponibles para pedidosYa se realiza la estimación de envío
            if (!empty($carts_available_per_store[$seller_id])) {
                Log::info('Hay carritos disponibles para envío doble');
                $firstCart = reset($carts_available_per_store[$seller_id]);
                if (!$firstCart) {
                    Log::error('No se encontró carrito disponible para el vendedor: ' . $seller_id);
                    continue;
                }
                $transportAddress = self::getTransportadoraAddress($firstCart);
                $res = self::calcDeliveryAvailableTime($firstCart);
                // if (!empty($carts_available_per_store[$seller_id])) {
                //     Log::info('Hay carritos disponibles para envío doble');

                //     // Obtener la dirección de la transportadora para este pedido
                //     $transportAddress = self::getTransportadoraAddress($carts_available_per_store[$seller_id][0]);

                //     $res = self::calcDeliveryAvailableTime($carts_available_per_store[$seller_id][0]);
                if ($res['delivery_now_available']) {
                    // Modificar buildWaypoints para usar la dirección de la transportadora
                    $body = self::prepareShippingData($carts_available_per_store[$seller_id], false, $transportAddress);
                } else {
                    $body = self::prepareShippingData($carts_available_per_store[$seller_id], true, $transportAddress);
                }

                $py_response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, $body);

                // Agregar flag de envío doble al guardar en DeliveryEstimate
                foreach ($carts_available_per_store[$seller_id] as $cart) {
                    $deliveryInfoJson = json_encode($py_response);
                    Log::info('Respuesta de PedidosYa para envío doble: ' . print_r($py_response, true));

                    // Verificar si ya existe un registro para este carrito con el mismo nombre
                    $existing_cost = DeliveryEstimate::where('cart_id', $cart->id)
                        ->where('name', 'PEDIDOS YA')
                        ->first();

                    // Preparar datos adicionales para el envío doble
                    $additionalData = [
                        'is_double_shipping' => true,
                        'transport_address_id' => $transportAddress->id,
                        'original_buyer_address_id' => $cart->address_id // Guardar la dirección original del comprador
                    ];

                    // Combinar la respuesta de PedidosYa con los datos adicionales
                    $mergedData = array_merge(json_decode($deliveryInfoJson, true), $additionalData);
                    $deliveryInfoJson = json_encode($mergedData);

                    // Si no existe o el costeo anterior ya venció se crea un nuevo registro
                    if (!$existing_cost) {
                        DeliveryEstimate::create([
                            'name' => 'PEDIDOS YA',
                            'delivery_info' => $deliveryInfoJson,
                            'cart_id' => $cart->id,
                            'is_double_shipping' => true
                        ]);
                    } else {
                        // Decodificar el JSON de la columna 'delivery_info'
                        $json = json_decode($existing_cost->delivery_info, true);
                        log::info('JSON de delivery_info existente: ' . print_r($json, true));
                        // Obtener 'confirmationTimeLimit' y convertirlo a un objeto Carbon
                        if (isset($json['deliveryOffers'][0]['confirmationTimeLimit'])) {
                            $confirmationTimeLimit = Carbon::parse($json['deliveryOffers'][0]['confirmationTimeLimit'])->setTimezone('America/Santo_Domingo');
                            // Si el tiempo de confirmación ya pasó, se actualiza el registro
                            if ($confirmationTimeLimit->isPast()) {
                                $existing_cost->update([
                                    'delivery_info' => $deliveryInfoJson,
                                    'is_double_shipping' => true
                                ]);
                            }
                        } else {
                            Log::warning('No se encontró confirmationTimeLimit en deliveryOffers', ['cart_id' => $cart->id, 'json' => $json]);
                            // No intentes usar $confirmationTimeLimit aquí
                        }
                    }
                }

                try {
                    $route = MapsController::getRouteData(
                        $sellerAddress,
                        $transportAddress
                    );
                } catch (\Exception $e) {
                    // Si hay un error, usar valores predeterminados
                    // $route = [
                    //     'distance' => 5,
                    //     'duration' => 20
                    // ];

                    \Log::warning('Error al obtener datos de ruta para envío doble: ' . $e->getMessage());
                }

                $added_time = 30 + (int)($route['duration'] ?? 20);

                // Si el peso o volumen son muy grandes, el pedido no es viable
                // if ($weight > 8.00 || $volume > 80840.00) {
                //     Log::warning('Peso o volumen exceden los límites para envío doble', [
                //         'weight' => $weight,
                //         'volume' => $volume
                //     ]);
                //     continue;
                // } else {
                $response[$seller_id]['PedidosYa']['productsPackage'] = array_map(fn($cart) => $cart->id, $carts_available_per_store[$seller_id]);
                $response[$seller_id]['PedidosYa']['pricing'] = [
                    'store' => [
                        'isClosed' => !$res['delivery_now_available'],
                        'message' => $res['delivery_now_not_available_message'],
                        'timeLeftToOpen' => $res['delivery_available_on'],
                    ],
                    'delivery' => [
                        'city' => $transportAddress->city,
                        'initialCost' => $py_response['deliveryOffers'][0]['pricing']['total'],
                        'endingCost' => $py_response['deliveryOffers'][0]['pricing']['total'],
                        'estimatedPickupTime' => Carbon::now('America/Santo_Domingo')->addMinutes(30)->format('h:i a'),
                        'estimatedDeliveryTime' => Carbon::now('America/Santo_Domingo')->addMinutes($added_time)->format('h:i a')
                    ],
                    'isDoubleShipping' => true,
                    // 'transportAddress' => $transportAddress->only(['id', 'address', 'city', 'phone'])
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
                // }
            } else {
                Log::info('No hay carritos disponibles para envío doble');
                // Si no hay carritos disponibles, no hacemos nada

            }
        }

        return response()->json([
            'result' => true,
            'status' => 'success',
            'data' => $response,
        ]);
    }


    public static function soloPedidosYaEstimatePorUsuario($userId)
    {
        // 1. Obtener todos los carritos del usuario
        $carts = \App\Models\Cart::where('user_id', $userId)
            ->with(['product', 'product.user.addresses', 'product.user.shop'])
            ->get();
        // log::info('Obteniendo carritos para el usuario: ' . $carts);
        // 2. Obtener la dirección del usuario (default o última)
        $buyerAddress = \App\Models\Address::where('user_id', $userId)
            ->where('set_default', 1)
            ->orderByDesc('id')
            ->first();
        if (!$buyerAddress) {
            $buyerAddress = \App\Models\Address::where('user_id', $userId)
                ->orderByDesc('id')
                ->first();
        }

        // 3. Agrupar carritos por tienda (seller_id)
        $cartsPerStore = $carts->groupBy(function ($cart) {
            return $cart->product->user_id;
        });

        $results = [];

        foreach ($cartsPerStore as $seller_id => $storeCarts) {
            log::info('Procesando carritos para el vendedor: ' . $seller_id);
            // Sumar peso y volumen por tienda
            $totalWeight = 0;
            $totalVolume = 0;
            foreach ($storeCarts as $cart) {
                $totalWeight += ($cart->cartWeight() / 2.205) * $cart->quantity;
                $totalVolume += ($cart->cartVolume() * 16.387) * $cart->quantity;
            }

            if ($totalWeight > 20.0) {
                $results[] = [
                    'status' => 'false',
                    'cart_id' => $cart->id,
                    'carts_id' => $storeCarts->pluck('id'),
                    'seller_id' => $seller_id,
                    'error' => 'El peso total excede el máximo permitido por PedidosYa (20 kg).'
                ];
                continue;
            }
            if ($totalVolume > 80840.0) {
                $results[] = [
                    'status' => 'false',
                    'seller_id' => $seller_id,
                    'error' => 'El volumen total excede el máximo permitido por PedidosYa.'
                ];
                continue;
            }

            // Preparar waypoints (de la tienda y del cliente)
            $firstCart = $storeCarts->first();
            $sellerAddress = $firstCart->product->user->addresses()->default()->first();

            if (!self::checkCoverage($sellerAddress, $buyerAddress)) {
                $results[] = [
                    'status' => 'false',
                     'cart_id' => $cart->id,
                    'carts_id' => $storeCarts->pluck('id'),
                    'seller_id' => $seller_id,
                    'error' => 'La dirección de entrega está fuera de la zona de cobertura de PedidosYa.'
                ];
                continue;
            }

            // Calcular tiempo estimado de entrega
            try {
                $route = \App\Http\Controllers\Delivery\MapsController::getRouteData($sellerAddress, $buyerAddress);
                $added_time = 30 + (int)($route['duration'] ?? 20); // 30 min extra
            } catch (\Exception $e) {
                $added_time = 30 + 20; // fallback: 50 min
            }
            $estimatedDeliveryTime = \Carbon\Carbon::now('America/Santo_Domingo')->addMinutes($added_time)->format('H:i a');

            // Armar items para todos los productos de la tienda
            $items = [];
            foreach ($storeCarts as $cart) {
                $items[] = [
                    "value" => $cart->product->unit_price,
                    "description" => $cart->product->name,
                    "quantity" => $cart->quantity,
                    "volume" => $cart->cartVolume() * 16.387,
                    "weight" => $cart->cartWeight() / 2.205
                ];
            }

            $waypoints = [
                [
                    "addressStreet" => $sellerAddress->address,
                    "city" => $sellerAddress->city,
                    "latitude" => (float)$sellerAddress->latitude,
                    "longitude" => (float)$sellerAddress->longitude,
                    "type" => "PICK_UP",
                    "name" => $firstCart->product->user->shop->name ?? 'Tienda',
                    "phone" => $sellerAddress->phone ?? ''
                ],
                [
                    "addressStreet" => $buyerAddress->address,
                    "city" => $buyerAddress->city,
                    "latitude" => (float)$buyerAddress->latitude,
                    "longitude" => (float)$buyerAddress->longitude,
                    "type" => "DROP_OFF",
                    "name" => $firstCart->user->name ?? 'Cliente',
                    "phone" => $buyerAddress->phone ?? ''
                ]
            ];

            $body = [
                "referenceId" => "PedidoYa_" . time() . "_{$seller_id}",
                "isTest" => config('app.pedidosya_mode') == 'development',
                "notificationMail" => $firstCart->user->email,
                "items" => $items,
                "waypoints" => $waypoints
            ];

            $py_response = self::makeRequest(self::ESTIMATE_SHIPPING_URI, json_encode($body));

            // Guardar o actualizar el estimate por tienda (puedes guardar en DeliveryEstimate solo para el primer cart_id o para todos)
            foreach ($storeCarts as $cart) {
                $deliveryInfoJson = json_encode($py_response);
                $existing_cost = \App\Models\DeliveryEstimate::where('cart_id', $cart->id)
                    ->where('name', 'PEDIDOS YA')
                    ->first();

                if (!$existing_cost) {
                    \App\Models\DeliveryEstimate::create([
                        'name' => 'PEDIDOS YA',
                        'delivery_info' => $deliveryInfoJson,
                        'cart_id' => $cart->id,
                        'is_double_shipping' => false
                    ]);
                } else {
                    // Decodificar el JSON de la columna 'delivery_info'
                    // $json = json_decode($existing_cost->delivery_info, true);
                    // // Obtener 'confirmationTimeLimit' y convertirlo a un objeto Carbon
                    // $confirmationTimeLimit = Carbon::parse($json['deliveryOffers'][0]['confirmationTimeLimit'])->setTimezone('America/Santo_Domingo');
                    // // Si el tiempo de confirmación ya pasó, se actualiza el registro
                    //  $existing_cost->update([
                    //         'delivery_info' => $deliveryInfoJson
                    //     ]);
                    // // if ($confirmationTimeLimit->isPast()) {

                    // // }
                    $json = json_decode($existing_cost->delivery_info, true);
                    log::info('JSON de delivery_info existente: ' . print_r($json, true));
                    if (isset($json['deliveryOffers'][0]['confirmationTimeLimit'])) {
                        $confirmationTimeLimit = \Carbon\Carbon::parse($json['deliveryOffers'][0]['confirmationTimeLimit'])->setTimezone('America/Santo_Domingo');
                        if ($confirmationTimeLimit->isPast()) {
                            $existing_cost->update([
                                'delivery_info' => $deliveryInfoJson,
                                'is_double_shipping' => false
                            ]);
                        }
                    } else {
                        \Log::warning('No se encontró confirmationTimeLimit en deliveryOffers', ['cart_id' => $cart->id, 'json' => $json]);
                    }
                }
            }

            $results[] = [
                'seller_id' => $seller_id,
                'cart_ids' => $storeCarts->pluck('id'),
                'estimateId' => $py_response['estimateId'] ?? null,
                'deliveryOfferId' => $py_response['deliveryOffers'][0]['deliveryOfferId'] ?? null,
                'price' => $py_response['deliveryOffers'][0]['pricing']['total'] ?? null,
                'estimated_delivery_time' => $estimatedDeliveryTime,
                'pedidosya_response' => $py_response
            ];
        }

        return $results;
    }



    /**
     * Obtiene la dirección de la transportadora para el envío doble
     * 
     * @param Cart $cart
     * @return Address|null
     */
    public static function getTransportadoraAddress(Cart $cart, ?int $transportCompanyId = null)
    {
        log::info('Obteniendo dirección de la transportadora para el envío doble' . $transportCompanyId . " Cart ID: " . $cart->id);
        // Si se proporciona explícitamente un ID de transportadora, usarlo
        if ($transportCompanyId) {
            $transportCompany = ShippingCompany::find($transportCompanyId);

            if ($transportCompany) {
                return self::createAddressObject($transportCompany);
            }
        }

        // Si no hay transportadora específica por parámetro, buscar la primera disponible
        $transportCompany = ShippingCompany::first();

        if ($transportCompany) {
            return self::createAddressObject($transportCompany);
        }

        // Si no hay transportadoras en la base de datos, usar datos predeterminados
        return null;
        // return (object)[
        //   (object)[
        //     'id' => 248,
        //     'address' => 'Av. Carlos Perez Ricart 17, Santo Domingo 10506, Dominican Republic',
        //     'city' => 'Santo Domingo',
        //     'latitude' => '-69.945693969726560',
        //     'longitude' => '18.502065658569336',
        //     'phone' => '+18294014117',
        //     'name' => 'Transportadora Por Defecto'
        // ];
    }

    /**
     * Crear objeto de dirección a partir de una transportadora
     * 
     * @param ShippingCompany $transportCompany
     * @return object
     */
    private static function createAddressObject(ShippingCompany $transportCompany)
    {
        return (object)[
            'id' => $transportCompany->id,
            'address' => $transportCompany->address,
            'city' => $transportCompany->city,
            'latitude' => $transportCompany->latitude,
            'longitude' => $transportCompany->longitude,
            'phone' => $transportCompany->whatsapp_number,
            'name' => $transportCompany->name
        ];
    }


    /**
     * Obtiene la ciudad de la transportadora
     * 
     * @param ShippingCompany $company
     * @return string
     */
    private static function getCityFromCompany(ShippingCompany $company)
    {
        // Obtener la primera ciudad asociada a la transportadora
        $city = $company->cities()->first();

        if ($city) {
            return $city->nombre;
        }

        // Si no hay ciudades asociadas, devolver un valor por defecto
        return 'Santo Domingo';
    }



    public static function checkCoverage($pickup_point, $dropoff_point): bool
    {
        $body = [
            "waypoints" => [
                [
                    "addressStreet" => $pickup_point->address,
                    "city" => $pickup_point->city,
                    "latitude" => (float)$pickup_point->latitude,
                    "longitude" => (float)$pickup_point->longitude,
                    "type" => "PICK_UP"
                ],
                [
                    "addressStreet" => $dropoff_point->address,
                    "city" => $dropoff_point->city,
                    "latitude" => (float)$dropoff_point->latitude,
                    "longitude" => (float)$dropoff_point->longitude,
                    "type" => "DROP_OFF"
                ]
            ]
        ];

        Log::info('PedidosYa checkCoverage request', $body);

        $response = self::makeRequest('https://courier-api.pedidosya.com/v3/estimates/coverage', json_encode($body));

        Log::info('PedidosYa checkCoverage response', $response);

        if (!isset($response['status']) || !is_numeric($response['status'])) {
            return false;
        }

        $statusOk = (int)($response['status'] == 200);

        return $statusOk ? true : false;
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

    // public static function prepareShippingData(array|Collection $carts, bool $scheduled = false, Address $transportAddress = null)
    public static function prepareShippingData(array|Collection $carts, bool $scheduled = false, $transportAddress = null)
    {
        $user = Auth::user();
        $data = [
            "referenceId" => "EnvioDoble_" . time(),
            "isTest" => config('app.pedidosya_mode') == 'development',
            "notificationMail" => $user->email,
            'items' => self::buildItems($carts),
            'waypoints' => self::buildWaypoints($carts[0], $transportAddress),
        ];

        if ($scheduled) {
            $data['deliveryTime'] = self::getScheduleTime($carts[0]);
        }

        Log::info('Datos de envío doble preparados', [
            'referenceId' => $data['referenceId'],
            'isDoubleShipping' => ($transportAddress !== null)
        ]);

        return json_encode($data);
    }

    private static function buildItems(array|Collection $carts): array
    {
        $items = [];
        $totalPrice = 0.00;

        // Calcular precio total
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->unit_price * $cart->quantity;
        }

        // Determinar si necesitamos aplicar precio seguro
        $isSecure = !($totalPrice > 2000.00);
        $secureUnitPrice = 0;

        if (!$isSecure) {
            $secureTotal = 1999.00;
            $totalItems = array_sum(array_map(fn($cart) => $cart->quantity, $carts));
            $secureUnitPrice = $secureTotal / $totalItems;
        }

        // Construir items
        foreach ($carts as $cart) {
            $items[] = [
                "value" => $isSecure ? $cart->product->unit_price : $secureUnitPrice,
                "description" => $cart->product->name,
                "quantity" => $cart->quantity,
                "volume" => $cart->cartVolume() * 16.387,
                "weight" => $cart->cartWeight() / 2.205
            ];
        }

        return $items;
    }

    private static function buildWaypoints(Cart $cart, $transportAddress = null): array
    {
        $waypoints = [];
        $sellerAddress = $cart->product->user->addresses()->default()->first();

        // Si se proporciona una dirección de transportadora, usarla como destino
        // De lo contrario, usar la dirección del comprador
        $destinationAddress = $transportAddress ?? $cart->address;

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
            "addressStreet" => $destinationAddress->address,
            "latitude" => $destinationAddress->latitude,
            "longitude" => $destinationAddress->longitude,
            "phone" => $destinationAddress->phone,
            // Si es la transportadora, usar su nombre, de lo contrario usar el del comprador
            "name" => $transportAddress ? "Transportadora: " . $destinationAddress->name : (Auth::user()->name ?? 'username'),
            "city" => $destinationAddress->city,
            "type" => "DROP_OFF"
        ];

        $waypoints[] = $pickup_point;
        $waypoints[] = $drop_off_point;

        return $waypoints;
    }

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

    public static function filterCarts() {}

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
