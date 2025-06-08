<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;
use PHPUnit\Framework\TestCase;

class CommentPolicyTest extends TestCase
{
    protected CommentPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new CommentPolicy();
    }

    /**
     * Test that a user can view any comments.
     * @return void
     */
    public function test_view(): void
    {
        $comment = $this->createMock(Comment::class);
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->view($user, $comment));
    }

    /**
     * Test that a user can view any comments.
     * @return void
     */
    public function test_viewAny(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
     * Test that a user can create a comment.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test that a user can update a comment.
     * @return void
     */
    public function test_update(): void
    {
        $comment = $this->createMock(Comment::class);
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->update($user, $comment));
    }

    /**
     * Test that a user can delete a comment.
     * @return void
     */
    public function test_delete(): void
    {
        $comment = $this->createMock(Comment::class);
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->delete($user, $comment));
    }

    /**
     * Test that a user can restore a comment.
     * @return void
     */
    public function test_restore(): void
    {
        $comment = $this->createMock(Comment::class);
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->restore($user, $comment));
    }

    /**
     * Test that a user can permanently delete a comment.
     * @return void
     */
    public function test_forceDelete(): void
    {
        $comment = $this->createMock(Comment::class);
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->forceDelete($user, $comment));
    }

    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $this->assertFalse($this->policy->view($user, new Comment()));
        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, new Comment()));
        $this->assertFalse($this->policy->delete($user, new Comment()));
        $this->assertFalse($this->policy->restore($user, new Comment()));
        $this->assertFalse($this->policy->forceDelete($user, new Comment()));
    }
}
