<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();

        return response([
            'status' => 200,
            'message' => 'user information retrieved',
            'data' => $user
        ], 200);
    });
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class);

    Route::apiResource('orders', OrderController::class);
    Route::get('/orders/{order}', [OrderController::class, 'checkout']);
});