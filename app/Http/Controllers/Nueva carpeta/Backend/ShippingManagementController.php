<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'api_endpoint' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'default_message' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        ShippingCompany::create($request->all());
        
        flash(translate('Shipping Company has been created successfully'))->success();
        return redirect()->route('admin.shipping-companies.index');
    }
    
    public function edit($id)
    {
        $shippingCompany = ShippingCompany::findOrFail($id);
        return view('backend.delivery_boys.company_delivery_edit', compact('shippingCompany'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'api_endpoint' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'default_message' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        $shippingCompany = ShippingCompany::findOrFail($id);
        $shippingCompany->update($request->all());
        
        flash(translate('Shipping Company has been updated successfully'))->success();
        return redirect()->route('admin.shipping-companies.index');
    }
    
    public function destroy($id)
    {
        $shippingCompany = ShippingCompany::findOrFail($id);
        $shippingCompany->delete();
        
        flash(translate('Shipping Company has been deleted successfully'))->success();
        return redirect()->route('admin.shipping-companies.index');
    }
}