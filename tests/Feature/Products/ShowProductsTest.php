<?php

use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use function Pest\Laravel\{ get };

it('can show products and reviews with a product resource and comment resource', function () {
    $product = Product::factory()->create();
    $reviews = Review::factory(2)->for($product)->create();

    $reviews->load('user');

    get($product->showRoute())
        ->assertHasResource('product', ProductResource::make($product))
        ->assertHasPaginatedResource('reviews', ReviewResource::collection($reviews->reverse()))
        ->assertComponent('Products/Show');
});

it('will redirect if the slug is incorrect', function () {
    $product = Product::factory()->create(['name' => 'Product Name']);

    get(route('products.show', [$product, 'Not The Product Name', 'page' => 2]))
        ->assertRedirect($product->showRoute(['page' => 2]));
});