<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Tag\StoreTagRequest;
use App\Http\Requests\API\V1\Tag\UpdateTagRequest;
use App\Http\Resources\API\V1\Tag\TagResource;
use App\Models\API\V1\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tags = Tag::all();

        return response()->json(
            TagResource::collection($tags),
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $newTag = Tag::query()
            ->create($request->validated());

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
        $tag->update($request->validated());

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
