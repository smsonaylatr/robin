<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Yonetici extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    
    protected $table = 'yoneticiler';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'olusturulma_tarihi' => 'datetime',
        'durum' => 'integer',
    ];

    public function getAuthPassword()
    {
        return $this->sifre;
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

    public function isAdmin()
    {
        return $this->yetki === 'admin';
    }

    public function isActive()
    {
        return $this->durum == 1;
    }
} 