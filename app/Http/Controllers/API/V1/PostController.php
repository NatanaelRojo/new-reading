<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Post\StorePostRequest;
use App\Http\Requests\API\V1\Post\UpdatePostRequest;
use App\Http\Resources\API\V1\Post\PostResource;
use App\Models\API\V1\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();

        return response()
            ->json(
                PostResource::collection($posts),
                JsonResponse::HTTP_OK
            );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $newPost = Post::query()
            ->create($request->validated());

        return response()
            ->json(
                new PostResource($newPost),
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        return response()
            ->json(
                new PostResource($post),
                JsonResponse::HTTP_OK
            );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return response()
            ->json(
                new PostResource($post),
                JsonResponse::HTTP_OK
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
