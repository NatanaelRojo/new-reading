<?php

namespace App\Http\Controllers\API\V1\Controllers;

use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Resources\API\V1\Book\BookResource;
use App\Models\API\V1\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Throwable;

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

    public function filter(Request $request): JsonResponse
    {
        $filteredBooks = Book::query()
            ->with([
                'authors',
                'genres',
            ])->filter($request->all())
            ->get();

        return response()->json(BookResource::collection($filteredBooks), JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $newBook = Book::query()
        ->create($request->validated());

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
        $validatedDataForBook = Arr::except($request->validated(), [
            'tag_id',
            'user_id',
            'pages_read',
        ]);
        $book->update($validatedDataForBook);
        $this->handleBookUserUpdates($request, $book);

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

    /**
     * Handle book user updates like tag assignment and progress updates.
     *
     * @param \App\Http\Requests\API\V1\Book\UpdateBookRequest $request
     * @param \App\Models\API\V1\Book $book
     * @return void
     */
    private function handleBookUserUpdates(UpdateBookRequest $request, Book $book): void
    {
        if ($request->has('user_id')) {
            $book->assignTagToUser($request->user_id, $request->tag_id);
            $book->updateUserProgress($request->user_id, $request->pages_read);
        }
    }
}
