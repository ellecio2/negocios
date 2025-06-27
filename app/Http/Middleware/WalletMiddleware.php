<?php

namespace App\Http\Middleware;

use App\Models\BusinessSetting;
use Closure;
use Illuminate\Http\Request;

class WalletMiddleware {
    public function handle(Request $request, Closure $next) {

        if(BusinessSetting::where('type', 'wallet_system')->first()->value == 0){
            if ($request->wantsJson()) {
                // Si la solicitud viene de un endpoint
                return response()->json([
                    'result' => false,
                    'message' => 'Sistema de Billetera Desactivado por Admin'
                ], 403);
            } else {
                // Si la solicitud viene de un navegador
                return abort(404, 'Not Found');
            }
        }


        return $next($request);
    }
}
