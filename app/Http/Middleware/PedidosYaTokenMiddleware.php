<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PedidosYaTokenMiddleware
{
    public function handle(Request $request, Closure $next) {
        $token = config('app.pedidosya_webhook_token');
        $requestToken = trim($request->header('x-api-key'), '{}');;

        if($token !== null && strtolower($token) === strtolower($requestToken))
            return $next($request);
        else
            return response('Unauthorized', 403); // consider changing the message
    }
}
