<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CasinoGame extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'gameId',
        'provider',
        'name',
        'description',
        'isActive',
        'metadata',
    ];

    protected $casts = [
        'isActive' => 'boolean',
        'metadata' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }

    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('metadata->type', $type);
    }

    public function getImageUrlAttribute()
    {
        return $this->metadata['cover'] ?? $this->metadata['thumbnail'] ?? null;
    }

    public function getGameTypeAttribute()
    {
        return $this->metadata['type'] ?? 'unknown';
    }

    public function getRtpAttribute()
    {
        return $this->metadata['rtp'] ?? 96.0;
    }

    public function isSlot()
    {
        return $this->gameType === 'slots';
    }

    public function isLive()
    {
        return $this->gameType === 'live';
    }

    public function isTable()
    {
        return $this->gameType === 'table';
    }
} 