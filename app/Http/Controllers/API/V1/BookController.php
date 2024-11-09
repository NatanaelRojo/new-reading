<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Resources\API\V1\Book\BookResource;
use App\Models\API\V1\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $books = Book::all();

        return response()->json(BookResource::collection($books), JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $newBook = Book::query()
        ->create($request->validated());

        return response()->json(new BookResource($newBook), JsonResponse::HTTP_OK);
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
        $book->update($request->validated());

        return response()->json(new BookResource($book), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
