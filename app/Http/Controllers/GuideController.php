<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use App\Http\Resources\EpisodeResource;
use App\Http\Resources\GuideResource;
use App\Models\Guide;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GuideController extends Controller
{
    public function index()
    {
        //
    }

    public function store(StoreGuideRequest $request, Product $product)
    {
        $data = $request->safe()->except('icon');

        if($request->file('icon')) {
            $icon_path = $request->file('icon')->storePublicly('icons');
            $data['icon_path'] = $icon_path;
        }

        $product->guides()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return Redirect::to($product->showRoute(['page' => $request->query('page')]))
            ->banner('Guide Added');
    }

    public function show(Request $request, Guide $guide)
    {
        if(! Str::endsWith($guide->showRoute(), $request->path())) {
            return redirect($guide->showRoute($request->query()), status: 301);
        }

        return Inertia::render('Guides/Show', [
            'guide' => fn () => GuideResource::make($guide),
            'episodes' => fn () => EpisodeResource::collection($guide->episodes()->with(['user', 'guide'])->latest('id')->paginate(10)),
        ]);
    }

    public function update(UpdateGuideRequest $request, Guide $guide)
    {
        $data = $request->safe()->except('icon');

        if($request->file('icon')) {
            Storage::delete('icons/' . $guide->icon_path);
            $icon_path = $request->file('icon')->storePublicly('icons');
            $data['icon_path'] = $icon_path;
        }

        $guide->update($data);

        return Redirect::to($guide->product->showRoute(['page' => $request->query('page')]))
            ->banner('Guide Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guide $guide)
    {
        //
    }
}
