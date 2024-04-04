<?php

use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\{ actingAs, post };

beforeEach(function () {
    $this->validData = [
        'name' => 'Product Name',
        'summary' => 'This is the product summary',
        'description' => 'This is a product',
    ];
});

it('requires authentication', function () {
    post(route('products.store'))
        ->assertRedirect(route('login'));
});

it('can store a product', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('products.store'), $this->validData);

    $this->assertDatabaseHas(Product::class, $this->validData);
});

it('redirects to the product show page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('products.store'), $this->validData)
        ->assertRedirect(Product::latest('id')->first()->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('products.store'), [
            ...$this->validData,
            ...$badData,
        ])
        ->assertInvalid($errors);
})->with([
    [['name' => null], 'name'],
    [['name' => 1], 'name'],
    [['name' => 1.5], 'name'],
    [['name' => true], 'name'],
    [['name' => str_repeat('a', 121)], 'name'],
    [['name' => str_repeat('a', 2)], 'name'],
    [['description' => null], 'description'],
    [['description' => 1], 'description'],
    [['description' => 1.5], 'description'],
    [['description' => true], 'description'],
    [['description' => str_repeat('a', 12001)], 'description'],
    [['description' => str_repeat('a', 9)], 'description'],
    [['summary' => null], 'summary'],
    [['summary' => 1], 'summary'],
    [['summary' => 1.5], 'summary'],
    [['summary' => true], 'summary'],
    [['summary' => str_repeat('a', 481)], 'summary'],
    [['summary' => str_repeat('a', 9)], 'summary'],
]);