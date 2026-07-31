<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BottomBannerImage extends Model
{
    use HasFactory;

    protected $table = 'bottom_banner_images';

    protected $fillable = [
        'gorsel',
        'url',
        'sira',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'sira' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('aktif', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sira', 'asc');
    }
}


