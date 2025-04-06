<?php

namespace App\Models\API\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookUser extends Pivot
{
    protected $table = 'book_user';

    /**
     * Get the book that owns the book_user.
     * @return BelongsTo<Book, BookUser>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the tag that owns the book_user.
     * @return BelongsTo<Tag, BookUser>
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the user that owns the book_user.
     * @return BelongsTo<User, BookUser>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
