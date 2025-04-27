<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Author\StoreAuthorDTO;
use App\DataTransferObjects\API\V1\Author\UpdateAuthorDTO;
use App\Models\API\V1\Author;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for AuthorService
 */
class AuthorService
{
    /**
     * Get all instances from the database
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function index(int $perPage = 10): LengthAwarePaginator
    {
        return Author::with('books')
        ->paginate($perPage);
    }

    /**
     * Store a new instance in the database
     * @param StoreAuthorDTO $storeAuthorDto
     *
     * @return Author
     */
    public function store(StoreAuthorDTO $storeAuthorDto): Author
    {
        return Author::query()
        ->create($storeAuthorDto->toArray());
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
    public function update(UpdateAuthorDTO $updateAuthorDto, Author $author): void
    {
        $author->update($updateAuthorDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Author $author): void
    {
        $author->delete();
    }
}
