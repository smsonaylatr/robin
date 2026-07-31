<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WagerHistory extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'userBonusId',
        'gameId',
        'amount',
        'sessionId',
        'gameType',
        'betAmount',
        'winAmount'
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'betAmount' => 'decimal:4',
        'winAmount' => 'decimal:4',
        'createdAt' => 'datetime'
    ];

    const GAME_TYPE_CASINO = 'casino';
    const GAME_TYPE_SPORTS = 'sports';

    public function userBonus()
    {
        return $this->belongsTo(UserBonus::class, 'userBonusId');
    }

    public function user()
    {
        return $this->userBonus->user();
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'gameId');
    }

    public function getNetAmountAttribute()
    {
        return $this->winAmount - $this->betAmount;
    }

    public function scopeByUserBonus($query, $userBonusId)
    {
        return $query->where('userBonusId', $userBonusId);
    }

    public function scopeByGameType($query, $gameType)
    {
        return $query->where('gameType', $gameType);
    }

    public function scopeCasino($query)
    {
        return $query->where('gameType', self::GAME_TYPE_CASINO);
    }

    public function scopeSports($query)
    {
        return $query->where('gameType', self::GAME_TYPE_SPORTS);
    }
} 