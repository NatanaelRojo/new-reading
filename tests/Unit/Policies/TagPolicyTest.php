<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Tag;
use App\Models\User;
use App\Policies\TagPolicy;
use PHPUnit\Framework\TestCase;

class TagPolicyTest extends TestCase
{
    protected TagPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new TagPolicy();
    }

    /**
     * Test that a user can view any tags.
     * @return void
     */
    public function test_view(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->view($user, new Tag()));
    }

    /**
     * Test that a user can view any tags.
     * @return void
     */
    public function test_viewAny(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a user can create tags.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test that a user can update tags.
     * @return void
     */
    public function test_update(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->update($user, new Tag()));
    }

    /**
     * Test that a user can delete tags.
     * @return void
     */
    public function test_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->delete($user, new Tag()));
    }

    /**
     * Test that a user can restore tags.
     * @return void
     */
    public function test_restore(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->restore($user, new Tag()));
    }

    /**
     * Test that a user can permanently delete tags.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);

        $this->assertTrue($this->policy->forceDelete($user, new Tag()));
    }

    /**
     * Test that a user cannot view any tags.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, new Tag()));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, new Tag()));
        $this->assertFalse($this->policy->delete($user, new Tag()));
        $this->assertFalse($this->policy->restore($user, new Tag()));
        $this->assertFalse($this->policy->forceDelete($user, new Tag()));
    }
}
