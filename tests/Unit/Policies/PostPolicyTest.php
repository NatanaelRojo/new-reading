<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use App\Traits\Test\RolesAndUsers;
use PHPUnit\Framework\TestCase;
use Spatie\Permission\Models\Role;
use Tests\TestCase as TestsTestCase; // Use Tests\TestCase for database/role setup

class PostPolicyTest extends TestsTestCase
{
    use RolesAndUsers; // Provides createUserWithRoles method

    protected PostPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear any mocks from previous tests if necessary (though TestCase usually handles this)
        // Set up a new policy instance for each test
        $this->policy = new PostPolicy();
    }

    // --- Tests for `viewAny` method (listing posts) ---

    /**
     * Test that an admin can view any posts.
     * @return void
     */
    public function test_an_admin_can_view_any_posts(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($user->can('viewAny', Post::class));
    }

    /**
     * Test that an editor can view any posts.
     * @return void
     */
    public function test_an_editor_can_view_any_posts(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that an author can view any posts.
     * @return void
     */
    public function test_an_author_can_view_any_posts(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a moderator can view any posts.
     * @return void
     */
    public function test_a_moderator_can_view_any_posts(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a regular user can view any posts.
     * @return void
     */
    public function test_a_regular_user_can_view_any_posts(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a guest cannot view any posts.
     * @return void
     */
    public function test_a_guest_cannot_view_any_posts(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles (guest)
        $this->assertFalse($this->policy->viewAny($user));
    }

    // --- Tests for `view` method (single post) ---

    /**
     * Test that an admin can view a specific post.
     * @return void
     */
    public function test_an_admin_can_view_a_specific_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($user->can('view', $post));
    }

    /**
     * Test that an editor can view a specific post.
     * @return void
     */
    public function test_an_editor_can_view_a_specific_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->view($user, $post));
    }

    /**
     * Test that an author can view a specific post.
     * @return void
     */
    public function test_an_author_can_view_a_specific_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->view($user, $post));
    }

    /**
     * Test that a moderator can view a specific post.
     * @return void
     */
    public function test_a_moderator_can_view_a_specific_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->view($user, $post));
    }

    /**
     * Test that a regular user can view a specific post.
     * @return void
     */
    public function test_a_regular_user_can_view_a_specific_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->view($user, $post));
    }

    /**
     * Test that a guest cannot view a specific post.
     * @return void
     */
    public function test_a_guest_cannot_view_a_specific_post(): void
    {
        $user = $this->createUserWithRoles([]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->view($user, $post));
    }

    // --- Tests for `create` method ---

    /**
     * Test that an admin can create a post.
     * @return void
     */
    public function test_an_admin_can_create_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($user->can('create', Post::class));
    }

    /**
     * Test that an author can create a post.
     * @return void
     */
    public function test_an_author_can_create_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($user->can('create', Post::class));
    }

    /**
     * Test that an editor cannot create a post.
     * @return void
     */
    public function test_an_editor_cannot_create_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a moderator cannot create a post.
     * @return void
     */
    public function test_a_moderator_cannot_create_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a regular user cannot create a post.
     * @return void
     */
    public function test_a_regular_user_cannot_create_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a guest cannot create a post.
     * @return void
     */
    public function test_a_guest_cannot_create_a_post(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($this->policy->create($user));
    }

    // --- Tests for `update` method ---

    /**
     * Test that an admin can update any post.
     * @return void
     */
    public function test_an_admin_can_update_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($user->can('update', $post));
    }

    /**
     * Test that an editor can update any post.
     * @return void
     */
    public function test_an_editor_can_update_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $post = Post::factory()->make();
        $this->assertTrue($user->can('update', $post));
    }

    /**
     * Test that an author can update their own post.
     * @return void
     */
    public function test_an_author_can_update_their_own_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = Post::factory()->make();
        $post->user_id = $user->id;
        $this->assertTrue($user->can('update', $post));
    }

    /**
     * Test that an author cannot update another author's post.
     * @return void
     */
    public function test_an_author_cannot_update_another_authors_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $anotherUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = Post::factory()->make();
        $post->user_id = $anotherUser->id;
        $this->assertFalse($user->can('update', $post));
    }

    /**
     * Test that a moderator cannot update a post.
     * @return void
     */
    public function test_a_moderator_can_update_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $post = Post::factory()->make();
        $this->assertTrue($user->can('update', $post));
    }

    /**
     * Test that a regular user cannot update a post.
     * @return void
     */
    public function test_a_regular_user_cannot_update_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->update($user, $post));
    }

    /**
     * Test that a guest cannot update a post.
     * @return void
     */
    public function test_a_guest_cannot_update_a_post(): void
    {
        $user = $this->createUserWithRoles([]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->update($user, $post));
    }

    // --- Tests for `delete` method ---

    /**
     * Test that an admin can delete any post.
     * @return void
     */
    public function test_an_admin_can_delete_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $post = Post::factory()->make();
        $this->assertTrue($user->can('delete', $post));
    }

    /**
     * Test that a moderator can delete any post.
     * @return void
     */
    public function test_a_moderator_can_delete_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->delete($user, $post));
    }

    /**
     * Test that an author can delete their own post.
     * @return void
     */
    public function test_an_author_can_delete_their_own_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = Post::factory()->make();
        $post->user_id = $user->id;
        $this->assertTrue($user->can('delete', $post));
    }

    /**
     * Test that an author cannot delete another author's post.
     * @return void
     */
    public function test_an_author_cannot_delete_another_authors_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $anotherUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = $this->createMock(Post::class);
        $post->user_id = $anotherUser->id; // Post owned by another author
        $this->assertFalse($this->policy->delete($user, $post));
    }

    /**
     * Test that an editor cannot delete a post.
     * @return void
     */
    public function test_an_editor_cannot_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->delete($user, $post));
    }

    /**
     * Test that a regular user cannot delete a post.
     * @return void
     */
    public function test_a_regular_user_cannot_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->delete($user, $post));
    }

    /**
     * Test that a guest cannot delete a post.
     * @return void
     */
    public function test_a_guest_cannot_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->delete($user, $post));
    }

    // --- Tests for `restore` method ---

    /**
     * Test that an admin can restore any post.
     * @return void
     */
    public function test_an_admin_can_restore_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->restore($user, $post));
    }

    /**
     * Test that a moderator can restore any post.
     * @return void
     */
    public function test_a_moderator_can_restore_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->restore($user, $post));
    }

    /**
     * Test that an editor cannot restore a post.
     * @return void
     */
    public function test_an_editor_cannot_restore_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->restore($user, $post));
    }

    /**
     * Test that an author cannot restore a post.
     * @return void
     */
    public function test_an_author_cannot_restore_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->restore($user, $post));
    }

    /**
     * Test that a regular user cannot restore a post.
     * @return void
     */
    public function test_a_regular_user_cannot_restore_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->restore($user, $post));
    }

    /**
     * Test that a guest cannot restore a post.
     * @return void
     */
    public function test_a_guest_cannot_restore_a_post(): void
    {
        $user = $this->createUserWithRoles([]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->restore($user, $post));
    }

    // --- Tests for `forceDelete` method ---

    /**
     * Test that an admin can force delete any post.
     * @return void
     */
    public function test_an_admin_can_force_delete_any_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $post = $this->createMock(Post::class);
        $this->assertTrue($this->policy->forceDelete($user, $post));
    }

    /**
     * Test that a moderator cannot force delete a post.
     * @return void
     */
    public function test_a_moderator_cannot_force_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->forceDelete($user, $post));
    }

    /**
     * Test that an editor cannot force delete a post.
     * @return void
     */
    public function test_an_editor_cannot_force_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->forceDelete($user, $post));
    }

    /**
     * Test that an author cannot force delete a post.
     * @return void
     */
    public function test_an_author_cannot_force_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->forceDelete($user, $post));
    }

    /**
     * Test that a regular user cannot force delete a post.
     * @return void
     */
    public function test_a_regular_user_cannot_force_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->forceDelete($user, $post));
    }

    /**
     * Test that a guest cannot force delete a post.
     * @return void
     */
    public function test_a_guest_cannot_force_delete_a_post(): void
    {
        $user = $this->createUserWithRoles([]);
        $post = $this->createMock(Post::class);
        $this->assertFalse($this->policy->forceDelete($user, $post));
    }

    // --- Comprehensive Test for No Permissions ---

    /**
     * Test that a user with no roles/permissions cannot perform any policy action.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles
        $post = $this->createMock(Post::class);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, $post));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, $post));
        $this->assertFalse($this->policy->delete($user, $post));
        $this->assertFalse($this->policy->restore($user, $post));
        $this->assertFalse($this->policy->forceDelete($user, $post));
    }
}
