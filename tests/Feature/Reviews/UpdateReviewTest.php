<?php

use App\Models\Review;
use App\Models\User;
use function Pest\Laravel\{ actingAs, patch };

beforeEach(function () {
    $this->validData = [
        'body' => 'This is a review',
        'stars' => 5,
    ];
});

it('requires authentication', function () {
    $review = Review::factory()->create();

    patch(route('reviews.update', $review))
        ->assertRedirect(route('login'));
});

it('can update a review', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', $review), $this->validData);

    $this->assertDatabaseHas(Review::class, [
        'id' => $review->id,
        ...$this->validData
    ]);
});

it('redirects to the product show page', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', $review), $this->validData)
        ->assertRedirect($review->product->showRoute());
});

it('redirects to the correct page of reviews', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', [
            'review' => $review,
            'page' => 2,
        ]), $this->validData)
        ->assertRedirect($review->product->showRoute(['page' => 2]));
});

it('does not let users update other users reviews', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create();

    actingAs($user)
        ->patch(route('reviews.update', $review), $this->validData)
        ->assertForbidden();
});

it('does not let guests update user reviews', function () {
    $review = Review::factory()->create();

    patch(route('reviews.update', $review), $this->validData)
        ->AssertRedirect('login');
});

it('requires valid data', function (array $badData, array|string $errors) {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', $review), [
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