<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "product_id" => $this->product_id,
            "product_name" =>$this->product->name,
            "product_slug" =>$this->product->slug,
            "product_image" => $this->product->media->where('type','image')->pluck('url')->first() ?? null,
            "product_brand" => $this->product?->brand?->name,
            "product_category" => $this->product?->category?->name,
            "product_stock" => $this->product?->stock,
            "quantity" => $this->quantity,
            "price" => $this->price,
        ];
    }
}
