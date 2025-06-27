<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PhoneVerifiedMiddleware {
    public function handle(Request $request, Closure $next) {

        if(auth()->user()->phone_verified_at == null) {
            return redirect()->route('shop.view.phone.verification');
        }

        return $next($request);
    }
}
