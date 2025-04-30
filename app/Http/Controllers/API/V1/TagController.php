<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Tag\StoreTagDTO;
use App\DataTransferObjects\API\V1\Tag\UpdateTagDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\Tag\StoreTagRequest;
use App\Http\Requests\API\V1\Tag\UpdateTagRequest;
use App\Http\Resources\API\V1\Tag\TagResource;
use App\Models\API\V1\Tag;
use App\Services\API\V1\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController
{
    public function __construct(private TagService $tagService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $tags = $this->tagService->index($paginateDto);

        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $storeTagDto = StoreTagDTO::fromRequest($request);

        $newTag = $this->tagService->store($storeTagDto);

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
        $updateTagDto = UpdateTagDTO::fromRequest($request);

        $this->tagService->update($updateTagDto, $tag);

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
        $this->tagService->destroy($tag);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
