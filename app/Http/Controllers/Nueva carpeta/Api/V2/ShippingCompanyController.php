<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCompany;

class ShippingCompanyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'api_endpoint' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'default_message' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $shippingCompany = ShippingCompany::create([
            'name' => $request->name,
            'api_endpoint' => $request->api_endpoint,
            'whatsapp_number' => $request->whatsapp_number,
            'default_message' => $request->default_message,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json($shippingCompany, 201);
    }

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