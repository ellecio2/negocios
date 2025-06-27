<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BusinessWorkingHours;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingCost;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PedidosYaController extends Controller
{
    private const TOKEN = "7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888";
    private const ESTIMATE_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates";
    private const CONFIRM_SHIPPING_URI = "https://courier-api.pedidosya.com/v3/shippings/estimates/{estimateId}/confirm";
    private const HEADERS = [
        "Accept: application/json",
        'Authorization: 7602-061934-71dbfa69-dda9-4b2a-420e-19dbea651888',
        "Content-Type: application/json",
    ];

    public static function make(array $body)
    {
        return self::buildInformation($body);
    }

    private static function buildInformation(array $body)
    {
        $data = [];

        if (self::checkAvailability($body)) {
            $apisData = self::getEstimatedShipping($body);
            foreach ($apisData['response']['deliveryOffers'] as $offer) {
                if ($offer['deliveryMode'] == 'EXPRESS') {
                    $data = [
                        'available' => self::checkAvailability($body),
                        'dropOffPoint' => $body['buyerAddress'],
                        'shippingCostId' => $apisData['response']['shippingCostId'],
                        'delivery' => [
                            'estimateId' => $apisData['response']['estimateId'],
                            'currency' => $offer['pricing']['currency'],
                            'starter_price' => $offer['pricing']['total'],
                            'ending_price' => $offer['pricing']['total'] + ($offer['pricing']['total'] * 0.25),
                            'deliveryOfferId' => $offer['deliveryOfferId'],
                            'confirmationTimeLimit' => $offer['confirmationTimeLimit']
                        ]
                    ];
                }
            }
            $data['storeAvailability'] = self::calcDeliveryAvailableTime($body);
            $data['route'] = $apisData['route'];
        } else {
            $data['available'] = false;
        }

        return $data;
    }

    public static function getEstimatedShipping(array $body)
    {
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
                'ending_price' => $price + ($price * 0.25),
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
                    'ending_price' => $price + ($price * 0.25),
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
    }

    private static function prepareShippingData(array $body)
    {
        $user = User::find($body['customerId'] ?? Auth::id());
        $data = [
            "referenceId" => "Client Internal Reference",
            "isTest" => false,
            "notificationMail" => $body['isTransferred'] ? 'pedidosya@lapieza.do' : $user->email,
            'items' => self::buildItems($body),
            'waypoints' => self::buildWaypoints($body),
        ];

//        dd($data);
        return json_encode($data);
    }

    private static function buildItems(array $body)
    {
        $isSecure = !(($body['product']->unit_price * $body['quantity']) > 2000.00);
        $items = [];
        $weight = $body['totalWeight'] / 2.205;
        $item = [
            "value" => ($isSecure) ? $body['product']->unit_price : 100.00,
            "description" => $body['product']->name,
            "quantity" => $body['quantity'],
            "volume" => $body['totalVolume'],
            "weight" => $weight
        ];

        $items[] = $item;

        return $items;
    }

    private static function buildWaypoints(array $body)
    {
        $waypoints = [];

        $pickup_point = [
            "addressStreet" => $body['sellerAddress']->address,
            "latitude" => $body['sellerAddress']->latitude,
            "longitude" => $body['sellerAddress']->longitude,
            "phone" => $body['sellerAddress']->phone,
            "name" => $body['product']->user->name,
            "city" => $body['sellerAddress']->city,
            "type" => "PICK_UP"
        ];

        $drop_off_point = [
            "addressStreet" => $body['buyerAddress']->address,
            "latitude" => $body['buyerAddress']->latitude,
            "longitude" => $body['buyerAddress']->longitude,
            "phone" => $body['buyerAddress']->phone,
            //TODO: Change this to the user name
            "name" => Auth::user()->name ?? 'username',
            "city" => $body['buyerAddress']->city,
            "type" => "DROP_OFF"
        ];

        $waypoints[] = $pickup_point;
        $waypoints[] = $drop_off_point;

        return $waypoints;
    }

    private static function makeRequest($uri, $data)
    {
        $purchase = curl_init($uri);
        curl_setopt($purchase, CURLOPT_URL, $uri);
        curl_setopt($purchase, CURLOPT_POST, true);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($purchase, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);

        $info = curl_getinfo($purchase);

        if (curl_errno($purchase)) {
            $error_msg = curl_error($purchase);
            Log::error('cURL Error: ' . $error_msg);
        }

        $resp = curl_exec($purchase);
        curl_close($purchase);

        Log::warning(json_decode($resp, true));
        return json_decode($resp, true);
    }

    private static function calcDeliveryAvailableTime(array $body)
    {
        $data = [];
        $estimatedDeliveryTime = Carbon::now('America/Santo_Domingo');
        $shop = Shop::where('user_id', $body['product']->user_id)->first();
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

    public static function checkAvailability($body)
    {
        //Weight on Kg
        $weight = $body['totalWeight'] / 2.205;
        // If the product exceeds the weight or volume limits, it is not eligible for delivery.
        if ($weight > 20.00 || $body['totalVolume'] > 80840.00) {
            return false;
        }
        // If the address of the seller or the buyer is not in the available areas, it is not eligible for delivery.
        if (!in_array($body['sellerAddress']->city, $body['availableAreas']) || !in_array($body['buyerAddress']->city, $body['availableAreas'])) {
            return false;
        }

        if (!self::checkCoverage($body['buyerAddress'], $body['sellerAddress'])) {
            return false;
        }

        return true;
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
            Log::error("Respuesta inesperada de la API: " . print_r($response, true));
            return false;
        }

        $statusOk = (int)($response['status'] == 200);

        if ($statusOk) {
            return true;
        }
        return false;
    }

    public static function confirmShipping($estimateId, $deliveryOfferId)
    {
        $uri = str_replace('{estimateId}', $estimateId, self::CONFIRM_SHIPPING_URI);
        $body = [
            'deliveryOfferId' => $deliveryOfferId
        ];

        return self::makeRequest($uri, json_encode($body));
    }

    public function makeFromAPI(Request $request)
    {
        $body = [
            'customerId' => $request->customerId,
            'product' => Product::find($request->productId),
            'quantity' => $request->quantity,
            'totalWeight' => $request->totalWeight,
            'totalVolume' => $request->totalVolume,
            'buyerAddress' => Address::find($request->buyerAddressId),
            'sellerAddress' => Address::find($request->sellerAddressId),
            'availableAreas' => [
                "Santo Domingo",
                "Santo Domingo Este",
                "Santo Domingo Norte",
                "Santo Domingo Oeste",
                "Distrito Nacional",
                "Pedro Brand",
                "Los Alcarrizos",
                "San Antonio de Guerra",
                "Boca Chica"
            ],
            'isTransferred' => $request->isTransferred
        ];
        return response()->json(self::buildInformation($body));
    }

    public function webhook(Request $request)
    {
        $orders = Order::whereHas('shippingCost', function ($query) use ($request) {
            $query->where('shipping_id', $request->id);
        })->get();

        if ($request->data['status'] == 'CONFIRMED') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'pending']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'pending']);
                });
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
            });
        }

        if ($request->data['status'] == 'NEAR_DROPOFF') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'on_the_way']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'on_the_way']);
                });
            });
        }

        if ($request->data['status'] == 'COMPLETED') {
            $orders->each(function ($order) {
                $order->update(['delivery_status' => 'delivered']);
                $order->orderDetails()->each(function ($orderDetail) {
                    $orderDetail->update(['delivery_status' => 'delivered']);
                });
            });
        }

        return response()->json('ACCEPTED');
    }



/**
 * Solicita un envío para una orden específica
 * 
 * @param \App\Models\Order $order
 * @return array
 */
public static function requestShippingForOrder($order)
{
    // Obtener el primer detalle de la orden para el envío
    $orderDetail = $order->orderDetails->first();
    if (!$orderDetail || !$orderDetail->product) {
        return ['error' => 'No hay productos en la orden'];
    }
    
    // Obtener la dirección del usuario
    $userAddress = $order->user->addresses()->where('set_default', 1)->first();
    if (!$userAddress) {
        return ['error' => 'Usuario sin dirección predeterminada'];
    }
    
    // Obtener estimación antes de confirmar
    $estimateInfo = self::getEstimateForOrder($order);
    
    if (!isset($estimateInfo['estimateId']) || !isset($estimateInfo['deliveryOfferId'])) {
        return ['error' => 'No se pudo obtener estimación', 'details' => $estimateInfo];
    }
    
    // Confirmar el envío con PedidosYa
    return self::confirmShipping(
        $estimateInfo['estimateId'],
        $estimateInfo['deliveryOfferId']
    );
}

/**
 * Obtiene la estimación de envío para una orden
 * 
 * @param \App\Models\Order $order
 * @return array
 */
public static function getEstimateForOrder($order)
{
    // Obtener el primer detalle de la orden para el envío
    $orderDetail = $order->orderDetails->first();
    if (!$orderDetail || !$orderDetail->product) {
        return ['error' => 'No hay productos en la orden'];
    }
    
    // Obtener la dirección del usuario
    $userAddress = $order->user->addresses()->where('set_default', 1)->first();
    if (!$userAddress) {
        return ['error' => 'Usuario sin dirección predeterminada'];
    }
    
    // Obtener la dirección del negocio
    $shopUser = User::find($orderDetail->product->user_id);
    $shop = Shop::where('user_id', $shopUser->id)->first();
    $shopAddress = Address::where('user_id', $shopUser->id)
        ->where('set_default', 1)
        ->first();
    
    if (!$shopAddress) {
        // Crear una dirección predeterminada para el negocio
        $shopAddress = Address::create([
            'address' => 'Av. Carlos Perez Ricart 17',
            'city' => 'Santo Domingo',
            'phone' => '8299221234',
            'state' => 'Distrito Nacional',
            'country' => 'Dominican Republic',
            'latitude' => 18.502065658569,
            'longitude' => -69.945693969727,
            'user_id' => $shopUser->id,
            'postal_code' => '10000'
        ]);
    }
    
    // Calcular peso y volumen
    $weight = 0;
    $volume = 0;
    
    foreach ($order->orderDetails as $detail) {
        $product = $detail->product;
        $weight += ($product->weight ?? 1) * $detail->quantity;
        
        // Calcular volumen (alto x ancho x largo) en cm³
        $height = $product->height ?? 10;
        $width = $product->width ?? 10;
        $length = $product->length ?? 10;
        $volume += ($height * $width * $length) * $detail->quantity;
    }
    
    // Buscar un carrito existente o crear uno
    $cart = Cart::where('user_id', $order->user_id)
        ->where('product_id', $orderDetail->product->id)
        ->first();
        
    if (!$cart) {
        $cart = Cart::create([
            'user_id' => $order->user_id,
            'product_id' => $orderDetail->product->id,
            'quantity' => $orderDetail->quantity,
            'price' => $orderDetail->product->unit_price * $orderDetail->quantity,
            'variation' => json_encode([]),
            'variation_id' => null,
            'temp_id' => null
        ]);
    }
    
    // Preparar el body para getEstimatedShipping
    $body = [
        'customerId' => $order->user_id,
        'product' => $orderDetail->product,
        'quantity' => $orderDetail->quantity,
        'totalWeight' => $weight,
        'totalVolume' => $volume,
        'buyerAddress' => $userAddress,
        'sellerAddress' => $shopAddress,
        'availableAreas' => [
            "Santo Domingo",
            "Santo Domingo Este",
            "Santo Domingo Norte",
            "Santo Domingo Oeste",
            "Distrito Nacional",
            "Pedro Brand",
            "Los Alcarrizos",
            "San Antonio de Guerra",
            "Boca Chica"
        ],
        'isTransferred' => true
    ];
    
    // Hacer la solicitud de estimación usando el método existente
    $estimationData = self::getEstimatedShipping($body);
    
    // Extraer la información relevante
    $estimateId = $estimationData['response']['estimateId'] ?? null;
    $deliveryOfferId = null;
    $starterPrice = null;
    $endingPrice = null;
    
    // Buscar la oferta EXPRESS
    if (isset($estimationData['response']['deliveryOffers'])) {
        foreach ($estimationData['response']['deliveryOffers'] as $offer) {
            if ($offer['deliveryMode'] == 'EXPRESS') {
                $deliveryOfferId = $offer['deliveryOfferId'];
                $starterPrice = $offer['pricing']['total'];
                $endingPrice = $starterPrice + ($starterPrice * 0.25);
                break;
            }
        }
    }
    
    // Devolver la información formateada
    return [
        'estimateId' => $estimateId,
        'deliveryOfferId' => $deliveryOfferId,
        'starter_price' => $starterPrice,
        'ending_price' => $endingPrice,
        'cart_id' => $cart->id
    ];
}
}
