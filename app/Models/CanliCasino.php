<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanliCasino extends Model
{
    protected $table = 'canli_casino';
    protected $guarded = [];
    public $timestamps = false;

    public function scopeActive($query)
    {
        // Aktif kolonu yok, tüm canlı casino oyunlarını döndür
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sira', 'asc');
    }
} 