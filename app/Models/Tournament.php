<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tournament extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'startDate',
        'endDate',
        'prizePool',
        'entryFee',
        'maxParticipants',
        'status',
        'rules',
        'winnerId'
    ];

    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'prizePool' => 'decimal:2',
        'entryFee' => 'decimal:2',
        'maxParticipants' => 'integer',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    const STATUS_UPCOMING = 'upcoming';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function participants()
    {
        return $this->hasMany(TournamentParticipant::class, 'tournamentId');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winnerId');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_UPCOMING => 'Yakında',
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_COMPLETED => 'Tamamlandı',
            self::STATUS_CANCELLED => 'İptal Edildi'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_UPCOMING => 'blue',
            self::STATUS_ACTIVE => 'green',
            self::STATUS_COMPLETED => 'gray',
            self::STATUS_CANCELLED => 'red'
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getParticipantCountAttribute()
    {
        return $this->participants()->count();
    }

    public function getIsFullAttribute()
    {
        return $this->participantCount >= $this->maxParticipants;
    }

    public function getIsActiveAttribute()
    {
        $now = now();
        return $this->status === self::STATUS_ACTIVE && 
               $this->startDate <= $now && 
               $this->endDate >= $now;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', self::STATUS_UPCOMING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
} 