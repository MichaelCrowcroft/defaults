<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        Review::create([
            ...$data,
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
        ]);

        return Redirect::to($product->showRoute(['page' => $request->query('page')]))
            ->banner('Review Added');
    }

    public function update(Request $request, Review $review)
    {
        Gate::authorize('update', $review);

        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        $review->update($data);

        return Redirect::to($review->product->showRoute(['page' => $request->query('page')]))
            ->banner('Review Updated');
    }

    public function delete(Request $request, Review $review)
    {
        Gate::authorize('delete', $review);

        $review->delete();

        return Redirect::to($review->product->showRoute(['page' => $request->query('page')]))
            ->bannerDanger('Review Deleted');

    }
}