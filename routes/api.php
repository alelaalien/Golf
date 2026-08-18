<?php

use App\Http\Api\Controllers\ClubController as ControllersClubController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\ReservationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('clubs', ClubController::class);
Route::apiResource('players', PlayerController::class);
Route::apiResource('reservations', ReservationController::class);
