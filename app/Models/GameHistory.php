<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GameHistory extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'userId',
        'gameId',
        'gameName',
        'betAmount',
        'winAmount'
    ];

    protected $casts = [
        'betAmount' => 'decimal:2',
        'winAmount' => 'decimal:2',
        'createdAt' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'gameId');
    }

    public function getNetAmountAttribute()
    {
        return $this->winAmount - $this->betAmount;
    }

    public function getProfitLossAttribute()
    {
        return $this->winAmount - $this->betAmount;
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('userId', $userId);
    }

    public function scopeByGame($query, $gameId)
    {
        return $query->where('gameId', $gameId);
    }

    public function scopeProfitable($query)
    {
        return $query->whereRaw('winAmount > betAmount');
    }

    public function scopeLossMaking($query)
    {
        return $query->whereRaw('winAmount < betAmount');
    }
} 