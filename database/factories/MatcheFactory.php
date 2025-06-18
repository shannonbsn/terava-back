<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Matche;
use App\Models\Trip;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matche>
 */
class MatcheFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Matche::class;

    public function definition(): array
    {
        return [
            // 'trip1_id' => Trip::inRandomOrder()->first()->id ?? Trip::factory()->create()->id,
            // 'trip2_id' => Trip::inRandomOrder()->first()->id ?? Trip::factory()->create()->id,
            // 'matched_at' => Carbon::now()->subDays(rand(1, 30)),
            // 'status' => $this->faker->randomElement(['pending', 'confirmed', 'declined']),
        ];
    }
}
