<?php

use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    Route::post('/products/{product}/guides', [GuideController::class, 'store'])->name('products.guides.store');
    // Route::delete('/guides/{guide}', [GuideController::class, 'delete'])->name('guides.delete');
    Route::patch('/guides/{guide}', [GuideController::class, 'update'])->name('guides.update');

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'delete'])->name('reviews.delete');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}/{slug}', [ProductController::class, 'show'])->name('products.show');

require __DIR__.'/auth.php';
