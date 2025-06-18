<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\Matche;
use App\Models\User;
use App\Models\Message;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'match_id' => Matche::inRandomOrder()->first()->id ?? Matche::factory()->create()->id,
            'sender_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'content' => $this->faker->sentence(),
            'sent_at' => Carbon::now()->subMinutes(rand(1, 1440)),
        ];
    }
}
