<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;

class ITBIS extends Controller {
    public function index() {
        return response()->json([
            'result' => true,
            'message' => 'success',
            'data' => config('app.itbis')
        ]);
    }
}
