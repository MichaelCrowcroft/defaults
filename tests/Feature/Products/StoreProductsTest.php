<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\{ actingAs, post };

beforeEach(function () {
    $this->validData = [
        'name' => 'Product Name',
        'summary' => 'This is the product summary',
        'body' => 'This is a product',
        'logo' => UploadedFile::fake()->image('logo.jpg', 1024, 1024),
    ];
});

it('requires authentication', function () {
    post(route('products.store'))
        ->assertRedirect(route('login'));
});

it('can store a product', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('products.store'), [
            ...$this->validData
        ]);

    Storage::disk()->assertExists('logos/' . $this->validData['logo']->hashName());
    $this->assertDatabaseHas(Product::class, [
        'name' => $this->validData['name'],
        'summary' => $this->validData['summary'],
        'body' => $this->validData['body'],
        'logo_path' => 'logos/' . $this->validData['logo']->hashName(),
    ]);

    Storage::delete('logos/' . $this->validData['logo']->hashName());
});

it('redirects to the product show page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('products.store'), $this->validData)
        ->assertRedirect(Product::latest('id')->first()->showRoute());

    Storage::delete('logos/' . $this->validData['logo']->hashName());
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
    [['body' => null], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 12001)], 'body'],
    [['body' => str_repeat('a', 9)], 'body'],
    [['summary' => null], 'summary'],
    [['summary' => 1], 'summary'],
    [['summary' => 1.5], 'summary'],
    [['summary' => true], 'summary'],
    [['summary' => str_repeat('a', 481)], 'summary'],
    [['summary' => str_repeat('a', 9)], 'summary'],
    [['logo' => UploadedFile::fake()->image('logo.jpg', 1024, 1024)->size(2048)], 'logo'],
    [['logo' => UploadedFile::fake()->image('logo.jpg', 800, 1024)], 'logo'],
    [['logo' => UploadedFile::fake()->create('logo.pdf')], 'logo'],
]);