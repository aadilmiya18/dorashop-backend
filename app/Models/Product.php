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

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function scopeQueryFilter($query, $search)
    {
        if(!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search){
            $q->where('name','like',"%{$search}%")
                ->orWhere('slug','like',"%{$search}%")
                ->orWhere('sku','like', "{$search}%")
            ;
        });

    }

    public function scopeStatusFilter($query, $val)
    {
        if($val === null){
            return $query;
        }

        return $query->where('status',$val);
    }
    public function scopeFeaturedFilter($query, $val)
    {
        if($val === null){
            return $query;
        }

        return $query->where('is_featured',$val);
    }
}
