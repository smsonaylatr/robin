<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParaYatir extends Model
{
    protected $table = 'parayatir';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'tarih' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'uye', 'id');
    }

    public function isPending()
    {
        return $this->durum == 0;
    }

    public function isApproved()
    {
        return $this->durum == 1;
    }

    public function isRejected()
    {
        return $this->durum == 2;
    }
} 