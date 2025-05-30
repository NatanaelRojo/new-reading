<?php

namespace App\Models\API\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 * title="Comment",
 * description="Comment model representing user feedback on commentable resources like books or posts.",
 * @OA\Xml(
 * name="Comment"
 * )
 * )
 */
class Comment extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="Unique identifier for the comment",
     * readOnly=true,
     * example=1
     * )
     * @OA\Property(
     * property="user_id",
     * type="integer",
     * format="int64",
     * description="The ID of the user who made the comment",
     * example=10
     * )
     * @OA\Property(
     * property="commentable_id",
     * type="integer",
     * format="int64",
     * description="The ID of the resource (e.g., book_id, post_id) the comment belongs to",
     * example=101
     * )
     * @OA\Property(
     * property="commentable_type",
     * type="string",
     * description="The type of the resource the comment belongs to (e.g., 'App\\Models\\API\\V1\\Book', 'App\\Models\\API\\V1\\Post')",
     * example="App\\Models\\API\\V1\\Book"
     * )
     * @OA\Property(
     * property="body",
     * type="string",
     * description="The content of the comment",
     * example="This is a great book! I really enjoyed it."
     * )
     * @OA\Property(
     * property="slug",
     * type="string",
     * description="Unique URL-friendly slug for the comment (generated from body)",
     * maxLength=255,
     * readOnly=true,
     * example="this-is-a-great-book"
     * )
     * @OA\Property(
     * property="related_to",
     * type="string",
     * description="The type of the resource the comment is related to (e.g., 'book', 'post')",
     * readOnly=true,
     * example="book"
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the comment record was created",
     * readOnly=true,
     * example="2023-01-01T12:00:00Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the comment record was last updated",
     * readOnly=true,
     * example="2023-01-01T12:15:00Z"
     * )
     */
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'body',
        'slug',
    ];

    /**
     * An accessor for the related model name.
     * @return Attribute
     */
    public function relatedTo(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->getRelatedModelName(),
        );
    }

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
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the commentable model that owns the comment.
     * @return MorphTo<Model, Comment>
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the comment.
     * @return BelongsTo<User, Comment>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model name.
     * @return string
     */
    private function getRelatedModelName(): string
    {
        return match ($this->commentable_type) {
            Book::class => 'book',
            Post::class => 'post',
            default => '',
        };
    }
}
