<?php

namespace App\Models;

use App\Traits\HandlesMediaUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HandlesMediaUploads;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'price',
        'discount_price',
        'stock',
        'sku',
        'is_featured',
        'status',
        'short_description',
        'description'
    ];

    public function media()
    {
        return $this->morphMany(Media::class,'mediable');
    }
}
