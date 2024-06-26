<?php

namespace Database\Seeders;

use App\Models\Episode;
use App\Models\Guide;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(10)->create();

        Product::factory(50)
            ->has(Review::factory(15)->recycle($users))
            ->has(Guide::factory(1)->recycle($users)->has(Episode::factory(3)->recycle($users)))
            ->recycle($users)
            ->create();

        User::factory()->create([
            'name' => 'Test User',
            'handle' => 'tester.man',
            'email' => 'test@mail.com',
        ]);
    }
}
