<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Comment\StoreCommentRequest;
use App\Http\Requests\API\V1\Comment\UpdateCommentRequest;
use App\Http\Resources\API\V1\Comment\CommentResource;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\User;
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
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Comment\StoreCommentRequest $request
     * @param \App\Models\API\V1\Post $post
     * @return JsonResponse|mixed
     */
    public function storeByPost(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['post_id'] = $post->id;
        $user = User::query()->firstWhere('id', $validatedData['user_id']);

        if (!$user->isFollowing($post->user)) {
            return response()
                ->json('User not following author', JsonResponse::HTTP_CONFLICT);
        }

        $newComment = $post->comments()->create($validatedData);

        return response()
            ->json(new CommentResource($newComment), JsonResponse::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Comment\StoreCommentRequest $request
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function storeByReview(StoreCommentRequest $request, Review $review): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['review_id'] = $review->id;
        $user = User::query()->firstWhere('id', $validatedData['user_id']);

        if (!$user->isFollowing($review->user)) {
            return response()
                ->json('User not following author', JsonResponse::HTTP_CONFLICT);
        }

        $newComment = $review->comments()->create($validatedData);

        return response()
            ->json(new CommentResource($newComment), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display a listing of the resource.
     * @param \App\Models\API\V1\Post $post
     * @return JsonResponse|mixed
     */
    public function indexByPost(Post $post): JsonResponse
    {
        $comments = $post->comments;

        return response()
            ->json(CommentResource::collection($comments), JsonResponse::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function indexByReview(Review $review): JsonResponse
    {
        $comments = $review->comments;

        return response()
            ->json(CommentResource::collection($comments), JsonResponse::HTTP_OK);
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
