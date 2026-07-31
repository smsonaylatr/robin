<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParaCek extends Model
{
    protected $table = 'paracek';
    protected $guarded = [];
    public $timestamps = true;

    protected $casts = [
        'tarih' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
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