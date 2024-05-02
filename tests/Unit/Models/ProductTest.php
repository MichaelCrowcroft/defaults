<?php

use App\Models\Product;
use Illuminate\Support\Str;

it('uses title case for titles', function () {
    $product = Product::factory()->create([
        'name' => 'a new product name',
    ]);

    expect($product->name)->toBe('A New Product Name');
});

it('can generate a route to the show page', function () {
    $product = Product::factory()->create();

    expect($product->showRoute())->toBe(route('products.show', [$product, Str::slug($product->name)]));
});

it('can generate additional query paramaters on the show route', function () {
    $product = Product::factory()->create();

    expect($product->showRoute(['page' => 2]))->toBe(route('products.show', [
        $product,
        Str::slug($product->name),
        'page' => 2,
    ]));
});

it('generates html from the body', function () {
    $product = Product::factory()->make(['body' => '## Hello World']);

    $product->save();

    expect($product->html)->toEqual(Str::markdown($product->body));
});