<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\User\AssignTagToBookDTO;
use App\DataTransferObjects\API\V1\User\StoreUserDTO;
use App\DataTransferObjects\API\V1\User\UpdateUserDTO;
use App\Models\API\V1\Book;
use App\Models\API\V1\Like;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for UserService
 */
class UserService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return User::with(['following', 'followers'])
        ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StoreUserDTO $storeUserDto): User
    {
        return User::query()
        ->create($storeUserDto->toArray());
    }

    /**
     * Get a single instance from the database
     */
    public function show()
    {
        //
    }

    /**
     * Update an existing instance in the database
     */
    public function update(UpdateUserDTO $updateUserDto, User $user): void
    {
        $user->update($updateUserDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(User $user): void
    {
        $user->delete();
    }

    /**
     * Like a review for the current user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\API\V1\Review $review
     * @return void
     */
    public function likeReview(User $user, Review $review): void
    {
        Like::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'is_dislike' => false,
                'likeable_id' => $review->id,
                'likeable_type' => $review::class,
            ]
        );
        $review->updateLikeCounters();
    }

    /**
     * Dislike a review for the current user.
     *
     * @param \App\Models\User $user
     * @param \App\Models\API\V1\Review $review
     * @return void
     */
    public function dislikeReview(User $user, Review $review): void
    {
        Like::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'is_dislike' => true,
                'likeable_id' => $review->id,
                'likeable_type' => $review::class,
            ]
        );
        $review->updateLikeCounters();
    }

    /**
     * Assign a tag to a book for the current user.
     *
     * @param \App\DataTransferObjects\API\V1\User\AssignTagToBookDTO $assignTagToBookDto
     * @return void
     */
    public function assignTagToBook(AssignTagToBookDTO $assignTagToBookDto): void
    {
        $user = User::query()->firstWhere('id', $assignTagToBookDto->user_id);

        if (!$user->books()->where('book_id', $assignTagToBookDto->book_id)->exists()) {
            $user->books()
                ->attach($assignTagToBookDto->book_id, ['tag_id' => $assignTagToBookDto->tag_id]);
        }

        $user->books()
            ->updateExistingPivot($assignTagToBookDto->book_id, ['tag_id' => $assignTagToBookDto->tag_id]);
    }
}
