<?php

use App\Models\Episode;
use App\Models\Guide;
use Spatie\LaravelMarkdown\MarkdownRenderer;

it('generates html from the body', function () {
    $episode = Episode::factory()->make(['body' => '## Hello World']);

    $episode->save();

    expect($episode->html)->toEqual(app(MarkdownRenderer::class)->toHTML($episode->body));
});

it('episodes in a guide are created in order', function () {
    $guide = Guide::factory()
        ->has(Episode::factory()->count(3))
        ->create();

    $episode = Episode::factory()->for($guide)->create();

    expect($episode->order)->toEqual(4);
});