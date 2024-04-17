<?php

use App\Models\User;
use function Pest\Laravel\{ actingAs, get };

it('requires authentication', function () {
    get(route('products.create'))
        ->assertRedirect(route('login'));
});

it('can show the product create form', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('products.create'))
        ->assertComponent('Products/Create');
});