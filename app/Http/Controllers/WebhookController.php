<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Log;


class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
       /* $data = $this->configureWebhook();*/

        Log::info('Webhook received: ' . json_encode($request->all(), true));

        return response()->json(['status' => 'success']);
    }

    public function configureWebhook()
    {
        $client = new Client();

        $requestBody = [
            'webhooksConfiguration' => [
                [
                    'isTest' => false,
                    'topic' => 'SHIPPING_STATUS',
                    'notificationType' => 'WEBHOOK',
                    'urls' => [
                        [
                            'url' => 'https://lapieza.do/pedidosYa/webhook',
                            'authorizationKey' => 'LaPieza.DO',
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $client->get('https://courier-api.pedidosya.com/v3/webhooks-configuration', [
                'json' => $requestBody,
                'headers' => [
                    'Authorization' => 'Bearer 7602-241217-670aed07-d984-4e65-6c69-55109c7e65c3'
                ]
            ]);

            $response = json_decode($response->getBody(), true);
            dd($response, ' pedidos ya app');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Manejar errores
            return ['error' => $e->getMessage()];
        }
    }

}
