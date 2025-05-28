<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Book\FilterBookDTO;
use App\DataTransferObjects\API\V1\Book\StoreBookDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookReadingProgressDTO;
use App\DataTransferObjects\API\V1\Book\UpdateBookTagDTO;
use App\Http\Requests\API\V1\Book\FilterBookRequest;
use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookReadingProgressRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookTagRequest;
use App\Http\Resources\API\V1\Book\BookResource;
use App\Models\API\V1\Book;
use App\Services\API\V1\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

/**
 * @OA\Tag(
 * name="Books",
 * description="API Endpoints for Books"
 * )
 */
class BookController
{
    /**
     * Create a new controller instance.
     *
     * @param BookService $bookService
     */
    public function __construct(private BookService $bookService)
    {
    }

    /**
     * @OA\Get(
     * path="/api/v1/books",
     * summary="Get a list of books with optional filtering and pagination",
     * operationId="bookIndex",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="title",
     * in="query",
     * description="Filter books by title (partial match).",
     * required=false,
     * @OA\Schema(type="string", nullable=true, example="galaxy")
     * ),
     * @OA\Parameter(
     * name="author_name",
     * in="query",
     * description="Filter books by author's full name (partial match).",
     * required=false,
     * @OA\Schema(type="string", nullable=true, example="douglas adams")
     * ),
     * @OA\Parameter(
     * name="genre_name",
     * in="query",
     * description="Filter books by genre name (partial match).",
     * required=false,
     * @OA\Schema(type="string", nullable=true, example="science fiction")
     * ),
     * @OA\Parameter(
     * name="tag_name",
     * in="query",
     * description="Filter books by tag name (partial match).",
     * required=false,
     * @OA\Schema(type="string", nullable=true, example="classics")
     * ),
     * @OA\Parameter(
     * name="year",
     * in="query",
     * description="Filter books by publication year. Must be a 4-digit integer.",
     * required=false,
     * @OA\Schema(type="integer", format="int32", minimum=1000, maximum=\App\Docs\V1\MAX_PUBLICATION_YEAR, nullable=true, example=1979)
     * ),
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Number of items to return per page.",
     * required=false,
     * @OA\Schema(type="integer", format="int32", minimum=1, maximum=50, nullable=true, example=15)
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="The page number to retrieve.",
     * required=false,
     * @OA\Schema(type="integer", format="int32", minimum=1, nullable=true, example=1)
     * ),
     * @OA\Parameter(
     * name="include",
     * in="query",
     * description="Include related resources (e.g., authors, genres, reviews). Use comma-separated values.",
     * required=false,
     * @OA\Schema(type="string", example="authors,genres")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Book")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * )
     * )
     */
    public function index(FilterBookRequest $request): AnonymousResourceCollection
    {
        $filterBookDto = FilterBookDTO::fromRequest($request);

        $books = $this->bookService->index($filterBookDto);

        return BookResource::collection($books);
    }

    /**
     * @OA\Post(
     * path="/api/v1/books",
     * summary="Create a new book",
     * operationId="bookStore",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Data to create a new book",
     * @OA\JsonContent(ref="#/components/schemas/StoreBookRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Book created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Book")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * )
     * )
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $storeBookDto = new StoreBookDTO(...$request->validated());

        $newBook = $this->bookService->store($storeBookDto);

        return response()->json(new BookResource($newBook), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     * path="/api/v1/books/{book}",
     * summary="Get a single book by slug",
     * operationId="bookShow",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book",
     * in="path",
     * description="Slug of the book to retrieve",
     * required=true,
     * @OA\Schema(type="string", example="the-lord-of-the-rings")
     * ),
     * @OA\Parameter(
     * name="include",
     * in="query",
     * description="Include related resources (e.g., authors, genres, reviews)",
     * required=false,
     * @OA\Schema(type="string", example="authors,genres,reviews")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Book")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=404,
     * description="Book not found",
     * )
     * )
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     * path="/api/v1/books/{book}",
     * summary="Update an existing book",
     * operationId="bookUpdate",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book",
     * in="path",
     * description="Slug of the book to update",
     * required=true,
     * @OA\Schema(type="string", example="the-lord-of-the-rings")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Data to update the book (all fields are optional but at least one must be provided)",
     * @OA\JsonContent(ref="#/components/schemas/UpdateBookRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Book updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Book")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=404,
     * description="Book not found",
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * )
     * )
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $updateBookDto = new UpdateBookDTO(...$request->validated());

        $this->bookService->update($updateBookDto, $book);

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Patch(
     * path="/api/v1/books/{book}/reading-progress",
     * summary="Update a user's reading progress for a specific book",
     * operationId="bookUpdateReadingProgress",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book",
     * in="path",
     * description="Slug of the book to update progress for",
     * required=true,
     * @OA\Schema(type="string", example="the-lord-of-the-rings")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="New reading progress data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateBookReadingProgressRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Reading progress updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Book")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=404,
     * description="Book or User-Book relationship not found",
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error (e.g., pages_read exceeds total pages)",
     * )
     * )
     */
    public function updateReadingProgress(UpdateBookReadingProgressRequest $request, Book $book): JsonResponse
    {
        $updateBookReadingProgressDto = UpdateBookReadingProgressDTO::fromRequest($request);

        $this->bookService->updateUserProgress($updateBookReadingProgressDto);

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Patch(
     * path="/api/v1/books/{book}/tag",
     * summary="Update a user's tag for a specific book",
     * operationId="bookUpdateTag",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book",
     * in="path",
     * description="Slug of the book to update tag for",
     * required=true,
     * @OA\Schema(type="string", example="the-lord-of-the-rings")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="New tag data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateBookTagRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Book tag updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Book")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=404,
     * description="Book or User-Book relationship not found",
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * )
     * )
     */
    public function updateTag(UpdateBookTagRequest $request, Book $book): JsonResponse
    {
        $updateBookTagDto = UpdateBookTagDTO::fromRequest($request);

        $this->bookService->updateTag($updateBookTagDto);

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/books/{book}",
     * summary="Delete a book",
     * operationId="bookDestroy",
     * tags={"Books"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="book",
     * in="path",
     * description="Slug of the book to delete",
     * required=true,
     * @OA\Schema(type="string", example="the-book-to-delete")
     * ),
     * @OA\Response(
     * response=204,
     * description="Book deleted successfully (No Content)",
     * @OA\MediaType(mediaType="application/json")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * ),
     * @OA\Response(
     * response=404,
     * description="Book not found",
     * )
     * )
     */
    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->destroy($book);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
