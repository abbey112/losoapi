<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::prefix('products')->group(function () {
    Route::get('/',        [ProductController::class, 'index']);
    Route::get('/search',  [ProductController::class, 'search']);
    Route::get('/{id}',    [ProductController::class, 'show']);
});

Route::middleware('auth:sanctum')->prefix('vendor')->group(function () {
    Route::get('/', [ProductController::class, 'vendorIndex']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);