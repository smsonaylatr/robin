<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'method_key',
        'method_name',
        'provider',
        'type',
        'image',
        'min_amount',
        'max_amount',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_amount' => 'integer',
        'max_amount' => 'integer',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('method_name');
    }
} 