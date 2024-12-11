<?php

namespace App\Http\Controllers\API\V1\Controllers;

use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Resources\API\V1\Genre\GenreResource;
use App\Models\API\V1\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $genres = Genre::with(['books'])
            ->get();

        return response()->json(GenreResource::collection($genres), JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $newGenre = Genre::query()
            ->create($request->validated());

        return response()->json(new GenreResource($newGenre), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre): JsonResponse
    {
        return response()->json(new GenreResource($genre), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $genre->update($request->validated());

        return response()->json(new GenreResource($genre), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre): JsonResponse
    {
        $genre->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
