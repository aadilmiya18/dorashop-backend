<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category?->name,
            'category_id' => $this->category_id,
            'brand' => $this->brand?->name,
            'brand_id' => $this->brand_id,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'media' => $this->media->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'url' => $item->url
                ];
            })
        ];
    }
}
