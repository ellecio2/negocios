<?php

namespace App\Http\Controllers\Api\Delivery\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\V1\DeliveryOptionResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class DeliveryController extends Controller {
    public function checkDeliveryAvailability(Request $request){
        $carts = Cart::where('user_id', $request->user()->id)->get();

        if(!$carts) {
            return response()->json(['message' => "Your cart is empty"], 404);
        }

        // Aquí utilizamos el ApiResource en lugar del método each() y push()
        return DeliveryOptionResource::collection($carts);
    }
}
