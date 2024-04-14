<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class GuideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'name' => fake()->sentence(3),
            'body' => Collection::times(3, fn () => fake()->realText(320))->join(PHP_EOL.PHP_EOL),
            'icon_path' => fake()->imageUrl(1024, 1024),
        ];
    }
}
