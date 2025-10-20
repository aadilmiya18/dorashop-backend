<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;
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
