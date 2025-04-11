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

class Comment extends Model
{
    use HasFactory;
    use HasSlug;

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
