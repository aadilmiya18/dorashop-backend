<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'name',
        'email',
        'mobile',
        'address',
        'subtotal',
        'shipping',
        'total',
        'ref_id'
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeQueryFilter($query, $val)
    {
        if (!$val) {
            return $query;
        }

        return $query->where('name', 'like', "%{$val}%");
    }

    public function scopeStatusFilter($query, $val)
    {
        if (!$val) return;

        return $query->where('status', $val);
    }


}
