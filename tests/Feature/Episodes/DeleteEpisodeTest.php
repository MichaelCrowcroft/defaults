<?php

use App\Models\Review;
use App\Models\User;
use function Pest\Laravel\{ actingAs, delete };

it('requires authentication', function () {
    $review = Review::factory()->create();

    delete(route('reviews.delete', $review))
        ->assertRedirect(route('login'));
});

it('can delete a review', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->delete(route('reviews.delete', $review));

    $this->assertModelMissing($review);
});

it('redirects to the product show page', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->delete(route('reviews.delete', $review))
        ->assertRedirect($review->product->showRoute());
});

it('redirects to the correct page of reviews', function () {
    $review = Review::factory()->create();

    actingAs($review->user)
        ->delete(route('reviews.delete', [
            'review' => $review,
            'page' => 2,
        ]))
        ->assertRedirect($review->product->showRoute(['page' => 2]));
});

it('does not let users delete other users reviews', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create();

    actingAs($user)
        ->delete(route('reviews.delete', $review))
        ->assertForbidden();
});

it('does not let guests delete user reviews', function () {
    $review = Review::factory()->create();

    delete(route('reviews.delete', $review))
        ->assertRedirect(route('login'));
});