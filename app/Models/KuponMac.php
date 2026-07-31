<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuponMac extends Model
{
    protected $table = 'kupon_mac';
    public $timestamps = false;
    
    protected $fillable = [
        'oranid',
        'kuponid',
        'userid',
        'oran',
        'tur',
        'macid',
        'sonuc',
        'tarih',
        'canli',
        'session_id',
        'canlidakika',
        'skor',
        'iptal',
        'aciklamasi',
        'orangrup',
        'evsahibi',
        'deplasman',
        'mackodu',
        'sport_id',
        'matchdate',
        'result',
        'canliskor',
        'botid'
    ];

    protected $casts = [
        'tarih' => 'datetime',
        'matchdate' => 'datetime',
        'oran' => 'float'
    ];

    // Kupon ile ilişki
    public function kupon()
    {
        return $this->belongsTo(Kupon::class, 'kuponid');
    }

    // Kullanıcı ile ilişki
    public function user()
    {
        return $this->belongsTo(Admin::class, 'userid');
    }
} 