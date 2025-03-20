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

Route::apiResources([
    'authors' => AuthorController::class,
    'books' => BookController::class,
    'comments' => CommentController::class,
    'genres' => GenreController::class,
    'posts' => PostController::class,
    'users' => UserController::class,
    'tags' => TagController::class,
    'reviews' => ReviewController::class
]);

Route::controller(BookController::class)->group(function () {
    Route::get('filter/books', 'filter')
        ->name('books.filter');
});

Route::prefix('books/{book}')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'storeByBook']);
});
Route::prefix('posts/{post}')->group(function () {
    Route::get('comments', [CommentController::class, 'indexByPost']);
    Route::post('comments', [CommentController::class, 'storeByPost']);
});
Route::prefix('reviews/{review}')->group(function () {
    Route::get('comments', [CommentController::class, 'indexByReview']);
    Route::post('comments', [CommentController::class, 'storeByReview']);
});
Route::prefix('users/{user}')->group(function () {
    Route::get('/posts', [PostController::class, 'indexByUser']);
    Route::get('/reviews', [ReviewController::class, 'indexByUser']);
    Route::post('/posts', [PostController::class, 'storeByUser']);
});
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
