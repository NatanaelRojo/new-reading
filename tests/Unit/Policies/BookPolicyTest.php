<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Book;
use App\Models\User;
use App\Policies\BookPolicy;
use PHPUnit\Framework\TestCase;

class BookPolicyTest extends TestCase
{
    protected BookPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new BookPolicy();
    }

    /**
     * Test that a user can view any books.
     * @return void
     */
    public function test_viewAny(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a user can view a book.
     * @return void
     */
    public function test_view(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->view($user));
    }

    /**
     * Test that a user can create a book.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->create($user));
    }

    /**     * Test that a user can update a book.
     * @return void
     */
    public function test_update(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $book = $this->createMock(Book::class);
        $this->assertTrue($this->policy->update($user, $book));
    }

    /**
     * Test that a user can delete a book.
     * @return void
     */
    public function test_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $book = $this->createMock(Book::class);
        $this->assertTrue($this->policy->delete($user, $book));
    }

    /**
     * Test that a user can restore a book.
     * @return void
     */
    public function test_restore(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $book = $this->createMock(Book::class);
        $this->assertTrue($this->policy->restore($user, $book));
    }

    /**
     * Test that a user can permanently delete a book.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $book = $this->createMock(Book::class);
        $this->assertTrue($this->policy->forceDelete($user, $book));
    }

    /**
     * Test that a user cannot view any books without permission.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, new Book()));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, new Book()));
        $this->assertFalse($this->policy->delete($user, new Book()));
        $this->assertFalse($this->policy->restore($user, new Book()));
        $this->assertFalse($this->policy->forceDelete($user, new Book()));
    }
}
