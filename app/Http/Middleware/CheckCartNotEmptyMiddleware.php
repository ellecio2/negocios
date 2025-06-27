<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;

class CheckCartNotEmptyMiddleware {
    public function handle(Request $request, Closure $next) {
        $carts = Cart::where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            if ($request->wantsJson()) {
                // Si la solicitud viene de un endpoint
                return response()->json(['error' => 'Your cart is empty'], 400);
            } else {
                // Si la solicitud viene de un navegador
                flash(translate('Your cart is empty'))->warning();
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
