<?php

namespace App\DataTransferObjects\API\V1\Review;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Data Transfer Object for StoreReviewByBook
 *
 * Auto-generated from App\Http\Requests\API\V1\Review\StoreReviewRequest.
 */
class StoreReviewByBookDTO extends BaseApiDTO
{
    public function __construct(
        public readonly Book $book,
        public readonly User $user,
        public readonly int $rating,
        public readonly string $comment,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        return new StoreReviewByBookDTO(
            book: $request->route('book'),
            user: $request->user(),
            rating: $request->input('rating'),
            comment: $request->input('comment'),
        );
    }

    public function toArray(bool $includNulls = false): array
    {
        return [
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ];
    }
}
