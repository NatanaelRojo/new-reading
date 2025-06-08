<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use PHPUnit\Framework\TestCase;

class PostPolicyTest extends TestCase
{
    protected PostPolicy $policy;

    public function setUp(): void
    {
        parent::setUp();
        $this->policy = new PostPolicy();
    }

    /**
     * Test that a user can view any posts.
     * @return void
     */
    public function test_view(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->view($user, new Post()));
    }

    /**
     * Test that a user can view any posts.
     * @return void
     */
    public function test_viewAny(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a user can create a post.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test that a user can update a post.
     * @return void
     */
    public function test_update(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->update($user, new Post()));
    }

    /**
     * Test that a user can delete a post.
     * @return void
     */
    public function test_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->delete($user, new Post()));
    }

    /**
     * Test that a user can restore a post.
     * @return void
     */
    public function test_restore(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->restore($user, new Post()));
    }

    /**
     * Test that a user can permanently delete a post.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->forceDelete($user, new Post()));
    }

    /**
     * Test that a user cannot view any posts.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, new Post()));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, new Post()));
        $this->assertFalse($this->policy->delete($user, new Post()));
        $this->assertFalse($this->policy->restore($user, new Post()));
        $this->assertFalse($this->policy->forceDelete($user, new Post()));
    }
}
