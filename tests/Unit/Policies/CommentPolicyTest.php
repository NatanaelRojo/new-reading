<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Traits\Test\RolesAndUsers;
use Tests\TestCase as TestsTestCase;

class CommentPolicyTest extends TestsTestCase
{
    use RolesAndUsers;

    /**
     * Test that an administrator can view any comments.
     * @return void
     */
    public function test_viewAny_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $this->assertTrue($user->can('viewAny', Comment::class));
    }

    /**
     * Test that an editor can view any comments.
     * @return void
     */
    public function test_viewAny_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $this->assertTrue($user->can('viewAny', Comment::class));
    }

    /**
     * Test that an author can view any comments.
     * @return void
     */
    public function test_viewAny_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($user->can('viewAny', Comment::class));
    }

    /**
     * Test that a moderator can view any comments.
     * @return void
     */
    public function test_viewAny_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $this->assertTrue($user->can('viewAny', Comment::class));
    }

    /**
     * Test that a regular user can view any comments.
     * @return void
     */
    public function test_viewAny_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $this->assertTrue($user->can('viewAny', Comment::class));
    }

    /**
     * Test that a guest cannot view any comments.
     * @return void
     */
    public function test_viewAny_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('viewAny', Comment::class));
    }

    /**
     * Test that an administrator can view a specific comment.
     * @return void
     */
    public function test_view_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertTrue($user->can('view', $comment));
    }

    /**
     * Test that an editor can view a specific comment.
     * @return void
     */
    public function test_view_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $comment = Comment::factory()->make();
        $this->assertTrue($user->can('view', $comment));
    }

    /**
     * Test that an author can view a specific comment.
     * @return void
     */
    public function test_view_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $comment = Comment::factory()->make();
        $this->assertTrue($user->can('view', $comment));
    }

    /**
     * Test that a moderator can view a specific comment.
     * @return void
     */
    public function test_view_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $comment = Comment::factory()->make();
        $this->assertTrue($user->can('view', $comment));
    }

    /**
     * Test that a regular user can view a specific comment.
     * @return void
     */
    public function test_view_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = Comment::factory()->make();
        $this->assertTrue($user->can('view', $comment));
    }

    /**
     * Test that a guest cannot view a specific comment.
     * @return void
     */
    public function test_view_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('view', $comment));
    }

    /**
     * Test that an administrator can create a comment.
     * @return void
     */
    public function test_create_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $this->assertTrue($user->can('create', Comment::class));
    }

    /**
     * Test that an editor cannot create a comment.
     * @return void
     */
    public function test_create_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $this->assertFalse($user->can('create', Comment::class));
    }

    /**
     * Test that an author can create a comment.
     * @return void
     */
    public function test_create_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($user->can('create', Comment::class));
    }

    /**
     * Test that a moderator can create a comment.
     * @return void
     */
    public function test_create_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $this->assertTrue($user->can('create', Comment::class));
    }

    /**
     * Test that a regular user can create a comment.
     * @return void
     */
    public function test_create_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $this->assertTrue($user->can('create', Comment::class));
    }

    /**
     * Test that a guest cannot create a comment.
     * @return void
     */
    public function test_create_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('create', Comment::class));
    }

    /**
     * Test that an administrator can update any comment.
     * @return void
     */
    public function test_update_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertTrue($user->can('update', $comment));
    }

    /**
     * Test that a moderator cannot update a comment.
     * @return void
     */
    public function test_update_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('update', $comment));
    }

    /**
     * Test that a user can update their own comment.
     * @return void
     */
    public function test_update_user_own_comment(): void
    {
        $commenterUser = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = Comment::factory()->make();
        $comment->user_id = $commenterUser->id;
        $this->assertTrue($commenterUser->can('update', $comment));
    }

    /**
     * Test that a user cannot update another user's comment.
     * @return void
     */
    public function test_update_user_other_comment(): void
    {
        $commenterUser = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $anotherUser = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = Comment::factory()->make();
        $comment->user_id = $anotherUser->id;
        $this->assertFalse($commenterUser->can('update', $comment));
    }

    /**
     * Test that an editor cannot update a comment.
     * @return void
     */
    public function test_update_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('update', $comment));
    }

    /**
     * Test that an author cannot update a comment.
     * @return void
     */
    public function test_update_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('update', $comment));
    }

    /**
     * Test that a guest cannot update a comment.
     * @return void
     */
    public function test_update_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('update', $comment));
    }

    /**
     * Test that an administrator can delete any comment.
     * @return void
     */
    public function test_delete_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertTrue($user->can('delete', $comment));
    }

    /**
     * Test that a moderator can delete any comment.
     * @return void
     */
    public function test_delete_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $comment = Comment::factory()->make();
        $this->assertTrue($user->can('delete', $comment));
    }

    /**
     * Test that a user can delete their own comment.
     * @return void
     */
    public function test_delete_user_own_comment(): void
    {
        $commenterUser = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = Comment::factory()->make();
        $comment->user_id = $commenterUser->id;
        $this->assertTrue($commenterUser->can('delete', $comment));
    }

    /**
     * Test that a user cannot delete another user's comment.
     * @return void
     */
    public function test_delete_user_other_comment(): void
    {
        $commenterUser = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $anotherUser = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = Comment::factory()->make();
        $comment->user_id = $anotherUser->id;
        $this->assertFalse($commenterUser->can('delete', $comment));
    }

    /**
     * Test that an editor cannot delete a comment.
     * @return void
     */
    public function test_delete_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('delete', $comment));
    }

    /**
     * Test that an author cannot delete a comment.
     * @return void
     */
    public function test_delete_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('delete', $comment));
    }

    /**
     * Test that a guest cannot delete a comment.
     * @return void
     */
    public function test_delete_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('delete', $comment));
    }

    /**
     * Test that an administrator can restore any comment.
     * @return void
     */
    public function test_restore_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertTrue($user->can('restore', $comment));
    }

    /**
     * Test that a moderator can restore any comment.
     * @return void
     */
    public function test_restore_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $comment = Comment::factory()->make();
        $this->assertTrue($user->can('restore', $comment));
    }

    /**
     * Test that an editor cannot restore a comment.
     * @return void
     */
    public function test_restore_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('restore', $comment));
    }

    /**
     * Test that an author cannot restore a comment.
     * @return void
     */
    public function test_restore_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('restore', $comment));
    }

    /**
     * Test that a user cannot restore a comment.
     * @return void
     */
    public function test_restore_as_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('restore', $comment));
    }

    /**
     * Test that a guest cannot restore a comment.
     * @return void
     */
    public function test_restore_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('restore', $comment));
    }

    /**
     * Test that an administrator can permanently delete any comment.
     * @return void
     */
    public function test_forceDelete_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertTrue($user->can('forceDelete', $comment));
    }

    /**
     * Test that a moderator cannot permanently delete a comment.
     * @return void
     */
    public function test_forceDelete_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('forceDelete', $comment));
    }

    /**
     * Test that an editor cannot permanently delete a comment.
     * @return void
     */
    public function test_forceDelete_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('forceDelete', $comment));
    }

    /**
     * Test that an author cannot permanently delete a comment.
     * @return void
     */
    public function test_forceDelete_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('forceDelete', $comment));
    }

    /**
     * Test that a user cannot permanently delete a comment.
     * @return void
     */
    public function test_forceDelete_as_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('forceDelete', $comment));
    }

    /**
     * Test that a guest cannot permanently delete a comment.
     * @return void
     */
    public function test_forceDelete_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $comment = $this->createMock(Comment::class);
        $this->assertFalse($user->can('forceDelete', $comment));
    }

    /**
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles([]);

        $this->assertFalse($user->can('viewAny', Comment::class));
        $this->assertFalse($user->can('view', $this->createMock(Comment::class)));
        $this->assertFalse($user->can('create', Comment::class));
        $this->assertFalse($user->can('update', $this->createMock(Comment::class)));
        $this->assertFalse($user->can('delete', $this->createMock(Comment::class)));
        $this->assertFalse($user->can('restore', $this->createMock(Comment::class)));
        $this->assertFalse($user->can('forceDelete', $this->createMock(Comment::class)));
    }
}
