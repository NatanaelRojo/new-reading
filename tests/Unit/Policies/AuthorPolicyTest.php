<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Author;
use App\Models\User;
use App\Policies\AuthorPolicy;
use PHPUnit\Framework\TestCase;

class AuthorPolicyTest extends TestCase
{
    protected AuthorPolicy $policy;

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
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
        * Test that a user can create an author.
     * @return void
     */
    public function test_view(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->view($user));
    }

    /**
     * Test that a user can create an author.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test that a user can update an author.
     * @return void
     */
    public function test_update(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $author = $this->createMock(Author::class);
        $author->user_id = $user->id;

        $this->assertTrue($this->policy->update($user, $author));
    }

    /**
     * Test that a user can delete an author.
     * @return void
     */
    public function test_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->delete($user, $author));
    }

    /**
     * Test that a user can restore an author.
     * @return void
     */
    public function test_restore(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->restore($user, $author));
    }

    /**
     * Test that a user can force delete an author.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $author = $this->createMock(Author::class);

        $this->assertTrue($this->policy->forceDelete($user, $author));
    }

    /**
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->delete($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->restore($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->forceDelete($user, $this->createMock(Author::class)));
    }
}
