<?php

use App\Models\Review;
use Illuminate\Support\Str;
use Spatie\LaravelMarkdown\MarkdownRenderer;

it('generates html from the body', function () {
    $review = Review::factory()->make(['body' => '## Hello World']);

    $review->save();

    expect($review->html)->toEqual(app(MarkdownRenderer::class)->toHTML(($review->body)));
});