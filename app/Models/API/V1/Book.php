<?php

namespace App\Models\API\V1;

use App\ModelFilters\API\V1\BookFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use EloquentFilter\Filterable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Book extends Model
{
    use HasFactory;
    use HasSlug;
    use Filterable;

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

    /**
     * Get the filter class name for the model.
     * @return string
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(BookFilter::class);
    }

    /**
     * Assign a tag to a user for the current book.
     *
     * @param int $userId The ID of the user to assign the tag to.
     * @param int $tagId The ID of the tag to assign to the user.
     * @return void
     */
    public function assignTagToUser(int $userId = null, int $tagId = null): void
    {
        if (is_null($userId) || is_null($tagId)) {
            return;
        }

        if (!$this->users()->where('user_id', $userId)->exists()) {
            $this->users()->attach($userId, ['tag_id' => $tagId]);
        }

        $this->users()->updateExistingPivot($userId, ['tag_id' => $tagId]);
    }

    /**
     * Update the progress of a user for a specific book.
     *
     * @param int $userId The ID of the user to update the progress for.
     * @param int $pagesRead The number of pages read by the user.
     * @return void
     */
public function updateUserProgress(int $userId = null, int $pagesRead = null): void
    {
        if (!is_null($userId) || !is_null($pagesRead)) {
            $this->users()->updateExistingPivot($userId, ['pages_read' => $pagesRead]);
        }
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

        return $userBook->pivot->pages_read ? ($userBook->pivot->pages_read / $totalPages) * 100 : 0;
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
     * @return HasMany<Comment, Book>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
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
     * The users that belong to the book.
     * @return BelongsToMany<User, Book>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('tag_id', 'pages_read')
            ->withTimestamps();
    }
}
