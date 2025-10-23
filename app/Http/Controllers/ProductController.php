<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $rowsPerPage = $request->input('rowsPerPage', 50);
        $sortBy = $request->input('sortBy', 'id');
        $descending = $request->boolean('descending', false);
        $filters = json_decode($request->input('filters', '{}'), true);

        $query = Product::query();

        if (!empty($filters['query'])) {
            $query->queryFilter($filters['query']);
        }

        if (array_key_exists('status', $filters)) {
            $query->statusFilter($filters['status']);
        }

        if (array_key_exists('featured', $filters)) {
            $query->featuredFilter($filters['featured']);
        }

        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        $products = $query->paginate($rowsPerPage, ['*'], 'page', $page);

        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $baseSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'short_description' => $request->short_description ?? null,
            'description' => $request->description ?? null,
            'price' => $request->price ?? 0,
            'discount_price' => $request->discount_price ?? 0,
            'stock' => $request->stock ?? 0,
            'sku' => $request->sku ?? null,
            'is_featured' => $request->is_featured,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            $product->uploadMedia($request->file('image'), 'image', 'dorashop/products');
        }

        if ($request->hasFile('gallery')) {
            $product->uploadMultipleMedia($request->file('gallery'), 'gallery', 'dorashop/products/gallery');
        }

        return new ProductResource($product->load('media'));

    }


    public function show($id)
    {
        $product = Product::query()->findOrFail($id);

        return ProductResource::make($product);
    }

    public function productDetailsBySlug($slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->with(['media' => function ($query) {
                $query->where('type', '!=', 'image');
            },
                'brand',
                'category'
            ])
            ->first();

        return ProductResource::make($product);
    }


    public function update($id, ProductRequest $request)
    {
        $product = Product::query()->findOrFail($id);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'short_description' => $request->short_description ?? null,
            'description' => $request->description ?? null,
            'price' => $request->price ?? 0,
            'discount_price' => $request->discount_price ?? 0,
            'stock' => $request->stock ?? 0,
            'sku' => $request->sku ?? null,
            'is_featured' => $request->is_featured,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            $product->replaceMedia($request->file('image'), 'image', 'dorashop/products');
        }

        if ($request->hasFile('gallery')) {
            $product->deleteMedia('gallery');
            $product->uploadMultipleMedia($request->file('gallery'), 'gallery', 'dorashop/products/gallery');
        }

        return response()->json([
            'message' => 'Product Updated successfully',
            'data' => ProductResource::make($product)
        ]);

    }


    public function destroy($id)
    {
        $product = Product::query()->findOrFail($id);

        $product->deleteMedia();
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

}
