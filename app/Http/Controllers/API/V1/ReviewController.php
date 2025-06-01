<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Review\StoreReviewByBookDTO;
use App\DataTransferObjects\API\V1\Review\StoreReviewDTO;
use App\DataTransferObjects\API\V1\Review\UpdateReviewDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\Review\StoreReviewRequest;
use App\Http\Requests\API\V1\Review\UpdateReviewRequest;
use App\Http\Resources\API\V1\Review\ReviewResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag; // Not used in this controller based on provided code
use App\Models\User;
use App\Services\API\V1\BookService;
use App\Services\API\V1\ReviewService;
use App\Services\API\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController
{
    /**
     * Create a new controller instance.
     *
     * @param ReviewService $reviewService
     */
    public function __construct(
        private BookService $bookService,
        private ReviewService $reviewService,
        private UserService $userService,
    ) {
    }

    /**
     * @OA\Get(
     * path="/api/v1/reviews",
     * summary="Get a paginated list of all reviews",
     * operationId="reviewIndex",
     * tags={"Reviews"},
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
     * @OA\Items(ref="#/components/schemas/Review")
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

        $reviews = $this->reviewService->index($paginateDto);

        return ReviewResource::collection($reviews);
    }

    /**
     * @OA\Get(
     * path="/api/v1/users/{user_slug}/reviews",
     * summary="Get a paginated list of reviews by a specific user",
     * operationId="reviewIndexByUser",
     * tags={"Users", "Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="user_slug",
     * in="path",
     * description="The slug of the user to retrieve reviews for.",
     * required=true,
     * @OA\Schema(type="string", example="johndoe")
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
     * @OA\Items(ref="#/components/schemas/Review")
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
     * description="User not found."
     * )
     * )
     * Display a listing of the resource.
     * @param \App\Models\User $user
     * @return JsonResponse|mixed
     */
    public function indexByUser(PaginateRequest $request, User $user): AnonymousResourceCollection
    {
        $userReviews = $user->reviews()
            ->paginate($request->query('per_page', 10));

        return ReviewResource::collection($userReviews);
    }

    /**
     * @OA\Get(
     * path="/api/v1/books/{book_slug}/reviews",
     * summary="Get a paginated list of reviews for a specific book",
     * operationId="reviewIndexByBook",
     * tags={"Books", "Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book_slug",
     * in="path",
     * description="The slug of the book to retrieve reviews for.",
     * required=true,
     * @OA\Schema(type="string", example="the-hobbit")
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
     * @OA\Items(ref="#/components/schemas/Review")
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
     * description="Book not found."
     * )
     * )
     * Display a listing of the resource.
     * @param \App\Models\API\V1\Book $book
     * @return JsonResponse|mixed
     */
    public function indexByBook(PaginateRequest $request, Book $book): AnonymousResourceCollection
    {
        $bookReviews = $book->reviews()
            ->paginate($request->query('per_page', 10));

        return ReviewResource::collection($bookReviews);
    }

    /**
     * @OA\Post(
     * path="/api/v1/reviews",
     * summary="Create a new review",
     * operationId="reviewStore",
     * tags={"Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Review data to store. Provide 'book_id' if not creating via a book-specific route.",
     * @OA\JsonContent(ref="#/components/schemas/StoreReviewRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Review created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Review")
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
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $storeReviewDto = StoreReviewDTO::fromRequest($request);

        $newReview = $this->reviewService->store($storeReviewDto);

        return response()
            ->json(new ReviewResource($newReview), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     * path="/api/v1/books/{book_slug}/reviews",
     * summary="Create a new review for a specific book",
     * description="Creates a new review associated with the given book. The 'book_id' in the request body is optional for this endpoint.",
     * operationId="reviewStoreByBook",
     * tags={"Books", "Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book_slug",
     * in="path",
     * description="The slug of the book to create the review for.",
     * required=true,
     * @OA\Schema(type="string", example="the-hobbit")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Review data to store.",
     * @OA\JsonContent(ref="#/components/schemas/StoreReviewRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Review created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Review")
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
     * description="Book not found."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Review\StoreReviewRequest $request
     * @return JsonResponse|mixed
     */
    public function storeByBook(StoreReviewRequest $request, Book $book): JsonResponse
    {
        $storeReviewByBookDto = StoreReviewByBookDTO::fromRequest($request);

        $newReview = $this->reviewService->storeByBook(
            $storeReviewByBookDto,
            $this->bookService
        );

        return response()
            ->json(new ReviewResource($newReview), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     * path="/api/v1/reviews/{review}",
     * summary="Get a single review by ID",
     * operationId="reviewShow",
     * tags={"Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review",
     * in="path",
     * description="The ID of the review to retrieve.",
     * required=true,
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Review")
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
     * description="Review not found."
     * )
     * )
     * Display the specified resource.
     */
    public function show(Review $review): JsonResponse
    {
        return response()
            ->json(new ReviewResource($review), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     * path="/api/v1/reviews/{review}",
     * summary="Update an existing review",
     * operationId="reviewUpdate",
     * tags={"Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review",
     * in="path",
     * description="The ID of the review to update.",
     * required=true,
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Updated review data. All fields are optional.",
     * @OA\JsonContent(ref="#/components/schemas/UpdateReviewRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Review updated successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Review")
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
     * description="Review not found."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $updateReviewDto = UpdateReviewDTO::fromRequest($request);

        $this->reviewService->update($updateReviewDto, $review);

        return response()
            ->json(new ReviewResource($review), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/reviews/{review}",
     * summary="Delete a review by ID",
     * operationId="reviewDestroy",
     * tags={"Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review",
     * in="path",
     * description="The ID of the review to delete.",
     * required=true,
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=204,
     * description="Review deleted successfully (No Content)",
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
     * description="Review not found."
     * )
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->reviewService->destroy($review);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     * path="/api/v1/reviews/{review}/like",
     * summary="Like a review",
     * operationId="reviewLike",
     * tags={"Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review",
     * in="path",
     * description="The ID of the review to like.",
     * required=true,
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Review liked successfully.",
     * @OA\JsonContent(
     * type="string",
     * example="Review liked"
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
     * description="Review not found."
     * )
     * )
     * Like a review.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function like(Request $request, Review $review): JsonResponse
    {
        $this->reviewService->like($review, $request->user(), $this->userService);

        return response()
            ->json('Review liked', JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Post(
     * path="/api/v1/reviews/{review}/dislike",
     * summary="Dislike a review",
     * operationId="reviewDislike",
     * tags={"Reviews"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="review",
     * in="path",
     * description="The ID of the review to dislike.",
     * required=true,
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Review disliked successfully.",
     * @OA\JsonContent(
     * type="string",
     * example="Review disliked"
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
     * description="Review not found."
     * )
     * )
     * Dislike a review.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function dislike(Request $request, Review $review): JsonResponse
    {
        $this->reviewService->dislike($review, $request->user(), $this->userService);

        return response()
            ->json('Review disliked', JsonResponse::HTTP_OK);
    }
}
