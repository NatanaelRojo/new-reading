<?php

namespace App\Models\API\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug; // Not used in this model, but keeping consistent with original
use Spatie\Sluggable\SlugOptions; // Not used in this model, but keeping consistent with original

/**
 * @OA\Schema(
 * schema="Review",
 * title="Review",
 * description="Review model representing a user's review of a book.",
 * @OA\Xml(
 * name="Review"
 * )
 * )
 */
class Review extends Model
{
    use HasFactory;
    // Removed HasSlug as it's not used in this model based on your provided code

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="Unique identifier for the review",
     * readOnly=true,
     * example=1
     * )
     * @OA\Property(
     * property="user_id",
     * type="integer",
     * format="int64",
     * description="The ID of the user who wrote the review",
     * example=10
     * )
     * @OA\Property(
     * property="book_id",
     * type="integer",
     * format="int64",
     * description="The ID of the book this review is for",
     * example=101
     * )
     * @OA\Property(
     * property="comment",
     * type="string",
     * description="The text content of the review",
     * example="An absolutely captivating read from start to finish!"
     * )
     * @OA\Property(
     * property="rating",
     * type="integer",
     * description="The rating given to the book (e.g., out of 5)",
     * minimum=1,
     * maximum=5,
     * example=5
     * )
     * @OA\Property(
     * property="like_count",
     * type="integer",
     * description="The number of likes the review has received",
     * readOnly=true,
     * example=25
     * )
     * @OA\Property(
     * property="dislike_count",
     * type="integer",
     * description="The number of dislikes the review has received",
     * readOnly=true,
     * example=3
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the review record was created",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the review record was last updated",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     */
    protected $fillable = [
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
     * Update the like counters.
     * @return void
     */
    public function updateLikeCounters(): void
    {
        $this->like_count = $this->likes()->where('is_dislike', false)->count();
        $this->dislike_count = $this->likes()->where('is_dislike', true)->count();
        $this->save();
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
