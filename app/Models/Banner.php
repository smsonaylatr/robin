<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'imageUrl',
        'link',
        'order',
        'isActive',
        'startDate',
        'endDate',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'order' => 'integer',
        'isActive' => 'boolean',
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('isActive', true)
                    ->where(function($q) {
                        $q->whereNull('startDate')
                          ->orWhere('startDate', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('endDate')
                          ->orWhere('endDate', '>=', now());
                    });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Methods
    public function isCurrentlyActive()
    {
        if (!$this->isActive) {
            return false;
        }

        $now = now();

        if ($this->startDate && $now < $this->startDate) {
            return false;
        }

        if ($this->endDate && $now > $this->endDate) {
            return false;
        }

        return true;
    }
} 