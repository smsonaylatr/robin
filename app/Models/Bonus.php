<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonuses';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function isActive()
    {
        return $this->aktif == 1;
    }

    public function isDemo()
    {
        return $this->deneme == 1;
    }

    public function isWelcome()
    {
        return $this->hosgeldin == 1;
    }

    public function isDeposit()
    {
        return $this->yatirim == 1;
    }
} 