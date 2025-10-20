<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'url',
        'type'
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}
