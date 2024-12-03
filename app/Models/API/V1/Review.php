<?php

namespace App\Models\API\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Review extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'user_id',
        'book_id',
        'comment',
        'rating',
        // 'slug',
    ];

    /**
     * Get the options for generating the slug.
     */
    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //     ->generateSlugsFrom('body')
    //     ->saveSlugsTo('slug');
    // }

    /**
     * Get the route key for the model.
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
