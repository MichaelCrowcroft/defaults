<?php

use App\Http\Resources\EpisodeResource;
use App\Http\Resources\GuideResource;
use App\Models\Episode;
use App\Models\Guide;
use function Pest\Laravel\{ get };

it('can show guides and episodes with a guide resource and episode resource', function () {
    $guide = Guide::factory()->create();
    $episodes = Episode::factory(2)->for($guide)->create();

    $episodes->load('user');

    get($guide->showRoute())
        ->assertHasResource('guide', GuideResource::make($guide))
        // ->assertHasPaginatedResource('episodes', EpisodeResource::collection($episodes->reverse()))
        ->assertComponent('Guides/Show');
});

it('will redirect if the slug is incorrect', function () {
    $guide = Guide::factory()->create(['name' => 'Guide Name']);

    get(route('guides.show', [$guide, 'Not The Guide Name', 'page' => 2]))
        ->assertRedirect($guide->showRoute(['page' => 2]));
});