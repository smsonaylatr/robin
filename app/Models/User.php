<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasUuids, HasApiTokens, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'username',
        'email',
        'password',
        'gender',
        'phoneNumber',
        'country',
        'address',
        'identityNumber',
        'dateOfBirth',
        'balance',
        'isActive',
        'termsAccepted',
        'privacyPolicyAccepted',
        'isAdmin',
        'userGroup',
        'lastLogin',
        'isPromoCodeBlocked',
        'promoCodeBlockReason',
        'promoCodeBlockedAt',
        'gameProviderId'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'balance' => 'decimal:4',
        'isActive' => 'boolean',
        'termsAccepted' => 'boolean',
        'privacyPolicyAccepted' => 'boolean',
        'isAdmin' => 'boolean',
        'dateOfBirth' => 'date',
        'lastLogin' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
        'isPromoCodeBlocked' => 'boolean',
        'promoCodeBlockedAt' => 'datetime'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'userId');
    }

    public function userBonuses()
    {
        return $this->hasMany(UserBonus::class, 'userId');
    }

    public function gameHistory()
    {
        return $this->hasMany(GameHistory::class, 'userId');
    }

    public function wagerHistory()
    {
        return $this->hasMany(WagerHistory::class, 'userBonusId');
    }

    public function userNotes()
    {
        return $this->hasMany(UserNote::class, 'userId');
    }

    public function getFullNameAttribute()
    {
        return trim($this->firstName . ' ' . ($this->middleName ? $this->middleName . ' ' : '') . $this->lastName);
    }

    public function getMaskedUsernameAttribute()
    {
        if (strlen($this->username) <= 4) {
            return $this->username;
        }
        
        return substr($this->username, 0, 3) . '****' . substr($this->username, -4);
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->balance, 2) . ' ₺';
    }

    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('isAdmin', true);
    }

    public function scopeByUserGroup($query, $group)
    {
        return $query->where('userGroup', $group);
    }
} 