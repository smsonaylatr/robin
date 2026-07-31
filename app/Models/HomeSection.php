<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'name',
        'title',
        'icon',
        'order',
        'is_active',
        'is_required'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Aktif bölümleri sıralı şekilde getir
     */
    public static function getActiveSections()
    {
        return self::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Bölüm sırasını güncelle
     */
    public static function updateOrder($sections)
    {
        foreach ($sections as $index => $sectionId) {
            self::where('id', $sectionId)->update(['order' => $index + 1]);
        }
    }

    /**
     * Bölümü aktif/pasif yap
     */
    public static function toggleActive($id)
    {
        $section = self::find($id);
        if ($section && !$section->is_required) {
            $section->update(['is_active' => !$section->is_active]);
            return $section;
        }
        return false;
    }
} 