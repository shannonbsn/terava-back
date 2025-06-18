<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ProfileController
 */
final class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_responds_with(): void
    {
        $profiles = Profile::factory()->count(3)->create();

        $response = $this->get(route('profiles.index'));

        $response->assertOk();
        $response->assertJson($profiles);
    }


    #[Test]
    public function show_responds_with(): void
    {
        $profile = Profile::factory()->create();
        $profiles = Profile::factory()->count(3)->create();

        $response = $this->get(route('profiles.show', $profile));

        $response->assertOk();
        $response->assertJson($profiles);
    }


    #[Test]
    public function store_saves_and_responds_with(): void
    {
        $response = $this->post(route('profiles.store'));

        $response->assertOk();
        $response->assertJson($profile);

        $this->assertDatabaseHas(profiles, [ /* ... */ ]);
    }


    #[Test]
    public function update_responds_with(): void
    {
        $profile = Profile::factory()->create();
        $profiles = Profile::factory()->count(3)->create();

        $response = $this->put(route('profiles.update', $profile));

        $profile->refresh();

        $response->assertOk();
        $response->assertJson($profile);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $profile = Profile::factory()->create();
        $profiles = Profile::factory()->count(3)->create();

        $response = $this->delete(route('profiles.destroy', $profile));

        $response->assertNoContent();

        $this->assertModelMissing($profile);
    }
}
