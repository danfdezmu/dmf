<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'stack' => 'Laravel, Vue, MySQL',
            'url' => fake()->optional()->url(),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_published' => true,
        ];
    }
}
