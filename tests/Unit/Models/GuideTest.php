<?php

use App\Models\Guide;
use Illuminate\Support\Str;

it('generates html from the body', function () {
    $guide = Guide::factory()->make(['body' => '## Hello World']);

    $guide->save();

    expect($guide->html)->toEqual(Str::markdown($guide->body));
});