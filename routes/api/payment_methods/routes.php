<?php

use App\Http\Controllers\Api\V2\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\WalletController;
use App\Http\Controllers\Api\V2\PaymentTypesController;
use App\Http\Controllers\Api\V2\PagoAzulController;

Route::middleware(['app_user_unbanned', 'auth:sanctum', 'app_language'])->prefix('v2')->group(function (){

    Route::prefix('payments/pay')->middleware('hasItemsInCart')->group(function (){
        Route::post('wallet', [ WalletController::class,  'processPayment' ]);
        Route::post('cod',    [ PaymentController::class, 'cashOnDelivery' ]);
        Route::post('manual', [ PaymentController::class, 'manualPayment'  ]);
        Route::post('wire-transfer', [ PaymentController::class, 'wireTransfer' ]);
    });
    Route::get('payment-types', [ PaymentTypesController::class, 'getList']);
});

Route::get('v2/payments/pay/azul', [PagoAzulController::class, 'pay']);
Route::get('v2/payments/pay/azul/denied', [PagoAzulController::class, 'denied']);
Route::get('v2/payments/pay/azul/aproved', [PagoAzulController::class, 'aproved']);
Route::get('v2/payments/pay/azul/canceled', [PagoAzulController::class, 'canceled']);
