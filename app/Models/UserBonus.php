<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserBonus extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'userId',
        'bonusId',
        'amountGiven',
        'wagerTarget',
        'wagerProgress',
        'status',
        'isWagerCompleted',
        'claimedAt'
    ];

    protected $casts = [
        'amountGiven' => 'decimal:4',
        'wagerTarget' => 'decimal:4',
        'wagerProgress' => 'decimal:4',
        'isWagerCompleted' => 'boolean',
        'claimedAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonusId');
    }

    public function wagerHistory()
    {
        return $this->hasMany(WagerHistory::class, 'userBonusId');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->wagerTarget == 0) return 0;
        return min(100, ($this->wagerProgress / $this->wagerTarget) * 100);
    }

    public function getRemainingWagerAttribute()
    {
        return max(0, $this->wagerTarget - $this->wagerProgress);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
} 