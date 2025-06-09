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
        $this->mockUser = $this->createUserWithRoles([AppRoles::AUTHOR->getValue()]);
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
     * Test that a user cannot perform actions without permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles();
        // $user = $this->createMock(User::class);
        // $user->method('hasAnyRole')->willReturn(false);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->delete($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->restore($user, $this->createMock(Author::class)));
        $this->assertFalse($this->policy->forceDelete($user, $this->createMock(Author::class)));
    }
}
