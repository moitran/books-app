<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->realText();

        return [
            'id' => Uuid::uuid4()->toString(),
            'book_number' => $this->faker->unique()->isbn13 . $this->faker->unique()->numberBetween(1, 1000000),
            'title' => 'Book title: ' . $this->faker->realText(20),
            'slug' => Str::slug($title) . time() . $this->faker->unique()->numberBetween(1, 1000000),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'provider_id' => Provider::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
