<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Review\StoreReviewDTO;
use App\DataTransferObjects\API\V1\Review\UpdateReviewDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\Review\StoreReviewRequest;
use App\Http\Requests\API\V1\Review\UpdateReviewRequest;
use App\Http\Resources\API\V1\Review\ReviewResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Review;
use App\Models\API\V1\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $reviews = Review::with(['book', 'user'])
            ->paginate($request->query('per_page', 10));

        return ReviewResource::collection($reviews);
    }

    /**
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
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $storeReviewDto = StoreReviewDTO::fromRequest($request);

        $newReview = Review::query()
            ->create($storeReviewDto->toArray());

        return response()
            ->json(new ReviewResource($newReview), JsonResponse::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Review\StoreReviewRequest $request
     * @param \App\Models\API\V1\Book $book
     * @return JsonResponse|mixed
     */
    public function storeByBook(StoreReviewRequest $request, Book  $book): JsonResponse
    {
        if (!$book->isCompletedByUser($request->user())) {
            return response()
                ->json('Book not completed', JsonResponse::HTTP_CONFLICT);
        }

        $newReview = $book->reviews()
        ->create($request->validated());

        return response()
            ->json(new ReviewResource($newReview), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): JsonResponse
    {
        return response()
            ->json(new ReviewResource($review), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $updateReviewDto = UpdateReviewDTO::fromRequest($request);

        $review->update($updateReviewDto->toArray());

        return response()
            ->json(new ReviewResource($review), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Like a review.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function like(Request $request, Review $review): JsonResponse
    {
        $user = $request->user();

        if ($review->likedBy($user)) {
            return response()
                ->json('Review already liked', JsonResponse::HTTP_CONFLICT);
        }

        $user->likeReview($review);

        return response()
            ->json('Review liked', JsonResponse::HTTP_OK);
    }

    /**
     * Dislike a review.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\API\V1\Review $review
     * @return JsonResponse|mixed
     */
    public function dislike(Request $request, Review $review): JsonResponse
    {
        $user = $request->user();

        if ($review->dislikeBy($user)) {
            return response()
                ->json('Review already disliked', JsonResponse::HTTP_CONFLICT);
        }

        $user->dislikeReview($review);

        return response()
            ->json('Review disliked', JsonResponse::HTTP_OK);
    }
}
