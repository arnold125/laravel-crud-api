<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\tareaController;

Route::get('/tasks', [tareaController::class, 'index']);

Route::get('/tasks/{id}', [tareaController::class, 'show']);

Route::post('/tasks', [tareaController::class, 'store']);

Route::put('/tasks/{id}', [tareaController::class, 'update']);

Route::delete('/tasks/{id}', [tareaController::class, 'destroy']);