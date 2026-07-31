<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialButton extends Model
{
    use HasFactory;

    protected $table = 'social_buttons';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'platform',
        'url',
        'order',
        'isActive',
        'createdAt',
        'updatedAt'
    ];

    protected $casts = [
        'order' => 'integer',
        'isActive' => 'boolean',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime'
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // Constants
    const PLATFORM_INSTAGRAM = 'instagram';
    const PLATFORM_TWITTER = 'twitter';
    const PLATFORM_TELEGRAM = 'telegram';
    const PLATFORM_FACEBOOK = 'facebook';
    const PLATFORM_YOUTUBE = 'youtube';
    const PLATFORM_TIKTOK = 'tiktok';

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function scopeByPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    // Methods
    public function isActive()
    {
        return $this->isActive;
    }

    public function getIconClass()
    {
        $icons = [
            self::PLATFORM_INSTAGRAM => 'fab fa-instagram',
            self::PLATFORM_TWITTER => 'fab fa-twitter',
            self::PLATFORM_TELEGRAM => 'fab fa-telegram',
            self::PLATFORM_FACEBOOK => 'fab fa-facebook',
            self::PLATFORM_YOUTUBE => 'fab fa-youtube',
            self::PLATFORM_TIKTOK => 'fab fa-tiktok',
        ];

        return $icons[$this->platform] ?? 'fas fa-link';
    }
} 