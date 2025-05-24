<?php

namespace App\Services\API\V1;

use App\Enums\Permissions\AuthorPermissions;
use App\Enums\Permissions\BookPermissions;
use App\Enums\Permissions\CommentPermissions;
use App\Enums\Permissions\GenrePermissions;
use App\Enums\Permissions\PostPermissions;
use App\Enums\Permissions\ReviewPermissions;
use App\Enums\Permissions\TagPermissions;
use App\Enums\Permissions\UserPermissions;

/**
 * Service for RolePermissionAssignerService
 */
class RolePermissionAssignerService
{
    public static function forAdmin(): array
    {
        return self::getPermissionsForAdminRole();
    }

    public static function forAuthor(): array
    {
        return self::getPermissionsForAuthorRole();
    }

    public static function forEditor(): array
    {
        return self::getPermissionsForEditorRole();
    }

    public static function forModerator(): array
    {
        return self::getPermissionsForModerator();
    }

    public static function forUser(): array
    {
        return self::getPermissionsForUser();
    }

    private static function getPermissionsForAdminRole(): array
    {
        return collect()
            ->merge(AuthorPermissions::getAllValues())
            ->merge(BookPermissions::getAllValues())
            ->merge(CommentPermissions::getAllValues())
            ->merge(GenrePermissions::getAllValues())
            ->merge(PostPermissions::getAllValues())
            ->merge(ReviewPermissions::getAllValues())
            ->merge(TagPermissions::getAllValues())
            ->merge(UserPermissions::getAllValues())
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private static function getPermissionsForAuthorRole(): array
    {
        return collect([
            AuthorPermissions::EDIT_AUTHORS->getValue(),
            AuthorPermissions::VIEW_ANY_AUTHORS->getValue(),
            AuthorPermissions::VIEW_ONE_AUTHOR->getValue(),
            BookPermissions::VIEW_ANY_BOOKS->getValue(),
            BookPermissions::VIEW_ONE_BOOK->getValue(),
            CommentPermissions::VIEW_ANY_COMMENTS->getValue(),
            CommentPermissions::VIEW_ONE_COMMENT->getValue(),
            GenrePermissions::VIEW_ANY_GENRES->getValue(),
            GenrePermissions::VIEW_ONE_GENRE->getValue(),
            PostPermissions::VIEW_ANY_POSTS->getValue(),
            PostPermissions::VIEW_ONE_POST->getValue(),
            ReviewPermissions::VIEW_ANY_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ONE_REVIEW->getValue(),
            TagPermissions::VIEW_ANY_TAGS->getValue(),
            TagPermissions::VIEW_ONE_TAG->getValue(),
            UserPermissions::VIEW_ANY_USERS->getValue(),
            UserPermissions::VIEW_ONE_USER->getValue(),
        ])->unique()
        ->sort()
        ->values()
        ->all();
    }

    private static function getPermissionsForEditorRole(): array
    {
        return collect([
            AuthorPermissions::EDIT_AUTHORS,
            AuthorPermissions::VIEW_ANY_AUTHORS,
            AuthorPermissions::VIEW_ONE_AUTHOR,
            BookPermissions::EDIT_BOOKS->getValue(),
            BookPermissions::VIEW_ANY_BOOKS->getValue(),
            BookPermissions::VIEW_ONE_BOOK->getValue(),
            CommentPermissions::EDIT_COMMENTS->getValue(),
            CommentPermissions::VIEW_ANY_COMMENTS->getValue(),
            CommentPermissions::VIEW_ONE_COMMENT->getValue(),
            GenrePermissions::EDIT_GENRES->getValue(),
            GenrePermissions::VIEW_ANY_GENRES->getValue(),
            GenrePermissions::VIEW_ONE_GENRE->getValue(),
            PostPermissions::EDIT_POSTS->getValue(),
            PostPermissions::VIEW_ANY_POSTS->getValue(),
            PostPermissions::VIEW_ONE_POST->getValue(),
            ReviewPermissions::EDIT_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ANY_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ONE_REVIEW->getValue(),
            TagPermissions::EDIT_TAGS->getValue(),
            TagPermissions::VIEW_ANY_TAGS->getValue(),
            TagPermissions::VIEW_ONE_TAG->getValue(),
            UserPermissions::EDIT_USERS->getValue(),
            UserPermissions::VIEW_ANY_USERS->getValue(),
            UserPermissions::VIEW_ONE_USER->getValue(),
        ])->unique()
        ->sort()
        ->values()
        ->all();
    }

    private static function getPermissionsForModerator(): array
    {
        return collect([
            AuthorPermissions::VIEW_ANY_AUTHORS->getValue(),
            AuthorPermissions::VIEW_ONE_AUTHOR->getValue(),
            BookPermissions::VIEW_ANY_BOOKS->getValue(),
            BookPermissions::VIEW_ONE_BOOK->getValue(),
            CommentPermissions::CREATE_COMMENTS->getValue(),
            CommentPermissions::DELETE_COMMENTS->getValue(),
            CommentPermissions::EDIT_COMMENTS->getValue(),
            CommentPermissions::VIEW_ANY_COMMENTS->getValue(),
            CommentPermissions::VIEW_ONE_COMMENT->getValue(),
            GenrePermissions::VIEW_ANY_GENRES->getValue(),
            GenrePermissions::VIEW_ONE_GENRE->getValue(),
            PostPermissions::CREATE_POSTS->getValue(),
            PostPermissions::DELETE_POSTS->getValue(),
            PostPermissions::EDIT_POSTS->getValue(),
            PostPermissions::VIEW_ANY_POSTS->getValue(),
            PostPermissions::VIEW_ONE_POST->getValue(),
            ReviewPermissions::CREATE_REVIEWS->getValue(),
            ReviewPermissions::DELETE_REVIEWS->getValue(),
            ReviewPermissions::DELETE_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ANY_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ONE_REVIEW->getValue(),
            TagPermissions::VIEW_ANY_TAGS->getValue(),
            TagPermissions::VIEW_ONE_TAG->getValue(),
            UserPermissions::DELETE_USERS->getValue(),
            UserPermissions::VIEW_ANY_USERS->getValue(),
            UserPermissions::VIEW_ONE_USER->getValue(),
        ])->unique()
        ->sort()
        ->values()
        ->all();
    }

    private static function getPermissionsForUser(): array
    {
        return collect([
            AuthorPermissions::VIEW_ANY_AUTHORS->getValue(),
            AuthorPermissions::VIEW_ONE_AUTHOR->getValue(),
            BookPermissions::VIEW_ANY_BOOKS->getValue(),
            BookPermissions::VIEW_ONE_BOOK->getValue(),
            CommentPermissions::CREATE_COMMENTS->getValue(),
            CommentPermissions::DELETE_COMMENTS->getValue(),
            CommentPermissions::EDIT_COMMENTS->getValue(),
            CommentPermissions::VIEW_ANY_COMMENTS->getValue(),
            CommentPermissions::VIEW_ONE_COMMENT->getValue(),
            GenrePermissions::VIEW_ANY_GENRES->getValue(),
            GenrePermissions::VIEW_ONE_GENRE->getValue(),
            PostPermissions::CREATE_POSTS->getValue(),
            PostPermissions::DELETE_POSTS->getValue(),
            PostPermissions::EDIT_POSTS->getValue(),
            PostPermissions::VIEW_ANY_POSTS->getValue(),
            PostPermissions::VIEW_ONE_POST->getValue(),
            ReviewPermissions::CREATE_REVIEWS->getValue(),
            ReviewPermissions::DELETE_REVIEWS->getValue(),
            ReviewPermissions::EDIT_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ANY_REVIEWS->getValue(),
            ReviewPermissions::VIEW_ONE_REVIEW->getValue(),
            TagPermissions::VIEW_ANY_TAGS->getValue(),
            TagPermissions::VIEW_ONE_TAG->getValue(),
            UserPermissions::VIEW_ANY_USERS->getValue(),
            UserPermissions::VIEW_ONE_USER->getValue(),
        ])->unique()
        ->sort()
        ->values()
        ->all();
    }
}
