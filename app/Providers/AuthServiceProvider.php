<?php

namespace App\Providers;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Author;
use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Genre;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Models\User;
use App\Policies\AuthorPolicy;
use App\Policies\BookPolicy;
use App\Policies\CommentPolicy;
use App\Policies\GenrePolicy;
use App\Policies\PostPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->hasRole(AppRoles::ADMIN)) {
                return true;
            }

            return null;
        });
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        //
    }
}
