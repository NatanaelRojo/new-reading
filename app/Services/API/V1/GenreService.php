<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Genre\StoreGenreDTO;
use App\DataTransferObjects\API\V1\Genre\UpdateGenreDTO;
use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\Models\API\V1\Genre;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for GenreService
 */
class GenreService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return Genre::with(['books'])
            ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StoreGenreDTO $storeGenreDto): Genre
    {
        return Genre::query()
            ->create($storeGenreDto->toArray());
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
    public function update(UpdateGenreDTO $updateGenreDto, Genre $genre): void
    {
        $genre->update($updateGenreDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Genre $genre): void
    {
        $genre->delete();
    }
}
