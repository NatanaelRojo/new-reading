<?php

namespace App\Http\Resources\API\V1\Author;

use App\Http\Resources\API\V1\Book\BookCollection;
use App\Http\Resources\API\V1\Book\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nationality' => $this->nationality,
            'date_of_birth' => $this->date_of_birth,
            'biography' => $this->biography,
            'image_url' => $this->image_url,
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
