<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';
    protected $guarded = [];
    public $timestamps = false;

    public function scopeActive($query)
    {
        // Aktif kolonu yok, tüm slider'ları döndür
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sira', 'asc');
    }
} 