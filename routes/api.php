<?php

use App\Http\Controllers\Api\SensorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('sensores', SensorController::class)->parameters([
    'sensores' => 'sensor'
]);

Route::post('sensores/{sensor}/lectura', [SensorController::class, 'lectura']);