<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TournamentParticipant extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tournamentId',
        'userId',
        'score',
        'rank',
        'joinedAt'
    ];

    protected $casts = [
        'score' => 'integer',
        'rank' => 'integer',
        'joinedAt' => 'datetime'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournamentId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
} 