<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Sports API validation callback - Removed, now direct to sports provider 
Route::post('/gateway/create-payment', [\App\Http\Controllers\GatewayApiController::class, 'createPayment']);
Route::post('/gateway/process-cc', [\App\Http\Controllers\GatewayApiController::class, 'processCreditCard']);
Route::post('/gateway/completed-payments', [\App\Http\Controllers\GatewayApiController::class, 'completedPayments']);