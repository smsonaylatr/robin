<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'name',
        'gorsel',
        'sira',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'sira' => 'integer'
    ];

    // Aktif ödeme yöntemlerini sıralı şekilde getir
    public static function getActivePayments()
    {
        return static::where('status', true)
                    ->orderBy('sira', 'asc')
                    ->get();
    }
} 