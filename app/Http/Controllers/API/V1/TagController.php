<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Tag\StoreTagDTO;
use App\DataTransferObjects\API\V1\Tag\UpdateTagDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\Tag\StoreTagRequest;
use App\Http\Requests\API\V1\Tag\UpdateTagRequest;
use App\Http\Resources\API\V1\Tag\TagResource;
use App\Models\API\V1\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $tags = Tag::query()
            ->paginate($request->query('per_page', 10));

        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $storeTagDto = new StoreTagDTO(...$request->validated());

        $newTag = Tag::query()
            ->create($storeTagDto->toArray());

        return response()->json(new TagResource($newTag), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): JsonResponse
    {
        return response()->json(new TagResource($tag), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $updateTagDto = new UpdateTagDTO(...$request->validated());

        $tag->update($updateTagDto->toArray());

        return response()->json(
            new TagResource($tag),
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
