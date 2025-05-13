<?php

namespace App\Providers;

use App\Models\API\V1\Author;
use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Genre;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Policies\API\V1\AuthorPolicy;
use App\Policies\API\V1\BookPolicy;
use App\Policies\API\V1\CommentPolicy;
use App\Policies\API\V1\GenrePolicy;
use App\Policies\API\V1\PostPolicy;
use App\Policies\API\V1\ReviewPolicy;
use App\Policies\API\V1\TagPolicy;
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
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Genre::class, GenrePolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
    }
}
