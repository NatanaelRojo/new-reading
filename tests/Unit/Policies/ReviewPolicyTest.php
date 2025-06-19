<?php

namespace Tests\Unit\Policies;

use App\Enums\Roles\AppRoles;
use App\Models\API\V1\Review;
use App\Models\User;
use App\Policies\ReviewPolicy;
use App\Traits\Test\RolesAndUsers;
use PHPUnit\Framework\TestCase; // Keep TestCase for basic PHPUnit assertions
use Tests\TestCase as TestsTestCase; // Use Tests\TestCase for Laravel specific setup

class ReviewPolicyTest extends TestsTestCase // Extend TestsTestCase for database and trait usage
{
    use RolesAndUsers; // Provides createUserWithRoles method
    // If you need actual database interactions for factories, you might need RefreshDatabase trait too.
    // use Illuminate\Foundation\Testing\RefreshDatabase;

    protected ReviewPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp(); // Call parent setUp for Laravel setup
        $this->policy = new ReviewPolicy();

        // Ensure roles are seeded if you use RefreshDatabase and them not present
        // $this->seedRoles(); // If you have a method to seed roles in your TestsTestCase or a specific Seeder
    }

    // --- Tests for `viewAny` method (listing reviews) ---

    /**
     * Test that an admin can view any reviews.
     * @return void
     */
    public function test_an_admin_can_view_any_reviews(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($user->can('viewAny', Review::class));
    }

    /**
     * Test that an editor can view any reviews.
     * @return void
     */
    public function test_an_editor_can_view_any_reviews(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertTrue($user->can('viewAny', Review::class));
    }

    /**
     * Test that an author can view any reviews.
     * @return void
     */
    public function test_an_author_can_view_any_reviews(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($user->can('viewAny', Review::class));
    }

    /**
     * Test that a moderator can view any reviews.
     * @return void
     */
    public function test_a_moderator_can_view_any_reviews(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertTrue($user->can('viewAny', Review::class));
    }

    /**
     * Test that a regular user can view any reviews.
     * @return void
     */
    public function test_a_regular_user_can_view_any_reviews(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($user->can('viewAny', Review::class));
    }

    /**
     * Test that a guest cannot view any reviews.
     * @return void
     */
    public function test_a_guest_cannot_view_any_reviews(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles (guest)
        $this->assertFalse($user->can('viewAny', Review::class));
    }

    // --- Tests for `view` method (single review) ---

    /**
     * Test that an admin can view a specific review.
     * @return void
     */
    public function test_an_admin_can_view_a_specific_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('view', $review));
    }

    /**
     * Test that an editor can view a specific review.
     * @return void
     */
    public function test_an_editor_can_view_a_specific_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('view', $review));
    }

    /**
     * Test that an author can view a specific review.
     * @return void
     */
    public function test_an_author_can_view_a_specific_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('view', $review));
    }

    /**
     * Test that a moderator can view a specific review.
     * @return void
     */
    public function test_a_moderator_can_view_a_specific_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('view', $review));
    }

    /**
     * Test that a regular user can view a specific review.
     * @return void
     */
    public function test_a_regular_user_can_view_a_specific_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('view', $review));
    }

    /**
     * Test that a guest cannot view a specific review.
     * @return void
     */
    public function test_a_guest_cannot_view_a_specific_review(): void
    {
        $user = $this->createUserWithRoles([]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('view', $review));
    }

    // --- Tests for `create` method ---

    /**
     * Test that an admin can create a review.
     * @return void
     */
    public function test_an_admin_can_create_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $this->assertTrue($user->can('create', Review::class));
    }

    /**
     * Test that an author can create a review.
     * @return void
     */
    public function test_an_author_can_create_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $this->assertTrue($user->can('create', Review::class));
    }

    /**
     * Test that a regular user can create a review.
     * @return void
     */
    public function test_a_regular_user_can_create_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $this->assertTrue($user->can('create', Review::class));
    }

    /**
     * Test that an editor cannot create a review.
     * @return void
     */
    public function test_an_editor_cannot_create_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $this->assertFalse($user->can('create', Review::class));
    }

    /**
     * Test that a moderator cannot create a review.
     * @return void
     */
    public function test_a_moderator_cannot_create_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $this->assertFalse($user->can('create', Review::class));
    }

    /**
     * Test that a guest cannot create a review.
     * @return void
     */
    public function test_a_guest_cannot_create_a_review(): void
    {
        $user = $this->createUserWithRoles([]);
        $this->assertFalse($user->can('create', Review::class));
    }

    // --- Tests for `update` method ---

    /**
     * Test that an admin can update any review.
     * @return void
     */
    public function test_an_admin_can_update_any_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('update', $review));
    }

    /**
     * Test that a moderator can update any review.
     * @return void
     */
    public function test_a_moderator_can_update_any_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('update', $review));
    }

    /**
     * Test that an author can update their own review.
     * @return void
     */
    public function test_an_author_can_update_their_own_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make(['user_id' => $user->id]);
        $this->assertTrue($user->can('update', $review));
    }

    /**
     * Test that a regular user can update their own review.
     * @return void
     */
    public function test_a_regular_user_can_update_their_own_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make(['user_id' => $user->id]);
        $this->assertTrue($user->can('update', $review));
    }

    /**
     * Test that an author cannot update another user's review.
     * @return void
     */
    public function test_an_author_cannot_update_another_users_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $anotherUser = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make(['user_id' => $anotherUser->id]);
        $this->assertFalse($user->can('update', $review));
    }

    /**
     * Test that a regular user cannot update another user's review.
     * @return void
     */
    public function test_a_regular_user_cannot_update_another_users_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $anotherUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make(['user_id' => $anotherUser->id]);
        $this->assertFalse($user->can('update', $review));
    }

    /**
     * Test that an editor cannot update a review.
     * @return void
     */
    public function test_an_editor_cannot_update_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('update', $review));
    }

    /**
     * Test that a guest cannot update a review.
     * @return void
     */
    public function test_a_guest_cannot_update_a_review(): void
    {
        $user = $this->createUserWithRoles([]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('update', $review));
    }

    // --- Tests for `delete` method ---

    /**
     * Test that an admin can delete any review.
     * @return void
     */
    public function test_an_admin_can_delete_any_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('delete', $review));
    }

    /**
     * Test that a moderator can delete any review.
     * @return void
     */
    public function test_a_moderator_can_delete_any_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('delete', $review));
    }

    /**
     * Test that an author can delete their own review.
     * @return void
     */
    public function test_an_author_can_delete_their_own_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make(['user_id' => $user->id]);
        $this->assertTrue($user->can('delete', $review));
    }

    /**
     * Test that a regular user can delete their own review.
     * @return void
     */
    public function test_a_regular_user_can_delete_their_own_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make(['user_id' => $user->id]);
        $this->assertTrue($user->can('delete', $review));
    }

    /**
     * Test that an author cannot delete another user's review.
     * @return void
     */
    public function test_an_author_cannot_delete_another_users_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $anotherUser = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make(['user_id' => $anotherUser->id]);
        $this->assertFalse($user->can('delete', $review));
    }

    /**
     * Test that a regular user cannot delete another user's review.
     * @return void
     */
    public function test_a_regular_user_cannot_delete_another_users_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $anotherUser = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make(['user_id' => $anotherUser->id]);
        $this->assertFalse($user->can('delete', $review));
    }

    /**
     * Test that an editor cannot delete a review.
     * @return void
     */
    public function test_an_editor_cannot_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('delete', $review));
    }

    /**
     * Test that a guest cannot delete a review.
     * @return void
     */
    public function test_a_guest_cannot_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('delete', $review));
    }

    // --- Tests for `restore` method ---

    /**
     * Test that an admin can restore a review.
     * @return void
     */
    public function test_an_admin_can_restore_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('restore', $review));
    }

    /**
     * Test that a moderator can restore a review.
     * @return void
     */
    public function test_a_moderator_can_restore_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('restore', $review));
    }

    /**
     * Test that an editor cannot restore a review.
     * @return void
     */
    public function test_an_editor_cannot_restore_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('restore', $review));
    }

    /**
     * Test that an author cannot restore a review.
     * @return void
     */
    public function test_an_author_cannot_restore_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('restore', $review));
    }

    /**
     * Test that a regular user cannot restore a review.
     * @return void
     */
    public function test_a_regular_user_cannot_restore_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('restore', $review));
    }

    /**
     * Test that a guest cannot restore a review.
     * @return void
     */
    public function test_a_guest_cannot_restore_a_review(): void
    {
        $user = $this->createUserWithRoles([]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('restore', $review));
    }

    // --- Tests for `forceDelete` method ---

    /**
     * Test that an admin can force delete a review.
     * @return void
     */
    public function test_an_admin_can_force_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::ADMIN->value]);
        $review = Review::factory()->make();
        $this->assertTrue($user->can('forceDelete', $review));
    }

    /**
     * Test that a moderator cannot force delete a review.
     * @return void
     */
    public function test_a_moderator_cannot_force_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::MODERATOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('forceDelete', $review));
    }

    /**
     * Test that an editor cannot force delete a review.
     * @return void
     */
    public function test_an_editor_cannot_force_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::EDITOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('forceDelete', $review));
    }

    /**
     * Test that an author cannot force delete a review.
     * @return void
     */
    public function test_an_author_cannot_force_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::AUTHOR->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('forceDelete', $review));
    }

    /**
     * Test that a regular user cannot force delete a review.
     * @return void
     */
    public function test_a_regular_user_cannot_force_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([AppRoles::USER->value]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('forceDelete', $review));
    }

    /**
     * Test that a guest cannot force delete a review.
     * @return void
     */
    public function test_a_guest_cannot_force_delete_a_review(): void
    {
        $user = $this->createUserWithRoles([]);
        $review = Review::factory()->make();
        $this->assertFalse($user->can('forceDelete', $review));
    }

    // --- Comprehensive Test for No Permissions ---

    /**
     * Test that a user with no roles/permissions cannot perform any policy action.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createUserWithRoles([]); // User with no roles
        $review = Review::factory()->make();

        $this->assertFalse($user->can('viewAny', Review::class));
        $this->assertFalse($user->can('view', $review));
        $this->assertFalse($user->can('create', Review::class));
        $this->assertFalse($user->can('update', $review));
        $this->assertFalse($user->can('delete', $review));
        $this->assertFalse($user->can('restore', $review));
        $this->assertFalse($user->can('forceDelete', $review));
    }
}
