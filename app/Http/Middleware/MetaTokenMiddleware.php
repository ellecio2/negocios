<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaTokenMiddleware {
    public function handle(Request $request, Closure $next) {
        $token = config('app.whatsapp_webhook_token');
        $requestToken = $request->hub_verify_token;

        if($token === $requestToken)
            return $next($request);
        else
            return response('INVALID_TOKEN', 403);
    }
}
