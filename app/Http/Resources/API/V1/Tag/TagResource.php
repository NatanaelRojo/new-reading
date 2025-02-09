<?php

namespace App\Http\Resources\API\V1\Tag;

use App\Http\Resources\API\V1\Book\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'books' => BookResource::collection($this->whenLoaded('books')),
        ];
    }
}
