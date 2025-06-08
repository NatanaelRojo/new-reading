<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Review;
use App\Models\User;
use App\Policies\ReviewPolicy;
use PHPUnit\Framework\TestCase;

class ReviewPolicyTest extends TestCase
{
    protected ReviewPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReviewPolicy();
    }

    /**
     * Test the view method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_view(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->view($user, new Review()));
    }

    /**
     * Test the viewAny method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_view_any(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->viewAny($user));
    }

    /**
        * Test the create method of the ReviewPolicy.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->policy->create($user));
    }

    /**
     * Test the update method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_update(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $review = new Review();
        $review->user_id = $user->id;
        $this->assertTrue($this->policy->update($user, $review));
    }

    /**
     * Test the delete method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $review = new Review();
        $this->assertTrue($this->policy->delete($user, $review));
    }

    /**
     * Test the restore method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_restore(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $review = new Review();
        $this->assertTrue($this->policy->restore($user, $review));
    }

    /**
     * Test the forceDelete method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_force_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $review = new Review();
        $this->assertTrue($this->policy->forceDelete($user, $review));
    }

    /**
     * Test the no permissions method of the ReviewPolicy.
     *
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $this->assertFalse($this->policy->view($user, new Review()));
        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->update($user, new Review()));
        $this->assertFalse($this->policy->delete($user, new Review()));
        $this->assertFalse($this->policy->restore($user, new Review()));
        $this->assertFalse($this->policy->forceDelete($user, new Review()));
    }
}
