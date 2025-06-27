<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TemporaryPasswordMiddleware {
    public function handle(Request $request, Closure $next) {
        if ($request->user()->referred_by !== null) {
            return redirect()->route('replace.password');
        }

        return $next($request);
    }
}
