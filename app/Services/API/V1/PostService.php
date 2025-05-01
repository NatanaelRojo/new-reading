<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Post\StorePostDTO;
use App\DataTransferObjects\API\V1\Post\UpdatePostDTO;
use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
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

    public function indexByBook(PaginateDTO $paginateDto, Book $book): LengthAwarePaginator
    {
        return $book->posts()
            ->paginate($paginateDto->perPage ?? 10);
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
