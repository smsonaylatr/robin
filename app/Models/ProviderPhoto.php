<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gorsel',
        'link',
        'sira',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'sira' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sira', 'asc');
    }
} 