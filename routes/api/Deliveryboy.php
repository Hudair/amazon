<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Deliveryboy\AuthController;
use App\Http\Controllers\Api\Deliveryboy\AccountController;
use App\Http\Controllers\Api\Deliveryboy\OrderController;

//Delivery boy
Route::prefix('deliveryboy')->namespace('Deliveryboy')->group(function () {

  //Delivery boy authentication
  Route::post('login', [AuthController::class, 'login']);
  Route::post('forgot', [AuthController::class, 'forgot']);
  Route::get('reset', [AuthController::class, 'token']);
  Route::post('reset', [AuthController::class, 'reset']);

  Route::group(['middleware' => ['auth:delivery_boy-api']], function () {
    // Profile
    Route::get('profile', [AccountController::class, 'profile']);
    Route::post('profile', [AccountController::class, 'updateProfile']);
    Route::get('vendor', [AccountController::class, 'vendor']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('password/update', [AuthController::class, 'updatePassword']);

    // Orders
    Route::get('orders', [
      OrderController::class, 'index'
    ]);

    Route::get('orders/{order}', [
      OrderController::class, 'show'
    ]);

    Route::post('orders/status/{order}', [
      OrderController::class, 'updateOrderStatus'
    ]);

    Route::post('orders/{order}/markasdelivered', [
      OrderController::class, 'markAsDelivered'
    ]);

    Route::post('orders/{order}/markaspaid', [
      OrderController::class, 'markAsPaid'
    ]);
  });
});
