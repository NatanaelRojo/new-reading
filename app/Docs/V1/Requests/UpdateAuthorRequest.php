<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateAuthorRequest",
 * title="Update Author Request",
 * description="Request body for updating an existing author. All fields are optional, providing only the ones to be updated.",
 * @OA\Property(
 * property="first_name",
 * type="string",
 * description="The updated first name of the author",
 * maxLength=255,
 * example="Janet"
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * description="The updated last name of the author",
 * maxLength=255,
 * example="Smith"
 * ),
 * @OA\Property(
 * property="nationality",
 * type="string",
 * description="The updated nationality of the author",
 * maxLength=255,
 * example="British"
 * ),
 * @OA\Property(
 * property="biography",
 * type="string",
 * description="An updated biography of the author",
 * example="Janet Smith is a celebrated author known for her historical fiction."
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * format="url",
 * description="Updated URL to the author's profile image",
 * example="https://example.com/images/janet_smith_new.jpg"
 * )
 * )
 */
class UpdateAuthorRequest
{
}
