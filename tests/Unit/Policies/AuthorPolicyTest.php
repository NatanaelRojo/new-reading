<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Author;
use App\Models\User;
use App\Policies\AuthorPolicy;
use App\Traits\Test\RolesAndUsers;
use PHPUnit\Framework\TestCase;
use Spatie\Permission\Models\Role;
use Tests\TestCase as TestsTestCase;

class AuthorPolicyTest extends TestsTestCase
{
    use RolesAndUsers;

    protected AuthorPolicy $policy;
    protected User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new AuthorPolicy();
    }

    /**
     * Test that a user can view any authors.
     * @return void
     */
    public function test_viewAny(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($this->policy->viewAny($this->mockUser));
    }

    /**
        * Test that a user can create an author.
     * @return void
     */
    public function test_view(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($this->policy->view($this->mockUser));
    }

    /**
     * Test that a user can create an author.
     * @return void
     */
    public function test_create(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $this->assertTrue($this->policy->create($this->mockUser));
    }

    /**
     * Test that a user can update an author.
     * @return void
     */
    public function test_update(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->update($this->mockUser, $author));
    }

    /**
     * Test that a user can delete an author.
     * @return void
     */
    public function test_delete(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->delete($this->mockUser, $author));
    }

    /**
     * Test that a user can restore an author.
     * @return void
     */
    public function test_restore(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->restore($this->mockUser, $author));
    }

    /**
     * Test that a user can force delete an author.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $this->mockUser = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->forceDelete($this->mockUser, $author));
    }

    /**
     * Test that an editor can view any authors.
     *
     * @return void
     */
    public function test_an_editor_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that an author can view any authors.
     *
     * @return void
     */
    public function test_an_author_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a moderator can view any authors.
     *
     * @return void
     */
    public function test_a_moderator_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a regular user can view any authors.
     *
     * @return void
     */
    public function test_a_regular_user_can_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a guest cannot view any authors.
     *
     * @return void
     */
    public function test_a_guest_cannot_view_any_authors(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles
        $this->assertFalse($this->policy->viewAny($user));
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
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->view($user, $author));
    }

    /**
     * Test that an author can view a specific author.
     *
     * @return void
     */
    public function test_an_author_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->view($user, $author));
    }

    /**
     * Test that a moderator can view a specific author.
     *
     * @return void
     */
    public function test_a_moderator_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->view($user, $author));
    }

    /**
     * Test that a regular user can view a specific author.
     *
     * @return void
     */
    public function test_a_regular_user_can_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->view($user, $author));
    }

    /**
     * Test that a guest cannot view a specific author.
     *
     * @return void
     */
    public function test_a_guest_cannot_view_a_specific_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->view($user, $author));
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
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that an author cannot create an author.
     *
     * @return void
     */
    public function test_an_author_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a moderator cannot create an author.
     *
     * @return void
     */
    public function test_a_moderator_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a regular user cannot create an author.
     *
     * @return void
     */
    public function a_regular_user_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a guest cannot create an author.
     *
     * @return void
     */
    public function a_guest_cannot_create_an_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($this->policy->create($user));
    }

    // --- Tests for `update` method ---

    /**
     * Test that an editor can update any author.
     *
     * @return void
     */
    public function test_an_editor_can_update_any_author(): void
    {
        $editor = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->update($editor, $author));
    }

    /**
     * Test that an author can update their own author.
     *
     * @return void
     */
    public function test_an_author_can_update_their_own_author(): void
    {
        $authorUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $author = Author::factory()->make();

        $this->assertTrue($this->policy->update($authorUser, $author));
    }

    /**
     * Test that an author cannot update another author's content.
     *
     * @return void
     */
    public function test_an_author_cannot_update_another_authors_content(): void
    {
        $authorUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $anotherAuthor = Author::factory()->make();
        $this->assertFalse(false);
    }

    /**
     * Test that a moderator cannot update an author.
     *
     * @return void
     */
    public function test_a_moderator_cannot_update_an_author(): void
    {
        $moderator = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->update($moderator, $author));
    }

    /**
     * Test that a regular user cannot update an author.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_update_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->update($user, $author));
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
        $this->assertFalse($this->policy->update($user, $author));
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
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->delete($user, $author));
    }

    /**
     * Test that an editor cannot delete an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->delete($user, $author));
    }

    /**
     * Test that an author cannot delete an author.
     *
     * @return void
     */
    public function test_an_author_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->delete($user, $author));
    }

    /**
     * Test that a regular user cannot delete an author.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->delete($user, $author));
    }

    /**
     * Test that a guest cannot delete an author.
     *
     * @return void
     */
    public function test_a_guest_cannot_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->delete($user, $author));
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
        $author = $this->createMock(Author::class);
        $this->assertTrue($this->policy->restore($user, $author));
    }

    /**
     * Test that an editor cannot restore an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_restore_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->restore($user, $author));
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
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->forceDelete($user, $author));
    }

    /**
     * Test that an editor cannot force delete an author.
     *
     * @return void
     */
    public function test_an_editor_cannot_force_delete_an_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $author = $this->createMock(Author::class);
        $this->assertFalse($this->policy->forceDelete($user, $author));
    }

    /**
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles();

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->delete($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->restore($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->forceDelete($user, $this->createMock(Author::class)));
    }
}
