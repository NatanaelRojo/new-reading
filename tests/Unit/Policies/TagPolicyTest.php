<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Tag;
use App\Models\User;
use App\Policies\TagPolicy;
use App\Traits\Test\RolesAndUsers;
use PHPUnit\Framework\TestCase;
use Spatie\Permission\Models\Role;
use Tests\TestCase as TestsTestCase; // Use Tests\TestCase for database/role setup

class TagPolicyTest extends TestsTestCase // Extend TestsTestCase for trait usage
{
    use RolesAndUsers; // Provides createUserWithRoles method

    protected TagPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp(); // Call parent setUp for Laravel setup
        $this->policy = new TagPolicy();
    }

    // --- Tests for `viewAny` method (listing tags) ---

    /**
     * Test that an admin can view any tags.
     * @return void
     */
    public function test_an_admin_can_view_any_tags(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($user->can('viewAny', Tag::class));
    }

    /**
     * Test that an editor can view any tags.
     * @return void
     */
    public function test_an_editor_can_view_any_tags(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that an author can view any tags.
     * @return void
     */
    public function test_an_author_can_view_any_tags(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a moderator can view any tags.
     * @return void
     */
    public function test_a_moderator_can_view_any_tags(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a regular user can view any tags.
     * @return void
     */
    public function test_a_regular_user_can_view_any_tags(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a guest cannot view any tags.
     * @return void
     */
    public function test_a_guest_cannot_view_any_tags(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles (guest)
        $this->assertFalse($this->policy->viewAny($user));
    }

    // --- Tests for `view` method (single tag) ---

    /**
     * Test that an admin can view a specific tag.
     * @return void
     */
    public function test_an_admin_can_view_a_specific_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($user->can('view', $tag));
    }

    /**
     * Test that an editor can view a specific tag.
     * @return void
     */
    public function test_an_editor_can_view_a_specific_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->view($user, $tag));
    }

    /**
     * Test that an author can view a specific tag.
     * @return void
     */
    public function test_an_author_can_view_a_specific_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->view($user, $tag));
    }

    /**
     * Test that a moderator can view a specific tag.
     * @return void
     */
    public function test_a_moderator_can_view_a_specific_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->view($user, $tag));
    }

    /**
     * Test that a regular user can view a specific tag.
     * @return void
     */
    public function test_a_regular_user_can_view_a_specific_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->view($user, $tag));
    }

    /**
     * Test that a guest cannot view a specific tag.
     * @return void
     */
    public function test_a_guest_cannot_view_a_specific_tag(): void
    {
        $user = $this->createUserWithRoles([]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->view($user, $tag));
    }

    // --- Tests for `create` method ---

    /**
     * Test that an admin can create a tag.
     * @return void
     */
    public function test_an_admin_can_create_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($user->can('create', Tag::class));
    }

    /**
     * Test that an editor can create a tag.
     * @return void
     */
    public function test_an_editor_can_create_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($user->can('create', Tag::class));
    }

    /**
     * Test that an author cannot create a tag.
     * @return void
     */
    public function test_an_author_cannot_create_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a moderator cannot create a tag.
     * @return void
     */
    public function test_a_moderator_cannot_create_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a regular user cannot create a tag.
     * @return void
     */
    public function test_a_regular_user_cannot_create_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a guest cannot create a tag.
     * @return void
     */
    public function test_a_guest_cannot_create_a_tag(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($this->policy->create($user));
    }

    // --- Tests for `update` method ---

    /**
     * Test that an admin can update a tag.
     * @return void
     */
    public function test_an_admin_can_update_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($user->can('update', $tag));
    }

    /**
     * Test that an editor can update a tag.
     * @return void
     */
    public function test_an_editor_can_update_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->update($user, $tag));
    }

    /**
     * Test that an author cannot update a tag.
     * @return void
     */
    public function test_an_author_cannot_update_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($user->can('update', $tag));
    }

    /**
     * Test that a moderator cannot update a tag.
     * @return void
     */
    public function test_a_moderator_cannot_update_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($user->can('update', $tag));
    }

    /**
     * Test that a regular user cannot update a tag.
     * @return void
     */
    public function test_a_regular_user_cannot_update_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->update($user, $tag));
    }

    /**
     * Test that a guest cannot update a tag.
     * @return void
     */
    public function test_a_guest_cannot_update_a_tag(): void
    {
        $user = $this->createUserWithRoles([]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->update($user, $tag));
    }

    // --- Tests for `delete` method ---

    /**
     * Test that an admin can delete a tag.
     * @return void
     */
    public function test_an_admin_can_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->delete($user, $tag));
    }

    /**
     * Test that a moderator can delete a tag.
     * @return void
     */
    public function test_a_moderator_can_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->delete($user, $tag));
    }

    /**
     * Test that an editor cannot delete a tag.
     * @return void
     */
    public function test_an_editor_cannot_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->delete($user, $tag));
    }

    /**
     * Test that an author cannot delete a tag.
     * @return void
     */
    public function test_an_author_cannot_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->delete($user, $tag));
    }

    /**
     * Test that a regular user cannot delete a tag.
     * @return void
     */
    public function test_a_regular_user_cannot_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->delete($user, $tag));
    }

    /**
     * Test that a guest cannot delete a tag.
     * @return void
     */
    public function test_a_guest_cannot_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->delete($user, $tag));
    }

    // --- Tests for `restore` method ---

    /**
     * Test that an admin can restore a tag.
     * @return void
     */
    public function test_an_admin_can_restore_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->restore($user, $tag));
    }

    /**
     * Test that a moderator can restore a tag.
     * @return void
     */
    public function test_a_moderator_can_restore_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->restore($user, $tag));
    }

    /**
     * Test that an editor cannot restore a tag.
     * @return void
     */
    public function test_an_editor_cannot_restore_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->restore($user, $tag));
    }

    /**
     * Test that an author cannot restore a tag.
     * @return void
     */
    public function test_an_author_cannot_restore_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->restore($user, $tag));
    }

    /**
     * Test that a regular user cannot restore a tag.
     * @return void
     */
    public function test_a_regular_user_cannot_restore_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->restore($user, $tag));
    }

    /**
     * Test that a guest cannot restore a tag.
     * @return void
     */
    public function test_a_guest_cannot_restore_a_tag(): void
    {
        $user = $this->createUserWithRoles([]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->restore($user, $tag));
    }

    // --- Tests for `forceDelete` method ---

    /**
     * Test that an admin can force delete a tag.
     * @return void
     */
    public function test_an_admin_can_force_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $tag = Tag::factory()->make();
        $this->assertTrue($this->policy->forceDelete($user, $tag));
    }

    /**
     * Test that a moderator cannot force delete a tag.
     * @return void
     */
    public function test_a_moderator_cannot_force_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->forceDelete($user, $tag));
    }

    /**
     * Test that an editor cannot force delete a tag.
     * @return void
     */
    public function test_an_editor_cannot_force_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->forceDelete($user, $tag));
    }

    /**
     * Test that an author cannot force delete a tag.
     * @return void
     */
    public function test_an_author_cannot_force_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->forceDelete($user, $tag));
    }

    /**
     * Test that a regular user cannot force delete a tag.
     * @return void
     */
    public function test_a_regular_user_cannot_force_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->forceDelete($user, $tag));
    }

    /**
     * Test that a guest cannot force delete a tag.
     * @return void
     */
    public function test_a_guest_cannot_force_delete_a_tag(): void
    {
        $user = $this->createUserWithRoles([]);
        $tag = Tag::factory()->make();
        $this->assertFalse($this->policy->forceDelete($user, $tag));
    }

    // --- Comprehensive Test for No Permissions ---

    /**
     * Test that a user with no roles/permissions cannot perform any policy action.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles
        $tag = Tag::factory()->make();

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, $tag));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, $tag));
        $this->assertFalse($this->policy->delete($user, $tag));
        $this->assertFalse($this->policy->restore($user, $tag));
        $this->assertFalse($this->policy->forceDelete($user, $tag));
    }
}
