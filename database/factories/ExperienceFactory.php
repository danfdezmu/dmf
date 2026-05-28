<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-5 years', '-1 year');

        return [
            'company' => fake()->company(),
            'role' => 'Desarrollador de software',
            'location' => fake()->city(),
            'started_at' => $startedAt,
            'ended_at' => fake()->optional()->dateTimeBetween($startedAt, 'now'),
            'description' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_published' => true,
        ];
    }

    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'ended_at' => null,
        ]);
    }
}
