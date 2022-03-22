<?php

use App\Http\Controllers\Customer\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'customerLogin']);
Route::middleware('auth:user')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
