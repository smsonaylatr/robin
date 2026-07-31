<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kupon extends Model
{
    protected $table = 'kupon';
    public $timestamps = false;
    
    protected $fillable = [
        'userid',
        'ad',
        'miktar',
        'oran',
        'tarih',
        'durum',
        'sil',
        'kesim',
        'odeme',
        'iptal',
        'ip',
        'odendi',
        'canli',
        'sonmactarih',
        'toplam',
        'kazanan',
        'skontrol',
        'bonus',
        'numara'
    ];

    protected $casts = [
        'tarih' => 'datetime',
        'miktar' => 'float',
        'oran' => 'float',
        'odeme' => 'float'
    ];

    // Bekleyen kuponlar (durum = 0)
    public static function bekleyenKuponlar()
    {
        return self::where('durum', 0)->count();
    }

    // Bugünkü kuponlar
    public static function bugunKuponlar()
    {
        return self::whereDate('tarih', today())->count();
    }

    // Toplam kuponlar
    public static function toplamKuponlar()
    {
        return self::count();
    }

    // Bugün vs dün kupon yüzde hesaplaması
    public static function bugunYuzde()
    {
        $bugun = self::bugunKuponlar(); // Bugünkü tüm kuponlar
        $dun = self::whereDate('tarih', today()->subDay())->count(); // Dünkü tüm kuponlar
        
        // Eğer her ikisi de 0 ise
        if ($bugun == 0 && $dun == 0) {
            return 0;
        }
        
        // Eğer dün 0 ise ama bugün var ise
        if ($dun == 0 && $bugun > 0) {
            return 0; // Artık %100 yerine 0 döndür
        }
        
        // Normal yüzde hesaplama
        if ($dun > 0) {
            $yuzde = (($bugun - $dun) / $dun) * 100;
            return round($yuzde, 1);
        }
        
        return 0;
    }

    // Kupon maçları ile ilişki
    public function kuponMaclar()
    {
        return $this->hasMany(KuponMac::class, 'kuponid');
    }

    // Kullanıcı ile ilişki
    public function user()
    {
        return $this->belongsTo(Admin::class, 'userid');
    }
} 