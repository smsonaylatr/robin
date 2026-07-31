<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasinoOyunlari extends Model
{
    protected $table = 'casino_oyunlari';
    protected $guarded = [];
    public $timestamps = false;

    public function scopeActive($query)
    {
        // Aktif kolonu yok, tüm casino oyunlarını döndür
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sira', 'asc');
    }
} 