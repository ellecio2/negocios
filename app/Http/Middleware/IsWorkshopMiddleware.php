<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsWorkshopMiddleware {
    public function handle(Request $request, Closure $next) {

        $user = $request->user();

        if( $user->add_user_type == 'workshop' && $user->user_type == 'seller'){
            return $next($request);
        }else{
            return back()->with('error', 'No tienes permisos para realizar esta accions');
        }

    }
}
