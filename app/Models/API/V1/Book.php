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
    public function updateUserProgress(User $user = null, int $pagesRead = null): void
    {
        if (!is_null($user) || !is_null($pagesRead)) {
            $pagesRead == $this->pages_amount ? $this->completeBook($user) : $this->readingProgress = $pagesRead;
        }
    }

    /**
     * Complete a book for a specific user.
     *
     * @param \App\Models\User|null $user
     * @return void
     */
    public function completeBook(User $user = null): void
    {
        $tag = Tag::query()
            ->firstWhere('name', config('tags.default_tags')[2]);
        $this->users()->updateExistingPivot($user->id, [
            'tag_id' => $tag->id,
            'pages_read' => $this->pages_amount,
        ]);
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

    public function isCompletedByUser(User   $user = null): bool
    {
        $bookTag = $this->getUserTag();

        if ($bookTag->name !== config('tags.default_tags')[2]) {
            return false;
        }

        return true;
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

    public function getUserTag(): ?Tag
    {
        return $this->users()
            ->firstWhere('user_id', auth()->id())
            ->pivot->tag;
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

    public function validateReadingProgress(int $pagesRead): void
    {
        $user = auth()->user();
        $currentProgress = $this->users()->where('user_id', $user->id)->value('pages_read');

        if ($pagesRead <= $currentProgress) {
            throw ValidationException::withMessages([
                'pages_read' => 'The new progress must be greater than the current progress.',
            ]);
        }
    }

    /**
     * Get the reading progress of a book for a specific user.
     * @return int
     */
    private function getReadingProgress(): int
    {
        $user = auth()->user();
        return $this->users()
            ->where('user_id', $user->id)
            ->value('pages_read');
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
            $this->validateReadingProgress($pagesRead);
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
