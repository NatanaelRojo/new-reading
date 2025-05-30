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
     * @OA\Get(
     * path="/api/v1/genres",
     * summary="Get a paginated list of all genres",
     * operationId="genreIndex",
     * tags={"Genres"},
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
     * @OA\Items(ref="#/components/schemas/Genre")
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

        $genres = $this->genreService->index($paginateDto);

        return GenreResource::collection($genres);
    }

    /**
     * @OA\Post(
     * path="/api/v1/genres",
     * summary="Create a new genre",
     * operationId="genreStore",
     * tags={"Genres"},
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Genre data to store",
     * @OA\JsonContent(ref="#/components/schemas/StoreGenreRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Genre created successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Genre")
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
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $storeGenreDto = StoreGenreDTO::fromRequest($request);

        $newGenre = $this->genreService->store($storeGenreDto);

        return response()->json(new GenreResource($newGenre), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     * path="/api/v1/genres/{genre_slug}",
     * summary="Get a single genre by its slug",
     * operationId="genreShow",
     * tags={"Genres"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="genre_slug",
     * in="path",
     * description="The slug of the genre to retrieve.",
     * required=true,
     * @OA\Schema(type="string", example="fantasy")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Genre")
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
     * description="Resource not found."
     * )
     * )
     * Display the specified resource.
     */
    public function show(Genre $genre): JsonResponse
    {
        return response()->json(new GenreResource($genre), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Put(
     * path="/api/v1/genres/{genre_slug}",
     * summary="Update an existing genre",
     * operationId="genreUpdate",
     * tags={"Genres"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="genre_slug",
     * in="path",
     * description="The slug of the genre to update.",
     * required=true,
     * @OA\Schema(type="string", example="fantasy")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Updated genre data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateGenreRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Genre updated successfully.",
     * @OA\JsonContent(ref="#/components/schemas/Genre")
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
     * description="Resource not found."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error."
     * )
     * )
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $updateGenreDto = UpdateGenreDTO::fromRequest($request);

        $this->genreService->update($updateGenreDto, $genre);

        return response()->json(new GenreResource($genre), JsonResponse::HTTP_OK);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/genres/{genre_slug}",
     * summary="Delete a genre by its slug",
     * operationId="genreDestroy",
     * tags={"Genres"},
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="genre_slug",
     * in="path",
     * description="The slug of the genre to delete.",
     * required=true,
     * @OA\Schema(type="string", example="fantasy")
     * ),
     * @OA\Response(
     * response=204,
     * description="Genre deleted successfully (No Content)",
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
     * description="Resource not found."
     * )
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre): JsonResponse
    {
        $this->genreService->destroy($genre);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
