<?php

namespace App\Models\API\V1;

use App\ModelFilters\API\V1\BookFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Validation\ValidationException;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @OA\Schema(
 * title="Book",
 * description="Book model representing a published literary work.",
 * @OA\Xml(
 * name="Book"
 * )
 * )
 */
class Book extends Model
{
    use HasFactory;
    use HasSlug;
    use Filterable;

    /**
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="The unique identifier of the book.",
     * readOnly=true,
     * example=101
     * )
     * @OA\Property(
     * property="title",
     * type="string",
     * description="The title of the book.",
     * maxLength=255,
     * example="The Hitchhiker's Guide to the Galaxy"
     * )
     * @OA\Property(
     * property="synopsis",
     * type="string",
     * description="A brief summary or outline of the book's plot and themes.",
     * example="Arthur Dent's journey through space after Earth is destroyed to make way for a hyperspace bypass."
     * )
     * @OA\Property(
     * property="isbn",
     * type="string",
     * description="The International Standard Book Number, a unique commercial book identifier.",
     * maxLength=255,
     * example="978-0-345-39180-3"
     * )
     * @OA\Property(
     * property="pages_amount",
     * type="integer",
     * format="int32",
     * description="The total number of pages in the book.",
     * minimum=1,
     * example=193
     * )
     * @OA\Property(
     * property="chapters_amount",
     * type="integer",
     * format="int32",
     * description="The total number of chapters in the book.",
     * minimum=1,
     * example=35
     * )
     * @OA\Property(
     * property="published_at",
     * type="string",
     * format="date",
     * description="The date when the book was originally published (YYYY-MM-DD).",
     * example="1979-10-12"
     * )
     * @OA\Property(
     * property="slug",
     * type="string",
     * description="A URL-friendly, unique identifier for the book, derived from its title.",
     * readOnly=true,
     * example="the-hitchhikers-guide-to-the-galaxy"
     * )
     * @OA\Property(
     * property="image_url",
     * type="string",
     * format="url",
     * description="The URL to the book's cover image.",
     * nullable=true,
     * example="https://example.com/books/hitchhikers_guide.jpg"
     * )
     * @OA\Property(
     * property="reading_progress",
     * type="integer",
     * format="int32",
     * description="The current authenticated user's reading progress percentage for this book (0-100).",
     * readOnly=true,
     * example=50
     * )
     * @OA\Property(
     * property="average_rating",
     * type="string",
     * description="The calculated average rating of the book, formatted to one decimal place.",
     * readOnly=true,
     * example="4.2"
     * )
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="The timestamp when the book record was created.",
     * readOnly=true,
     * example="2023-01-01T10:00:00Z"
     * )
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="The timestamp when the book record was last updated.",
     * readOnly=true,
     * example="2023-01-01T11:00:00Z"
     * )
     */
    protected $fillable = [
        'title',
        'synopsis',
        'isbn',
        'pages_amount',
        'chapters_amount',
        'published_at',
        'slug',
        'image_url',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
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

    protected function readingProgress(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getReadingProgress(),
            set: fn (int $value) => $this->setReadingProgress($value)
        );
    }

    /**
     * Get the filter class name for the model.
     * @return string
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(BookFilter::class);
    }

    /**
     * Get the completion percentage of a book for a specific user.
     *
     * @param int $userId
     * @return float|int|null
     */
    public function getUserCompletionPercentage(int $userId = null): ?int
    {
        if (is_null($userId)) {
            return null;
        }

        $userBook = $this->users()->firstWhere('user_id', $userId);
        $totalPages = $this->pages_amount;

        if (!$userBook || !$totalPages) {
            return null;
        }

        return $userBook->pivot->pages_read ? (int)(($userBook->pivot->pages_read / $totalPages) * 100) : 0;
    }

    /**
     * Get the average rating of a book.
     * @return Attribute
     */
    public function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->getFormatedAverageRating(),
        );
    }

    /**
     * The authors that belong to the book.
     * @return BelongsToMany<Author, Book>
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * The comments that belong to the book.
     * @return MorphMany<Comment, Book>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->chaperone();
    }

    /**
     * The genres that belong to the book.
     * @return BelongsToMany<Genre, Book>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * The posts that belong to the book.
     * @return HasMany<Post, Book>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * The reviews that belong to the book.
     * @return HasMany<Review, Book>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The users that belong to the book.
     * @return BelongsToMany<User, Book>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(BookUser::class)
            ->withPivot('tag_id', 'pages_read')
            ->withTimestamps();
    }

    /**
     * Get the reading progress of a book for a specific user.
     * @return int
     */
    private function getReadingProgress(): int
    {
        $user = auth()->user();
        if (!$user) {
            return 0; // Or throw an exception if not authenticated
        }
        return $this->users()
            ->where('user_id', $user->id)
            ->value('pages_read') ?? 0;
    }

    /**
     * Set the reading progress of a book for a specific user.
     * @param int $pagesRead
     * @return void
     */
    private function setReadingProgress(int $pagesRead = null): void
    {
        if (!is_null($pagesRead)) {
            $user = auth()->user();
            if (!$user) {
                // Handle case where user is not authenticated
                throw ValidationException::withMessages(['user' => 'Authentication required to set reading progress.']);
            }
            $this->validateReadingProgress($pagesRead); // Assuming this method exists
            $this->users()->updateExistingPivot($user->id, ['pages_read' => $pagesRead]);
        }
    }

    /**
     * Get the formated average rating of a book.
     * @return string
     */
    private function getFormatedAverageRating(): string
    {
        $averageRating = $this->reviews()->avg('rating') ?? 0;
        $averageRating = number_format((float) $averageRating, 1);
        return $averageRating;
    }
}
