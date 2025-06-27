<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaController;




Route::get('/envia', [WaController::class,'envia']);



