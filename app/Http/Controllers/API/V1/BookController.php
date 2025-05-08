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
     * Display a listing of the resource.
     */
    public function index(FilterBookRequest $request): AnonymousResourceCollection
    {
        $filterBookDto = FilterBookDTO::fromRequest($request);

        $books = $this->bookService->index($filterBookDto);

        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $storeBookDto = new StoreBookDTO(...$request->validated());

        $newBook = $this->bookService->store($storeBookDto);

        return response()->json(new BookResource($newBook), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $updateBookDto = new UpdateBookDTO(...$request->validated());

        $this->bookService->update($updateBookDto, $book);

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\API\V1\Book\UpdateBookReadingProgressRequest $request
     * @param \App\Models\API\V1\Book $book
     * @return JsonResponse|mixed
     */
    public function updateReadingProgress(UpdateBookReadingProgressRequest $request, Book $book): JsonResponse
    {
        $updateBookReadingProgressDto = UpdateBookReadingProgressDTO::fromRequest($request);

        $this->bookService->updateUserProgress($updateBookReadingProgressDto);

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\API\V1\Book\UpdateBookTagRequest $request
     * @param \App\Models\API\V1\Book $book
     * @return JsonResponse|mixed
     */
    public function updateTag(UpdateBookTagRequest $request, Book $book): JsonResponse
    {
        $updateBookTagDto = UpdateBookTagDTO::fromRequest($request);

        $this->bookService->updateTag($updateBookTagDto);

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->destroy($book);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
