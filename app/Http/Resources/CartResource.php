<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->items?->id,
            "product_id" => $this->items?->product_id,
            "quantity" => $this->items?->quantity,
            "price" => $this->items?->price,
            "created_at" => $this->items?->created_at,
            "updated_at" => $this->items?->updated_at,
        ];
    }
}
