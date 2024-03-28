<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'description' => Collection::times(4, fn () => fake()->realText(1250))->join(PHP_EOL.PHP_EOL),
            'logo_path' => fake()->imageUrl(1024, 1024),
        ];
    }
}