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
     * @OA\Get(
     * path="/api/v1/tags",
     * summary="Get a paginated list of all tags",
     * operationId="tagIndex",
     * tags={"Tags"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Number of items to return per page.",
     * required=false,
     * @OA\Schema(type="integer", format="int32", minimum=1, maximum=50, example=15)
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="The page number to retrieve.",
     * required=false,
     * @OA\Schema(type="integer", format="int32", minimum=1, example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Tag")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden."
     * )
     * )
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $tags = $this->tagService->index($paginateDto);

        return TagResource::collection($tags);
    }

    /**
     * @OA\Post(
     * path="/api/v1/tags",
     * summary="Create a new tag",
     * operationId="tagStore",
     * tags={"Tags"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Tag data to store",
     * @OA\JsonContent(ref="#/components/schemas/StoreTagRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Tag created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Tag")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $storeTagDto = StoreTagDTO::fromRequest($request);

        $newTag = $this->tagService->store($storeTagDto);

        return response()->json(new TagResource($newTag), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     * path="/api/v1/tags/{tag_slug}",
     * summary="Get a single tag by its slug",
     * operationId="tagShow",
     * tags={"Tags"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="tag_slug",
     * in="path",
     * description="The slug of the tag to retrieve.",
     * required=true,
     * @OA\Schema(type="string", example="fiction")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Tag")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden."
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found."
     * )
     * )
     * Display the specified resource.
     */
    public function show(Tag $tag): JsonResponse
    {
        return response()->json(new TagResource($tag), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     * path="/api/v1/tags/{tag_slug}",
     * summary="Update an existing tag",
     * operationId="tagUpdate",
     * tags={"Tags"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="tag_slug",
     * in="path",
     * description="The slug of the tag to update.",
     * required=true,
     * @OA\Schema(type="string", example="fiction")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Updated tag data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateTagRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tag updated successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Tag")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden."
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
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
     * @OA\Delete(
     * path="/api/v1/tags/{tag_slug}",
     * summary="Delete a tag by its slug",
     * operationId="tagDestroy",
     * tags={"Tags"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="tag_slug",
     * in="path",
     * description="The slug of the tag to delete.",
     * required=true,
     * @OA\Schema(type="string", example="fiction")
     * ),
     * @OA\Response(
     * response=204,
     * description="Tag deleted successfully (No Content)",
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden."
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found."
     * )
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $this->tagService->destroy($tag);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
