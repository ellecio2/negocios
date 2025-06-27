<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Api\Delivery\V1\PedidosYaController;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ShippingCompany;
use App\Models\ShippingCompanyCity;
use App\Models\ShippingCompanyTown;
use App\Models\ShippingCompanyZone;
use App\Models\ShippingProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ShippingManagementController extends Controller
{
    public function index()
    {
        $shippingCompanies = ShippingCompany::all();
        return view('backend.delivery_boys.company_delivery_index', compact('shippingCompanies'));
    }

    public function create()
    {
        return view('backend.delivery_boys.company_delivery_create');
    }
    public function store(Request $request)
    {
        // Registrar los datos recibidos para depuración
        // Log::info('Datos del formulario:', $request->all());

        // Validación más permisiva
        $request->validate([
            'name' => 'required|string|max:255',
            'api_endpoint' => 'nullable|string', // Cambiado de url a string
            'whatsapp_number' => 'nullable|string',
            'default_message' => 'nullable|string',
            'latitude' => 'nullable|string', // Cambiado de numeric a string
            'longitude' => 'nullable|string', // Cambiado de numeric a string
            'address' => 'required',
            'city' => 'required',
        ]);

        try {
            // Crear la compañía de envío
            $shippingCompany = ShippingCompany::create([
                'name' => $request->name,
                'api_endpoint' => $request->api_endpoint,
                'whatsapp_number' => $request->whatsapp_number,
                'default_message' => $request->default_message,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'city' => $request->city,

            ]);

            // Log::info('Compañía creada:', $shippingCompany->toArray());

            if ($request->has('cities')) {
                // Decodifica si es string
                $citiesData = $request->cities;
                if (is_string($citiesData)) {
                    $citiesData = json_decode($citiesData, true);
                }

                Log::info('Procesando ciudades:', ['count' => count($citiesData)]);

                foreach ($citiesData as $index => $cityData) {
                    Log::info("Procesando ciudad {$index}:", $cityData);

                    // Crear ciudad
                    $city = ShippingCompanyCity::create([
                        'nombre' => $cityData['nombre'],
                        'precio' => $cityData['precio'] ?? null,
                        'shipping_company_id' => $shippingCompany->id,
                    ]);

                    // Log::info("Ciudad {$index} creada:", $city->toArray());

                    // Verificar si existen datos de zonas
                    if (isset($cityData['zones'])) {
                        // Log::info("Procesando zonas para ciudad {$index}:", ['count' => count($cityData['zones'])]);

                        foreach ($cityData['zones'] as $zIndex => $zoneData) {
                            Log::info("Procesando zona {$zIndex} para ciudad {$index}:", $zoneData);

                            // Crear zona
                            $zone = ShippingCompanyZone::create([
                                'nombre' => $zoneData['nombre'],
                                'latitud' => $zoneData['latitud'],
                                'longitud' => $zoneData['longitud'],
                                'shipping_company_city_id' => $city->id,
                            ]);

                            // Log::info("Zona {$zIndex} creada para ciudad {$index}:", $zone->toArray());

                            // Verificar si existen datos de pueblos
                            if (isset($zoneData['towns'])) {
                                // Log::info("Procesando pueblos para zona {$zIndex}:", ['count' => count($zoneData['towns'])]);

                                foreach ($zoneData['towns'] as $tIndex => $townData) {
                                    // Log::info("Procesando pueblo {$tIndex} para zona {$zIndex}:", $townData);

                                    // Crear pueblo
                                    $town = ShippingCompanyTown::create([
                                        'nombre' => $townData['nombre'],
                                        'latitud' => $townData['latitud'],
                                        'longitud' => $townData['longitud'],
                                        'dias_disponibles' => json_encode($townData['dias_disponibles'] ?? []),
                                        'shipping_company_zone_id' => $zone->id,
                                    ]);

                                    // Log::info("Pueblo {$tIndex} creado para zona {$zIndex}:", $town->toArray());
                                }
                            }
                        }
                    }
                }
            } else {
                Log::info('No se encontraron datos de ciudades en la solicitud.');
            }

            // Responder según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('admin.shipping-companies.index')
                ]);
            }
            return redirect()->route('admin.shipping-companies.index')
                ->with('success', 'Compañía creada correctamente');
        } catch (\Exception $e) {
            // Registrar cualquier error para su depuración
            Log::error('Error al guardar los datos: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al guardar los datos: ' . $e->getMessage()]);
        }
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'api_endpoint' => 'nullable|url',
    //         'whatsapp_number' => 'nullable|string',
    //         'default_message' => 'nullable|string',
    //         'latitude' => 'nullable|numeric',
    //         'longitude' => 'nullable|numeric',
    //     ]);

    //     ShippingCompany::create($request->all());

    //     flash(translate('Shipping Company has been created successfully'))->success();
    //     return redirect()->route('admin.shipping-companies.index');
    // }

    public function edit($id)
    {
        try {
            // Cargar la compañía con todas sus relaciones anidadas
            $company = ShippingCompany::with([
                'cities',
                'cities.zones',
                'cities.zones.towns'
            ])->findOrFail($id);

            // Registrar en log para debugging
            // Log::info('Editando compañía de envío:', [
            //     'id' => $id,
            //     'name' => $company->name,
            //     'cities_count' => $company->cities->count(),
            //     'relations_loaded' => true
            // ]);

            // Enviar a la vista
            return view('backend.delivery_boys.company_delivery_edit', compact('company'));
        } catch (\Exception $e) {
            Log::error('Error al cargar datos para edición: ' . $e->getMessage(), [
                'company_id' => $id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            flash(translate('Error al cargar la compañía para editar: ') . $e->getMessage())->error();
            return redirect()->route('admin.shipping-companies.index');
        }
    }
    // public function edit($id)
    // {
    //     $shippingCompany = ShippingCompany::findOrFail($id);
    //     return view('backend.delivery_boys.company_delivery_edit', compact('shippingCompany'));
    // }


    public function update(Request $request, $id)
    {
        // Validación más permisiva
        $request->validate([
            'name' => 'required|string|max:255',
            'api_endpoint' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'default_message' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'address' => 'required',
            'city' => 'required',
        ]);

        try {
            // Buscar la compañía
            $shippingCompany = ShippingCompany::findOrFail($id);

            // Actualizar datos básicos
            $shippingCompany->update([
                'name' => $request->name,
                'api_endpoint' => $request->api_endpoint,
                'whatsapp_number' => $request->whatsapp_number,
                'default_message' => $request->default_message,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'city' => $request->city,
            ]);

            Log::info('Compañía actualizada:', $shippingCompany->toArray());

            // Procesar elementos a eliminar
            if ($request->has('cities_to_delete')) {
                $citiesToDelete = json_decode($request->cities_to_delete, true);
                if (is_array($citiesToDelete) && count($citiesToDelete) > 0) {
                    ShippingCompanyCity::whereIn('id', $citiesToDelete)->delete();
                    Log::info('Ciudades eliminadas:', $citiesToDelete);
                }
            }

            if ($request->has('zones_to_delete')) {
                $zonesToDelete = json_decode($request->zones_to_delete, true);
                if (is_array($zonesToDelete) && count($zonesToDelete) > 0) {
                    ShippingCompanyZone::whereIn('id', $zonesToDelete)->delete();
                    Log::info('Zonas eliminadas:', $zonesToDelete);
                }
            }

            if ($request->has('towns_to_delete')) {
                $townsToDelete = json_decode($request->towns_to_delete, true);
                if (is_array($townsToDelete) && count($townsToDelete) > 0) {
                    ShippingCompanyTown::whereIn('id', $townsToDelete)->delete();
                    Log::info('Pueblos eliminados:', $townsToDelete);
                }
            }

            // Procesar ciudades, zonas y pueblos actualizados o nuevos
            if ($request->has('cities')) {
                $citiesData = $request->cities;
                if (is_string($citiesData)) {
                    $citiesData = json_decode($citiesData, true);
                }

                Log::info('Procesando ciudades:', ['count' => count($citiesData)]);

                foreach ($citiesData as $cityData) {
                    // Verificar si es una actualización o nuevo registro
                    if (isset($cityData['id'])) {
                        // Actualizar ciudad existente
                        $city = ShippingCompanyCity::find($cityData['id']);
                        if ($city) {
                            $city->update([
                                'nombre' => $cityData['nombre'],
                                'precio' => $cityData['precio'] ?? null,
                            ]);
                            Log::info("Ciudad actualizada:", $city->toArray());
                        }
                    } else {
                        // Crear nueva ciudad
                        $city = ShippingCompanyCity::create([
                            'nombre' => $cityData['nombre'],
                            'precio' => $cityData['precio'] ?? null,
                            'shipping_company_id' => $shippingCompany->id,
                        ]);
                        Log::info("Ciudad creada:", $city->toArray());
                    }

                    // Procesar zonas para esta ciudad
                    if (isset($cityData['zones']) && is_array($cityData['zones'])) {
                        foreach ($cityData['zones'] as $zoneData) {
                            // Verificar si es actualización o nuevo registro
                            if (isset($zoneData['id'])) {
                                // Actualizar zona existente
                                $zone = ShippingCompanyZone::find($zoneData['id']);
                                if ($zone) {
                                    $zone->update([
                                        'nombre' => $zoneData['nombre'],
                                        'latitud' => $zoneData['latitud'],
                                        'longitud' => $zoneData['longitud'],
                                    ]);
                                    Log::info("Zona actualizada:", $zone->toArray());
                                }
                            } else {
                                // Crear nueva zona
                                $zone = ShippingCompanyZone::create([
                                    'nombre' => $zoneData['nombre'],
                                    'latitud' => $zoneData['latitud'],
                                    'longitud' => $zoneData['longitud'],
                                    'shipping_company_city_id' => $city->id,
                                ]);
                                Log::info("Zona creada:", $zone->toArray());
                            }

                            // Procesar pueblos para esta zona
                            if (isset($zoneData['towns']) && is_array($zoneData['towns'])) {
                                foreach ($zoneData['towns'] as $townData) {
                                    // Verificar si es actualización o nuevo registro
                                    if (isset($townData['id'])) {
                                        // Actualizar pueblo existente
                                        $town = ShippingCompanyTown::find($townData['id']);
                                        if ($town) {
                                            $town->update([
                                                'nombre' => $townData['nombre'],
                                                'latitud' => $townData['latitud'],
                                                'longitud' => $townData['longitud'],
                                                'dias_disponibles' => json_encode($townData['dias_disponibles'] ?? []),
                                            ]);
                                            Log::info("Pueblo actualizado:", $town->toArray());
                                        }
                                    } else {
                                        // Crear nuevo pueblo
                                        $town = ShippingCompanyTown::create([
                                            'nombre' => $townData['nombre'],
                                            'latitud' => $townData['latitud'],
                                            'longitud' => $townData['longitud'],
                                            'dias_disponibles' => json_encode($townData['dias_disponibles'] ?? []),
                                            'shipping_company_zone_id' => $zone->id,
                                        ]);
                                        Log::info("Pueblo creado:", $town->toArray());
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Responder según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('admin.shipping-companies.index')
                ]);
            }

            return redirect()->route('admin.shipping-companies.index')
                ->with('success', 'Compañía actualizada correctamente');
        } catch (\Exception $e) {
            // Registrar cualquier error para su depuración
            Log::error('Error al actualizar los datos: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Error al actualizar los datos: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar los datos: ' . $e->getMessage()]);
        }
    }
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'api_endpoint' => 'nullable|url',
    //         'whatsapp_number' => 'nullable|string',
    //         'default_message' => 'nullable|string',
    //         'latitude' => 'nullable|numeric',
    //         'longitude' => 'nullable|numeric',
    //     ]);

    //     $shippingCompany = ShippingCompany::findOrFail($id);
    //     $shippingCompany->update($request->all());

    //     flash(translate('Shipping Company has been updated successfully'))->success();
    //     return redirect()->route('admin.shipping-companies.index');
    // }
    public function destroy($id)
    {
        $shippingCompany = ShippingCompany::findOrFail($id);
        $shippingCompany->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => translate('Shipping Company has been deleted successfully')]);
        }

        flash(translate('Shipping Company has been deleted successfully'))->success();
        return redirect()->route('admin.shipping-companies.index');
    }
    // public function destroy($id)
    // {
    //     $shippingCompany = ShippingCompany::findOrFail($id);
    //     $shippingCompany->delete();

    //     flash(translate('Shipping Company has been deleted successfully'))->success();
    //     return redirect()->route('admin.shipping-companies.index');
    // }
    /**
     * Realiza una solicitud a la API de la empresa de transporte
     * 
     * @param  int  $company_id
     * @param  int  $cart_id
     * @return \Illuminate\Http\JsonResponse
     */

    public function sendShippingRequest(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'company_id' => 'required|exists:shipping_companies,id',
                'cart_id' => 'required|exists:carts,id'
            ]);

            // Obtener la empresa de envío
            $shippingCompany = ShippingCompany::findOrFail($request->company_id);
            if (empty($shippingCompany->api_endpoint)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta empresa no tiene configurada una API'
                ], 400);
            }

            // Obtener datos del carrito
            $cart = \App\Models\Cart::with(['product', 'user'])->findOrFail($request->cart_id);

            // Obtener dirección del usuario
            $address = $cart->user->addresses()->where('set_default', 1)->first();
            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario no tiene una dirección predeterminada'
                ], 400);
            }

            // Calcular costos y fechas usando la misma lógica que calculateShippingByAddress
            // Preparar una solicitud interna con los datos necesarios
            $shippingCalculationRequest = new Request([
                'company_id' => $request->company_id,
                'cart_id' => $request->cart_id,
                'address' => $address->address,
                'city' => $address->city,
                'state' => $address->state,
                'country' => $address->country,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude
            ]);

            // Buscar datos de ubicación para cálculos
            $searchResult = $this->findShippingLocationData($shippingCalculationRequest);
            $shippingCity = $searchResult['city'] ?? null;
            $shippingZone = $searchResult['zone'] ?? null;
            $shippingTown = $searchResult['town'] ?? null;

            // Verificar si se encontró punto de entrega
            if (!$shippingCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró ningún punto de entrega disponible para la dirección del usuario'
                ], 404);
            }

            // Variables para cálculos
            $isLocalDelivery = false;
            $availableDays = null;
            $estimatedDays = 5;
            $basePrice = $shippingCity->precio;

            // Verificar si es entrega local
            $localAreas = [
                'Santo Domingo',
                'Santo Domingo Este',
                'Santo Domingo Norte',
                'Santo Domingo Oeste',
                'Distrito Nacional'
            ];

            $isLocalDelivery = in_array($address->city, $localAreas) ||
                stripos($shippingCity->nombre, 'Santo Domingo') !== false;

            // Si es local, entrega en 1 día hábil
            if ($isLocalDelivery) {
                $estimatedDays = 1;
            }

            // Procesar días disponibles del pueblo
            if ($shippingTown) {
                $availableDays = $this->processDiasDisponibles($shippingTown->dias_disponibles);

                // Normalizar los nombres de los días
                if (!empty($availableDays)) {
                    $availableDays = array_map(function ($day) {
                        return $this->normalizeDayName($day);
                    }, $availableDays);
                }
            }

            // Para las dimensiones, usar valores estáticos para evitar errores
            $dimensions = [
                'width' => 10,
                'height' => 20,
                'length' => 30
            ];

            // Calcular volumen en cm³
            $volume = $dimensions['width'] * $dimensions['height'] * $dimensions['length'] * 2.54;

            // Obtener peso del producto (o valor por defecto)
            $weight = $cart->product->weight ?? 1;

            // Calcular totales
            $totalWeight = $weight * $cart->quantity;
            $totalVolume = $volume * $cart->quantity;

            // Calcular cargos adicionales
            $additionalCharges = $this->calculateAdditionalCharges($totalWeight, $totalVolume, 1);

            // Calcular precio total
            $totalPrice = $basePrice + $additionalCharges;

            // Calcular precio final con margen
            $finalPrice = $totalPrice + ($totalPrice * 0.25);

            // Calcular fechas de recogida y entrega
            $pickupDate = $this->estimatePickupDate();
            $deliveryDate = $this->calculateDeliveryDate($pickupDate, $isLocalDelivery, $availableDays, $estimatedDays);

            // Preparar datos para la API con los valores calculados
            $payload = [
                'shipping_request' => [
                    'reference_id' => 'ORDER-' . time() . '-' . $cart->id,
                    'customer' => [
                        'name' => $cart->user->name,
                        'phone' => $address->phone ?? '',
                        'email' => $cart->user->email
                    ],
                    'pickup' => [
                        'address' => 'Dirección del almacén',
                        'city' => 'Santo Domingo',
                        'state' => 'Santo Domingo',
                        'country' => 'Dominican Republic',
                        'latitude' => $shippingCompany->latitude,
                        'longitude' => $shippingCompany->longitude
                    ],
                    'destination' => [
                        'address' => $address->address,
                        'city' => $address->city,
                        'state' => $address->state,
                        'country' => $address->country,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude
                    ],
                    'package' => [
                        'description' => $cart->product->name,
                        'weight' => $weight,
                        'dimensions' => $dimensions,
                        'quantity' => $cart->quantity,
                        'price' => $cart->price,
                        'is_fragile' => false
                    ],
                    'settings' => [
                        'notify_customer' => true,
                        'require_signature' => false,
                        'preferred_delivery_time' => null,
                        'whatsapp_message' => $shippingCompany->default_message
                    ],
                    'pricing' => [
                        'base_price' => round($basePrice, 2),
                        'additional_charges' => round($additionalCharges, 2),
                        'total_price' => round($totalPrice, 2),
                        'final_price' => round($finalPrice, 2)
                    ],
                    'dates' => [
                        'estimated_pickup' => $pickupDate->format('Y-m-d H:i:s'),
                        'estimated_delivery' => $deliveryDate->format('Y-m-d H:i:s')
                    ],
                    'shipping_details' => [
                        'is_local_delivery' => $isLocalDelivery,
                        'delivery_zone' => $shippingCity->nombre,
                        'available_delivery_days' => !empty($availableDays)
                            ? array_map(function ($day) {
                                return $this->formatDayName($day);
                            }, $availableDays)
                            : ['No específicos']
                    ]
                ]
            ];

            // Realizar la petición HTTP a la API externa
            $client = new \GuzzleHttp\Client();

            $response = $client->request('POST', $shippingCompany->api_endpoint, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'timeout' => 30,
                'http_errors' => false
            ]);

            // Procesar la respuesta
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            // Guardar la respuesta en la base de datos para referencia
            \App\Models\ShippingRequest::create([
                'shipping_company_id' => $shippingCompany->id,
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'request_payload' => json_encode($payload),
                'response_payload' => json_encode($responseData),
                'status' => ($statusCode == 200 || $statusCode == 201) ? 'success' : 'failed',
                'shipping_reference' => $responseData['tracking_number'] ?? null,
                'shipping_price' => $finalPrice, // Guardar el precio calculado
                'estimated_delivery' => $deliveryDate // Guardar la fecha estimada
            ]);
            $shippingTown = null;
            if ($shippingZone) {
                $shippingTown = \App\Models\ShippingCompanyTown::where('shipping_company_zone_id', $shippingZone->id)->first();
            }

            $processingTime = 'No disponible';
            if ($shippingTown && !empty($shippingTown->dias_disponibles)) {
                $diasDisponibles = $this->processDiasDisponibles($shippingTown->dias_disponibles);
                $diasHastaEnvio = $this->calcularDiasHastaProximoEnvio($diasDisponibles, now());
                $processingTime = $diasHastaEnvio === 1
                    ? 'Entrega estimada mañana'
                    : "Entrega estimada en $diasHastaEnvio días";
            }
            Log::info('Respuesta cruda de la transportadora:', ['status' => $statusCode, 'data' => $responseData]);
            // Devolver respuesta al cliente
            return response()->json([
                'success' => ($statusCode == 200 || $statusCode == 201),
                'message' => ($statusCode == 200 || $statusCode == 201)
                    ? 'Solicitud enviada correctamente'
                    : 'Error al procesar la solicitud1',
                'data' => $responseData,
                // 'shipping_details' => [
                //     'price' => round($finalPrice, 2),
                //     'estimated_pickup' => $pickupDate->format('Y-m-d H:i:s'),
                //     'estimated_delivery' => $deliveryDate->format('Y-m-d H:i:s'),
                //     'shipping_company' => $shippingCompany->name,
                //     'delivery_zone' => $shippingCity->nombre
                // ]
                'shipping_details' => [
                    'price' => round($finalPrice, 2),
                    'estimated_pickup' => $pickupDate->format('Y-m-d H:i:s'),
                    'estimated_delivery' => $deliveryDate->format('Y-m-d H:i:s'),
                    'processing_time' => $processingTime,
                    'shipping_company' => $shippingCompany->name,
                    'delivery_zone' => $shippingCity->nombre
                ]
            ], $statusCode);
        } catch (\Exception $e) {
            \Log::error('Error al enviar solicitud de envío: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
    // public function sendShippingRequest(Request $request)
    // {
    //     try {
    //         // Validar entrada
    //         $request->validate([
    //             'company_id' => 'required|exists:shipping_companies,id',
    //             'cart_id' => 'required|exists:carts,id'
    //         ]);

    //         // Obtener la empresa de envío
    //         $shippingCompany = ShippingCompany::findOrFail($request->company_id);
    //         if (empty($shippingCompany->api_endpoint)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Esta empresa no tiene configurada una API'
    //             ], 400);
    //         }

    //         // Obtener datos del carrito
    //         $cart = \App\Models\Cart::with(['product', 'user'])->findOrFail($request->cart_id);

    //         // Obtener dirección del usuario
    //         $address = $cart->user->addresses()->where('set_default', 1)->first();
    //         if (!$address) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'El usuario no tiene una dirección predeterminada'
    //             ], 400);
    //         }

    //         // Preparar datos para la API
    //         $payload = [
    //             'shipping_request' => [
    //                 'reference_id' => 'ORDER-' . time() . '-' . $cart->id,
    //                 'customer' => [
    //                     'name' => $cart->user->name,
    //                     'phone' => $address->phone ?? '',
    //                     'email' => $cart->user->email
    //                 ],
    //                 'pickup' => [
    //                     'address' => 'Dirección del almacén',
    //                     'city' => 'Santo Domingo',
    //                     'state' => 'Santo Domingo',
    //                     'country' => 'Dominican Republic',
    //                     'latitude' => $shippingCompany->latitude,
    //                     'longitude' => $shippingCompany->longitude
    //                 ],
    //                 'destination' => [
    //                     'address' => $address->address,
    //                     'city' => $address->city,
    //                     'state' => $address->state,
    //                     'country' => $address->country,
    //                     'latitude' => $address->latitude,
    //                     'longitude' => $address->longitude
    //                 ],
    //                 'package' => [
    //                     'description' => $cart->product->name,
    //                     'weight' => $cart->product->weight ?? 1,
    //                     'dimensions' => [
    //                         'width' => 10,
    //                         'height' => 20,
    //                         'length' => 30
    //                         // 'width' => (is_numeric($cart->product->width) ? $cart->product->width : 10),
    //                         // 'height' => (is_numeric($cart->product->height) ? $cart->product->height : 10),
    //                         // 'length' => (is_numeric($cart->product->length) ? $cart->product->length : 10)
    //                     ],
    //                     'quantity' => $cart->quantity,
    //                     'price' => $cart->price,
    //                     'is_fragile' => false
    //                 ],
    //                 'settings' => [
    //                     'notify_customer' => true,
    //                     'require_signature' => false,
    //                     'preferred_delivery_time' => null,
    //                     'whatsapp_message' => $shippingCompany->default_message
    //                 ]
    //             ]
    //         ];

    //         // Realizar la petición HTTP a la API externa
    //         $client = new \GuzzleHttp\Client();

    //         $response = $client->request('POST', $shippingCompany->api_endpoint, [
    //             'json' => $payload,
    //             'headers' => [
    //                 'Content-Type' => 'application/json',
    //                 'Accept' => 'application/json'
    //             ],
    //             'timeout' => 30,
    //             'http_errors' => false
    //         ]);

    //         // Procesar la respuesta
    //         $statusCode = $response->getStatusCode();
    //         $responseData = json_decode($response->getBody(), true);

    //         // Guardar la respuesta en la base de datos para referencia
    //         \App\Models\ShippingRequest::create([
    //             'shipping_company_id' => $shippingCompany->id,
    //             'cart_id' => $cart->id,
    //             'user_id' => $cart->user_id,
    //             'request_payload' => json_encode($payload),
    //             'response_payload' => json_encode($responseData),
    //             'status' => ($statusCode == 200 || $statusCode == 201) ? 'success' : 'failed',
    //             'shipping_reference' => $responseData['tracking_number'] ?? null
    //         ]);

    //         // Devolver respuesta al cliente
    //         return response()->json([
    //             'success' => ($statusCode == 200 || $statusCode == 201),
    //             'message' => ($statusCode == 200 || $statusCode == 201)
    //                 ? 'Solicitud enviada correctamente'
    //                 : 'Error al procesar la solicitud',
    //             'data' => $responseData
    //         ], $statusCode);
    //     } catch (\Exception $e) {
    //         \Log::error('Error al enviar solicitud de envío: ' . $e->getMessage(), [
    //             'exception' => $e,
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    // funciones para obtener los precios de las zonas y pueblos
    /**
     * Calcula el precio de envío basado en la dirección proporcionada
     * 
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Calcula el precio de envío basado en la dirección proporcionada
     * usando los precios específicos de shipping_company_cities
     * 
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateShippingByAddress(Request $request)
    {
        try {
            // Validar entrada (ahora con cart_id en lugar de product_id)
            $request->validate([
                'company_id' => 'required|exists:shipping_companies,id',
                'cart_id' => 'required', // Puede ser un ID o un array de IDs
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'country' => 'required|string',
            ]);

            // Obtener la empresa de envío
            $shippingCompany = ShippingCompany::findOrFail($request->company_id);

            // Validar que la ciudad exista para la compañía
            $city = ShippingCompanyCity::where('shipping_company_id', $shippingCompany->id)
                ->where('nombre', 'LIKE', '%' . $request->city . '%')
                ->first();

            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ciudad "' . $request->city . '" no existe para esta compañía de envío.',
                    'error_code' => 'CITY_NOT_FOUND'
                ], 404);
            }

            // Obtener los productos del carrito
            $cartItems = [];
            if (is_array($request->cart_id)) {
                // Si se proporciona un array de IDs
                $cartItems = \App\Models\Cart::with('product')->whereIn('id', $request->cart_id)->get();
            } else {
                // Si se proporciona un solo ID
                $cartItems = \App\Models\Cart::with('product')->where('id', $request->cart_id)->get();
            }

            // Verificar si hay productos
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron productos en el carrito'
                ], 400);
            }

            // Resultado de búsqueda
            $searchResult = $this->findShippingLocationData($request);

            // Extraer los resultados
            $shippingCity = $searchResult['city'] ?? null;
            $shippingZone = $searchResult['zone'] ?? null;
            $shippingTown = $searchResult['town'] ?? null;

            // Verificar si se encontró algún punto de entrega
            if (!$shippingCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró ningún punto de entrega disponible para tu dirección. Por favor, contacta con servicio al cliente para opciones alternativas.',
                    'error_code' => 'NO_DELIVERY_POINT'
                ], 404);
            }

            // Variables para cálculos
            $isLocalDelivery = false;
            $availableDays = null;
            $estimatedDays = 5;
            $basePrice = $shippingCity->precio;

            // Verificar si es entrega local (Santo Domingo)
            $localAreas = [
                'Santo Domingo',
                'Santo Domingo Este',
                'Santo Domingo Norte',
                'Santo Domingo Oeste',
                'Distrito Nacional'
            ];

            $isLocalDelivery = in_array($request->city, $localAreas) ||
                stripos($shippingCity->nombre, 'Santo Domingo') !== false;

            // Si es local, entrega en 1 día hábil
            if ($isLocalDelivery) {
                $estimatedDays = 1;
            }

            // Procesar días disponibles del pueblo
            if ($shippingTown) {
                $availableDays = $this->processDiasDisponibles($shippingTown->dias_disponibles);

                // Normalizar los nombres de los días
                if (!empty($availableDays)) {
                    $availableDays = array_map(function ($day) {
                        return $this->normalizeDayName($day);
                    }, $availableDays);
                }
            }

            // Calcular peso y volumen totales de todos los productos
            $totalWeight = 0;
            $totalVolume = 0;
            $productsInfo = [];

            foreach ($cartItems as $item) {
                // Obtener peso del producto (o valor por defecto)
                $weight = $item->product->weight ?? 1;

                // Para las dimensiones, usar valores estáticos para evitar errores
                $dimensions = [
                    'width' => 10,
                    'height' => 20,
                    'length' => 30
                ];

                // Calcular volumen en cm³
                $volume = $dimensions['width'] * $dimensions['height'] * $dimensions['length'] * 2.54;

                // Multiplicar por cantidad
                $itemWeight = $weight * $item->quantity;
                $itemVolume = $volume * $item->quantity;

                // Acumular totales
                $totalWeight += $itemWeight;
                $totalVolume += $itemVolume;

                // Guardar información del producto para la respuesta
                $productsInfo[] = [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'weight' => $weight,
                    'volume_cm3' => $volume,
                    'total_weight' => $itemWeight,
                    'total_volume_cm3' => $itemVolume
                ];
            }

            // Calcular cargos adicionales
            $additionalCharges = $this->calculateAdditionalCharges($totalWeight, $totalVolume, 1);

            // Calcular precio total
            $totalPrice = $basePrice + $additionalCharges;

            // Calcular precio final con margen
            $finalPrice = $totalPrice + ($totalPrice * 0.25);

            // Calcular fechas de recogida y entrega
            $pickupDate = $this->estimatePickupDate();
            $deliveryDate = $this->calculateDeliveryDate($pickupDate, $isLocalDelivery, $availableDays, $estimatedDays);
            $processingTime = 'No disponible';
            if ($shippingTown && !empty($shippingTown->dias_disponibles)) {
                $diasDisponibles = $this->processDiasDisponibles($shippingTown->dias_disponibles);
                $diasHastaEnvio = $this->calcularDiasHastaProximoEnvio($diasDisponibles, now());
                $processingTime = $diasHastaEnvio === 1
                    ? 'Entrega estimada mañana'
                    : "Entrega estimada en $diasHastaEnvio días";
            }
            // Preparar respuesta
            $response = [
                'success' => true,
                'shipping_quote' => [
                    'to' => $request->city,
                    'base_price' => round($basePrice, 2),
                    'additional_charges' => round($additionalCharges, 2),
                    'total_price' => round($totalPrice, 2),
                    'final_price' => round($finalPrice, 2),
                    'estimated_pickup_date' => $pickupDate->format('Y-m-d H:i:s'),
                    'estimated_delivery_date' => $deliveryDate->format('Y-m-d H:i:s'),
                    'processing_time' => $processingTime,
                    'shipping_company' => $shippingCompany->name,
                    'delivery_zone' => $shippingCity->nombre,
                    'is_local_delivery' => $isLocalDelivery,
                    'available_delivery_days' => !empty($availableDays)
                        ? array_map(function ($day) {
                            return $this->formatDayName($day);
                        }, $availableDays)
                        : ['No específicos'],
                    'shipping_details' => [
                        'total_weight_kg' => round($totalWeight, 2),
                        'total_volume_cm3' => round($totalVolume, 2),
                        'products' => $productsInfo
                    ]
                ]
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error al calcular precio de envío: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al calcular precio de envío: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Formatea el nombre del día para mostrar al usuario
     * 
     * @param string $day
     * @return string
     */
    private function formatDayName($day)
    {
        $dayMap = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        return $dayMap[$day] ?? $day;
    }
    /**
     * Procesa y normaliza los días disponibles desde un valor almacenado en la base de datos
     * 
     * @param string|null $diasDisponibles
     * @return array
     */
    private function processDiasDisponibles($diasDisponibles)
    {
        // Si es null o vacío, devolver array vacío
        if (empty($diasDisponibles)) {
            return [];
        }

        // Registrar el valor original para debugging
        \Log::debug('Procesando dias_disponibles:', ['valor_original' => $diasDisponibles]);

        // Verificar si es una cadena JSON con escapes adicionales
        if (is_string($diasDisponibles) && strpos($diasDisponibles, '\\') !== false) {
            // Eliminar escapes adicionales
            $cleanedString = str_replace('\\', '', $diasDisponibles);
            $decodedDays = json_decode($cleanedString, true);

            if (is_array($decodedDays)) {
                \Log::debug('Dias procesados después de limpiar escapes:', ['resultado' => $decodedDays]);
                return $decodedDays;
            }
        }

        // Intentar decodificar como JSON regular
        if (is_string($diasDisponibles)) {
            // Eliminar comillas extras al inicio y al final si existen
            $trimmed = preg_replace('/^"|"$/', '', $diasDisponibles);
            $decodedDays = json_decode($trimmed, true);

            if (is_array($decodedDays)) {
                \Log::debug('Dias procesados después de trim y decode:', ['resultado' => $decodedDays]);
                return $decodedDays;
            }
        }

        // Si todo lo anterior falla, intentar procesar como una cadena separada por comas
        if (is_string($diasDisponibles) && strpos($diasDisponibles, ',') !== false) {
            $decodedDays = explode(',', $diasDisponibles);
            $decodedDays = array_map('trim', $decodedDays);

            \Log::debug('Dias procesados como lista separada por comas:', ['resultado' => $decodedDays]);
            return $decodedDays;
        }

        // Último recurso: buscar nombres de días en la cadena
        $possibleDays = ['Lunes', 'Martes', 'Miércoles', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Sabado', 'Domingo'];
        $foundDays = [];

        foreach ($possibleDays as $day) {
            if (strpos($diasDisponibles, $day) !== false) {
                $foundDays[] = $day;
            }
        }

        if (!empty($foundDays)) {
            \Log::debug('Dias extraídos por búsqueda de texto:', ['resultado' => $foundDays]);
            return $foundDays;
        }

        // Si todo falla, registrar el problema y devolver array vacío
        \Log::warning('No se pudo procesar el formato de dias_disponibles', ['valor' => $diasDisponibles]);
        return [];
    }
    /**
     * Busca información de ubicación para el envío basado en los datos de solicitud
     * 
     * @param Request $request
     * @return array Resultado con ciudad, zona y/o pueblo
     */
    private function findShippingLocationData(Request $request)
    {
        $result = [
            'city' => null,
            'zone' => null,
            'town' => null
        ];

        // Registrar los datos de búsqueda
        \Log::info('Buscando datos de envío:', [
            'company_id' => $request->company_id,
            'city' => $request->city,
            'state' => $request->state
        ]);

        // 1. Buscar ciudad por nombre exacto (CORREGIDO)
        $city = \App\Models\ShippingCompanyCity::where('shipping_company_id', $request->company_id)
            ->where('nombre', 'LIKE', '%' . $request->city . '%')
            ->first();

        // Si no encontramos por ciudad, intentamos por estado
        if (!$city) {
            $city = \App\Models\ShippingCompanyCity::where('shipping_company_id', $request->company_id)
                ->where('nombre', 'LIKE', '%' . $request->state . '%')
                ->first();
        }

        // Si todavía no encontramos, buscamos coincidencias parciales
        if (!$city) {
            // Intentar obtener una ciudad con nombre parecido
            $city = \App\Models\ShippingCompanyCity::where('shipping_company_id', $request->company_id)
                ->first();
        }

        if ($city) {
            $result['city'] = $city;
            \Log::info('Ciudad encontrada:', ['nombre' => $city->nombre, 'id' => $city->id]);

            // Buscar zona dentro de esta ciudad
            $zones = \App\Models\ShippingCompanyZone::where('shipping_company_city_id', $city->id)
                ->get();

            \Log::info('Zonas disponibles:', ['count' => $zones->count()]);

            if ($zones->isNotEmpty()) {
                // Tomamos la primera zona disponible
                $zone = $zones->first();
                $result['zone'] = $zone;
                \Log::info('Zona seleccionada:', ['nombre' => $zone->nombre, 'id' => $zone->id]);

                // Buscar pueblo dentro de esta zona
                $towns = \App\Models\ShippingCompanyTown::where('shipping_company_zone_id', $zone->id)
                    ->get();

                \Log::info('Pueblos disponibles:', ['count' => $towns->count()]);

                if ($towns->isNotEmpty()) {
                    // Tomar el primer pueblo disponible
                    $town = $towns->first();
                    $result['town'] = $town;
                    \Log::info('Pueblo seleccionado:', [
                        'nombre' => $town->nombre,
                        'id' => $town->id,
                        'dias_disponibles' => $town->dias_disponibles
                    ]);
                }
            }
        } else {
            \Log::warning('No se encontró ninguna ciudad para la empresa y ciudad especificadas');
        }

        return $result;
    }

    /**
     * Calcula la fecha estimada de entrega basado en los días disponibles
     * 
     * @param \Carbon\Carbon $pickupDate Fecha de recogida
     * @param bool $isLocalDelivery Si es entrega local
     * @param array|null $availableDays Días disponibles para entrega
     * @param int $estimatedDays Días estimados para entrega
     * @return \Carbon\Carbon
     */
    private function calculateDeliveryDate($pickupDate, $isLocalDelivery, $availableDays, $estimatedDays)
    {
        // Clonar la fecha para no modificar el original
        $deliveryDate = clone $pickupDate;

        // Si es entrega local, entregar al siguiente día hábil desde la recogida
        if ($isLocalDelivery) {
            $deliveryDate->addDay();
            while ($deliveryDate->isWeekend()) {
                $deliveryDate->addDay();
            }
            return $deliveryDate;
        }

        // Si hay días específicos de entrega
        if (is_array($availableDays) && !empty($availableDays)) {
            // Normalizar nombres de días a formato inglés
            $availableDays = array_map(function ($day) {
                return $this->normalizeDayName($day);
            }, $availableDays);

            // Avanzar al menos un día desde la recogida
            $deliveryDate->addDay();

            // Buscar el próximo día disponible
            $maxIterations = 14; // Evitar bucle infinito
            $iterations = 0;

            while (!in_array($deliveryDate->englishDayOfWeek, $availableDays) && $iterations < $maxIterations) {
                $deliveryDate->addDay();
                $iterations++;
            }

            return $deliveryDate;
        }

        // Si no hay días específicos, usar estimación general (sumamos los días adicionales)
        for ($i = 0; $i < $estimatedDays; $i++) {
            $deliveryDate->addDay();
            // Saltar fines de semana
            while ($deliveryDate->isWeekend()) {
                $deliveryDate->addDay();
            }
        }

        return $deliveryDate;
    }

    /**
     * Normaliza el nombre del día en inglés
     * 
     * @param string $day
     * @return string
     */
    private function normalizeDayName($day)
    {
        $day = trim(strtolower($day));

        $dayMap = [
            'lunes' => 'Monday',
            'martes' => 'Tuesday',
            'miércoles' => 'Wednesday',
            'miercoles' => 'Wednesday',
            'jueves' => 'Thursday',
            'viernes' => 'Friday',
            'sábado' => 'Saturday',
            'sabado' => 'Saturday',
            'domingo' => 'Sunday',
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ];

        return $dayMap[$day] ?? ucfirst($day);
    }

    /**
     * Determina la zona de envío basada en la dirección
     */
    // private function determineShippingZone($address)
    // {
    //     // Lista de áreas locales (similar a PricingController)
    //     $localAreas = [
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

    //     // Determinar si es un área local
    //     if (in_array($address->city, $localAreas)) {
    //         return [
    //             'name' => $address->city,
    //             'is_local' => true,
    //             'base_price' => 240.00 + 350.00, // Precio base local (similar a TB)
    //             'delivery_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
    //         ];
    //     }

    //     // Buscar en la base de datos si es un pueblo
    //     $pueblo = \App\Models\TransporteBlancoPueblo::where('nombre', strtoupper($address->city))
    //         ->orWhere(function ($query) use ($address) {
    //             $query->where('latitud', $address->latitude)
    //                 ->where('longitud', $address->longitude);
    //         })->first();

    //     if ($pueblo) {
    //         return [
    //             'name' => $pueblo->nombre,
    //             'is_local' => false,
    //             'is_town' => true,
    //             'base_price' => $pueblo->zona->categoria->precio ?? 800.00,
    //             'delivery_days' => json_decode($pueblo->dias_de_entrega) ?? ['Monday', 'Wednesday', 'Friday']
    //         ];
    //     }

    //     // Buscar si es una ciudad/zona
    //     $ciudad = \App\Models\TransporteBlancoZona::where('nombre', 'like', '%' . $address->state . '%')->first();

    //     if ($ciudad) {
    //         return [
    //             'name' => $ciudad->nombre,
    //             'is_local' => false,
    //             'is_town' => false,
    //             'base_price' => $ciudad->categoria->precio ?? 650.00,
    //             'delivery_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
    //         ];
    //     }

    //     // Si no se encuentra, usar un valor por defecto
    //     return [
    //         'name' => $address->city,
    //         'is_local' => false,
    //         'is_town' => false,
    //         'base_price' => 900.00, // Precio por defecto para zonas desconocidas
    //         'delivery_days' => ['Monday', 'Wednesday', 'Friday']
    //     ];
    // }

    // /**
    //  * Calcula el precio base según la zona y empresa de envío
    //  */
    // private function calculateBasePrice($zone, $shippingCompany)
    // {
    //     // Si la empresa tiene precios específicos configurados, usarlos
    //     if ($shippingCompany->has_custom_pricing) {
    //         // Implementar lógica para precios personalizados por empresa
    //         // Por ahora, usar precios estándar de zona
    //         return $zone['base_price'];
    //     }

    //     return $zone['base_price'];
    // }

    // /**
    //  * Calcula el volumen del producto
    //  */
    // private function calculateVolume($product)
    // {
    //     // Implementar cálculo de volumen similar a PricingController
    //     // Para mantener simple, usar valores de la solicitud original
    //     return (10 * 20 * 30) * 2.54; // cm³ (convertidos desde pulgadas)
    // }

    /**
     * Calcula cargos adicionales basados en peso y volumen
     */
    private function calculateAdditionalCharges($weight, $volume, $quantity)
    {
        $totalWeight = $weight * $quantity;
        $totalVolume = $volume * $quantity;
        $additionalCharges = 0;

        // Calcular cargos por exceso de peso (similar a PricingController)
        if ($totalWeight > 40.00 && $totalWeight < 150.00) {
            $totalWeightExceeded = $totalWeight - 40.00;
            $extraWeightPrice = $totalWeightExceeded * 10.00;
            $additionalCharges += $extraWeightPrice;
        }

        // Calcular cargos por exceso de volumen
        $maxVolume = (19 * 2.54) * (12 * 2.54) * (12 * 2.54); // MAX_VOLUME de PricingController
        if ($totalVolume > $maxVolume) {
            $actualVolumeOnPie = $totalVolume / 30.48;
            $maxVolumeOnPie = $maxVolume / 30.48;
            $volumeExceeded = $actualVolumeOnPie - $maxVolumeOnPie;
            $extraVolumePrice = $volumeExceeded * 30.00;
            $additionalCharges += $extraVolumePrice;
        }

        return $additionalCharges;
    }

    /**
     * Estima la fecha de recogida (similar a PricingController)
     */
    private function estimatePickupDate()
    {
        $date = \Carbon\Carbon::today()->timezone('America/Santo_Domingo');

        // Agregar 5 días hábiles para la recogida
        for ($i = 0; $i < 5; $i++) {
            $date->addDay();
            // Saltamos los fines de semana
            while ($date->isWeekend()) {
                $date->addDay();
            }
        }

        return $date;
    }
    /**
     * Calcula los días hábiles hasta el próximo día de entrega disponible
     * 
     * @param array $diasDisponibles Ej: ['Lunes', 'Miércoles', 'Viernes']
     * @param \Carbon\Carbon $fechaInicio Fecha desde la que calcular (ej: hoy)
     * @return int Días hasta el próximo día disponible
     */
    private function calcularDiasHastaProximoEnvio(array $diasDisponibles, \Carbon\Carbon $fechaInicio)
    {
        // Normaliza los nombres de los días a inglés para Carbon
        $diasMap = [
            'Lunes' => Carbon::MONDAY,
            'Martes' => Carbon::TUESDAY,
            'Miércoles' => Carbon::WEDNESDAY,
            'Miercoles' => Carbon::WEDNESDAY,
            'Jueves' => Carbon::THURSDAY,
            'Viernes' => Carbon::FRIDAY,
            'Sábado' => Carbon::SATURDAY,
            'Sabado' => Carbon::SATURDAY,
            'Domingo' => Carbon::SUNDAY,
        ];

        $diasDisponiblesNum = [];
        foreach ($diasDisponibles as $dia) {
            if (isset($diasMap[$dia])) {
                $diasDisponiblesNum[] = $diasMap[$dia];
            }
        }

        // Si no hay días disponibles, retorna 0
        if (empty($diasDisponiblesNum)) {
            return 0;
        }

        $dias = 1;
        $fecha = $fechaInicio->copy()->addDay(); // Empieza a contar desde mañana

        while ($dias < 15) { // Máximo 2 semanas para evitar bucles infinitos
            if (in_array($fecha->dayOfWeek, $diasDisponiblesNum)) {
                return $dias;
            }
            $fecha->addDay();
            $dias++;
        }

        return $dias; // Si no encuentra, retorna el máximo
    }
    /**
     * Estima la fecha de entrega (similar a PricingController)
     */
    private function estimateDeliveryDate($pickupDate, $zone)
    {
        $date = clone $pickupDate;

        if ($zone['is_local']) {
            // Entrega local: próximo día hábil
            $date->addDay();
            while ($date->isWeekend()) {
                $date->addDay();
            }
        } else if (isset($zone['is_town']) && $zone['is_town']) {
            // Entrega en pueblo: próximo día de disponibilidad
            $availableDays = array_map('ucfirst', $zone['delivery_days']);

            // Si el día de recogida es un día de entrega, agrega un día
            if (in_array($date->englishDayOfWeek, $availableDays)) {
                $date->addDay();
            }

            // Calcula el próximo día de entrega
            while (!in_array($date->englishDayOfWeek, $availableDays)) {
                $date->addDay();
            }
        } else {
            // Entrega en ciudad/zona: 5 días hábiles
            for ($i = 0; $i < 5; $i++) {
                $date->addDay();
                while ($date->isWeekend()) {
                    $date->addDay();
                }
            }
        }

        return $date;
    }










    /**
     * Gestiona la recogida por PedidosYa y envío por transportadora
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function managePedidosYaPickupAndShipping(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'company_id' => 'required|exists:shipping_companies,id',
                'pickup_time' => 'nullable|date_format:Y-m-d H:i:s',
                'delivery_notes' => 'nullable|string'
            ]);

            // Obtener datos necesarios
            $order = \App\Models\Order::with(['orderDetails.product', 'user.addresses'])->findOrFail($request->order_id);
            $shippingCompany = \App\Models\ShippingCompany::findOrFail($request->company_id);

            // Verificar si hay productos
            if ($order->orderDetails->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La orden no tiene productos'
                ], 400);
            }

            // Verificar dirección del usuario
            $userAddress = $order->user->addresses()->where('set_default', 1)->first();
            if (!$userAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario no tiene una dirección predeterminada'
                ], 400);
            }

            try {
                // Programar la hora de recogida
                $pickupTime = $request->pickup_time ?
                    Carbon::parse($request->pickup_time) :
                    Carbon::now('America/Santo_Domingo')->addHours(2);

                // Asegurar que la hora es horario comercial
                if ($pickupTime->hour < 9) {
                    $pickupTime->setTime(9, 0);
                } else if ($pickupTime->hour >= 17) {
                    $pickupTime->addDay()->setTime(9, 0);
                }

                // Saltar fines de semana
                if ($pickupTime->isWeekend()) {
                    $pickupTime->next(Carbon::MONDAY)->setTime(9, 0);
                }

                // PASO 1: Comprobamos el método de pago del pedido
                $isPendingPayment = ($order->payment_status != 'paid' && $order->payment_type == 'Transferencia Bancaria');

                // Aplicar monkey patch para evitar el error de height como relación
                // Esta es la solución clave para evitar el error
                $this->applyProductHeightPatch();

                // PASO 3: Verificar si está pendiente de pago o realizarlo inmediatamente
                $pedidosYaTrackingNumber = null;
                $pedidosYaCost = 0;
                $pedidosYaResponse = null;

                if (!$isPendingPayment) {
                    // Solicitar envío a PedidosYa pasando directamente el orden
                    $pedidosYaResponse = \App\Http\Controllers\Delivery\PedidosYaController::requestShippingForOrder($order);

                    if (!isset($pedidosYaResponse['id'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'PedidosYa no está disponible para esta ruta',
                            'details' => $pedidosYaResponse
                        ], 400);
                    }

                    $pedidosYaTrackingNumber = $pedidosYaResponse['id'];
                    $pedidosYaCost = $pedidosYaResponse['ending_price'] ?? 350;

                    // Crear el servicio de entrega con PedidosYa
                    $deliveryService = \App\Models\DeliveryService::create([
                        'delivery_company' => 'PEDIDOS YA',
                        'py_info' => json_encode($pedidosYaResponse)
                    ]);

                    Log::info('DeliveryService creado para orden confirmada', [
                        'delivery_service_id' => $deliveryService->id,
                        'order_id' => $order->id
                    ]);
                } else {
                    // Guardar información para procesamiento posterior
                    $estimateInfo = \App\Http\Controllers\Delivery\PedidosYaController::getEstimateForOrder($order);

                    if (!isset($estimateInfo['estimateId']) || !isset($estimateInfo['deliveryOfferId'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se pudo obtener estimación de PedidosYa',
                            'details' => $estimateInfo
                        ], 400);
                    }

                    // Crear servicio de entrega pendiente
                    $pendingData = [
                        'status' => 'pending_payment',
                        'order_id' => $order->id,
                        'estimateId' => $estimateInfo['estimateId'],
                        'deliveryOfferId' => $estimateInfo['deliveryOfferId'],
                        'starter_price' => $estimateInfo['starter_price'] ?? 280,
                        'ending_price' => $estimateInfo['ending_price'] ?? 350
                    ];

                    $deliveryService = \App\Models\DeliveryService::create([
                        'delivery_company' => 'PEDIDOS YA',
                        'py_info' => json_encode($pendingData)
                    ]);

                    $pedidosYaCost = $pendingData['ending_price'];
                    $pedidosYaResponse = $pendingData;

                    Log::info('DeliveryService creado con estado pendiente', [
                        'delivery_service_id' => $deliveryService->id,
                        'order_id' => $order->id
                    ]);
                }

                // Restaurar clases a su estado original
                $this->restoreClassFunctions();

                // Preparar respuesta
                return response()->json([
                    'success' => true,
                    'message' => $isPendingPayment ?
                        'Proceso de envío registrado correctamente. Se confirmará al recibir el pago.' :
                        'Proceso de envío iniciado correctamente',
                    'pickup_details' => [
                        'provider' => 'PedidosYa',
                        'tracking_number' => $pedidosYaTrackingNumber,
                        'status' => $isPendingPayment ? 'pending_payment' : 'scheduled',
                        'cost' => $pedidosYaCost
                    ]
                ]);
            } catch (\Exception $innerException) {
                // Restaurar clases a su estado original en caso de error
                $this->restoreClassFunctions();
                throw $innerException; // Re-lanzar para que lo maneje el catch externo
            }
        } catch (\Exception $e) {
            \Log::error('Error al gestionar recogida y envío: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aplica un patch temporal para evitar el error de height como relación
     */
    private function applyProductHeightPatch()
    {
        // Guardar una referencia al método original getEstimateForOrder
        // if (method_exists('\App\Http\Controllers\Delivery\PedidosYaController', 'getEstimateForOrder')) {
        //     $originalMethod = \App\Http\Controllers\Delivery\PedidosYaController::class . '::getEstimateForOrder';

        //     // Crear un nuevo método getEstimateForOrder que no acceda a height/width/length
        //     \App\Http\Controllers\Delivery\PedidosYaController::getEstimateForOrder = function ($order) {
        //         // Obtener el primer detalle de la orden para el envío
        //         $orderDetail = $order->orderDetails->first();
        //         if (!$orderDetail || !$orderDetail->product) {
        //             return ['error' => 'No hay productos en la orden'];
        //         }

        //         // Obtener la dirección del usuario
        //         $userAddress = $order->user->addresses()->where('set_default', 1)->first();
        //         if (!$userAddress) {
        //             return ['error' => 'Usuario sin dirección predeterminada'];
        //         }

        //         // Obtener la dirección del negocio
        //         $shopUser = \App\Models\User::find($orderDetail->product->user_id);
        //         $shop = \App\Models\Shop::where('user_id', $shopUser->id)->first();
        //         $shopAddress = \App\Models\Address::where('user_id', $shopUser->id)
        //             ->where('set_default', 1)
        //             ->first();

        //         if (!$shopAddress) {
        //             // Crear una dirección predeterminada para el negocio
        //             $shopAddress = \App\Models\Address::create([
        //                 'address' => 'Av. Carlos Perez Ricart 17',
        //                 'city' => 'Santo Domingo',
        //                 'phone' => '8299221234',
        //                 'state' => 'Distrito Nacional',
        //                 'country' => 'Dominican Republic',
        //                 'latitude' => 18.502065658569,
        //                 'longitude' => -69.945693969727,
        //                 'user_id' => $shopUser->id,
        //                 'postal_code' => '10000'
        //             ]);
        //         }

        //         // Usar un valor fijo para volumen para evitar acceder a height/width/length
        //         $weight = ($orderDetail->product->weight ?? 1) * $orderDetail->quantity;
        //         $volume = 2000 * $orderDetail->quantity; // Valor fijo para evitar problemas

        //         // Buscar un carrito existente o crear uno
        //         $cart = \App\Models\Cart::where('user_id', $order->user_id)
        //             ->where('product_id', $orderDetail->product->id)
        //             ->first();

        //         if (!$cart) {
        //             $cart = \App\Models\Cart::create([
        //                 'user_id' => $order->user_id,
        //                 'product_id' => $orderDetail->product->id,
        //                 'quantity' => $orderDetail->quantity,
        //                 'price' => $orderDetail->product->unit_price * $orderDetail->quantity,
        //                 'variation' => json_encode([]),
        //                 'variation_id' => null,
        //                 'temp_id' => null
        //             ]);
        //         }

        //         // Preparar el body para getEstimatedShipping pero evitando acceder a height/width/length
        //         $body = [
        //             'customerId' => $order->user_id,
        //             'product' => $orderDetail->product,
        //             'quantity' => $orderDetail->quantity,
        //             'totalWeight' => $weight,
        //             'totalVolume' => $volume,
        //             'buyerAddress' => $userAddress,
        //             'sellerAddress' => $shopAddress,
        //             'availableAreas' => [
        //                 "Santo Domingo",
        //                 "Santo Domingo Este",
        //                 "Santo Domingo Norte",
        //                 "Santo Domingo Oeste",
        //                 "Distrito Nacional",
        //                 "Pedro Brand",
        //                 "Los Alcarrizos",
        //                 "San Antonio de Guerra",
        //                 "Boca Chica"
        //             ],
        //             'isTransferred' => true
        //         ];

        //         // Generar un mock de respuesta para evitar problemas
        //         return [
        //             'estimateId' => 'est-' . time(),
        //             'deliveryOfferId' => 'do-' . time(),
        //             'starter_price' => 280,
        //             'ending_price' => 350,
        //             'cart_id' => $cart->id
        //         ];
        //     };
        // }
    }

    /**
     * Restaura las funciones de clase a su estado original
     */
    private function restoreClassFunctions()
    {
        // if (isset(PedidosYaController::getEstimateForOrder)) {
        //     unset(PedidosYaController::getEstimateForOrder);
        // }
    }

    /**
     * Procesa y confirma envíos pendientes a PedidosYa después de confirmar pago
     * 
     * @param int $orderId
     * @return bool
     */
    public function confirmPendingPedidosYaShipment($orderId)
    {
        try {
            $order = \App\Models\Order::findOrFail($orderId);

            // Verificar si el pedido ya está pagado
            if ($order->payment_status != 'paid') {
                return false;
            }

            // Buscar el ShippingProcess asociado
            $shippingProcess = \App\Models\ShippingProcess::where('order_id', $order->id)
                ->where('pedidosya_status', 'pending_payment')
                ->first();

            if (!$shippingProcess) {
                return false;
            }

            // Buscar el DeliveryService asociado
            $deliveryService = \App\Models\DeliveryService::find($shippingProcess->delivery_service_id);
            if (!$deliveryService) {
                $deliveryService = \App\Models\DeliveryService::whereHas('orderDetails', function ($query) use ($order) {
                    $query->where('order_id', $order->id);
                })->first();
            }

            if (!$deliveryService) {
                Log::error('No se encontró DeliveryService para el envío pendiente', [
                    'order_id' => $order->id,
                    'shipping_process_id' => $shippingProcess->id
                ]);
                return false;
            }

            // Decodificar la información de PedidosYa
            $pyInfo = json_decode($deliveryService->py_info, true);

            // Verificar si es un servicio pendiente de pago
            if (!isset($pyInfo['status']) || $pyInfo['status'] != 'pending_payment') {
                return false;
            }

            // Verificar si tenemos la información necesaria
            if (!isset($pyInfo['estimateId']) || !isset($pyInfo['deliveryOfferId'])) {
                Log::info('Información incompleta en py_info para envío pendiente', [
                    'delivery_service_id' => $deliveryService->id,
                    'order_id' => $order->id,
                    'py_info' => $pyInfo
                ]);
                return false;
            }

            // Confirmar el envío con PedidosYa
            $confirmResponse = \App\Http\Controllers\Delivery\PedidosYaController::confirmShipping(
                $pyInfo['estimateId'],
                $pyInfo['deliveryOfferId']
            );

            if (!isset($confirmResponse['id'])) {
                Log::error('Error al confirmar envío pendiente con PedidosYa', [
                    'order_id' => $order->id,
                    'response' => $confirmResponse
                ]);
                return false;
            }

            // Actualizar el ShippingProcess
            $shippingProcess->pedidosya_tracking = $confirmResponse['id'];
            $shippingProcess->pedidosya_status = 'scheduled';
            $shippingProcess->pedidosya_data = json_encode($confirmResponse);
            $shippingProcess->save();

            // Actualizar el DeliveryService
            $deliveryService->py_info = json_encode($confirmResponse);
            $deliveryService->save();

            Log::info('Envío a PedidosYa confirmado después de pago', [
                'order_id' => $order->id,
                'tracking_number' => $confirmResponse['id']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al procesar confirmación de envío pendiente: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'exception' => $e
            ]);
            return false;
        }
    }
    /**
     * Calcula el peso total de una orden en libras
     * 
     * @param \App\Models\Order $order
     * @return float
     */
    private function calculateOrderWeight($order)
    {
        $totalWeight = 0;

        foreach ($order->order_details as $item) {
            $weight = $item->product->weight ?? 0.5;
            $totalWeight += $weight * $item->quantity;
        }

        return max($totalWeight, 0.5); // Mínimo 0.5 lb
    }

    /**
     * Calcula el volumen total de una orden en cm³
     * 
     * @param \App\Models\Order $order
     * @return float
     */
    private function calculateOrderVolume($order)
    {
        $totalVolume = 0;

        foreach ($order->order_details as $item) {
            // Usar dimensiones estándar si no hay datos de producto
            $dimensions = [
                'width' => 10, // en pulgadas
                'height' => 20, // en pulgadas
                'length' => 30  // en pulgadas
            ];

            // Convertir pulgadas a cm³
            $volume = $dimensions['width'] * $dimensions['height'] * $dimensions['length'] * 2.54;
            $totalVolume += $volume * $item->quantity;
        }

        return max($totalVolume, 1000); // Volumen mínimo 1000 cm³
    }

    /**
     * Construye los detalles del paquete a partir de la orden
     * 
     * @param \App\Models\Order $order
     * @return array
     */
    private function buildPackageDetails($order)
    {
        // Calcular peso y dimensiones totales
        $totalWeight = 0;
        $maxDimension = 0;
        $productDescriptions = [];

        foreach ($order->order_details as $item) {
            $weight = $item->product->weight ?? 0.5;
            $totalWeight += $weight * $item->quantity;

            // Para dimensiones, usamos valores por defecto
            $maxDimension = max($maxDimension, 30);

            $productDescriptions[] = $item->quantity . 'x ' . $item->product->name;
        }

        return [
            'weight' => $totalWeight > 0 ? $totalWeight : 1,
            'dimensions' => [
                'width' => $maxDimension,
                'height' => $maxDimension,
                'length' => $maxDimension
            ],
            'description' => 'Pedido #' . $order->id . ': ' . implode(', ', $productDescriptions),
            'is_fragile' => true,
            'value' => $order->total
        ];
    }

    /**
     * Prepara los datos para el envío con la transportadora
     * 
     * @param \App\Models\Order $order
     * @param \App\Models\ShippingCompany $shippingCompany
     * @param \App\Models\Address $userAddress
     * @return array
     */
    private function prepareShippingData($order, $shippingCompany, $userAddress)
    {
        $packageDetails = $this->buildPackageDetails($order);

        return [
            'shipping_request' => [
                'reference_id' => 'ORDER-' . $order->id . '-' . time(),
                'customer' => [
                    'name' => $order->user->name,
                    'phone' => $userAddress->phone ?? '',
                    'email' => $order->user->email
                ],
                'pickup' => [
                    'address' => $shippingCompany->address ?? 'Sucursal Principal',
                    'city' => $shippingCompany->city ?? 'Santo Domingo',
                    'state' => $shippingCompany->state ?? 'Santo Domingo',
                    'country' => $shippingCompany->country ?? 'Dominican Republic',
                    'latitude' => $shippingCompany->latitude,
                    'longitude' => $shippingCompany->longitude
                ],
                'destination' => [
                    'address' => $userAddress->address,
                    'city' => $userAddress->city,
                    'state' => $userAddress->state,
                    'country' => $userAddress->country,
                    'latitude' => $userAddress->latitude,
                    'longitude' => $userAddress->longitude
                ],
                'package' => [
                    'description' => $packageDetails['description'],
                    'weight' => $packageDetails['weight'],
                    'dimensions' => $packageDetails['dimensions'],
                    'quantity' => count($order->order_details),
                    'price' => $order->total,
                    'is_fragile' => $packageDetails['is_fragile']
                ],
                'settings' => [
                    'notify_customer' => true,
                    'require_signature' => true,
                    'preferred_delivery_time' => null,
                    'whatsapp_message' => $shippingCompany->default_message
                ]
            ]
        ];
    }

    /**
     * Calcula estimaciones de precios y fechas para el envío
     * 
     * @param \App\Models\Order $order
     * @param \App\Models\ShippingCompany $shippingCompany
     * @param \App\Models\Address $userAddress
     * @return array
     */
    private function calculateShippingEstimates($order, $shippingCompany, $userAddress)
    {
        // Resultado de búsqueda para cálculos
        $shippingCalculationRequest = new Request([
            'company_id' => $shippingCompany->id,
            'city' => $userAddress->city,
            'state' => $userAddress->state,
            'latitude' => $userAddress->latitude,
            'longitude' => $userAddress->longitude
        ]);

        $searchResult = $this->findShippingLocationData($shippingCalculationRequest);
        $shippingCity = $searchResult['city'] ?? null;
        $shippingZone = $searchResult['zone'] ?? null;
        $shippingTown = $searchResult['town'] ?? null;

        // Variables para cálculos
        $isLocalDelivery = false;
        $availableDays = null;
        $estimatedDays = 5;
        $basePrice = $shippingCity ? $shippingCity->precio : 900.00;

        // Verificar si es entrega local
        $localAreas = ['Santo Domingo', 'Santo Domingo Este', 'Santo Domingo Norte', 'Santo Domingo Oeste', 'Distrito Nacional'];
        $isLocalDelivery = in_array($userAddress->city, $localAreas) ||
            ($shippingCity && stripos($shippingCity->nombre, 'Santo Domingo') !== false);

        // Si es local, entrega en 1 día hábil
        if ($isLocalDelivery) {
            $estimatedDays = 1;
        }

        // Procesar días disponibles del pueblo
        if ($shippingTown) {
            $availableDays = $this->processDiasDisponibles($shippingTown->dias_disponibles);
        }

        // Calcular peso y volumen totales
        $totalWeight = 0;
        $totalVolume = 0;

        foreach ($order->order_details as $item) {
            $weight = $item->product->weight ?? 1;
            $dimensions = [
                'width' => 10,
                'height' => 20,
                'length' => 30
            ];
            $volume = $dimensions['width'] * $dimensions['height'] * $dimensions['length'] * 2.54;

            $totalWeight += $weight * $item->quantity;
            $totalVolume += $volume * $item->quantity;
        }

        // Calcular cargos adicionales
        $additionalCharges = $this->calculateAdditionalCharges($totalWeight, $totalVolume, 1);

        // Calcular precio total
        $totalPrice = $basePrice + $additionalCharges;

        // Calcular precio final con margen
        $finalPrice = $totalPrice + ($totalPrice * 0.25);

        // Calcular fechas de recogida y entrega
        $pickupDate = now()->addDays(1); // Ya está en la sucursal, así que recogida al día siguiente
        $deliveryDate = $this->calculateDeliveryDate($pickupDate, $isLocalDelivery, $availableDays, $estimatedDays);

        return [
            'pricing' => [
                'base_price' => round($basePrice, 2),
                'additional_charges' => round($additionalCharges, 2),
                'total_price' => round($totalPrice, 2),
                'final_price' => round($finalPrice, 2)
            ],
            'dates' => [
                'estimated_pickup' => $pickupDate->format('Y-m-d H:i:s'),
                'estimated_delivery' => $deliveryDate->format('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Realiza la solicitud de envío final a la transportadora
     * 
     * @param array $data
     * @param \App\Models\ShippingCompany $shippingCompany
     * @return array
     */
    private function requestFinalShipping($data, $shippingCompany)
    {
        try {
            $client = new \GuzzleHttp\Client();

            // Endpoint de la transportadora
            $endpoint = $shippingCompany->api_endpoint;

            // Registrar payload para debugging
            \Log::info('Solicitud enviada a transportadora:', $data);

            // Enviar solicitud
            $response = $client->request('POST', $endpoint, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-API-Key' => $shippingCompany->api_key
                ],
                'timeout' => 30,
                'http_errors' => false
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            // Registrar respuesta para debugging
            \Log::info('Respuesta de transportadora:', [
                'status' => $statusCode,
                'data' => $responseData
            ]);

            return $responseData;
        } catch (\Exception $e) {
            \Log::error('Error al solicitar envío a transportadora: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Error en comunicación con transportadora: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el estado actual de un proceso de envío
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function trackShippingProcess($id)
    {
        try {
            $process = ShippingProcess::with(['order', 'shippingCompany'])->findOrFail($id);

            // Obtener estado actualizado de PedidosYa
            $pedidosYaStatus = null;
            // if ($process->pedidosya_tracking) {
            //     $pedidosYaStatus = PedidosYaController::getShippingStatus($process->pedidosya_tracking);
            // }

            // Obtener estado actualizado de la transportadora
            $shippingStatus = null;
            if ($process->shipping_tracking && $process->shippingCompany && $process->shippingCompany->api_endpoint) {
                // Implementar llamada a API de transportadora para obtener estado
                // Por ahora, usar datos almacenados
                $shippingStatus = json_decode($process->shipping_data, true);
            }

            // Actualizar estado en base de datos si hay cambios
            if ($pedidosYaStatus && isset($pedidosYaStatus['status'])) {
                $process->pedidosya_status = $pedidosYaStatus['status'];
                $process->save();
            }

            // Preparar datos para respuesta
            $trackingData = [
                'process_id' => $process->id,
                'order_id' => $process->order_id,
                'created_at' => $process->created_at->format('Y-m-d H:i:s'),
                'pickup' => [
                    'provider' => 'PedidosYa',
                    'tracking_number' => $process->pedidosya_tracking,
                    'status' => $process->pedidosya_status,
                    'pickup_date' => $process->pickup_date,
                    'estimated_branch_arrival' => $process->estimated_branch_arrival,
                    'details' => $pedidosYaStatus
                ],
                'shipping' => [
                    'company' => $process->shippingCompany->name,
                    'tracking_number' => $process->shipping_tracking,
                    'status' => $process->shipping_status,
                    'estimated_delivery_date' => $process->estimated_delivery_date,
                    'details' => $shippingStatus
                ],
                'total_cost' => $process->total_cost
            ];

            // Responder según el tipo de solicitud
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'tracking_data' => $trackingData
                ]);
            }

            // Para solicitudes web, devolver vista
            return view('shipping.tracking', compact('trackingData'));
        } catch (\Exception $e) {
            \Log::error('Error al obtener datos de tracking: ' . $e->getMessage(), [
                'process_id' => $id,
                'exception' => $e
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener datos de seguimiento: ' . $e->getMessage()
                ], 500);
            }

            return view('shipping.tracking_error', ['error' => $e->getMessage()]);
        }
    }
}
