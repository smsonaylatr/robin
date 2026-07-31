<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayarlar extends Model
{
    protected $table = 'ayarlar';
    protected $guarded = [];

    protected $casts = [
        'site_durum' => 'integer',
        'homespor' => 'integer',
        'homewin' => 'integer',
        'smsadet' => 'integer',
        'dogrulama' => 'integer',
    ];

    public static function getSettings()
    {
        return static::first();
    }

    public function isSiteActive()
    {
        return $this->site_durum == 1;
    }

    public function deleteOldFile($filePath)
    {
        if ($filePath && file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }
    }
}
