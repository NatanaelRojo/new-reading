<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
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
     * Assign a tag to a book for the current user.
     *
     * @param int $bookId
     * @param int $tagId
     * @return void
     */
    public function assignTagToBook(int $bookId, int $tagId): void
    {
        if (!$this->books()->where('book_id', $bookId)->exists()) {
            $this->books()->attach($bookId, ['tag_id' => $tagId]);
        }

        $this->books()->updateExistingPivot($bookId, ['tag_id' => $tagId]);
    }

    public function getBookCompletionPercentage(int $bookId = null): ?int
    {
        if (is_null($bookId)) {
            return null;
        }

        $userBook = $this->books()->firstWhere('book_id', $bookId);

        if (!$userBook) {
            return null; // The user hasn't started this book
        }

        $totalPages = $userBook->pages_amount;

        if ($totalPages && $userBook->pivot->pages_read) {
            return ($userBook->pivot->pages_read / $totalPages) * 100;
        }

        return 0; // No progress
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)
            ->withPivot('tag_id', 'pages_read')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function follow(User $user): void
    {
        $this->following()->attach($user);
    }

    public function unfollow(User $user): void
    {
        $this->following()->detach($user);
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
