<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Comment\StoreCommentByPostDTO;
use App\DataTransferObjects\API\V1\Comment\StoreCommentByReviewDTO;
use App\DataTransferObjects\API\V1\Comment\StoreCommentDTO;
use App\DataTransferObjects\API\V1\Comment\UpdateCommentDTO;
use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\Exceptions\API\V1\User\UserNotFollowingException;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for CommentService
 */
class CommentService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return Comment::query()
        ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Get all instances from the database
     *
     * @param \App\DataTransferObjects\API\V1\Paginate\PaginateDTO $paginateDto
     * @param \App\Models\API\V1\Post $post
     * @return LengthAwarePaginator
     */
    public function indexByPost(PaginateDTO $paginateDto, Post $post): LengthAwarePaginator
    {
        return $post->comments()
            ->paginate($paginateDto->perPage ?? 10);
    }

    public function indexByReview(PaginateDTO $paginateDto, Review $review): LengthAwarePaginator
    {
        return $review->comments()
            ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StoreCommentDTO $storeCommentDto): Comment
    {
        return Comment::query()
            ->create($storeCommentDto->toArray());
    }

    /**
     * Store a new instance in the database
     *
     * @param \App\DataTransferObjects\API\V1\Comment\StoreCommentByPostDTO $storeCommentByPostDto
     * @throws \App\Exceptions\API\V1\User\UserNotFollowingException
     * @return Comment
     */
    public function storeByPost(StoreCommentByPostDTO $storeCommentByPostDto): Comment
    {
        $user = User::query()->firstWhere('id', $storeCommentByPostDto->userId);
        $post = Post::query()->firstWhere('id', $storeCommentByPostDto->postId);

        if (!$user->isFollowing($post->user)) {
            throw new UserNotFollowingException();
        }

        return $post->comments()
            ->create($storeCommentByPostDto->toArray());
    }

    /**
     * Store a new instance in the database
     *
     * @param \App\DataTransferObjects\API\V1\Comment\StoreCommentByReviewDTO $storeCommentByReviewDto
     * @throws \App\Exceptions\API\V1\User\UserNotFollowingException
     * @return Comment
     */
    public function storeByReview(StoreCommentByReviewDTO $storeCommentByReviewDto): Comment
    {
        if (!$storeCommentByReviewDto->user->isFollowing($storeCommentByReviewDto->review->user)) {
            throw new UserNotFollowingException();
        }

        return $storeCommentByReviewDto->review->comments()
        ->create($storeCommentByReviewDto->toArray());
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
    public function update(UpdateCommentDTO $updateCommentDto, Comment $comment): void
    {
        $comment->update($updateCommentDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Comment $comment): void
    {
        $comment->delete();
    }
}
