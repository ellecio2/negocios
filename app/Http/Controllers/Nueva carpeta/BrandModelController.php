<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandDetail;
use Illuminate\Http\Request;

class BrandDetailController extends Controller
{
    public function getBrandsByBrands($brandId)
    {
        dd($brandId);
        $brands = BrandDetail::where('brand_id', $brandId)->get();
        return response()->json($brands);
    }
}
