<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Comment\StoreCommentByPostDTO;
use App\DataTransferObjects\API\V1\Comment\StoreCommentByReviewDTO;
use App\DataTransferObjects\API\V1\Comment\StoreCommentDTO;
use App\DataTransferObjects\API\V1\Comment\UpdateCommentDTO;
use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\Exceptions\API\V1\User\UserNotFollowingException;
use App\Http\Requests\API\V1\Comment\StoreCommentRequest;
use App\Http\Requests\API\V1\Comment\UpdateCommentRequest;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Resources\API\V1\Comment\CommentResource;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
use App\Models\API\V1\Review;
use App\Models\User;
use App\Services\API\V1\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController
{
    public function __construct(private CommentService $commentService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $comments = $this->commentService->index($paginateDto);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $storeCommentDto = StoreCommentDTO::fromRequest($request);

        $newComment = $this->commentService->store($storeCommentDto);

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
        try {
            $storeByPostDto = StoreCommentByPostDTO::fromRequest($request);

            $newComment = $this->commentService->storeByPost($storeByPostDto);

            return response()
                ->json(new CommentResource($newComment), JsonResponse::HTTP_CREATED);
        } catch (UserNotFollowingException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], JsonResponse::HTTP_CONFLICT);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Comment\StoreCommentRequest $request
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function storeByReview(StoreCommentRequest $request, Review $review): JsonResponse
    {
        try {
            $storeCommentByReviewDto = StoreCommentByReviewDTO::fromRequest($request);

            $newComment = $this->commentService->storeByReview($storeCommentByReviewDto);

            return response()
                ->json(new CommentResource($newComment), JsonResponse::HTTP_CREATED);
        } catch (UserNotFollowingException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], JsonResponse::HTTP_CONFLICT);
        }
    }

    /**
     * Display a listing of the resource.
     * @param \App\Models\API\V1\Post $post
     * @return JsonResponse|mixed
     */
    public function indexByPost(PaginateRequest $request, Post $post): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $comments = $this->commentService->indexByPost($paginateDto, $post);

        return CommentResource::collection($comments);
    }

    /**
     * Display a listing of the resource.
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function indexByReview(PaginateRequest $request, Review $review): AnonymousResourceCollection
    {
        $comments = $review->comments()
            ->paginate($request->query('per_page', 10));

        return CommentResource::collection($comments);
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
        $updateCommentDto = UpdateCommentDTO::fromRequest($request);

        $this->commentService->update($updateCommentDto, $comment);

        return response()
            ->json(new CommentResource($comment), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->destroy($comment);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
