<?php

use App\Models\Guide;
use Spatie\LaravelMarkdown\MarkdownRenderer;

it('generates html from the body', function () {
    $guide = Guide::factory()->make(['body' => '## Hello World']);

    $guide->save();

    expect($guide->html)->toEqual(app(MarkdownRenderer::class)->toHTML($guide->body));
});