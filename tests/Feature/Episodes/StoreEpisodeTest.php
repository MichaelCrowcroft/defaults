<?php

use App\Models\episode;
use App\Models\Guide;
use App\Models\User;
use function Pest\Laravel\{ actingAs, post };

beforeEach(function () {
    $this->validData = [
        'name' => 'Episode Name',
        'video_url' => 'https://www.youtube.com/watch?v=eVli-tstM5E',
        'body' => 'This is an episode',
    ];
});

it('requires authentication', function () {
    $guide = Guide::factory()->create();

    post(route('guides.episodes.store', $guide))
        ->assertRedirect(route('login'));
});

it('can store a episode', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create();

    actingAs($user)
        ->post(route('guides.episodes.store', $guide), $this->validData);

    $this->assertDatabaseHas(Episode::class, [
        'guide_id' => $guide->id,
        'user_id' => $user->id,
        ...$this->validData,
    ]);
});

it('redirects to the guide show page after storing a episode', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create();

    actingAs($user)
        ->post(route('guides.episodes.store', $guide), $this->validData)
        ->assertRedirect($guide->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();
    $guide = Guide::factory()->create();

    actingAs($user)
        ->post(route('guides.episodes.store', $guide), [
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
    [['video_url' => null], 'video_url'],
    [['video_url' => 1.5], 'video_url'],
    [['video_url' => 'not a url'], 'video_url'],
]);