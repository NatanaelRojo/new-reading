<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Tag\StoreTagDTO;
use App\DataTransferObjects\API\V1\Tag\UpdateTagDTO;
use App\Models\API\V1\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for TagService
 */
class TagService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return Tag::query()
    ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StoreTagDTO $storeTagDto): Tag
    {
        return Tag::query()
    ->create($storeTagDto->toArray());
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
    public function update(UpdateTagDTO $updateTagDto, Tag $tag): void
    {
        $tag->update($updateTagDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(Tag $tag): void
    {
        $tag->delete();
    }
}
