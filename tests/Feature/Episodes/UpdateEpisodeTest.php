<?php

use App\Models\Episode;
use App\Models\User;
use function Pest\Laravel\{ actingAs, patch };

beforeEach(function () {
    $this->validData = [
        'name' => 'Episode Name',
        'video_url' => 'https://www.youtube.com/watch?v=eVli-tstM5E',
        'body' => 'This is an episode',
    ];
});

it('requires authentication', function () {
    $episode = Episode::factory()->create();

    patch(route('episodes.update', $episode))
        ->assertRedirect(route('login'));
});

it('can update a episode', function () {
    $episode = Episode::factory()->create();

    actingAs($episode->user)
        ->patch(route('episodes.update', $episode), $this->validData);

    $this->assertDatabaseHas(Episode::class, [
        'id' => $episode->id,
        ...$this->validData
    ]);
});

it('redirects to the guide show page', function () {
    $episode = Episode::factory()->create();

    actingAs($episode->user)
        ->patch(route('episodes.update', $episode), $this->validData)
        ->assertRedirect($episode->guide->showRoute());
});

it('redirects to the correct page of episodes', function () {
    $episode = episode::factory()->create();

    actingAs($episode->user)
        ->patch(route('episodes.update', [
            'episode' => $episode,
            'page' => 2,
        ]), $this->validData)
        ->assertRedirect($episode->product->showRoute(['page' => 2]));
});

it('does not let users update other users episodes', function () {
    $user = User::factory()->create();
    $episode = episode::factory()->create();

    actingAs($user)
        ->patch(route('episodes.update', $episode), $this->validData)
        ->assertForbidden();
});

it('does not let guests update user episodes', function () {
    $episode = episode::factory()->create();

    patch(route('episodes.update', $episode), $this->validData)
        ->AssertRedirect('login');
});

it('requires valid data', function (array $badData, array|string $errors) {
    $episode = episode::factory()->create();

    actingAs($episode->user)
        ->patch(route('episodes.update', $episode), [
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