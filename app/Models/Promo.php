<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo_codes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'code',
        'amount',
        'usedCount',
        'maxUses',
        'status',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'usedCount' => 'integer',
        'maxUses' => 'integer',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_EXPIRED = 'expired';

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('usedCount', '<', 'maxUses');
    }

    // Methods
    public function isAvailable()
    {
        return $this->status === self::STATUS_ACTIVE && $this->usedCount < $this->maxUses;
    }

    public function incrementUsage()
    {
        $this->increment('usedCount');
        
        if ($this->usedCount >= $this->maxUses) {
            $this->update(['status' => self::STATUS_EXPIRED]);
        }
    }
} 