<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duyuru extends Model
{
    protected $table = 'duyuru';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];
} 