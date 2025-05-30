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
     * @OA\Get(
     * path="/api/v1/comments",
     * summary="Get a paginated list of all comments",
     * operationId="commentIndex",
     * tags={"Comments"},
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
     * @OA\Items(ref="#/components/schemas/Comment")
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
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $comments = $this->commentService->index($paginateDto);

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Post(
     * path="/api/v1/comments",
     * summary="Store a new comment ",
     * description="This endpoint allows creating a comment.",
     * operationId="commentStore",
     * tags={"Comments"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Comment data",
     * @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Comment created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Comment")
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
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $storeCommentDto = StoreCommentDTO::fromRequest($request);

        $newComment = $this->commentService->store($storeCommentDto);

        return response()
            ->json(new CommentResource($newComment), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     * path="/api/v1/posts/{post_slug}/comments",
     * summary="Add a new comment to a specific post",
     * operationId="storeCommentByPost",
     * tags={"Comments", "Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="post_slug",
     * in="path",
     * description="The slug of the post to comment on.",
     * required=true,
     * @OA\Schema(type="string", example="my-first-post")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Comment data",
     * @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Comment created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Comment")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden (e.g., user not following the post's author, or other access restrictions)."
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found."
     * ),
     * @OA\Response(
     * response=409,
     * description="Conflict (e.g., User not following post author - UserNotFollowingException)",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="You must follow the author to comment on this post.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
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
     * @OA\Post(
     * path="/api/v1/reviews/{review_slug}/comments",
     * summary="Add a new comment to a specific review",
     * operationId="storeCommentByReview",
     * tags={"Comments", "Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review_slug",
     * in="path",
     * description="The slug of the review to comment on.",
     * required=true,
     * @OA\Schema(type="string", example="great-read-review")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Comment data",
     * @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Comment created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Comment")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden (e.g., user not following the review's author, or other access restrictions)."
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found."
     * ),
     * @OA\Response(
     * response=409,
     * description="Conflict (e.g., User not following review author - UserNotFollowingException)",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="You must follow the author to comment on this review.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
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
     * @OA\Get(
     * path="/api/v1/posts/{post_slug}/comments",
     * summary="Get comments for a specific post",
     * operationId="indexCommentsByPost",
     * tags={"Comments", "Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="post_slug",
     * in="path",
     * description="The slug of the post to retrieve comments for.",
     * required=true,
     * @OA\Schema(type="string", example="my-first-post")
     * ),
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
     * @OA\Items(ref="#/components/schemas/Comment")
     * )
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
     * @OA\Get(
     * path="/api/v1/reviews/{review_slug}/comments",
     * summary="Get comments for a specific review",
     * operationId="indexCommentsByReview",
     * tags={"Comments", "Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review_slug",
     * in="path",
     * description="The slug of the review to retrieve comments for.",
     * required=true,
     * @OA\Schema(type="string", example="awesome-book-review")
     * ),
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
     * @OA\Items(ref="#/components/schemas/Comment")
     * )
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
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function indexByReview(PaginateRequest $request, Review $review): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $comments = $this->commentService->indexByReview($paginateDto, $review);

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Get(
     * path="/api/v1/comments/{comment_slug}",
     * summary="Get a single comment by its slug",
     * operationId="commentShow",
     * tags={"Comments"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="comment_slug",
     * in="path",
     * description="The slug of the comment to retrieve.",
     * required=true,
     * @OA\Schema(type="string", example="my-great-comment-slug")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Comment")
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
     */
    public function show(Comment $comment): JsonResponse
    {
        return response()
            ->json(new CommentResource($comment), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     * path="/api/v1/comments/{comment_slug}",
     * summary="Update an existing comment",
     * operationId="commentUpdate",
     * tags={"Comments"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="comment_slug",
     * in="path",
     * description="The slug of the comment to update.",
     * required=true,
     * @OA\Schema(type="string", example="my-great-comment-slug")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Updated comment data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateCommentRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Comment updated successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Comment")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden (e.g., user is not the owner of the comment)."
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
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $updateCommentDto = UpdateCommentDTO::fromRequest($request);

        $this->commentService->update($updateCommentDto, $comment);

        return response()
            ->json(new CommentResource($comment), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/comments/{comment_slug}",
     * summary="Delete a comment by its slug",
     * operationId="commentDestroy",
     * tags={"Comments"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="comment_slug",
     * in="path",
     * description="The slug of the comment to delete.",
     * required=true,
     * @OA\Schema(type="string", example="my-great-comment-to-delete")
     * ),
     * @OA\Response(
     * response=204,
     * description="Comment deleted successfully (No Content)",
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden (e.g., user is not the owner of the comment)."
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found."
     * )
     * )
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->destroy($comment);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
