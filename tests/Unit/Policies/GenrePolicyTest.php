<?php

namespace Tests\Unit\Policies;

use App\Models\API\V1\Genre;
use App\Models\User;
use App\Policies\GenrePolicy;
use PHPUnit\Framework\TestCase;

class GenrePolicyTest extends TestCase
{
    protected GenrePolicy $genrePolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genrePolicy = new GenrePolicy();
    }

    /**
     * Test that a user can view any genres.
     * @return void
     */
    public function test_view(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->genrePolicy->view($user, new Genre()));
    }

    /**
     * Test that a user can view any genres.
     * @return void
     */
    public function test_view_any(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->genrePolicy->viewAny($user));
    }

    /**
     * Test that a user can create a genre.
     * @return void
     */
    public function test_create(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $this->assertTrue($this->genrePolicy->create($user));
    }

    /**
     * Test that a user can update a genre.
     * @return void
     */
    public function test_update(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $genre = new Genre();
        $this->assertTrue($this->genrePolicy->update($user, $genre));
    }

    /**
     * Test that a user can delete a genre.
     * @return void
     */
    public function test_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $genre = new Genre();
        $this->assertTrue($this->genrePolicy->delete($user, $genre));
    }

    /**
     * Test that a user can restore a genre.
     * @return void
     */
    public function test_restore(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $genre = new Genre();
        $this->assertTrue($this->genrePolicy->restore($user, $genre));
    }

    /**
     * Test that a user can permanently delete a genre.
     * @return void
     */
    public function test_force_delete(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(true);
        $genre = new Genre();
        $this->assertTrue($this->genrePolicy->forceDelete($user, $genre));
    }

    /**
     * Test that a user cannot perform any action without the required permissions.
     * @return void
     */
    public function test_no_permissions(): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasPermissionTo')->willReturn(false);

        $genre = new Genre();

        $this->assertFalse($this->genrePolicy->view($user, $genre));
        $this->assertFalse($this->genrePolicy->viewAny($user));
        $this->assertFalse($this->genrePolicy->create($user));
        $this->assertFalse($this->genrePolicy->update($user, $genre));
        $this->assertFalse($this->genrePolicy->delete($user, $genre));
        $this->assertFalse($this->genrePolicy->restore($user, $genre));
        $this->assertFalse($this->genrePolicy->forceDelete($user, $genre));
    }
}
