<?php

namespace App\Http\Resources\API\V1\Book;

use App\Http\Resources\API\V1\Author\AuthorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'synopsis' => $this->synopsis,
            'pages_amount' => $this->pages_amount,
            'chapters_amount' => $this->chapters_amount,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
        ];

    }
}
