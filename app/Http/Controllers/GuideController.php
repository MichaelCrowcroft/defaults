<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use App\Models\Guide;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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

    public function show(Guide $guide)
    {
        //
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
