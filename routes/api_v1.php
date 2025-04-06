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
    return $request->user()->books;
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')
    ->group(function () {
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
        Route::prefix('books/{book}')->group(function () {
            Route::post('comments', [CommentController::class, 'storeByBook'])
                ->name('books.comments.store');
            Route::get('posts', [PostController::class, 'indexByBook'])
            ->name('books.posts.index');
            Route::post('posts', [PostController::class, 'storeByBook'])
            ->name('books.posts.store');
            Route::put('tags', [BookController::class, 'updateTag'])
                ->name('books.tags.update');
            Route::put('reading-progress', [BookController::class, 'updateReadingProgress'])
            ->name('books.reading-progress.update');
            Route::get('reviews', [ReviewController::class, 'indexByBook'])
                ->name('books.reviews.index');
            Route::post('reviews', [ReviewController::class, 'storeByBook'])
                ->name('books.reviews.store');
        });
        Route::prefix('posts/{post}')->group(function () {
            Route::get('comments', [CommentController::class, 'indexByPost'])
                ->name('posts.comments.index');
            Route::post('comments', [CommentController::class, 'storeByPost'])
                ->name('posts.comments.store');
        });
        Route::prefix('reviews/{review}')->group(function () {
            Route::get('comments', [CommentController::class, 'indexByReview'])
                ->name('reviews.comments.index');
            Route::post('comments', [CommentController::class, 'storeByReview'])
            ->name('reviews.comments.store');
        });
        Route::prefix('users/{user}')->group(function () {
            Route::get('reviews', [ReviewController::class, 'indexByUser'])
            ->name('users.reviews.index');
            Route::get('posts', [PostController::class, 'indexByUser'])
                ->name('users.posts.index');
            Route::post('posts', [PostController::class, 'storeByUser'])
                ->name('users.posts.store');
        });
        Route::post('/logout', [AuthController::class, 'logout']);
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('users/{user}/follow', 'follow')
                ->name('users.follow');
            Route::delete('users/{user}/follow', 'unfollow')
                ->name('users.unfollow');
        });
    });
