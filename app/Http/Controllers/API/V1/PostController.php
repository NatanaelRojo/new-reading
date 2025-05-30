<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\Post\StorePostDTO;
use App\DataTransferObjects\API\V1\Post\UpdatePostDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\Post\StorePostRequest;
use App\Http\Requests\API\V1\Post\UpdatePostRequest;
use App\Http\Resources\API\V1\Post\PostResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
use App\Models\User;
use App\Services\API\V1\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController
{
    /**
     * Create a new controller instance.
     *
     * @param PostService $postService
     */
    public function __construct(private PostService $postService)
    {
    }

    /**
     * @OA\Get(
     * path="/api/v1/posts",
     * summary="Get a paginated list of all posts",
     * operationId="postIndex",
     * tags={"Posts"},
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
     * @OA\Items(ref="#/components/schemas/Post")
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

        $posts = $this->postService->index($paginateDto);

        return PostResource::collection($posts);
    }

    /**
     * @OA\Get(
     * path="/api/v1/books/{book_slug}/posts",
     * summary="Get a paginated list of posts for a specific book",
     * operationId="postIndexByBook",
     * tags={"Books", "Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book_slug",
     * in="path",
     * description="The slug of the book to retrieve posts for.",
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
     * @OA\Items(ref="#/components/schemas/Post")
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
        $paginateDto = PaginateDTO::fromRequest($request);

        $postsByBook = $this->postService->indexByBook($paginateDto, $book);

        return PostResource::collection($postsByBook);
    }

    /**
     * @OA\Get(
     * path="/api/v1/users/{user_slug}/posts",
     * summary="Get a paginated list of posts by a specific user",
     * operationId="postIndexByUser",
     * tags={"Users", "Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="user_slug",
     * in="path",
     * description="The slug of the user to retrieve posts for.",
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
     * @OA\Items(ref="#/components/schemas/Post")
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
        $paginateDto = PaginateDTO::fromRequest($request);

        $userPosts = $this->postService->indexByUser($paginateDto, $user);

        return PostResource::collection($userPosts);
    }

    /**
     * @OA\Post(
     * path="/api/v1/posts",
     * summary="Create a new post",
     * operationId="postStore",
     * tags={"Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Post data to store. Provide 'book_id' if not creating via a book-specific route.",
     * @OA\JsonContent(ref="#/components/schemas/StorePostRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Post created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Post")
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
    public function store(StorePostRequest $request): JsonResponse
    {
        $storePostDto = StorePostDTO::fromRequest($request);

        $newPost = $this->postService->store($storePostDto);

        return response()
            ->json(
                new PostResource($newPost),
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * @OA\Post(
     * path="/api/v1/books/{book_slug}/posts",
     * summary="Create a new post for a specific book",
     * description="Creates a new post associated with the given book. The 'book_id' in the request body is optional for this endpoint.",
     * operationId="postStoreByBook",
     * tags={"Books", "Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book_slug",
     * in="path",
     * description="The slug of the book to create the post for.",
     * required=true,
     * @OA\Schema(type="string", example="the-hobbit")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Post data to store.",
     * @OA\JsonContent(ref="#/components/schemas/StorePostRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Post created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Post")
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
     * @param \App\Http\Requests\API\V1\Post\StorePostRequest $request
     * @param \App\Models\API\V1\Book $book
     * @return JsonResponse|mixed
     */
    public function storeByBook(StorePostRequest $request, Book $book): JsonResponse
    {
        $storePostDto = StorePostDTO::fromRequest($request);

        $newPost = $this->postService->storeByBook($storePostDto, $book);

        return response()
            ->json(
                new PostResource($newPost),
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * @OA\Post(
     * path="/api/v1/users/{user_slug}/posts",
     * summary="Create a new post for a specific user",
     * description="Creates a new post associated with the given user.",
     * operationId="postStoreByUser",
     * tags={"Users", "Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="user_slug",
     * in="path",
     * description="The slug of the user to create the post for.",
     * required=true,
     * @OA\Schema(type="string", example="johndoe")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Post data to store.",
     * @OA\JsonContent(ref="#/components/schemas/StorePostRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Post created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Post")
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
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\API\V1\Post\StorePostRequest $request
     * @param \App\Models\User $user
     * @return JsonResponse|mixed
     */
    public function storeByUser(StorePostRequest $request, User $user): JsonResponse
    {
        $storePostDto = StorePostDTO::fromRequest($request);

        $newPost = $this->postService->storeByUser($storePostDto, $user);

        return response()
            ->json(
                new PostResource($newPost),
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * @OA\Get(
     * path="/api/v1/posts/{post_slug}",
     * summary="Get a single post by its slug",
     * operationId="postShow",
     * tags={"Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="post_slug",
     * in="path",
     * description="The slug of the post to retrieve.",
     * required=true,
     * @OA\Schema(type="string", example="my-reading-update")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Post")
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
     * description="Post not found."
     * )
     * )
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
     * @OA\Put(
     * path="/api/v1/posts/{post_slug}",
     * summary="Update an existing post",
     * operationId="postUpdate",
     * tags={"Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="post_slug",
     * in="path",
     * description="The slug of the post to update.",
     * required=true,
     * @OA\Schema(type="string", example="my-reading-update")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Updated post data.",
     * @OA\JsonContent(ref="#/components/schemas/UpdatePostRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Post updated successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Post")
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
     * description="Post not found."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
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
     * @OA\Delete(
     * path="/api/v1/posts/{post_slug}",
     * summary="Delete a post by its slug",
     * operationId="postDestroy",
     * tags={"Posts"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="post_slug",
     * in="path",
     * description="The slug of the post to delete.",
     * required=true,
     * @OA\Schema(type="string", example="my-reading-update")
     * ),
     * @OA\Response(
     * response=204,
     * description="Post deleted successfully (No Content)",
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
     * description="Post not found."
     * )
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
