<?php

namespace App\Models;

use App\Traits\HandlesMediaUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes, HandlesMediaUploads;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function scopeQueryFilter($query, $search)
    {
        if (!$search) {
            return $query;
        }
//        return $query->where('name','like',"%{$search}%");

        return  $query->where(function ($q) use ($search){
            $q->where('name','like',"%{$search}%")
                ->orWhere('slug','like',"%{$search}%");
        });

    }

    public function scopeStatusFilter($query, $val)
    {
        if($val === null) {
            return $query;
        }

        return $query->where('status',$val);
    }

}
