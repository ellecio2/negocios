<?php

// use App\Http\Controllers\Api\Delivery\V1\DeliveryController;
// use App\Http\Controllers\Api\TransporteBlanco\V1\PricingController;
// use App\Http\Controllers\Delivery\PedidosYaController;
// use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Api\V2\Delivery\PedidosYaController as PedidosYaV2Controller;
// use App\Http\Controllers\Api\V2\Delivery\DeliveryController as DeliveryV2Controller;
// use App\Http\Controllers\Backend\ShippingManagementController;

// Route::prefix('v1')->middleware('app_language')->group(function () {
//     Route::prefix('transporte-blanco')->group(function () {
//         Route::post('pricing', [PricingController::class, 'pricing']);
//     });
//     Route::prefix('delivery')->middleware(['auth:sanctum'])->group(function () {
//         Route::get('check', [DeliveryController::class, 'checkDeliveryAvailability']);
//     });
//     Route::prefix('pedidosya')->group(function () {
//         Route::get('check-availability', [PedidosYaController::class, 'checkAvailability'])->middleware('hasItemsInCart');
//         Route::post('pricing', [PedidosYaController::class, 'makeFromAPI']);
//     });
// });

// Route::prefix('v2')->middleware(['auth:sanctum', 'hasItemsInCart'])->group(function () {
//     Route::prefix('transporte-blanco')->group(function () {});

//     Route::prefix('delivery')->group(function () {
//         Route::get('pricing', [DeliveryV2Controller::class, 'index']);
//     });

//     Route::prefix('pedidosya')->group(function () {
//         Route::get('check-availability', [PedidosYaV2Controller::class, 'checkAvailability']);
//     });
// });


use App\Http\Controllers\Api\Delivery\V1\DeliveryController;
use App\Http\Controllers\Api\TransporteBlanco\V1\PricingController;
use App\Http\Controllers\Delivery\PedidosYaController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V2\Delivery\PedidosYaController as PedidosYaV2Controller;
use App\Http\Controllers\Api\V2\Delivery\DeliveryController as DeliveryV2Controller;
use App\Http\Controllers\Api\V2\Delivery\EnvioDobleController;
use App\Http\Controllers\Api\V2\Delivery\EnvioDoblePedidosYaController;
use App\Http\Controllers\Backend\ShippingManagementController;
use Illuminate\Http\Request;



Route::prefix('v1')->middleware('app_language')->group(function () {
    Route::prefix('transporte-blanco')->group(function () {
        Route::post('pricing', [PricingController::class, 'pricing']);
    });
    Route::prefix('delivery')->middleware(['auth:sanctum'])->group(function () {
        Route::get('check', [DeliveryController::class, 'checkDeliveryAvailability']);
    });
    Route::prefix('pedidosya')->group(function () {
        Route::get('check-availability', [PedidosYaController::class, 'checkAvailability'])->middleware('hasItemsInCart');
        Route::post('pricing', [PedidosYaController::class, 'makeFromAPI']);
    });
});


Route::prefix('v2')->middleware(['auth:sanctum', 'hasItemsInCart'])->group(function () {
    Route::prefix('transporte-blanco')->group(function () {});

    Route::prefix('delivery')->group(function () {
        Route::get('pricing', [DeliveryV2Controller::class, 'index']);
    });
});

//rutas para el envio doble
Route::prefix('v2')->middleware(['auth:sanctum', 'hasItemsInCart'])->group(function () {
    Route::prefix('enviodoble')->group(function () {
        Route::get('pricingenvio', [EnvioDobleController::class, 'index']);
        // Route::post('pedidosya-confirm', function (Request $request) {
        //     return response()->json([
        //         'success' => true,
        //         'response' => PedidosYaController::confirmShipping(
        //             $request->estimateId,
        //             $request->deliveryOfferId
        //         )
        //     ]);
        // });
    });
});

// Route::post('api-pedidosya-confirm', function (Request $request) {
//     return response()->json([
//         'success' => true,
//         'response' => EnvioDoblePedidosYaController::confirmShipping(
//             $request->estimateId,
//             $request->deliveryOfferId
//         )
//     ]);
// });
Route::post('api-pedidosya-confirm', function (Request $request) {
    // 1. Confirmar en PedidosYa
    $pyResponse = EnvioDoblePedidosYaController::confirmShipping(
        $request->estimateId,
        $request->deliveryOfferId
    );

    // Si la confirmación de PedidosYa falla, retorna error
    if (!isset($pyResponse['status']) || $pyResponse['status'] !== 'CONFIRMED') {
        return response()->json([
            'success' => false,
            'message' => 'No se pudo confirmar el envío en PedidosYa',
            'pedidosya_response' => $pyResponse
        ], 400);
    }

    // 2. Hacer el envío a la transportadora
    // Debes recibir también company_id y cart_id en el request
    $companyId = $request->input('company_id');
    $cartId = $request->input('cart_id');

    if (!$companyId || !$cartId) {
        return response()->json([
            'success' => false,
            'message' => 'Faltan parámetros para el envío a la transportadora (company_id, cart_id)'
        ], 400);
    }

    // Crear un request interno para ShippingManagementController
    $shippingRequest = new Request([
        'company_id' => $companyId,
        'cart_id' => $cartId
    ]);
    $shippingController = app(ShippingManagementController::class);
    $transportadoraResponse = $shippingController->sendShippingRequest($shippingRequest);

    // 3. Retornar ambas respuestas
    return response()->json([
        'success' => true,
        'pedidosya_response' => $pyResponse,
        'transportadora_response' => $transportadoraResponse->getData(true)
    ]);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('postman-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
});

// ruta para obtener el precio de pedidos ya
Route::prefix('v2')->middleware(['auth:sanctum', 'hasItemsInCart'])->group(function () {
    Route::prefix('pedidos-ya')->group(function () {
        Route::get('pricingenvio', [EnvioDobleController::class, 'pricingEnvioPedidosYa']);
        // Nueva ruta para solo PedidosYa
        Route::post('pedidosya-confirm', function (Request $request) {
            return response()->json([
                'success' => true,
                'response' => EnvioDoblePedidosYaController::confirmShipping(
                    $request->estimateId,
                    $request->deliveryOfferId
                )
            ]);
        });
    });
});



