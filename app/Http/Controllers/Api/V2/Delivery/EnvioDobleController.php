<?php

namespace App\Http\Controllers\Api\V2\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Log;

class EnvioDobleController extends Controller
{
    public function pricingEnvioPedidosYa(\Illuminate\Http\Request $request)
{
    $userId = auth()->user()->id;
    $results = \App\Http\Controllers\Api\V2\Delivery\EnvioDoblePedidosYaController::soloPedidosYaEstimatePorUsuario($userId);

    return response()->json([
        'result' => true,
        'status' => 'success',
        'pedidosya_estimates' => $results
    ]);
}
    public function index(\Illuminate\Http\Request $request)
    {
        // Validar que transportCompanyId venga en el request
        $transportCompanyId = $request->input('transportCompanyId');
        if (!$transportCompanyId) {
            return response()->json([
                'result' => false,
                'status' => 'error',
                'message' => 'El parámetro transportCompanyId es obligatorio.'
            ], 400);
        }
        // Validar que la transportadora exista
        $transportCompany = ShippingCompany::find($transportCompanyId);
        if (!$transportCompany) {
            return response()->json([
                'result' => false,
                'status' => 'error',
                'message' => 'Transportadora no encontrada.'
            ], 404);
        }

        $carts_per_store = Cart::where('user_id', auth()->user()->id)
            ->with('product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->product->user_id;
            });
        foreach ($carts_per_store as $store_carts) {
            $firstCart = $store_carts[0];
            $sellerAddress = $firstCart->product->user->addresses()->default()->first();

            // Obtén la dirección de la transportadora
            $transportCompany = ShippingCompany::find($transportCompanyId);
            $transportAddress = EnvioDoblePedidosYaController::getTransportadoraAddress($firstCart, $transportCompanyId);

            // Verifica cobertura entre tienda y transportadora
            $hasCoverage = \App\Http\Controllers\Api\V2\Delivery\EnvioDoblePedidosYaController::checkCoverage($sellerAddress, $transportAddress);

            // Si no hay cobertura, retorna error
            if (!$hasCoverage) {
                return response()->json([
                    'result' => false,
                    'status' => 'error',
                    'message' => 'No hay cobertura de PedidosYa entre la tienda y la dirección seleccionada.'
                ], 400);
            }
        }
        // Si algun carrito no tiene direccion seleccionada interrumpe el proceso
        foreach ($carts_per_store as $store_carts) {
            foreach ($store_carts as $cart) {
                if ($cart->address_id === 0) {
                    return response()->json([
                        'result' => false,
                        'status' => 'error',
                        'message' => 'Select delivery address before check availability'
                    ], 400);
                }
            }
        }

        // Revisar disponibilidad del pedido con PedidosYa
        // $transportCompanyId = 33; // O el valor dinámico que corresponda

        // $pedidos_ya_response = EnvioDoblePedidosYaController::checkAvailability($carts_per_store, $transportCompanyId);

        // $pedidosYaData = $pedidos_ya_response->getData(true)['data'];
        // $store_id = array_key_first($pedidosYaData);

        $pedidos_ya_response = EnvioDoblePedidosYaController::checkAvailability($carts_per_store, $transportCompanyId);
        $pyData = $pedidos_ya_response->getData(true);

        if (!isset($pyData['data'])) {
            // Retorna el error recibido de PedidosYa
            return response()->json([
                'result' => false,
                'status' => 'error',
                'message' => $pyData['message'] ?? 'No se pudo obtener la cotización de PedidosYa.'
            ], 400);
        }

        $pedidosYaData = $pyData['data'];
        $store_id = array_key_first($pedidosYaData);
        $endingCost = $pedidosYaData[$store_id]['PedidosYa']['pricing']['delivery']['endingCost'] ?? 0;
        // Obtén el primer carrito del vendedor actual
        $firstCart = $carts_per_store[$store_id][0];

        // Obtén los datos de la transportadora desde la respuesta de PedidosYa
        $transportPoint = $pedidosYaData[$store_id]['transportPoint'] ?? [];

        // Prepara el request dinámicamente
        $request = new \Illuminate\Http\Request([
            'company_id' => $transportPoint['id'] ?? null,
            'cart_id'    => $firstCart->id ?? null,
            'address'    => $transportPoint['address'] ?? null,
            'city'       => $transportPoint['city'] ?? null,
            'state'      => $firstCart->address->state ?? null, // Asegúrate de tener la relación address cargada
            'country'    => $firstCart->address->country ?? null,
        ]);
        //   'company_id' => 'required|exists:shipping_companies,id',
        //     'cart_id' => 'required', // Puede ser un ID o un array de IDs
        //     'address' => 'required|string',
        //     'city' => 'required|string',
        //     'state' => 'required|string',
        //     'country' => 'required|string',
        // ...otros datos necesarios...

        $shippingController = app(\App\Http\Controllers\Backend\ShippingManagementController::class);
        $transportadoraResponse = $shippingController->calculateShippingByAddress($request);
        $transportadoraData = $transportadoraResponse->getData(true);

        // Usar base_price o final_price según tu lógica
        $transportadoraCost = $transportadoraData['shipping_quote']['base_price'] ?? 0;
        // O si prefieres sumar el final_price:
        // $transportadoraCost = $transportadoraData['shipping_quote']['final_price'] ?? 0;

        $totalEnvioDoble = $endingCost + $transportadoraCost;

        return response()->json([
            'result' => true,
            'status' => 'success',
            'pedidosya_cost' => $endingCost,
            'transportadora_cost' => $transportadoraCost,
            'total_envio_doble' => $totalEnvioDoble,
            'pedidosya_response' => $pedidos_ya_response->getData(true), // aquí agregas toda la respuesta de PedidosYa
        ]);
    }

    /**
     * Verifica disponibilidad para empresas de envío genéricas
     * 
     * @param ShippingCompany $company
     * @param Collection $carts_per_store
     * @return array
     */
    private function checkGenericCompanyAvailability($company, $carts_per_store)
    {
        // Esta es una implementación básica, deberás adaptarla según tus necesidades

        $response = [];

        foreach ($carts_per_store as $store_id => $carts) {
            $cart_ids = $carts->pluck('id')->toArray();

            $response[$store_id] = [
                $company->name => [
                    'available' => true, // Por defecto asumimos que está disponible
                    'productsPackage' => $cart_ids,
                    'shippingCost' => 0, // Definir un costo por defecto o calcularlo según la distancia
                    'estimatedDeliveryTime' => '30-60 min', // Tiempo estimado por defecto
                    'company_id' => $company->id,
                    'company_name' => $company->name
                ]
            ];
        }

        return $response;
    }

    private function injectProductInfo(array $data): array
    {
        $products = collect();

        foreach ($data as $store_key => $store) {
            foreach ($store as $key => $id_list) {
                // Procesar TransporteBlanco
                if (isset($id_list['transporteBlanco'])) {
                    foreach ($id_list['transporteBlanco']['productsPackage'] as $cart_id) {
                        $cart = Cart::find($cart_id);
                        $brand = trim($cart->product->brand->name);
                        $oem = $cart->product->stocks->first()->sku;
                        $products->push([
                            'cart_id' => $cart->id,
                            'model' => "$brand - OEM: $oem",
                            'name' => $cart->product->name,
                            'quantity' => $cart->quantity,
                            'thumbnail' => asset('public/' . $cart->product->thumbnail->file_name),
                            'price' => home_discounted_base_price($cart->product)
                        ]);
                    }
                    $data[$store_key][$key]['transporteBlanco']['productsPackage'] = $products;
                    $products = collect();
                }

                // Procesar PedidosYa
                if (isset($id_list['PedidosYa'])) {
                    foreach ($id_list['PedidosYa']['productsPackage'] as $cart_id) {
                        $cart = Cart::find($cart_id);
                        $brand = trim($cart->product->brand->name);
                        $oem = $cart->product->stocks->first()->sku;
                        $products->push([
                            'cart_id' => $cart->id,
                            'model' => "$brand - OEM: $oem",
                            'name' => $cart->product->name,
                            'quantity' => $cart->quantity,
                            'thumbnail' => asset('public/' . $cart->product->thumbnail->file_name),
                            'price' => home_discounted_base_price($cart->product)
                        ]);
                    }
                    $data[$store_key][$key]['PedidosYa']['productsPackage'] = $products;
                    $products = collect();
                }

                // Procesar empresas de envío adicionales
                $shipping_companies = ShippingCompany::all();
                foreach ($shipping_companies as $company) {
                    $company_key = str_replace(' ', '', $company->name);
                    if (isset($id_list[$company_key])) {
                        foreach ($id_list[$company_key]['productsPackage'] as $cart_id) {
                            $cart = Cart::find($cart_id);
                            $brand = trim($cart->product->brand->name);
                            $oem = $cart->product->stocks->first()->sku;
                            $products->push([
                                'cart_id' => $cart->id,
                                'model' => "$brand - OEM: $oem",
                                'name' => $cart->product->name,
                                'quantity' => $cart->quantity,
                                'thumbnail' => asset('public/' . $cart->product->thumbnail->file_name),
                                'price' => home_discounted_base_price($cart->product)
                            ]);
                        }
                        $data[$store_key][$key][$company_key]['productsPackage'] = $products;
                        $products = collect();
                    }
                }
            }
        }

        return $data;
    }
}
