<?php

namespace App\Models\API\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Review extends Model
{
    use HasFactory;

    protected array $fillable = [
        'user_id',
        'book_id',
        'comment',
        'rating',
        'like_count',
        'dislike_count',
    ];

    /**
     * Check if the current user has liked the review.
     * @param \App\Models\User $user
     * @return bool
     */
    public function likedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)
            ->where('is_dislike', false)
            ->exists();
    }

    /**
     * Check if the current user has disliked the review.
     * @param \App\Models\User $user
     * @return bool
     */
    public function dislikeBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)
            ->where('is_dislike', true)
            ->exists();
    }

    /**
     * Get the book that owns the review.
     * @return BelongsTo<Book, Review>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * The likes that belong to the review.
     * @return MorphMany<Like, Review>
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')
            ->chaperone();
    }

    /**
     * The comments that belong to the review.
     * @return MorphMany<Comment, Review>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->chaperone();
    }

    /**
     * Get the user that owns the review.
     * @return BelongsTo<User, Review>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
