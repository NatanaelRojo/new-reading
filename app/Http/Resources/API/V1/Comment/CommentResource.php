<?php

namespace App\Http\Resources\API\V1\Comment;

use App\Http\Resources\API\V1\Book\BookResource;
use App\Http\Resources\API\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'body' => $this->body,
            'book' => BookResource::make($this->whenLoaded('book')),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
