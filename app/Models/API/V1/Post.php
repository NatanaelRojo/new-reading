<?php

namespace App\Models\API\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 * schema="Post",
 * title="Post",
 * description="Post model representing a user's reading update or comment on a book.",
 * @OA\Xml(
 * name="Post"
 * )
 * )
 */
class Post extends Model
{
    use HasSlug;
    use HasFactory;

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="Unique identifier for the post",
     * readOnly=true,
     * example=1
     * )
     * @OA\Property(
     * property="book_id",
     * type="integer",
     * format="int64",
     * description="The ID of the book this post is related to",
     * example=101
     * )
     * @OA\Property(
     * property="user_id",
     * type="integer",
     * format="int64",
     * description="The ID of the user who created the post",
     * example=10
     * )
     * @OA\Property(
     * property="body",
     * type="string",
     * description="The content of the post",
     * example="Just finished chapter 5 of 'Dune' and it's getting intense!"
     * )
     * @OA\Property(
     * property="progress",
     * type="integer",
     * description="The reading progress associated with the post (e.g., page number, percentage)",
     * nullable=true,
     * example=120
     * )
     * @OA\Property(
     * property="slug",
     * type="string",
     * description="Unique URL-friendly slug for the post (generated from body)",
     * maxLength=255,
     * readOnly=true,
     * example="just-finished-chapter-5-of-dune"
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the post record was created",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the post record was last updated",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'body',
        'progress',
        'slug',
    ];

    /**
     * Get the options for generating the slug.
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('body')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * The comments that belong to the post.
     * @return MorphMany<Comment, Post>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->chaperone();
    }

    /**
     * Get the user that owns the post.
     * @return BelongsTo<User, Post>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
