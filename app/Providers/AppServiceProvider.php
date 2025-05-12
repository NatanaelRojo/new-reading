<?php

namespace App\Providers;

use App\Models\API\V1\Author;
use App\Models\API\V1\Book;
use App\Policies\API\V1\AuthorPolicy;
use App\Policies\API\V1\BookPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Author::class, AuthorPolicy::class);
        Gate::policy(Book::class, BookPolicy::class);
    }
}
