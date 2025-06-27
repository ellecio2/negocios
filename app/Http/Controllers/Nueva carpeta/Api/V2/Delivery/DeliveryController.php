<?php

namespace App\Http\Controllers\Api\V2\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function index()
    {
        // Carritos por tienda
        $carts_per_store = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->product->user_id;
            });

        // Si algun carrito no tiene direccion seleccionada interrumpe el proceso
        foreach($carts_per_store as $store_carts){
            foreach($store_carts as $cart){
                if($cart->address_id === 0){
                    return response()->json([
                        'result' => false,
                        'status' => 'error',
                        'message' => 'Select delivery address before check availability'
                    ], 400);
                }
            }
        }

        // Revisar disponibilidad del pedido con PedidosYa
        $pedidos_ya_response = PedidosYaController::checkAvailability($carts_per_store);

        // Se obtiene la data de los carritos disponibles y no disponibles segun PedidosYa
        $data = $pedidos_ya_response->getData(true)['data'];

        // Revisar disponibilidad del pedido con Transporte Blanco
        $transporte_blanco_response = TransporteBlancoController::checkAvailability($data);

        // Inicializar el array de datos
        $data = [];
        
        // Agregar respuesta de TransporteBlanco
        $data[] = $transporte_blanco_response;
        
        // Obtener todas las empresas de envío
        $shipping_companies = ShippingCompany::all();
        
        // Para cada empresa de envío, verificar disponibilidad
        foreach ($shipping_companies as $company) {
            // Verificar si existe un controlador específico para esta empresa
            $controller_class = "App\\Http\\Controllers\\Api\\V2\\Delivery\\" . str_replace(' ', '', $company->name) . "Controller";
            
            if (class_exists($controller_class)) {
                // Si existe un controlador específico, usarlo
                $company_response = $controller_class::checkAvailability($carts_per_store);
                $data[] = $company_response;
            } else {
                // De lo contrario, usar una lógica genérica
                // Esto dependerá de cómo quieras manejar las empresas sin controlador específico
                // Por ejemplo, podrías tener un controlador genérico:
                $company_response = $this->checkGenericCompanyAvailability($company, $carts_per_store);
                $data[] = $company_response;
            }
        }
        
        // Inyectar información del producto
        $data = $this->injectProductInfo($data);

        return response()->json([
            'result' => true,
            'status' => 'success',
            'data' => $data,
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

    private function injectProductInfo(array $data) : array
    {
        $products = collect();

        foreach($data as $store_key => $store){
            foreach($store as $key => $id_list){
                // Procesar TransporteBlanco
                if (isset($id_list['transporteBlanco'])) {
                    foreach($id_list['transporteBlanco']['productsPackage'] as $cart_id){
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
                    foreach($id_list['PedidosYa']['productsPackage'] as $cart_id){
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
                        foreach($id_list[$company_key]['productsPackage'] as $cart_id){
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