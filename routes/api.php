<?php

use App\Http\Controllers\Api\AuthController;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
