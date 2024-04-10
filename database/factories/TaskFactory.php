<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'user_id' => \App\Models\User::factory(),
            'description' => $this->faker->text,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'category' => $this->faker->randomElement(['work', 'personal']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'attachment' => null,
        ];
    }
}
