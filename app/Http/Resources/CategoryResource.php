<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'parent_slug' => $this->parent?->slug,
            'description' => $this->description,
            'status' => $this->status,
            'media' => $this->media->first() ? [
                'id' => $this->media->first()->id,
                'url' => $this->media->first()->url,
                'type' => $this->media->first()->type
            ] : null,
            'children' => $this->whenLoaded('children',function (){
                return $this->children
                    ->where('status',1)
                    ->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'slug' => $child->slug,
                        'parent_slug' => $child->parent?->slug,
                        'media' => $child->media ? [
                            'id' => $child->media->first()?->id,
                            'url' => $child->media->first()?->url,
                        ] : null,
                    ];
                })->values();
            }),
            'products' => $this->whenLoaded('products',function () {
                return $this->products->map(function ($product) {
                    $media = $product->media->first();
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'stock' => $product->stock,
                        'media' => $media ? [
                          'id' => $media->id,
                          'url' => $media->url,
                        ] : null,
                    ];
                });
            }),
            'all_products' => $this->when(isset($this->all_products), function () {
                return $this->all_products->map(function ($product) {
                    $media = $product->media->first();
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'stock' => $product->stock,
                        'media' => $media ? [
                            'id' => $media->id,
                            'url' => $media->url,
                        ] : null,
                    ];
                });
            }),

        ];
    }
}
