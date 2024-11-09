<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Models\API\V1\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Author::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $newAuthor = Author::query()
        ->create($request->all());

        return response()->json($newAuthor, JsonResponse::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author): JsonResponse
    {
        return response()->json($author, JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author): JsonResponse
    {
        $author->update($request->all());

        return response()->json($author, JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
