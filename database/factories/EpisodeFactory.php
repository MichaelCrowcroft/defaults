<?php

namespace Database\Factories;

use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class EpisodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'guide_id' => Guide::factory(),
            'name' => fake()->sentence(3),
            'body' => Collection::times(3, fn () => fake()->realText(320))->join(PHP_EOL.PHP_EOL),
            'video_url' => fake()->url(),
        ];
    }
}
