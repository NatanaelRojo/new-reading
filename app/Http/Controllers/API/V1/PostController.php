<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Post\StorePostRequest;
use App\Http\Requests\API\V1\Post\UpdatePostRequest;
use App\Http\Resources\API\V1\Post\PostResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
use App\Models\User;
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

    public function indexByBook(Book $book): JsonResponse
    {
        $bookPosts = $book->posts;

        return response()
            ->json(
                PostResource::collection($bookPosts),
                JsonResponse::HTTP_OK
            );
    }

    /**
     * Display a listing of the resource.
     * @param \App\Models\User $user
     * @return JsonResponse|mixed
     */
    public function indexByUser(User $user): JsonResponse
    {
        $userPosts = $user->posts;

        return response()
            ->json(
                PostResource::collection($userPosts),
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
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Post\StorePostRequest $request
     * @param \App\Models\API\V1\Book $book
     * @return JsonResponse|mixed
     */
    public function storeByBook(StorePostRequest $request, Book $book): JsonResponse
    {
        $newPost = $book->posts()->create($request->validated());

        return response()
            ->json(
                new PostResource($newPost),
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Post\StorePostRequest $request
     * @param \App\Models\User $user
     * @return JsonResponse|mixed
     */
    public function storeByUser(StorePostRequest $request, User $user): JsonResponse
    {
        $newPost = $user->posts()->create($request->validated());

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
