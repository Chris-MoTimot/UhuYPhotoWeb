<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PinController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('pins', [PinController::class, 'index']);
Route::get('pins/{pin}', [PinController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    Route::apiResource('pins', PinController::class)->except(['index', 'show']);
    Route::post('pins/{pin}/like', [PinController::class, 'like']);
    Route::post('pins/{pin}/comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    
    Route::apiResource('boards', BoardController::class);
    
    Route::get('users/{user}', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    Route::post('users/{user}/follow', [UserController::class, 'follow']);
});
