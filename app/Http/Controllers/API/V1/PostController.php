<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Post\StorePostDTO;
use App\DataTransferObjects\API\V1\Post\UpdatePostDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\Post\StorePostRequest;
use App\Http\Requests\API\V1\Post\UpdatePostRequest;
use App\Http\Resources\API\V1\Post\PostResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->paginate($request->query('per_page', 10));

        return PostResource::collection($posts);
    }

    /**
     * This PHP function returns a JSON response containing a collection of posts related to a specific
     * book.
     *
     * @param Book book The `indexByBook` function takes a `Book` object as a parameter. It retrieves
     * the posts associated with the provided book and returns a JSON response containing a collection
     * of `PostResource` objects representing those posts. The HTTP status code of the response is set
     * to 200 (OK).
     * @return AnonymousResourceCollection A JSON response containing a collection of Post resources related to the
     * provided Book object is being returned with an HTTP status code of 200 (OK).
     */
    public function indexByBook(PaginateRequest $request, Book $book): AnonymousResourceCollection
    {
        $bookPosts = $book->posts()
            ->paginate($request->query('per_page', 10));

        return PostResource::collection($bookPosts);
    }

    /**
     * This PHP function retrieves and paginates posts belonging to a specific user and returns them as
     * a collection of Post resources.
     *
     * @param PaginateRequest request The `` parameter is an instance of `PaginateRequest`,
     * which is used to handle pagination requests. It allows you to retrieve the pagination parameters
     * such as the page number and the number of items per page.
     * @param User user The `user` parameter in the `indexByUser` function is an instance of the `User`
     * model. It is used to retrieve posts associated with a specific user.
     * @return AnonymousResourceCollection An AnonymousResourceCollection of PostResource objects
     * representing the paginated posts belonging to the specified user.
     */
    public function indexByUser(PaginateRequest $request, User $user): AnonymousResourceCollection
    {
        $userPosts = $user->posts()
            ->paginate($request->query('per_page', 10));

        return PostResource::collection($userPosts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $storePostDto = StorePostDTO::fromRequest($request);

        $newPost = Post::query()
            ->create($storePostDto->toArray());

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
        $updatePostDto = UpdatePostDTO::fromRequest($request);

        $post->update($updatePostDto->toArray());

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
