<?php

namespace App\Models;

use App\Traits\HandlesMediaUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, HandlesMediaUploads;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'parent_id'
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeQueryFilter($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        });

    }

    public function scopeStatusFilter($query, $val)
    {
        if ($val === null) {
            return $query;
        }

        return $query->where('status', $val);

    }

}
