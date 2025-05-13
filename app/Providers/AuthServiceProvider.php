<?php

namespace App\Providers;

use App\Models\API\V1\Author;
use App\Models\API\V1\Book;
use App\Models\API\V1\Genre;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Models\User;
use App\Policies\API\V1\AuthorPolicy;
use App\Policies\API\V1\BookPolicy;
use App\Policies\API\V1\CommentPolicy;
use App\Policies\API\V1\GenrePolicy;
use App\Policies\API\V1\PostPolicy;
use App\Policies\API\V1\ReviewPolicy;
use App\Policies\API\V1\TagPolicy;
use App\Policies\UserPolicy;
use Dom\Comment;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Author::class => AuthorPolicy::class,
        Book::class => BookPolicy::class,
        Comment::class => CommentPolicy::class,
        Genre::class => GenrePolicy::class,
        Post::class => PostPolicy::class,
        Review::class => ReviewPolicy::class,
        Tag::class => TagPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        //
    }
}
