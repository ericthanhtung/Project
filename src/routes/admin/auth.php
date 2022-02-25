<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
