<?php

use App\Http\Controllers\Api\MpesaCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handleCallback'])
    ->name('mpesa.callback');

Route::post('/mpesa/timeout', [MpesaCallbackController::class, 'handleTimeout'])
    ->name('mpesa.timeout');
