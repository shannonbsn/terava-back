<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\MatcheController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AuthController;

Route::apiResource('users', App\Http\Controllers\Api\UserController::class);
Route::get('/users/{id}/profil', [UserController::class, 'getUserProfile']);
Route::put('/users/{id}/profil', [UserController::class, 'updateUserProfile']);
// Route::get('/api/users/{id}/profile', [UserController::class, 'getUserProfile']);
// Route::get('/users', [UserController::class, 'index']);
// Route::get('/users/{id}', [UserController::class, 'show']);
// Route::post('/users/add', [UserController::class, 'store']);
// Route::put('/users/{id}', [UserController::class, 'update']);
// Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Route::get('/profiles', [ProfileController::class, 'index']);
// Route::get('/profiles/{id}', [ProfileController::class, 'show']);
// Route::post('/profiles', [ProfileController::class, 'store']);
// Route::put('/profiles/{id}', [ProfileController::class, 'update']);
// Route::delete('/profiles/{id}', [ProfileController::class, 'destroy']);

Route::apiResource('locations', LocationController::class);
Route::apiResource('trips', TripController::class);
Route::apiResource('matches', MatcheController::class);
Route::apiResource('profiles', ProfileController::class);
Route::apiResource('messages', MessageController::class);

Route::post('/register', [AuthController::class, 'register']);
