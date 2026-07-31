<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsApi extends Model
{
    protected $table = 'sports_api';
    
    protected $fillable = [
        'agent_code',
        'api_secret_key', 
        'api_token',
        'api_url',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public static function getActiveApi()
    {
        return static::where('status', 1)->first();
    }
}
