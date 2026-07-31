<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Admin extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    
    protected $table = 'admin';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'bakiye' => 'float',
        'durum' => 'integer',
        'postakodu' => 'integer',
        'tc' => 'integer',
        'spor' => 'integer',
        'casino' => 'integer',
        'cekim' => 'integer',
        '2factor' => 'integer',
        'aff' => 'integer',
        'kayit_tarih' => 'datetime',
        'dt' => 'date',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function isActive()
    {
        return $this->durum == 1;
    }

    public function getBalanceAttribute()
    {
        return $this->bakiye;
    }

    public function getCurrencyAttribute()
    {
        return $this->parabirimi;
    }

    public function deposits()
    {
        return $this->hasMany(ParaYatir::class, 'uye', 'id');
    }

    public function withdrawals()
    {
        return $this->hasMany(ParaCek::class, 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }

    public function bonusClaims()
    {
        return $this->hasMany(BonusClaim::class, 'user_id', 'id');
    }
} 