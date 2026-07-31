<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GameSettings extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'gameId',
        'rtp',
        'minBet',
        'maxBet',
        'maxWin',
        'additionalSettings'
    ];

    protected $casts = [
        'rtp' => 'decimal:2',
        'minBet' => 'decimal:2',
        'maxBet' => 'decimal:2',
        'maxWin' => 'decimal:2',
        'additionalSettings' => 'array',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'gameId');
    }
} 