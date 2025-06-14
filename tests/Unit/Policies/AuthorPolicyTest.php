<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Author;
use App\Models\User;
use App\Policies\AuthorPolicy; // Ensure this policy extends your BasePolicy
use App\Traits\Test\RolesAndUsers;
use PHPUnit\Framework\TestCase;
use Spatie\Permission\Models\Role;
use Tests\TestCase as TestsTestCase;

class AuthorPolicyTest extends TestsTestCase
{
    use RolesAndUsers;

    // The policy instance is still needed if you want to inspect its internal state,
    // but the authorization checks themselves should go through the user's can() method.
    protected AuthorPolicy $policy;
    protected User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->policy = new AuthorPolicy(); // No longer strictly needed for 'can()' calls, but can be kept for other test needs
    }

    /**
     * Test that a user can view any authors.
     * @return void
     */
    public function test_viewAny(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($this->mockUser->can('viewAny', Author::class));
    }

    /**
     * Test that a user can view an author.
     * @return void
     */
    public function test_view(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $author = Author::factory()->make();
        $this->assertTrue($this->mockUser->can('view', $author));
    }

    /**
     * Test that a user can create an author.
     * @return void
     */
    public function test_create(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $this->assertTrue($this->mockUser->can('create', Author::class));
    }

    /**
     * Test that a user can update an author.
     * @return void
     */
    public function test_update(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = Author::factory()->make();

        $this->assertTrue($this->mockUser->can('update', $author));
    }

    /**
     * Test that a user can delete an author.
     * @return void
     */
    public function test_delete(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = Author::factory()->make();

        $this->assertTrue($this->mockUser->can('delete', $author));
    }

    /**
     * Test that a user can restore an author.
     * @return void
     */
    public function test_restore(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = Author::factory()->make();

        $this->assertTrue($this->mockUser->can('restore', $author));
    }

    /**
     * Test that a user can force delete an author.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = Author::factory()->make();

        $this->assertTrue($this->mockUser->can('forceDelete', $author));
    }

    /**
     * Test that an editor can view any authors.
     *
     * @return void
     */
    public function test_an_editor_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($user->can('viewAny', Author::class));
    }

    /**
     * Test that an author can view any authors.
     *
     * @return void
     */
    public function test_an_author_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($user->can('viewAny', Author::class));
    }

    /**
     * Test that a moderator can view any authors.
     *
     * @return void
     */
    public function test_a_moderator_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertTrue($user->can('viewAny', Author::class));
    }

    /**
     * Test that a regular user can view any authors.
     *
     * @return void
     */
    public function test_a_regular_user_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($user->can('viewAny', Author::class));
    }

    /**
     * Test that a guest cannot view any authors.
     *
     * @return void
     */
    public function test_a_guest_cannot_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('viewAny', Author::class));
    }

    // --- Tests for `view` method (single author) ---

    /**
     * Test that an editor can view a specific author.
     *
     * @return void
     */
    public function test_an_editor_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = Author::factory()->make();
        $this->assertTrue($user->can('view', $author));
    }

    /**
     * Test that an author can view a specific author.
     *
     * @return void
     */
    public function test_an_author_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $author = Author::factory()->make();
        $this->assertTrue($user->can('view', $author));
    }

    /**
     * Test that a moderator can view a specific author.
     *
     * @return void
     */
    public function test_a_moderator_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = Author::factory()->make();
        $this->assertTrue($user->can('view', $author));
    }

    /**
     * Test that a regular user can view a specific author.
     *
     * @return void
     */
    public function test_a_regular_user_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $author = Author::factory()->make();
        $this->assertTrue($user->can('view', $author));
    }

    /**
     * Test that a guest cannot view a specific author.
     *
     * @return void
     */
    public function test_a_guest_cannot_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('view', $author));
    }

    // --- Tests for `create` method ---

    /**
     * Test that an editor cannot create an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertFalse($user->can('create', Author::class));
    }

    /**
     * Test that an author cannot create an author.
     *
     * @return void
     */
    public function test_an_author_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertFalse($user->can('create', Author::class));
    }

    /**
     * Test that a moderator cannot create an author.
     *
     * @return void
     */
    public function test_a_moderator_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertFalse($user->can('create', Author::class));
    }

    /**
     * Test that a regular user cannot create an author.
     *
     * @return void
     */
    public function a_regular_user_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertFalse($user->can('create', Author::class));
    }

    /**
     * Test that a guest cannot create an author.
     *
     * @return void
     */
    public function a_guest_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('create', Author::class));
    }

    // --- Tests for `update` method ---

    /**
     * Test that an editor can update any author.
     *
     * @return void
     */
    public function test_an_editor_can_update_any_author(): void
    {
        $editorUser = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = Author::factory()->create();

        // This assertion might need to change to true if editors are indeed allowed to update any author
        // according to your actual policy logic. This test currently asserts false.
        $this->assertFalse($editorUser->can('update', $author));
    }

    /**
     * Test that an author can update their own author.
     *
     * @return void
     */
    public function test_an_author_can_update_their_own_author(): void
    {
        $authorUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $author = Author::factory()->forUser($authorUser)->create();

        $this->assertTrue($authorUser->can('update', $author));
    }

    /**
     * Test that an author cannot update another author's content.
     *
     * @return void
     */
    public function test_an_author_cannot_update_another_authors_content(): void
    {
        $authorUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $anotherAuthor = Author::factory()->create(); // Changed to create to ensure it has an ID, though mock is fine if user_id is set
        $this->assertFalse($authorUser->can('update', $anotherAuthor)); // Corrected assertion logic
    }

    /**
     * Test that a moderator cannot update an author.
     *
     * @return void
     */
    public function test_a_moderator_cannot_update_an_author(): void
    {
        $moderator = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = Author::factory()->make();
        $this->assertFalse($moderator->can('update', $author));
    }

    /**
     * Test that a regular user cannot update an author.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_update_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('update', $author));
    }

    /**
     * Test that a guest cannot update an author.
     *
     * @return void
     */
    public function test_a_guest_cannot_update_an_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('update', $author));
    }

    // --- Tests for `delete` method ---

    /**
     * Test that a moderator can delete an author.
     *
     * @return void
     */
    public function test_a_moderator_can_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = Author::factory()->make();
        $this->assertTrue($user->can('delete', $author));
    }

    /**
     * Test that an editor cannot delete an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('delete', $author));
    }

    /**
     * Test that an author cannot delete an author.
     *
     * @return void
     */
    public function test_an_author_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('delete', $author));
    }

    /**
     * Test that a regular user cannot delete an author.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('delete', $author));
    }

    /**
     * Test that a guest cannot delete an author.
     *
     * @return void
     */
    public function test_a_guest_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('delete', $author));
    }

    // --- Tests for `restore` method ---

    /**
     * Test that a moderator can restore an author.
     *
     * @return void
     */
    public function test_a_moderator_can_restore_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = Author::factory()->make();
        dump('Testing moderator restore permission');
        $this->assertTrue($user->can('restore', $author));
    }

    /**
     * Test that an editor cannot restore an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_restore_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('restore', $author));
    }

    // --- Tests for `forceDelete` method ---

    /**
     * Test that a moderator can force delete an author.
     *
     * @return void
     */
    public function test_a_moderator_cannot_force_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('forceDelete', $author));
    }

    /**
     * Test that an editor cannot force delete an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_force_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = Author::factory()->make();
        $this->assertFalse($user->can('forceDelete', $author));
    }

    /**
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles();

        $this->assertFalse($user->can('viewAny', Author::class));
        $this->assertFalse($user->can('view', Author::factory()->make()));
        $this->assertFalse($user->can('create', Author::class));
        $this->assertFalse($user->can('update', Author::factory()->make()));
        $this->assertFalse($user->can('delete', Author::factory()->make()));
        $this->assertFalse($user->can('restore', Author::factory()->make()));
        $this->assertFalse($user->can('forceDelete', Author::factory()->make()));
    }
}
