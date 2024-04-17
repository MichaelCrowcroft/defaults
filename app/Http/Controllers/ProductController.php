<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest('id')
            ->paginate();

        return Inertia::render('Products/Index', [
            'products' => fn () => ProductResource::collection($products),
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->safe()->except('logo');

        if($request->file('logo')) {
            $logo_path = $request->file('logo')->storePublicly('logos');
            $data['logo_path'] = $logo_path;
        }

        $product = Product::create($data);

        return Redirect::to($product->showRoute());
    }

    public function show(Request $request, Product $product)
    {
        if(! Str::endsWith($product->showRoute(), $request->path())) {
            return redirect($product->showRoute($request->query()), status: 301);
        }

        return Inertia::render('Products/Show', [
            'product' => fn () => ProductResource::make($product),
            'reviews' => fn () => ReviewResource::collection($product->reviews()->with('user')->latest('id')->paginate(10)),
        ]);
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }
}
