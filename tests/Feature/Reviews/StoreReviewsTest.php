<?php

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\{ actingAs, post };

beforeEach(function () {
    $this->validData = [
        'body' => 'This is a review',
        'stars' => 5,
    ];
});

it('requires authentication', function () {
    $product = Product::factory()->create();

    post(route('products.reviews.store', $product))
        ->assertRedirect(route('login'));
});

it('can store a review', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), $this->validData);

    $this->assertDatabaseHas(Review::class, [
        'product_id' => $product->id,
        'user_id' => $user->id,
        ...$this->validData,
    ]);
});

it('redirects to the product show page after storing a review', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), $this->validData)
        ->assertRedirect($product->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.reviews.store', $product), [
            ...$this->validData,
            ...$badData,
        ])
        ->assertInvalid($errors);
})->with([
    [['body' => null], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 2501)], 'body'],
    [['stars' => null], 'stars'],
    [['stars' => 1.5], 'stars'],
    [['stars' => str_repeat('a', 5)], 'stars'],
]);