<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oyunlar extends Model
{
    protected $table = 'oyunlar';
    protected $guarded = [];
    public $timestamps = false;

    public function scopeActive($query)
    {
        // Aktif kolonu yok, tüm oyunları döndür
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sira', 'asc');
    }
} 