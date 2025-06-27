<?php

use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ShopViewsController;

Route::middleware('guest')->group(function (){

    Route::controller(LoginController::class)->group(function () {
        Route::get('social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
        Route::get('social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');

        //Apple Callback
        Route::post('apple-callback', 'handleAppleCallback');
        Route::get('account-deletion', 'account_deletion')->name('account_delete');
    });

    Route::view('iniciar-sesion', 'frontend.user_login')->name('user.login');
    Route::view('vendedores/iniciar-sesion', 'frontend.seller_login')->name('seller.login');
    Route::view('repartidores/iniciar-sesion', 'frontend.deliveryboy_login')->name('deliveryboy.login');

    Route::controller(HomeController::class)->group(function () {
        Route::get('/users/registration', 'registration')->name('user.registration');
        Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit');
    });

    Route::prefix('registro')->group(function () {
        Route::prefix('comprador')->name('register.buyer.')->group( function (){
            Route::view('', 'frontend.registro-comercio.views.buyers.index')->name('index');
            Route::post('', [RegisterController::class, 'registerCustomer'])->name('store');
        });

        Route::prefix('vendedor')->name('register.business.')->group( function (){
            Route::get('', [RegisterController::class, 'sellerView'])->name('index');
            Route::post('', [RegisterController::class, 'registerSeller'])->name('store');
        });

        Route::prefix('taller')->name('register.workshop.')->group( function (){
            Route::get('', [RegisterController::class, 'workshopView'])->name('index');
            Route::post('', [RegisterController::class, 'registerWorkshop'])->name('store');
        });
         Route::view('buyer', 'auth.register-buyer')->name('register.buyer.form');
    });

    Route::prefix('registro')->group(function () {
        Route::view('/', 'auth.register')->middleware('guest')->name('registro');
        //selecionar tipo de cuenta al registrar
        Route::get('tipo-de-cuenta', [ShopViewsController::class, 'account_type'])->name('shop.view.account.type');

    });
});

Route::middleware(['auth'])->group(function (){
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('registro')->group(function () {
        //vista de registro exitoso
        Route::get('registro-completo', [ShopViewsController::class, 'signup_complete'])->name('shop.view.signup.complete');
        //Vista para cambiar el password
        Route::view('generar-nueva-clave-de-acceso', 'frontend.registro-comercio.views.replace-generated-password')->name('replace.password');
        Route::post('guardar-nueva-clave-de-acceso', [AuthController::class, 'passwordReset'])->name('generate.own.password');
        //vista donde muestra mensaje de verificar cuenta
        Route::get('verificacion-de-correo-electronico', [ShopViewsController::class, 'verification_email'])->name('shop.view.email.verification');
        //vista para verificar numero de telefono
        Route::view('verificacion-de-numero-telefonico', 'frontend.registro-comercio.views.phone_verification')->name('shop.view.phone.verification');
    });

    Route::post('phone/verification', [PhoneController::class, 'verifiedPhone'])->name('phone.verified');
});

Route::get('account/verification/successful/{token}', [AuthController::class, 'accountSuccessfulVerified'])->name('email.successful.verified');




