<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Genre\StoreGenreDTO;
use App\DataTransferObjects\API\V1\Genre\UpdateGenreDTO;
use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Resources\API\V1\Genre\GenreResource;
use App\Models\API\V1\Genre;
use App\Services\API\V1\GenreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GenreController
{
    /**
     * Create a new controller instance.
     *
     * @param GenreService $genreService
     */
    public function __construct(private GenreService $genreService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $genres = $this->genreService->index($paginateDto);

        return GenreResource::collection($genres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $storeGenreDto = StoreGenreDTO::fromRequest($request);

        $newGenre = $this->genreService->store($storeGenreDto);

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
        $updateGenreDto = UpdateGenreDTO::fromRequest($request);

        $this->genreService->update($updateGenreDto, $genre);

        return response()->json(new GenreResource($genre), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre): JsonResponse
    {
        $this->genreService->destroy($genre);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
