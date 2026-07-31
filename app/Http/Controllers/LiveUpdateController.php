<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class LiveUpdateController extends Controller
{
    public function updateLiveMatches()
    {
        try {
            // XML kaynağını tanımla
            $xmlUrl = "https://betsapi.tech/api/livesports.php?token=QMyGD2MXGZaXBzQutcJ4hStV4";

            // cURL ile XML verisini çek
            $ch = curl_init($xmlUrl);
            if ($ch === false) {
                throw new Exception("cURL başlatılamadı.");
            }

            // cURL seçeneklerini ayarla
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Geliştirme için

            $xmlContent = curl_exec($ch);
            if (curl_errno($ch)) {
                $errorMsg = curl_error($ch);
                curl_close($ch);
                throw new Exception("cURL Hatası: " . $errorMsg);
            }
            curl_close($ch);

            // XML'i parse et
            libxml_use_internal_errors(true);
            $xmlData = simplexml_load_string($xmlContent);

            if (!$xmlData) {
                $errors = libxml_get_errors();
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        Log::error("XML Hatası: " . $error->message);
                    }
                }
                libxml_clear_errors();
                throw new Exception("XML verisi parse edilemedi!");
            }

            // Transaction başlat
            DB::beginTransaction();

            $processedMacIds = [];

            // XML içindeki <Mac> etiketlerini işle
            foreach ($xmlData->Mac as $mac) {
                $macData = [
                    'mac_id' => (string)$mac['id'],
                    'sportid' => (string)$mac['sportid'],
                    'betradar_id' => (string)$mac['betradar_id'],
                    'skor' => (string)$mac['skor'],
                    'baslangic' => (string)$mac['baslangic'],
                    'dakika' => (string)$mac['dakika'],
                    'sure_detay' => (string)$mac['sure_detay'],
                    'oynuyormu' => (string)$mac['oynuyormu'],
                    'aktifmi' => (string)$mac['aktifmi'],
                    'ulke' => (string)$mac['ulke'],
                    'ulke_id' => (string)$mac['ulke_id'],
                    'lig' => (string)$mac['lig'],
                    'lig_id' => (string)$mac['lig_id'],
                    'oran_adet' => (string)$mac['oran_adet'],
                    'tip' => (string)$mac['tip'],
                    'tur' => (string)$mac['tur'],
                    'evsahibi_isim' => (string)$mac['evsahibi_isim'],
                    'misafir_isim' => (string)$mac['misafir_isim'],
                ];

                // Insert veya update işlemi
                DB::table('canlibulten')->updateOrInsert(
                    ['mac_id' => $macData['mac_id']],
                    $macData
                );

                $processedMacIds[] = $macData['mac_id'];
            }

            // XML'de olmayan maçları (silinenleri) veritabanından temizle
            if (!empty($processedMacIds)) {
                DB::table('canlibulten')
                    ->whereNotIn('mac_id', $processedMacIds)
                    ->delete();
            }

            // Transaction'ı onayla
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Canlı maçlar başarıyla güncellendi.',
                'processed_count' => count($processedMacIds)
            ]);

        } catch (Exception $e) {
            // Hata durumunda transaction'ı geri al
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::error('Live Update Hatası: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
}