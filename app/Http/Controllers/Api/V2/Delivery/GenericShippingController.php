<?php

namespace App\Http\Controllers\Api\V2\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenericShippingController extends Controller
{
    /**
     * Verifica disponibilidad para cualquier empresa de envío
     * 
     * @param int $company_id
     * @param Collection $carts_per_store
     * @return array
     */
    public static function checkAvailability($company_id, $carts_per_store)
    {
        $company = ShippingCompany::findOrFail($company_id);
        $response = [];
        
        foreach ($carts_per_store as $store_id => $carts) {
            $cart_ids = $carts->pluck('id')->toArray();
            
            // Si la empresa tiene API endpoint, intentar consultar su API
            if ($company->api_endpoint) {
                try {
                    $api_response = self::callCompanyApi($company, $carts);
                    
                    // Procesar respuesta de la API
                    // Esto es un ejemplo, deberás adaptarlo según la respuesta real de la API
                    $response[$store_id] = [
                        $company->name => [
                            'available' => $api_response['available'] ?? true,
                            'productsPackage' => $cart_ids,
                            'shippingCost' => $api_response['cost'] ?? 0,
                            'estimatedDeliveryTime' => $api_response['time'] ?? '30-60 min',
                            'company_id' => $company->id,
                            'company_name' => $company->name
                        ]
                    ];
                } catch (\Exception $e) {
                    Log::error('Error al consultar API de envío: ' . $e->getMessage());
                    // En caso de error, asumimos que está disponible pero con un mensaje de advertencia
                    $response[$store_id] = [
                        $company->name => [
                            'available' => true,
                            'productsPackage' => $cart_ids,
                            'shippingCost' => 0,
                            'estimatedDeliveryTime' => '30-60 min',
                            'warning' => 'No se pudo verificar la disponibilidad en tiempo real',
                            'company_id' => $company->id,
                            'company_name' => $company->name
                        ]
                    ];
                }
            } else {
                // Si no tiene API, asumimos disponibilidad por defecto
                $response[$store_id] = [
                    $company->name => [
                        'available' => true,
                        'productsPackage' => $cart_ids,
                        'shippingCost' => self::calculateDefaultShippingCost($carts),
                        'estimatedDeliveryTime' => '30-60 min',
                        'company_id' => $company->id,
                        'company_name' => $company->name
                    ]
                ];
            }
        }
        
        return $response;
    }
    
    /**
     * Llama a la API de la empresa de envío
     * 
     * @param ShippingCompany $company
     * @param Collection $carts
     * @return array
     */
    private static function callCompanyApi($company, $carts)
    {
        // Esta es una implementación básica, debes adaptarla según la API real
        try {
            $response = Http::post($company->api_endpoint, [
                'carts' => $carts->toArray(),
                'company_id' => $company->id
            ]);
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error al llamar API de envío: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Calcula un costo de envío predeterminado
     * 
     * @param Collection $carts
     * @return float
     */
    private static function calculateDefaultShippingCost($carts)
    {
        // Implementación básica: un costo base más un adicional por producto
        $base_cost = 500; // Costo base en la moneda correspondiente
        $per_item_cost = 100; // Costo por producto
        
        $total_items = $carts->sum('quantity');
        
        return $base_cost + ($per_item_cost * $total_items);
    }
}