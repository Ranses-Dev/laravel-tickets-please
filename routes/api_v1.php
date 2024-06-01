<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\TicketController;

use App\Models\Ticket;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsContoller;
use App\Http\Controllers\Api\V1\UserController;
use App\V1\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('tickets', TicketController::class)->except('update');
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);
    Route::patch('tickets/{ticket}', [TicketController::class, 'update']);
    Route::apiResource('users', UserController::class)->except('update');
    Route::put('users/{ticket}', [UserController::class, 'replace']);
    Route::patch('users/{ticket}', [UserController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('authors', AuthorController::class)->except(['store', 'update', 'delete']);
    Route::apiResource('authors.tickets', AuthorTicketsContoller::class)->except('update');
    Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketsContoller::class, 'replace']);
    Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketsContoller::class, 'update']);
});
