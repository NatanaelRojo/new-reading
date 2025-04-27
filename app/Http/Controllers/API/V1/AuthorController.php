<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Author\StoreAuthorDTO;
use App\DataTransferObjects\API\V1\Author\UpdateAuthorDTO;
use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Http\Requests\API\V1\Author\UpdateAuthorRequest;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Resources\API\V1\Author\AuthorResource;
use App\Models\API\V1\Author;
use App\Services\API\V1\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController
{
    /**
     * Create a new controller instance.
     *
     * @param AuthorService $authorService
    */
    public function __construct(private AuthorService $authorService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $per_page = $request->query('per_page', 10);

        $authors = $this->authorService->index($per_page);

        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Author $author): JsonResponse
    {
        return response()->json(new AuthorResource($author), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        $this->authorService->destroy($author);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
