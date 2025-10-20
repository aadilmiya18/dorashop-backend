<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function media()
    {
        return $this->morphMany(Media::class,'mediable');
    }
}
