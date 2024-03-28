<?php

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\{ actingAs, post };

it('requires authentication', function () {
    $product = Product::factory()->create();

    post(route('products.reviews.store', $product))
        ->assertRedirect(route('login'));
});

it('can store a review', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), [
            'body' => 'This is a review',
        ]);

    $this->assertDatabaseHas(Review::class, [
        'product_id' => $product->id,
        'user_id' => $user->id,
        'body' => 'This is a review',
    ]);
});

it('redirects to the product show page after storing a review', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), [
            'body' => 'This is a review',
        ])
        ->assertRedirect($product->showRoute());
});

it('requires a valid body', function ($value) {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), [
            'body' => $value,
        ])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 2501),
]);

it('redirects to the product show page', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), [
            'body' => 'this is a comment',
        ])
        ->assertRedirect($product->showRoute());
});