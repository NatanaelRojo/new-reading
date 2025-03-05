<?php

namespace App\Http\Resources\API\V1\Review;

use App\Http\Resources\API\V1\Book\BookResource;
use App\Http\Resources\API\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'book' => new BookResource($this->whenLoaded('book')),
        ];
    }
}
