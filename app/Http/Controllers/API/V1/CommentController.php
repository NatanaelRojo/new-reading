<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Comment\StoreCommentRequest;
use App\Http\Requests\API\V1\Comment\UpdateCommentRequest;
use App\Http\Resources\API\V1\Comment\CommentResource;
use App\Models\API\V1\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = Comment::all();

        return response()
            ->json(CommentResource::collection($comments), JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $newComment = Comment::query()
            ->create($request->validated());

        return response()
            ->json(new CommentResource($newComment), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        return response()
            ->json(new CommentResource($comment), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());

        return response()
            ->json(new CommentResource($comment), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
