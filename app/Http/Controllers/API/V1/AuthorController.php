<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Author\StoreAuthorDTO;
use App\DataTransferObjects\API\V1\Author\UpdateAuthorDTO;
use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Http\Requests\API\V1\Author\UpdateAuthorRequest;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Resources\API\V1\Author\AuthorResource;
use App\Models\API\V1\Author; // Your Author Model
use App\Services\API\V1\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Keep this for context if needed, though specific request classes are used
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 * name="Authors",
 * description="API Endpoints for Authors"
 * )
 */
class AuthorController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    /**
     * @OA\Get(
     * path="/api/authors",
     * operationId="getAuthorsList",
     * tags={"Authors"},
     * summary="Get a paginated list of authors",
     * description="Returns a paginated list of authors, optionally filtered.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Number of items per page (default: 10)",
     * required=false,
     * @OA\Schema(type="integer", format="int32", default=10)
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number for pagination",
     * required=false,
     * @OA\Schema(type="integer", format="int32", default=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * )
     * )
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $per_page = $request->query('per_page', 10);

        $authors = $this->authorService->index($per_page);

        return AuthorResource::collection($authors);
    }

    /**
     * @OA\Post(
     * path="/api/authors",
     * operationId="createAuthor",
     * tags={"Authors"},
     * summary="Create a new author",
     * description="Creates a new author record in the database.",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Author data to store",
     * @OA\JsonContent(ref="#/components/schemas/StoreAuthorRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Author created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Author")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     * )
     * )
     * )
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $storeAuthorDto = new StoreAuthorDTO(...$request->validated());

        $newAuthor = $this->authorService->store($storeAuthorDto);

        return response()
            ->json(
                new AuthorResource($newAuthor),
                JsonResponse::HTTP_CREATED
            );
    }

    /**
     * @OA\Get(
     * path="/api/authors/{author}",
     * operationId="getAuthorById",
     * tags={"Authors"},
     * summary="Get a single author by ID",
     * description="Returns a single author record.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="author",
     * in="path",
     * description="ID of the author to retrieve",
     * required=true,
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Author")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="Author not found"
     * )
     * )
     */
    public function show(Author $author): JsonResponse
    {
        return response()->json(new AuthorResource($author), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     * path="/api/authors/{author}",
     * operationId="updateAuthor",
     * tags={"Authors"},
     * summary="Update an existing author",
     * description="Updates an existing author record by ID.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="author",
     * in="path",
     * description="ID of the author to update",
     * required=true,
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Author data to update",
     * @OA\JsonContent(ref="#/components/schemas/UpdateAuthorRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Author updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Author")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="Author not found"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"email": {"The email has already been taken."}})
     * )
     * )
     * )
     */
    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $updateAuthorDto = new UpdateAuthorDTO(...$request->validated());

        $this->authorService->update($updateAuthorDto, $author);

        return response()
            ->json(
                new AuthorResource($author),
                JsonResponse::HTTP_OK
            );
    }

    /**
     * @OA\Delete(
     * path="/api/authors/{author}",
     * operationId="deleteAuthor",
     * tags={"Authors"},
     * summary="Delete an author",
     * description="Deletes an author record by ID.",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="author",
     * in="path",
     * description="ID of the author to delete",
     * required=true,
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=204,
     * description="Author deleted successfully (No Content)"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="Author not found"
     * )
     * )
     */
    public function destroy(Author $author): JsonResponse
    {
        $this->authorService->destroy($author);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
