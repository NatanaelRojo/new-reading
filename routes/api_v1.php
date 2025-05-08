<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\{
    AuthController,
    AuthorController,
    BookController,
    CommentController,
    GenreController,
    PostController,
    ReviewController,
    TagController,
    UserController
};

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn ($request) => $request->user()->books);

    // RESTful resources
    Route::apiResources([
        'authors' => AuthorController::class,
        'books' => BookController::class,
        'comments' => CommentController::class,
        'genres' => GenreController::class,
        'posts' => PostController::class,
        'users' => UserController::class,
        'tags' => TagController::class,
        'reviews' => ReviewController::class,
    ]);

    // Book nested routes
    Route::prefix('books/{book}')->name('books.')->group(function () {
        Route::post('comments', [CommentController::class, 'storeByBook'])->name('comments.store');
        Route::get('posts', [PostController::class, 'indexByBook'])->name('posts.index');
        Route::post('posts', [PostController::class, 'storeByBook'])->name('posts.store');
        Route::put('tags', [BookController::class, 'updateTag'])->name('tags.update');
        Route::put('reading-progress', [BookController::class, 'updateReadingProgress'])->name('reading-progress.update');
        Route::get('reviews', [ReviewController::class, 'indexByBook'])->name('reviews.index');
        Route::post('reviews', [ReviewController::class, 'storeByBook'])->name('reviews.store');
    });

    // Post nested routes
    Route::prefix('posts/{post}')->name('posts.')->group(function () {
        Route::get('comments', [CommentController::class, 'indexByPost'])->name('comments.index');
        Route::post('comments', [CommentController::class, 'storeByPost'])->name('comments.store');
    });

    // Review nested routes
    Route::prefix('reviews/{review}')->name('reviews.')->group(function () {
        Route::get('comments', [CommentController::class, 'indexByReview'])->name('comments.index');
        Route::post('comments', [CommentController::class, 'storeByReview'])->name('comments.store');
        Route::post('like', [ReviewController::class, 'like'])->name('like');
        Route::post('dislike', [ReviewController::class, 'dislike'])->name('dislike');
    });

    // User nested routes
    Route::prefix('users/{user}')->name('users.')->group(function () {
        Route::get('reviews', [ReviewController::class, 'indexByUser'])->name('reviews.index');
        Route::get('posts', [PostController::class, 'indexByUser'])->name('posts.index');
        Route::post('posts', [PostController::class, 'storeByUser'])->name('posts.store');
        Route::post('follow', [UserController::class, 'follow'])->name('follow');
        Route::delete('follow', [UserController::class, 'unfollow'])->name('unfollow');
    });

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Auth (no middleware)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
