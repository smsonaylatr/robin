<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasinoRecentWinner extends Model
{
    use HasFactory;

    protected $table = 'casino_recent_winners';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'username',
        'game',
        'amount',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Scopes
    public function scopeRecent($query, $limit = 6)
    {
        return $query->orderBy('createdAt', 'desc')->limit($limit);
    }

    public function scopeByGame($query, $game)
    {
        return $query->where('game', $game);
    }
} 