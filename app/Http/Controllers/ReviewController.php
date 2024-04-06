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
    public function store(StoreReviewRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->reviews()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return Redirect::to($product->showRoute(['page' => $request->query('page')]))
            ->banner('Review Added');
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $data = $request->validated();

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