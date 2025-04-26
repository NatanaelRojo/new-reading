<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Genre\StoreGenreDTO;
use App\DataTransferObjects\API\V1\Genre\UpdateGenreDTO;
use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Resources\API\V1\Genre\GenreResource;
use App\Models\API\V1\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GenreController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $genres = Genre::with(['books'])
            ->paginate($request->query('per_page', 10));

        return GenreResource::collection($genres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $storeGenreDto = new StoreGenreDTO(...$request->validated());

        $newGenre = Genre::query()
            ->create($storeGenreDto->toArray());

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
        $updateGenreDto = new UpdateGenreDTO(...$request->validated());

        $genre->update($updateGenreDto->toArray());

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
