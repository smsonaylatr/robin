<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserNote extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'type',
        'content',
        'userId',
        'createdBy'
    ];

    protected $casts = [
        'createdAt' => 'datetime'
    ];

    const TYPE_RISK = 'risk';
    const TYPE_VIP = 'vip';
    const TYPE_BONUS = 'bonus';
    const TYPE_PAYMENT = 'payment';
    const TYPE_SUPPORT = 'support';
    const TYPE_RULE_VIOLATION = 'rule_violation';
    const TYPE_GENERAL = 'general';
    const TYPE_BAN = 'ban';
    const TYPE_KYC = 'kyc';
    const TYPE_TRANSACTION = 'transaction';

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_RISK => 'Risk',
            self::TYPE_VIP => 'VIP',
            self::TYPE_BONUS => 'Bonus',
            self::TYPE_PAYMENT => 'Ödeme',
            self::TYPE_SUPPORT => 'Destek',
            self::TYPE_RULE_VIOLATION => 'Kural İhlali',
            self::TYPE_GENERAL => 'Genel',
            self::TYPE_BAN => 'Yasak',
            self::TYPE_KYC => 'KYC',
            self::TYPE_TRANSACTION => 'İşlem'
        ];

        return $labels[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            self::TYPE_RISK => 'red',
            self::TYPE_VIP => 'purple',
            self::TYPE_BONUS => 'yellow',
            self::TYPE_PAYMENT => 'blue',
            self::TYPE_SUPPORT => 'green',
            self::TYPE_RULE_VIOLATION => 'red',
            self::TYPE_GENERAL => 'gray',
            self::TYPE_BAN => 'red',
            self::TYPE_KYC => 'orange',
            self::TYPE_TRANSACTION => 'blue'
        ];

        return $colors[$this->type] ?? 'gray';
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('userId', $userId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('createdAt', 'desc')->limit($limit);
    }
} 