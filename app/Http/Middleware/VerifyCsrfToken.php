<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
     protected $except = [
         '/sslcommerz*',
         '/config_content',
         '/paytm*',
         '/payhere*',
         '/stripe*',
         '/iyzico*',
         '/payfast*',
         '/bkash*',
         'api/v2/bkash*',
         '/aamarpay*',
         '/mock_payments',
         '/apple-callback',
         '/lnmo*',
         '/rozer*',
         'whatsapp/webhook',
         '/envia',
         '/pedidosYa/webhook',
         'webhook/pedidosya',
         'api/v2/workshops/request-service',
         '/api/v2/seller/*',
         'phone/check',
         'email/check',
         'api*'
     ];
}
