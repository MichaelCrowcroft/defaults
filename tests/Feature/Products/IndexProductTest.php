<?php

use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use function Pest\Laravel\{ get };

it('can index products with paginated products', function () {
    $products = Product::factory(5)->create();

    get(route('products.index'))
        ->assertHasPaginatedResource('products', ProductResource::collection($products->reverse()))
        ->assertComponent('Products/Index');
});
