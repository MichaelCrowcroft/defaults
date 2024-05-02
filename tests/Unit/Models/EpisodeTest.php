<?php

use App\Models\Episode;
use App\Models\Guide;
use Illuminate\Support\Str;

it('generates html from the body', function () {
    $episode = Episode::factory()->make(['body' => '## Hello World']);

    $episode->save();

    expect($episode->html)->toEqual(Str::markdown($episode->body));
});

it('episodes in a guide are created in order', function () {
    $guide = Guide::factory()
        ->has(Episode::factory()->count(3))
        ->create();

    $episode = Episode::factory()->for($guide)->create();

    expect($episode->order)->toEqual(4);
});