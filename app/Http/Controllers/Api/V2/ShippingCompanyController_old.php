<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCompany;
use App\Models\ShippingCompanyCity;
use App\Models\ShippingCompanyTown;
use App\Models\ShippingCompanyZone;
use Illuminate\Support\Facades\Log;

class ShippingCompanyController extends Controller
{
    
public function store(Request $request)
{
    // Registrar los datos recibidos para depuración
    Log::info('Datos del formulario:', $request->all());
    
    // Validación más permisiva
    $request->validate([
        'name' => 'required|string|max:255',
        'api_endpoint' => 'nullable|string', // Cambiado de url a string
        'whatsapp_number' => 'nullable|string',
        'default_message' => 'nullable|string',
        'latitude' => 'nullable|string', // Cambiado de numeric a string
        'longitude' => 'nullable|string', // Cambiado de numeric a string
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
        ]);
        
        Log::info('Compañía creada:', $shippingCompany->toArray());

        // Verificar si existen datos de ciudades antes de procesarlos
        if ($request->has('cities')) {
            // Si cities viene como JSON, decodificarlo
            $citiesData = $request->cities;
            if (is_string($citiesData)) {
                $citiesData = json_decode($citiesData, true);
            }
            
            Log::info('Procesando ciudades:', ['count' => count($citiesData)]);
            
            foreach ($citiesData as $cityData) {
                Log::info("Procesando ciudad:", $cityData);
                
                // Crear ciudad
                $city = ShippingCompanyCity::create([
                    'nombre' => $cityData['nombre'],
                    'precio' => $cityData['precio'] ?? null,
                    'shipping_company_id' => $shippingCompany->id,
                ]);
                
                Log::info("Ciudad creada:", $city->toArray());
                
                // Verificar si existen datos de zonas
                if (isset($cityData['zones']) && is_array($cityData['zones'])) {
                    Log::info("Procesando zonas para ciudad:", ['count' => count($cityData['zones'])]);
                    
                    foreach ($cityData['zones'] as $zoneData) {
                        Log::info("Procesando zona:", $zoneData);
                        
                        // Crear zona
                        $zone = ShippingCompanyZone::create([
                            'nombre' => $zoneData['nombre'],
                            'latitud' => $zoneData['latitud'],
                            'longitud' => $zoneData['longitud'],
                            'shipping_company_city_id' => $city->id,
                        ]);
                        
                        Log::info("Zona creada:", $zone->toArray());
                        
                        // Verificar si existen datos de pueblos
                        if (isset($zoneData['towns']) && is_array($zoneData['towns'])) {
                            Log::info("Procesando pueblos para zona:", ['count' => count($zoneData['towns'])]);
                            
                            foreach ($zoneData['towns'] as $townData) {
                                Log::info("Procesando pueblo:", $townData);
                                
                                // Convertir array a JSON si es necesario
                                $dias_disponibles = $townData['dias_disponibles'];
                                if (is_array($dias_disponibles)) {
                                    $dias_disponibles = json_encode($dias_disponibles);
                                }
                                
                                // Crear pueblo
                                $town = ShippingCompanyTown::create([
                                    'nombre' => $townData['nombre'],
                                    'latitud' => $townData['latitud'],
                                    'longitud' => $townData['longitud'],
                                    'dias_disponibles' => $dias_disponibles,
                                    'shipping_company_zone_id' => $zone->id,
                                ]);
                                
                                Log::info("Pueblo creado:", $town->toArray());
                            }
                        }
                    }
                }
            }
        } else {
            Log::info('No se encontraron datos de ciudades en la solicitud.');
        }

        // Responder según el tipo de solicitud
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($shippingCompany, 201);
        }
        
        // Asegurarse de que esta ruta existe y tiene el nombre correcto
        Log::info('Redirigiendo a la ruta de índice.');
        return redirect()->route('admin.shipping-companies.index')
            ->with('success', 'Compañía creada correctamente');
            
    } catch (\Exception $e) {
        // Registrar cualquier error para su depuración
        Log::error('Error al guardar los datos: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
        }
        
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Error al guardar los datos: ' . $e->getMessage()]);
    }
}
    // public function store(Request $request)
    // {
    //     // Registrar los datos recibidos para depuración
    //     Log::info('Datos del formulario:', $request->all());
        
    //     // Validación más permisiva
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'api_endpoint' => 'nullable|string', // Cambiado de url a string
    //         'whatsapp_number' => 'nullable|string',
    //         'default_message' => 'nullable|string',
    //         'latitude' => 'nullable|string', // Cambiado de numeric a string
    //         'longitude' => 'nullable|string', // Cambiado de numeric a string
    //     ]);

    //     try {
    //         // Crear la compañía de envío
    //         $shippingCompany = ShippingCompany::create([
    //             'name' => $request->name,
    //             'api_endpoint' => $request->api_endpoint,
    //             'whatsapp_number' => $request->whatsapp_number,
    //             'default_message' => $request->default_message,
    //             'latitude' => $request->latitude,
    //             'longitude' => $request->longitude,
    //         ]);
            
    //         Log::info('Compañía creada:', $shippingCompany->toArray());

    //         // Verificar si existen datos de ciudades antes de procesarlos
    //         if ($request->has('cities')) {
    //             Log::info('Procesando ciudades:', ['count' => count($request->cities)]);
                
    //             foreach ($request->cities as $index => $cityData) {
    //                 Log::info("Procesando ciudad {$index}:", $cityData);
                    
    //                 // Crear ciudad
    //                 $city = ShippingCompanyCity::create([
    //                     'nombre' => $cityData['nombre'],
    //                     'precio' => $cityData['precio'] ?? null,
    //                     'shipping_company_id' => $shippingCompany->id,
    //                 ]);
                    
    //                 Log::info("Ciudad {$index} creada:", $city->toArray());
                    
    //                 // Verificar si existen datos de zonas
    //                 if (isset($cityData['zones'])) {
    //                     Log::info("Procesando zonas para ciudad {$index}:", ['count' => count($cityData['zones'])]);
                        
    //                     foreach ($cityData['zones'] as $zIndex => $zoneData) {
    //                         Log::info("Procesando zona {$zIndex} para ciudad {$index}:", $zoneData);
                            
    //                         // Crear zona
    //                         $zone = ShippingCompanyZone::create([
    //                             'nombre' => $zoneData['nombre'],
    //                             'latitud' => $zoneData['latitud'],
    //                             'longitud' => $zoneData['longitud'],
    //                             'shipping_company_city_id' => $city->id,
    //                         ]);
                            
    //                         Log::info("Zona {$zIndex} creada para ciudad {$index}:", $zone->toArray());
                            
    //                         // Verificar si existen datos de pueblos
    //                         if (isset($zoneData['towns'])) {
    //                             Log::info("Procesando pueblos para zona {$zIndex}:", ['count' => count($zoneData['towns'])]);
                                
    //                             foreach ($zoneData['towns'] as $tIndex => $townData) {
    //                                 Log::info("Procesando pueblo {$tIndex} para zona {$zIndex}:", $townData);
                                    
    //                                 // Crear pueblo
    //                                 $town = ShippingCompanyTown::create([
    //                                     'nombre' => $townData['nombre'],
    //                                     'latitud' => $townData['latitud'],
    //                                     'longitud' => $townData['longitud'],
    //                                     'dias_disponibles' => json_encode($townData['dias_disponibles'] ?? []),
    //                                     'shipping_company_zone_id' => $zone->id,
    //                                 ]);
                                    
    //                                 Log::info("Pueblo {$tIndex} creado para zona {$zIndex}:", $town->toArray());
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         } else {
    //             Log::info('No se encontraron datos de ciudades en la solicitud.');
    //         }

    //         // Responder según el tipo de solicitud
    //         if ($request->wantsJson()) {
    //             return response()->json($shippingCompany, 201);
    //         }
            
    //         // Asegurarse de que esta ruta existe y tiene el nombre correcto
    //         Log::info('Redirigiendo a la ruta de índice.');
    //         return redirect()->route('admin.shipping-companies.index')
    //             ->with('success', 'Compañía creada correctamente');
                
    //     } catch (\Exception $e) {
    //         // Registrar cualquier error para su depuración
    //         Log::error('Error al guardar los datos: ' . $e->getMessage(), [
    //             'exception' => $e,
    //             'trace' => $e->getTraceAsString()
    //         ]);
            
    //         if ($request->wantsJson()) {
    //             return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
    //         }
            
    //         return redirect()->back()
    //             ->withInput()
    //             ->withErrors(['error' => 'Error al guardar los datos: ' . $e->getMessage()]);
    //     }
    // }
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

    //     $shippingCompany = ShippingCompany::create([
    //         'name' => $request->name,
    //         'api_endpoint' => $request->api_endpoint,
    //         'whatsapp_number' => $request->whatsapp_number,
    //         'default_message' => $request->default_message,
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //     ]);

    //     return response()->json($shippingCompany, 201);
    // }

    public function create()
    {
        return view('backend.delivery_boys.company_delivery_create'); // Asegúrate de que la ruta sea correcta
    }
    public function edit($id)
{
    $shippingCompany = ShippingCompany::findOrFail($id);
    return view('backend.delivery_boys.company_delivery_edit', compact('shippingCompany')); // Ensure this path is correct
}
public function update(Request $request, $id)
{
    $shippingCompany = ShippingCompany::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'api_endpoint' => 'nullable|url',
        'whatsapp_number' => 'nullable|string',
        'default_message' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $shippingCompany->update([
        'name' => $request->name,
        'api_endpoint' => $request->api_endpoint,
        'whatsapp_number' => $request->whatsapp_number,
        'default_message' => $request->default_message,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    return response()->json($shippingCompany, 200);
}
    public function destroy($id)
    {
        $shippingCompany = ShippingCompany::findOrFail($id);
        $shippingCompany->delete();

        return response()->json(null, 204);
    }
    
}