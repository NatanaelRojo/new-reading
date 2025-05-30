<?php

namespace App\Models\API\V1;

use App\ModelFilters\API\V1\TagFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 * schema="Tag",
 * title="Tag",
 * description="Tag model representing keywords or categories for resources.",
 * @OA\Xml(
 * name="Tag"
 * )
 * )
 */
class Tag extends Model
{
    use HasFactory;
    use Filterable;
    use HasSlug;

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="Unique identifier for the tag",
     * readOnly=true,
     * example=1
     * )
     * @OA\Property(
     * property="name",
     * type="string",
     * description="The name of the tag",
     * maxLength=255,
     * example="Programming"
     * )
     * @OA\Property(
     * property="slug",
     * type="string",
     * description="Unique URL-friendly slug for the tag (generated from name)",
     * maxLength=255,
     * readOnly=true,
     * example="programming"
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the tag record was created",
     * readOnly=true,
     * example="2024-05-30T10:00:00.000000Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the tag record was last updated",
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
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
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
     * Get the filter class name for the model.
     * @return string
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(TagFilter::class);
    }
}
