<?php

namespace App\Models\API\V1;

use App\ModelFilters\API\V1\GenreFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 * title="Genre",
 * description="Genre model representing different categories for books.",
 * @OA\Xml(
 * name="Genre"
 * )
 * )
 */
class Genre extends Model
{
    use HasFactory;
    use HasSlug;
    use Filterable;

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="Unique identifier for the genre",
     * readOnly=true,
     * example=1
     * )
     * @OA\Property(
     * property="name",
     * type="string",
     * description="The name of the genre",
     * maxLength=255,
     * example="Fantasy"
     * )
     * @OA\Property(
     * property="slug",
     * type="string",
     * description="Unique URL-friendly slug for the genre (generated from name)",
     * maxLength=255,
     * readOnly=true,
     * example="fantasy"
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the genre record was created",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the genre record was last updated",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
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

    /**
     * Get the filter class name for the model.
     * @return string
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(GenreFilter::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}
