<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSettings;
use Illuminate\Http\Request;

class RegistrationSettingsController extends Controller
{




    /**
     * Tek bir alanın aktif/pasif durumunu değiştir
     */
    public function toggleField(Request $request, $fieldName)
    {
        $request->validate([
            'is_active' => 'boolean',
            'is_required' => 'boolean'
        ]);

        try {
            $alwaysRequired = RegistrationSettings::getAlwaysRequiredFields();
            $alwaysActive = RegistrationSettings::getAlwaysActiveFields();
            
            $updateData = [];
            
            // Aktif durumu kontrolü
            if ($request->has('is_active')) {
                if (in_array($fieldName, $alwaysActive)) {
                    $updateData['is_active'] = true; // Temel alanlar her zaman aktif
                } else {
                    $updateData['is_active'] = $request->is_active;
                }
            }
            
            // Zorunlu durumu kontrolü
            if ($request->has('is_required')) {
                if (in_array($fieldName, $alwaysRequired)) {
                    $updateData['is_required'] = true; // Temel alanlar her zaman zorunlu
                } else {
                    $updateData['is_required'] = $request->is_required;
                }
            }
            
            if (!empty($updateData)) {
                RegistrationSettings::updateFieldSettings($fieldName, $updateData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Alan durumu başarıyla güncellendi!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alan sırasını güncelle
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.field_name' => 'required|string',
            'orders.*.field_order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->orders as $order) {
                RegistrationSettings::updateFieldSettings($order['field_name'], [
                    'field_order' => $order['field_order']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Alan sıralaması başarıyla güncellendi!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}