<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Book;
use App\Models\User;
use App\Policies\BookPolicy;
use App\Traits\Test\RolesAndUsers;
use Tests\TestCase as TestsTestCase;

class BookPolicyTest extends TestsTestCase
{
    use RolesAndUsers;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that an administrator can view any books.
     * @return void
     */
    public function test_viewAny_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $this->assertTrue($user->can('viewAny', Book::class));
    }

    /**
     * Test that an editor can view any books.
     * @return void
     */
    public function test_viewAny_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $this->assertTrue($user->can('viewAny', Book::class));
    }

    /**
     * Test that an author can view any books.
     * @return void
     */
    public function test_viewAny_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($user->can('viewAny', Book::class));
    }

    /**
     * Test that a moderator can view any books.
     * @return void
     */
    public function test_viewAny_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $this->assertTrue($user->can('viewAny', Book::class));
    }

    /**
     * Test that a regular user can view any books.
     * @return void
     */
    public function test_viewAny_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $this->assertTrue($user->can('viewAny', Book::class));
    }

    /**
     * Test that a guest cannot view any books.
     * @return void
     */
    public function test_viewAny_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('viewAny', Book::class));
    }

    /**
     * Test that an administrator can view a specific book.
     * @return void
     */
    public function test_view_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('view', $book));
    }

    /**
     * Test that an editor can view a specific book.
     * @return void
     */
    public function test_view_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('view', $book));
    }

    /**
     * Test that an author can view a specific book.
     * @return void
     */
    public function test_view_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('view', $book));
    }

    /**
     * Test that a moderator can view a specific book.
     * @return void
     */
    public function test_view_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('view', $book));
    }

    /**
     * Test that a regular user can view a specific book.
     * @return void
     */
    public function test_view_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('view', $book));
    }

    /**
     * Test that a guest cannot view a specific book.
     * @return void
     */
    public function test_view_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('view', $book));
    }

    /**
     * Test that an administrator can create a book.
     * @return void
     */
    public function test_create_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $this->assertTrue($user->can('create', Book::class));
    }

    /**
     * Test that an editor cannot create a book.
     * @return void
     */
    public function test_create_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $this->assertFalse($user->can('create', Book::class));
    }

    /**
     * Test that an author can create a book.
     * @return void
     */
    public function test_an_author_can_create_a_book(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $this->assertTrue($user->can('create', Book::class));
    }

    /**
     * Test that a moderator cannot create a book.
     * @return void
     */
    public function test_create_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $this->assertFalse($user->can('create', Book::class));
    }

    /**
     * Test that a regular user cannot create a book.
     * @return void
     */
    public function test_create_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $this->assertFalse($user->can('create', Book::class));
    }

    /**
     * Test that a guest cannot create a book.
     * @return void
     */
    public function test_create_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('create', Book::class));
    }

    /**
     * Test that an administrator can update any book.
     * @return void
     */
    public function test_update_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('update', $book));
    }

    /**
     * Test that an editor can update any book.
     * @return void
     */
    public function test_update_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('update', $book));
    }

    /**
     * Test that an author can update their own book.
     * @return void
     */
    public function test_update_author_own_book(): void
    {
        $authorUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $book = Book::factory()->hasUsers(1)->make();
        $book->user_id = $authorUser->id;
        $this->assertTrue($authorUser->can('update', $book));
    }

    /**
     * Test that an author cannot update another author's book.
     * @return void
     */
    public function test_update_author_other_book(): void
    {
        $authorUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $anotherUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $book = Book::factory()->hasUsers(1)->make();
        $book->user_id = $anotherUser->id;
        $this->assertFalse($authorUser->can('update', $book));
    }

    /**
     * Test that a moderator cannot update a book.
     * @return void
     */
    public function test_update_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('update', $book));
    }

    /**
     * Test that a regular user cannot update a book.
     * @return void
     */
    public function test_update_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('update', $book));
    }

    /**
     * Test that a guest cannot update a book.
     * @return void
     */
    public function test_update_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('update', $book));
    }

    /**
     * Test that an administrator can delete any book.
     * @return void
     */
    public function test_delete_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('delete', $book));
    }

    /**
     * Test that a moderator can delete any book.
     * @return void
     */
    public function test_delete_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('delete', $book));
    }

    /**
     * Test that an editor cannot delete a book.
     * @return void
     */
    public function test_delete_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('delete', $book));
    }

    /**
     * Test that an author cannot delete a book.
     * @return void
     */
    public function test_delete_as_author(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('delete', $book));
    }

    /**
     * Test that a regular user cannot delete a book.
     * @return void
     */
    public function test_delete_as_regular_user(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('delete', $book));
    }

    /**
     * Test that a guest cannot delete a book.
     * @return void
     */
    public function test_delete_as_guest(): void
    {
        $user = $this->createUserWithRoles([]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('delete', $book));
    }

    /**
     * Test that an administrator can restore any book.
     * @return void
     */
    public function test_restore_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('restore', $book));
    }

    /**
     * Test that a moderator can restore any book.
     * @return void
     */
    public function test_restore_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('restore', $book));
    }

    /**
     * Test that an editor cannot restore a book.
     * @return void
     */
    public function test_restore_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('restore', $book));
    }

    /**
     * Test that an administrator can force delete any book.
     * @return void
     */
    public function test_forceDelete_as_admin(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->getValue()]);
        $book = Book::factory()->make();
        $this->assertTrue($user->can('forceDelete', $book));
    }

    /**
     * Test that a moderator cannot force delete a book.
     * @return void
     */
    public function test_forceDelete_as_moderator(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('forceDelete', $book));
    }

    /**
     * Test that an editor cannot force delete a book.
     * @return void
     */
    public function test_forceDelete_as_editor(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->getValue()]);
        $book = Book::factory()->make();
        $this->assertFalse($user->can('forceDelete', $book));
    }

    /**
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles([]);

        $this->assertFalse($user->can('viewAny', Book::class));
        $this->assertFalse($user->can('view', Book::factory()->make()));
        $this->assertFalse($user->can('create', Book::class));
        $this->assertFalse($user->can('update', Book::factory()->make()));
        $this->assertFalse($user->can('delete', Book::factory()->make()));
        $this->assertFalse($user->can('restore', Book::factory()->make()));
        $this->assertFalse($user->can('forceDelete', Book::factory()->make()));
    }
}
