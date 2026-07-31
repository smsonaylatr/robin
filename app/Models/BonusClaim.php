<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusClaim extends Model
{
    protected $table = 'bonus_claims';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id', 'id');
    }
} 