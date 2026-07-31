<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $guarded = [];
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function isWin()
    {
        return $this->type === 'win';
    }

    public function isBet()
    {
        return $this->type === 'bet';
    }
} 