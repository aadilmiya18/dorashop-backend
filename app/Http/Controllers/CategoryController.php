<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::query()
            ->with('media')
            ->get();
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {

        $baseSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
            'parent_id' => $request->parent_id,
        ]);


        if ($request->hasFile('image')) {
            $category->uploadMedia($request->file('image'),'image','dorashop/categories');
        }

        return new CategoryResource($category);
    }

    public function show($id)
    {
        $category = Category::query()
            ->with('media')
            ->findOrFail($id);
        return CategoryResource::make($category);

    }

    public function update($id, CategoryRequest $request)
    {

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'parent_id' => $request->parent_id,
        ]);

        if ($request->hasFile('image')) {
            $category->replaceMedia($request->file('image'),'image','dorashop/categories');
        }

        return response()->json([
            'message' => 'Category Updated successfully',
            'data' => CategoryResource::make($category)
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->deleteMedia();
        $category->delete();

        return response()->json([
            'message' => 'Category Deleted successfully'
        ]);


    }
}
