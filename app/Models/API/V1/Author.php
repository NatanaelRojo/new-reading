<?php

namespace App\Models\API\V1;

use App\Models\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 * title="Author",
 * description="Author model representing a creative writer or artist.",
 * @OA\Xml(
 * name="Author"
 * )
 * )
 */
class Author extends Model
{
    use HasFactory;
    use HasSlug;
    use Filterable;

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="Unique identifier for the author",
     * readOnly=true,
     * example=1
     * )
     * @OA\Property(
     * property="first_name",
     * type="string",
     * description="The first name of the author",
     * maxLength=255,
     * example="J.K."
     * )
     * @OA\Property(
     * property="last_name",
     * type="string",
     * description="The last name of the author",
     * maxLength=255,
     * example="Rowling"
     * )
     * @OA\Property(
     * property="full_name",
     * type="string",
     * description="The concatenated full name of the author (first_name + last_name)",
     * readOnly=true,
     * example="J.K. Rowling"
     * )
     * @OA\Property(
     * property="nationality",
     * type="string",
     * description="The nationality of the author",
     * maxLength=255,
     * example="British"
     * )
     * @OA\Property(
     * property="biography",
     * type="string",
     * description="A brief biographical summary of the author",
     * example="Joanne Rowling, most famously known as J.K. Rowling, is a British author, screenwriter, and producer best known for writing the Harry Potter fantasy series."
     * )
     * @OA\Property(
     * property="image_url",
     * type="string",
     * format="url",
     * description="URL to the author's profile image or photograph",
     * example="https://example.com/images/jk_rowling.jpg"
     * )
     * @OA\Property(
     * property="slug",
     * type="string",
     * description="Unique URL-friendly slug for the author (generated from full name)",
     * maxLength=255,
     * readOnly=true,
     * example="jk-rowling"
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the author record was created",
     * readOnly=true,
     * example="2023-01-01T12:30:00Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="Timestamp when the author record was last updated",
     * readOnly=true,
     * example="2023-01-05T15:45:00Z"
     * )
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name', // Though typically not directly fillable if it's an accessor
        'nationality',
        'biography',
        'image_url',
        'slug',
    ];

    /**
     * Get the full name of the author as an accessor.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    /**
     * Get the options for generating the slug using Spatie/Sluggable.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model (for route model binding).
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the books that belongs to the author (Many-to-Many relationship).
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    /**
     * Get the user that owns the author.
     *
     * @return BelongsTo<User, Author>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
