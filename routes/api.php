<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
  Route::post('/reset-password', [AuthController::class, 'resetPassword']);

  Route::middleware('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
  });
});

Route::prefix('categories')->middleware('auth')->group(function () {
  Route::get('/', [CategoryController::class, 'index']);
  Route::post('/', [CategoryController::class, 'store']);
  Route::put('/{category}', [CategoryController::class, 'update']);
  Route::delete('/{category}', [CategoryController::class, 'destroy']);
});

Route::prefix('transactions')->middleware('auth')->group(function () {
  Route::get('/', [TransactionController::class, 'index']);
  Route::post('/', [TransactionController::class, 'store']);
  Route::get('/{transaction}', [TransactionController::class, 'show']);
  Route::put('/{transaction}', [TransactionController::class, 'update']);
  Route::delete('/{transaction}', [TransactionController::class, 'destroy']);
});

Route::prefix('dashboard')->middleware('auth')->group(function () {
  Route::get('/balance', [TransactionController::class, 'dashboard']);
});
