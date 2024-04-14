<?php

use App\Models\Guide;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\{ actingAs, patch };

beforeEach(function () {
    $this->validData = [
        'name' => 'Guide Name',
        'body' => 'This is a guide',
        'icon' => UploadedFile::fake()->image('icon.jpg', 1024, 1024),
    ];
});

it('requires authentication', function () {
    $guide = Guide::factory()->create();

    patch(route('guides.update', $guide))
        ->assertRedirect(route('login'));
});

it('can update a guide', function () {
    $guide = Guide::factory()->create();

    actingAs($guide->user)
        ->patch(route('guides.update', $guide), $this->validData);

    $this->assertDatabaseHas(Guide::class, [
        'id' => $guide->id,
        'name' => $this->validData['name'],
        'body' => $this->validData['body'],
        'icon_path' => 'icons/' . $this->validData['icon']->hashName(),
    ]);

    Storage::delete('icons/' . $this->validData['icon']->hashName());
});

it('redirects to the product show page', function () {
    $guide = Guide::factory()->create();

    actingAs($guide->user)
        ->patch(route('guides.update', $guide), $this->validData)
        ->assertRedirect($guide->product->showRoute());
});

it('redirects to the correct page of guides', function () {
    $guide = Guide::factory()->create();

    actingAs($guide->user)
        ->patch(route('guides.update', [
            'guide' => $guide,
            'page' => 2,
        ]), $this->validData)
        ->assertRedirect($guide->product->showRoute(['page' => 2]));
});

it('does not let users update other users guides', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create();

    actingAs($user)
        ->patch(route('guides.update', $guide), $this->validData)
        ->assertForbidden();
});

it('does not let guests update user guides', function () {
    $guide = Guide::factory()->create();

    patch(route('guides.update', $guide), $this->validData)
        ->AssertRedirect('login');
});

it('requires valid data', function (array $badData, array|string $errors) {
    $guide = Guide::factory()->create();

    actingAs($guide->user)
        ->patch(route('guides.update', $guide), [
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