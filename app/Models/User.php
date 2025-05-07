<?php

namespace App\Models;

use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Like;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_image_url',
        'first_name',
        'last_name',
        'birth_date',
        'description',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the full name of the user.
     * @return Attribute
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    /**
     * Follow a user.
     * @param \App\Models\User $user
     * @return void
     */
    public function follow(User $userToFollow): void
    {
        if (!$this->isFollowing($userToFollow)) {
            $this->following()->attach($userToFollow);
        }
    }

    /**
     * Unfollow a user.
     * @param \App\Models\User $user
     * @return void
     */
    public function unfollow(User $user): void
    {
        if ($this->isFollowing($user)) {
            $this->following()->detach($user);
        }
    }

    /**
     * Check if the user is following the current user.
     * @param \App\Models\User $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    /**
     * Check if the user is followed by the current user.
     * @param \App\Models\User $user
     * @return bool
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    /**
     * Get the books which owns this user.
     * @return BelongsToMany<Book, User>
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)
            ->withPivot('tag_id', 'pages_read')
            ->withTimestamps();
    }

    /**
     * Get the comments that belong to the user.
     * @return HasMany<Comment, User>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes that belong to the user.
     * @return HasMany<Like, User>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the followers of the user.
     * @return BelongsToMany<User, User>
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    /**
     * Get the users that the user is following.
     * @return BelongsToMany<User, User>
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    /**
     * Get the posts that belong to the user.
     * @return HasMany<Post, User>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the reviews that belong to the user.
     * @return HasMany<Review, User>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
