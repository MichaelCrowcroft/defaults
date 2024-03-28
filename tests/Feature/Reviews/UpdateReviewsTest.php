<?php

use App\Models\Review;
use App\Models\User;
use function Pest\Laravel\{ actingAs, patch };

it('requires authentication', function () {
    $review = Review::factory()->create();

    patch(route('reviews.update', $review))
        ->assertRedirect(route('login'));
});

it('can update a review', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', $review), [
            'body' => 'Updated body text'
        ]);

    $this->assertDatabaseHas(Review::class, [
        'id' => $review->id,
        'body' => 'Updated body text',
    ]);
});

it('redirects to the product show page', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', $review), [
            'body' => 'Updated body text',
        ])
        ->assertRedirect($review->product->showRoute());
});

it('redirects to the correct page of reviews', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', [
            'review' => $review,
            'page' => 2,
        ]), [
            'body' => 'Updated body text',
        ])
        ->assertRedirect($review->product->showRoute(['page' => 2]));
});

it('does not let users update other users reviews', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create();

    actingAs($user)
        ->patch(route('reviews.update', $review), [
            'body' => 'Updated body text',
        ])
        ->assertForbidden();
});

it('does not let guests update user reviews', function () {
    $review = Review::factory()->create();

    patch(route('reviews.update', $review), [
            'body' => 'Updated body text',
        ])
        ->AssertRedirect('login');
});

it('requires a valid body', function ($body) {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->patch(route('reviews.update', $review), [
            'body' => $body,
        ])
        ->assertInvalid('body');
})->with([
    null,
    true,
    1,
    1.5,
    str_repeat('a', 2501)
]);