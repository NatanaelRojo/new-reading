<?php

use App\Http\Controllers\API\V1\CommentController;
use App\Http\Controllers\API\V1\Controllers\AuthController;
use App\Http\Controllers\API\V1\Controllers\AuthorController;
use App\Http\Controllers\API\V1\Controllers\BookController;
use App\Http\Controllers\API\V1\Controllers\GenreController;
use App\Http\Controllers\API\V1\Controllers\ReviewController;
use App\Http\Controllers\API\V1\Controllers\UserController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('genres', GenreController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('tags', TagController::class);
Route::apiResource('reviews', ReviewController::class);
Route::middleware('auth:sanctum')
    ->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index');
            Route::get('/users/{user}', 'show');
            Route::put('/users/{user}', 'update');
            Route::delete('/users/{user}', 'destroy');
            Route::post('/users/{user}/follow', 'follow');
        });
    });
