<?php

namespace App\Http\Resources;

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
            'description' => $this->description,
            'status' => $this->status,
            'media' => $this->media->first() ? [
                'id' => $this->media->first()->id,
                'url' => $this->media->first()->url,
                'type' => $this->media->first()->type
            ] : null,
        ];
    }
}
