<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'name' => $this->faker->word,
            'level' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->sentence,
            // 'slug' => $this->faker->slug,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
