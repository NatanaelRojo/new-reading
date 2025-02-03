<?php

namespace App\Http\Resources\API\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'profile_image_url' => $this->profile_image_url,
            'full_name' => $this->full_name,
            // 'first_name' => $this->first_name,
            // 'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'biography' => $this->biography,
            'username' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'followers' => UserResource::collection($this->whenLoaded('followers')),
            'following' => UserResource::collection($this->whenLoaded('following')),
        ];
    }
}
