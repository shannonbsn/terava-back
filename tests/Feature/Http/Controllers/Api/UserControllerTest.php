<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\UserController
 */
final class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_responds_with(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertJson($users);
    }


    #[Test]
    public function show_responds_with(): void
    {
        $user = User::factory()->create();
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('users.show', $user));

        $response->assertOk();
        $response->assertJson($users);
    }


    #[Test]
    public function store_saves_and_responds_with(): void
    {
        $response = $this->post(route('users.store'));

        $response->assertOk();
        $response->assertJson($user);

        $this->assertDatabaseHas(users, [ /* ... */ ]);
    }


    #[Test]
    public function update_responds_with(): void
    {
        $user = User::factory()->create();
        $users = User::factory()->count(3)->create();

        $response = $this->put(route('users.update', $user));

        $user->refresh();

        $response->assertOk();
        $response->assertJson($user);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $user = User::factory()->create();
        $users = User::factory()->count(3)->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }
}
