<?php

namespace App\DataTransferObjects\API\V1\Comment;

use App\DataTransferObjects\Base\BaseApiDTO;
use App\Http\Requests\API\V1\Comment\StoreCommentRequest;
use App\Models\API\V1\Post;
use App\Models\User;
use Illuminate\Http\Request;

class StoreCommentByPostDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $body,
        public readonly User $user,
        public readonly Post $post,
        public readonly int $commentable_id,
        public readonly string $commentable_type,
    ) {
    }

    /**
     * Create a new instance from a request.
     *
     * @param \Illuminate\Http\Request $request
     * @return StoreCommentByPostDTO
     */
    public static function fromRequest(Request $request): static
    {
        return new StoreCommentByPostDTO(
            body: $request->body,
            user: $request->user(),
            post: $request->post,
            commentable_id: $request->post->id,
            commentable_type: $request->post::class,
        );
    }

    public function toArray(bool $includeNulls = false): array
    {
        return [
            'body' => $this->body,
            'user_id' => $this->user->id,
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
        ];
    }
}
