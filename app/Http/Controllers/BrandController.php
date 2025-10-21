<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\CloudinaryService;
use Illuminate\Support\Str;

class BrandController extends Controller
{

    public function index()
    {
        $brands = Brand::query()
            ->with('media')
            ->get();
        return BrandResource::collection($brands);
    }

    public function store(BrandRequest $request)
    {

        $baseSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Brand::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $brand = Brand::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);


        if ($request->hasFile('image')) {
            $brand->uploadMedia($request->file('image'),'image','dorashop/brands');
        }

        return new BrandResource($brand);
    }

    public function show($id)
    {
        $brand = Brand::query()
            ->with('media')
            ->findOrFail($id);
        return BrandResource::make($brand);

    }

    public function update($id, BrandRequest $request)
    {

        $brand = Brand::findOrFail($id);

        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            $brand->replaceMedia($request->file('image'),'image','dorashop/brands');
        }

        return response()->json([
            'message' => 'Brand Updated successfully',
            'data' => BrandResource::make($brand)
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->deleteMedia();
        $brand->delete();

        return response()->json([
            'message' => 'Brand Deleted successfully'
        ]);


    }
}
