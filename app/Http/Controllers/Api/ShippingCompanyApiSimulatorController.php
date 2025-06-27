<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingCompanyApiSimulatorController extends Controller
{
    /**
     * Simula recibir una solicitud de envío
     */
    public function receiveShippingRequest(Request $request)
{
    try {
        // Registrar la solicitud recibida
        Log::info('Solicitud de envío recibida en API simulada:', $request->all());
        
        // Validar datos mínimos
        $request->validate([
            'shipping_request.reference_id' => 'required|string',
            'shipping_request.customer' => 'required|array',
            'shipping_request.destination' => 'required|array',
            'shipping_request.package' => 'required|array',
        ]);
        
        // Simular procesamiento
        $processingTime = rand(1, 3); // 1-3 segundos
        sleep($processingTime);
        
        // Generar tracking number
        $trackingNumber = 'TR-' . strtoupper(substr(md5(time()), 0, 10));
        
        // Comprobar si hay información de precios enviada por el cliente
        $usePricingFromRequest = $request->has('shipping_request.pricing');
        
        if ($usePricingFromRequest) {
            // Usar la información de precios proporcionada en la solicitud
            $pricing = $request->input('shipping_request.pricing');
            $baseCost = $pricing['base_price'] ?? 0;
            $additionalFees = $pricing['additional_charges'] ?? 0;
            $totalCost = $pricing['final_price'] ?? ($baseCost + $additionalFees);
        } else {
            // Generar precios aleatorios como fallback
            $cost = rand(300, 1000) + (rand(0, 99) / 100);
            $baseCost = round($cost * 0.8, 2);
            $additionalFees = round($cost * 0.2, 2);
            $totalCost = $cost;
        }
        
        // Comprobar si hay información de fechas enviada por el cliente
        $useDatesFromRequest = $request->has('shipping_request.dates');
        
        if ($useDatesFromRequest) {
            // Usar las fechas proporcionadas en la solicitud
            $dates = $request->input('shipping_request.dates');
            $estimatedPickup = $dates['estimated_pickup'] ?? now()->addDays(1)->format('Y-m-d H:i:s');
            $estimatedDelivery = $dates['estimated_delivery'] ?? now()->addDays(5)->format('Y-m-d H:i:s');
        } else {
            // Generar fechas aleatorias como fallback
            $estimatedPickup = now()->addDays(1)->format('Y-m-d H:i:s');
            $estimatedDelivery = now()->addDays(rand(3, 7))->format('Y-m-d H:i:s');
        }
        
        // Simular respuesta
        $response = [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'reference_id' => $request->input('shipping_request.reference_id'),
            'status' => 'pending_pickup',
            'estimated_pickup' => $estimatedPickup,
            'estimated_delivery' => $estimatedDelivery,
            'courier' => [
                'name' => 'Courier Simulado',
                'phone' => '809-555-' . rand(1000, 9999)
            ],
            'shipping_cost' => [
                'base_cost' => $baseCost,
                'additional_fees' => $additionalFees,
                'total_cost' => $totalCost,
                'currency' => 'DOP'
            ],
            'tracking_url' => 'https://miempresa.com/track/' . $trackingNumber
        ];
        
        // Registrar respuesta
        Log::info('Respuesta simulada enviada:', $response);
        
        // Devolver respuesta simulada
        return response()->json($response, 201);
        
    } catch (\Exception $e) {
        Log::error('Error en API simulada: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Error en procesamiento: ' . $e->getMessage(),
            'error_code' => 'PROCESSING_ERROR'
        ], 500);
    }
}
    
    /**
     * Simula obtener el estado de un envío
     */
    public function getShippingStatus(Request $request, $trackingNumber)
    {
        try {
            // Simular diferentes estados aleatorios
            $statuses = [
                'pending_pickup', 
                'picked_up', 
                'in_transit', 
                'out_for_delivery', 
                'delivered', 
                'failed_delivery'
            ];
            
            $randomStatus = $statuses[array_rand($statuses)];
            
            // Generar respuesta
            $response = [
                'success' => true,
                'tracking_number' => $trackingNumber,
                'status' => $randomStatus,
                'last_update' => now()->subHours(rand(1, 12))->format('Y-m-d H:i:s'),
                'history' => [
                    [
                        'status' => 'pending_pickup',
                        'timestamp' => now()->subDays(2)->format('Y-m-d H:i:s'),
                        'location' => 'Central de Envíos',
                        'description' => 'Envío registrado'
                    ],
                    [
                        'status' => 'picked_up',
                        'timestamp' => now()->subDays(1)->format('Y-m-d H:i:s'),
                        'location' => 'Central de Envíos',
                        'description' => 'Paquete recogido'
                    ]
                ]
            ];
            
            // Si está entregado, agregar fecha de entrega
            if ($randomStatus == 'delivered') {
                $response['delivered_at'] = now()->subHours(rand(1, 8))->format('Y-m-d H:i:s');
                $response['recipient_name'] = 'Cliente ' . $trackingNumber;
                $response['history'][] = [
                    'status' => 'delivered',
                    'timestamp' => $response['delivered_at'],
                    'location' => 'Dirección de destino',
                    'description' => 'Entregado a ' . $response['recipient_name']
                ];
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener estado: ' . $e->getMessage()
            ], 500);
        }
    }
}