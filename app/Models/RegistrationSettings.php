<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_name',
        'field_label',
        'is_active',
        'is_required',
        'field_order',
        'field_type',
        'field_options'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'field_order' => 'integer',
        'field_options' => 'array'
    ];

    /**
     * Aktif olan form alanlarını getir
     */
    public static function getActiveFields()
    {
        return self::where('is_active', true)
                   ->orderBy('field_order')
                   ->get();
    }

    /**
     * İlk adımdaki alanları getir (Step 1)
     */
    public static function getStep1Fields()
    {
        $step1Fields = [
            'firstName', 'lastName', 'tc', 'birthDate', 'il', 'ilce', 'postakodu'
        ];
        
        return self::whereIn('field_name', $step1Fields)
                   ->where('is_active', true)
                   ->orderBy('field_order')
                   ->get();
    }

    /**
     * İkinci adımdaki alanları getir (Step 2)
     */
    public static function getStep2Fields()
    {
        $step2Fields = [
            'username', 'email', 'phoneNumber', 'parabirimi'
        ];
        
        return self::whereIn('field_name', $step2Fields)
                   ->where('is_active', true)
                   ->orderBy('field_order')
                   ->get();
    }

    /**
     * Her zaman zorunlu olması gereken alanlar
     */
    public static function getAlwaysRequiredFields()
    {
        return ['firstName', 'lastName', 'username', 'phoneNumber'];
    }

    /**
     * Her zaman aktif olması gereken alanlar
     */
    public static function getAlwaysActiveFields()
    {
        return ['firstName', 'lastName', 'username', 'phoneNumber'];
    }

    /**
     * Belirli bir alanın aktif olup olmadığını kontrol et
     */
    public static function isFieldActive($fieldName)
    {
        // Her zaman aktif olması gereken alanlar
        if (in_array($fieldName, self::getAlwaysActiveFields())) {
            return true;
        }
        
        $field = self::where('field_name', $fieldName)->first();
        return $field ? $field->is_active : false;
    }

    /**
     * Belirli bir alanın zorunlu olup olmadığını kontrol et
     */
    public static function isFieldRequired($fieldName)
    {
        // Her zaman zorunlu olması gereken alanlar
        if (in_array($fieldName, self::getAlwaysRequiredFields())) {
            return true;
        }
        
        $field = self::where('field_name', $fieldName)->first();
        return $field ? $field->is_required : false;
    }

    /**
     * Tüm form alanlarını admin paneli için getir
     */
    public static function getAllFieldsForAdmin()
    {
        return self::orderBy('field_order')->get();
    }

    /**
     * Alan ayarlarını güncelle
     */
    public static function updateFieldSettings($fieldName, $settings)
    {
        return self::where('field_name', $fieldName)
                   ->update($settings);
    }
}