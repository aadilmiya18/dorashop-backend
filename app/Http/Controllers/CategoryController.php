<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $page = $request->input('page',1);
        $rowsPerPage = $request->input('rowsPerPage',50);
        $sortBy = $request->input('sortBy','id');
        $descending = $request->boolean('descending',false);
        $filters = json_decode($request->input('filters','{}'),true);

        $query = Category::query();

        if(!empty($filters['query']))
        {
            $query->queryFilter($filters['query']);
        }

        if(array_key_exists('status',$filters))
        {
            $query->statusFilter($filters['status']);
        }

        $query->orderBy($sortBy,$descending ? 'desc' : 'asc');

        $categories = $query->paginate($rowsPerPage,['*'],'page',$page);

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
