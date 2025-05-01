<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Post\StorePostDTO;
use App\DataTransferObjects\API\V1\Post\UpdatePostDTO;
use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for PostService
 */
class PostService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return Post::query()
        ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Get all instances from the database
     *
     * @param \App\DataTransferObjects\API\V1\Paginate\PaginateDTO $paginateDto
     * @param \App\Models\API\V1\Book $book
     * @return LengthAwarePaginator
     */
    public function indexByBook(PaginateDTO $paginateDto, Book $book): LengthAwarePaginator
    {
        return $book->posts()
            ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Get all instances from the database
     *
     * @param \App\DataTransferObjects\API\V1\Paginate\PaginateDTO $paginateDTO
     * @param \App\Models\User $user
     * @return LengthAwarePaginator
     */
    public function indexByUser(PaginateDTO $paginateDTO, User $user): LengthAwarePaginator
    {
        return $user->posts()
            ->paginate($paginateDTO->perPage ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StorePostDTO $storePostDto): Post
    {
        return Post::query()
        ->create($storePostDto->toArray());
    }

    /**
     * Store a new instance in the database
     *
     * @param \App\DataTransferObjects\API\V1\Post\StorePostDTO $storePostDto
     * @param \App\Models\API\V1\Book $book
     * @return Post
     */
    public function storeByBook(StorePostDTO $storePostDto, Book $book): Post
    {
        return $book->posts()
            ->create($storePostDto->toArray());
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
    public function update(UpdatePostDTO $updatePostDto, Post $post): void
    {
        $post->update($updatePostDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Post $post): void
    {
        $post->delete();
    }
}
