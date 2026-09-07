<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'author' => $this->faker->name(),
            'text' => $this->faker->sentence(),
            'rating' => 5,
        ];
    }
}
