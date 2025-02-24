<?php

namespace App\Http\Resources\API\V1\Book;

use App\Http\Resources\API\V1\Author\AuthorResource;
use App\Http\Resources\API\V1\Genre\GenreResource;
use App\Http\Resources\API\V1\Tag\TagResource;
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
            'isbn' => $this->isbn,
            'pages_amount' => $this->pages_amount,
            'chapters_amount' => $this->chapters_amount,
            'published_at' => $this->published_at,
            'image_url' => $this->image_url,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];

    }
}
