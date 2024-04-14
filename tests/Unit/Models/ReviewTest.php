<?php

use App\Models\Review;
use Illuminate\Support\Str;

it('generates html from the body', function () {
    $review = Review::factory()->make(['body' => '## Hello World']);

    $review->save();

    expect($review->html)->toEqual(Str::markdown($review->body));
});