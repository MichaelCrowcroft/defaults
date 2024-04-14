<?php

use App\Models\Guide;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\{ actingAs, post };

beforeEach(function () {
    $this->validData = [
        'name' => 'Guide Name',
        'body' => 'This is a guide',
        'icon' => UploadedFile::fake()->image('icon.jpg', 1024, 1024),
    ];
});

it('requires authentication', function () {
    $product = Product::factory()->create();

    post(route('products.guides.store', $product))
        ->assertRedirect(route('login'));
});

it('can store a guide', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.guides.store', $product), $this->validData);

    $this->assertDatabaseHas(Guide::class, [
        'product_id' => $product->id,
        'user_id' => $user->id,
        'name' => $this->validData['name'],
        'body' => $this->validData['body'],
        'icon_path' => 'icons/' . $this->validData['icon']->hashName(),
    ]);

    Storage::delete('icons/' . $this->validData['icon']->hashName());
});

it('redirects to the product show page after storing a guide', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.guides.store', $product), $this->validData)
        ->assertRedirect($product->showRoute());

    Storage::delete('icons/' . $this->validData['icon']->hashName());
});

it('requires valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    actingAs($user)
        ->post(route('products.guides.store', $product), [
            ...$this->validData,
            ...$badData,
        ])
        ->assertInvalid($errors);
})->with([
    [['body' => null], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 12001)], 'body'],
    [['name' => null], 'name'],
    [['name' => 1], 'name'],
    [['name' => 1.5], 'name'],
    [['name' => true], 'name'],
    [['name' => str_repeat('a', 2501)], 'name'],
    [['icon' => UploadedFile::fake()->image('icon.jpg', 1024, 1024)->size(2048)], 'icon'],
    [['icon' => UploadedFile::fake()->image('icon.jpg', 800, 1024)], 'icon'],
    [['icon' => UploadedFile::fake()->create('icon.pdf')], 'icon'],
]);