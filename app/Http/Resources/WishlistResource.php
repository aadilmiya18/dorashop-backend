<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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
            'product_id' => $this->product ? $this->product?->id : null,
            'product_slug' => $this->product ? $this->product?->slug : null,
            'product_name' => $this->product ? $this->product?->name : null,
            'product_price' => $this->product ? $this->product?->price : null,
            'product_discount_price' => $this->product ? $this->product?->discount_price : null,
            'product_stock' => $this->product ? $this->product?->stock : null,
            "product_image" => $this->product->media->where('type','image')->pluck('url')->first() ?? null,
        ];
    }
}
