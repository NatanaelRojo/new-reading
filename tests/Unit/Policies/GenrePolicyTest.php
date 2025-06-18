<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Genre;
use App\Models\User;
use App\Policies\GenrePolicy;
use App\Traits\Test\RolesAndUsers;
use PHPUnit\Framework\TestCase;
use Spatie\Permission\Models\Role;
use Tests\TestCase as TestsTestCase;

class GenrePolicyTest extends TestsTestCase
{
    use RolesAndUsers;

    protected GenrePolicy $policy;
    protected User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new GenrePolicy();
    }

    // --- Tests for `viewAny` method (listing genres) ---

    /**
     * Test that an editor can view any genres.
     *
     * @return void
     */
    public function test_an_editor_can_view_any_genres(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that an author can view any genres.
     *
     * @return void
     */
    public function test_an_author_can_view_any_genres(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a moderator can view any genres.
     *
     * @return void
     */
    public function test_a_moderator_can_view_any_genres(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a regular user can view any genres.
     *
     * @return void
     */
    public function test_a_regular_user_can_view_any_genres(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a guest cannot view any genres.
     *
     * @return void
     */
    public function test_a_guest_cannot_view_any_genres(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($this->policy->viewAny($user));
    }

    // --- Tests for `view` method (single genre) ---

    /**
     * Test that an editor can view a specific genre.
     *
     * @return void
     */
    public function test_an_editor_can_view_a_specific_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->view($user, $genre));
    }

    /**
     * Test that an author can view a specific genre.
     *
     * @return void
     */
    public function test_an_author_can_view_a_specific_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->view($user, $genre));
    }

    /**
     * Test that a moderator can view a specific genre.
     *
     * @return void
     */
    public function test_a_moderator_can_view_a_specific_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->view($user, $genre));
    }

    /**
     * Test that a regular user can view a specific genre.
     *
     * @return void
     */
    public function test_a_regular_user_can_view_a_specific_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->view($user, $genre));
    }

    /**
     * Test that a guest cannot view a specific genre.
     *
     * @return void
     */
    public function test_a_guest_cannot_view_a_specific_genre(): void
    {
        $user = $this->createUserWithRoles([]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->view($user, $genre));
    }

    // --- Tests for `create` method ---

    /**
     * Test that an admin can create a genre.
     *
     * @return void
     */
    public function test_an_admin_can_create_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test that an editor cannot create a genre.
     *
     * @return void
     */
    public function test_an_editor_cannot_create_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that an author cannot create a genre.
     *
     * @return void
     */
    public function test_an_author_cannot_create_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a moderator cannot create a genre.
     *
     * @return void
     */
    public function test_a_moderator_cannot_create_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a regular user cannot create a genre.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_create_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertFalse($this->policy->create($user));
    }

    /**
     * Test that a guest cannot create a genre.
     *
     * @return void
     */
    public function test_a_guest_cannot_create_a_genre(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($this->policy->create($user));
    }

    // --- Tests for `update` method ---

    /**
     * Test that an admin can update a genre.
     *
     * @return void
     */
    public function test_an_admin_can_update_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->update($user, $genre));
    }

    /**
     * Test that an editor can update a genre.
     *
     * @return void
     */
    public function test_an_editor_can_update_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->update($user, $genre));
    }

    /**
     * Test that an author cannot update a genre.
     *
     * @return void
     */
    public function test_an_author_cannot_update_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->update($user, $genre));
    }

    /**
     * Test that a moderator cannot update a genre.
     *
     * @return void
     */
    public function test_a_moderator_cannot_update_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->update($user, $genre));
    }

    /**
     * Test that a regular user cannot update a genre.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_update_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->update($user, $genre));
    }

    /**
     * Test that a guest cannot update a genre.
     *
     * @return void
     */
    public function test_a_guest_cannot_update_a_genre(): void
    {
        $user = $this->createUserWithRoles([]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->update($user, $genre));
    }

    // --- Tests for `delete` method ---

    /**
     * Test that an admin can delete a genre.
     *
     * @return void
     */
    public function test_an_admin_can_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->delete($user, $genre));
    }

    /**
     * Test that a moderator can delete a genre.
     *
     * @return void
     */
    public function test_a_moderator_can_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->delete($user, $genre));
    }

    /**
     * Test that an editor cannot delete a genre.
     *
     * @return void
     */
    public function test_an_editor_cannot_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->delete($user, $genre));
    }

    /**
     * Test that an author cannot delete a genre.
     *
     * @return void
     */
    public function test_an_author_cannot_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->delete($user, $genre));
    }

    /**
     * Test that a regular user cannot delete a genre.
     *
     * @return void
     */
    public function test_a_regular_user_cannot_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->delete($user, $genre));
    }

    /**
     * Test that a guest cannot delete a genre.
     *
     * @return void
     */
    public function test_a_guest_cannot_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->delete($user, $genre));
    }

    // --- Tests for `restore` method ---

    /**
     * Test that an admin can restore a genre.
     *
     * @return void
     */
    public function test_an_admin_can_restore_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->restore($user, $genre));
    }

    /**
     * Test that a moderator can restore a genre.
     *
     * @return void
     */
    public function test_a_moderator_can_restore_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->restore($user, $genre));
    }

    /**
     * Test that an editor cannot restore a genre.
     *
     * @return void
     */
    public function test_an_editor_cannot_restore_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->restore($user, $genre));
    }

    /**
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles
        $genre = $this->createMock(Genre::class);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, $genre));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, $genre));
        $this->assertFalse($this->policy->delete($user, $genre));
        $this->assertFalse($this->policy->restore($user, $genre));
        $this->assertFalse($this->policy->forceDelete($user, $genre));
    }

    // --- Tests for `forceDelete` method ---

    /**
     * Test that an admin can force delete a genre.
     *
     * @return void
     */
    public function test_an_admin_can_force_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertTrue($this->policy->forceDelete($user, $genre));
    }

    /**
     * Test that a moderator cannot force delete a genre.
     *
     * @return void
     */
    public function test_a_moderator_cannot_force_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->forceDelete($user, $genre));
    }

    /**
     * Test that an editor cannot force delete a genre.
     *
     * @return void
     */
    public function test_an_editor_cannot_force_delete_a_genre(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $genre = $this->createMock(Genre::class);
        $this->assertFalse($this->policy->forceDelete($user, $genre));
    }
}
