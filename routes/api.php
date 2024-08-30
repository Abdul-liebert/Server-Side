<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GameScoreController;
use App\Http\Controllers\PortfolioController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/posts', function () {
    return 'API';
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::patch('update/{$id}', [AuthController::class, 'update']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected Routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {

    // User Routes
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::put('user/update', [UserController::class, 'update']);


    // Blog Routes
    Route::apiResource('blogs', BlogController::class);

    // Comment Routes
    Route::apiResource('comments', CommentController::class);

    // Banner Routes
    Route::apiResource('banners', BannerController::class);

    // Portfolio Routes
    Route::apiResource('portfolios', PortfolioController::class);

    // Game Routes
    Route::get('game/leaderboard', [GameScoreController::class, 'leaderboard']);
    Route::post('game/score', [GameScoreController::class, 'store']);
});

// Public or Unauthenticated Routes
Route::get('public/banners', [BannerController::class, 'index']);
Route::get('public/portfolios', [PortfolioController::class, 'index']);

// Error Route for Unauthorized Access
Route::fallback(function () {
    return response()->json(['message' => 'Resource not found.'], 404);
});