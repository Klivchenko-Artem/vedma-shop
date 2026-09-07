<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 999999),
            'description' => $this->faker->sentence(),
            'composition' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(500, 10000),
            'images' => [],
            'is_popular' => false,
            'sort_order' => 0,
        ];
    }

    public function popular(): static
    {
        return $this->state(fn () => ['is_popular' => true]);
    }

    public function price(float $price): static
    {
        return $this->state(fn () => ['price' => $price]);
    }
}
