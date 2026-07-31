<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatchUpdateController extends Controller
{
    public function updateMatches()
    {
        // Türkiye saat dilimini ayarla
        date_default_timezone_set('Europe/Istanbul');
        
        // Hata raporlamayı etkinleştir
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        try {
            // XML verisini URL'den çek
            $xmlUrl = "https://betsapi.tech/api/presports.php?token=QMyGD2MXGZaXBzQutcJ4hStV4";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $xmlUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $xmlData = curl_exec($ch);

            // cURL hatalarını kontrol et
            if ($xmlData === false) {
                throw new \Exception("cURL Hatası: " . curl_error($ch));
            }
            curl_close($ch);

            // XML verisini kontrol et
            if (empty($xmlData)) {
                throw new \Exception("XML verisi boş.");
            }

            // XML verisini parse et
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlData);

            if ($xml === false) {
                $errors = [];
                foreach (libxml_get_errors() as $error) {
                    $errors[] = $error->message;
                }
                throw new \Exception("XML parse hataları: " . implode(", ", $errors));
            }

            // Şu anki Türkiye saatini al
            $current_time = time();
            $threshold_time = $current_time + 60; // 1 dakika ekleyerek eşik zamanı hesapla
            $threshold_datetime = date('Y-m-d H:i:s', $threshold_time);

            $addedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;

            // Her bir <Mac> elemanını döngüyle işle
            foreach ($xml->Mac as $mac) {
                // XML attribute'larını çek
                $eventid = isset($mac['eventid']) ? (string)$mac['eventid'] : '';
                $botid = isset($mac['botid']) ? (string)$mac['botid'] : '';
                $pluskod = isset($mac['pluskod']) ? (string)$mac['pluskod'] : '';
                $hitit_kod = isset($mac['hitit_kod']) ? (string)$mac['hitit_kod'] : '';

                // XML'den gelen maç başlangıç zamanını string olarak al
                $baslangic_str = isset($mac['baslangic']) ? (string)$mac['baslangic'] : '';
                
                // ISO 8601 formatını MySQL datetime formatına çevir
                if ($baslangic_str) {
                    try {
                        // Carbon ile parse et ve MySQL formatına çevir
                        $carbonDate = Carbon::parse($baslangic_str);
                        $baslangic_str = $carbonDate->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        // Eğer Carbon parse edemezse, manuel dönüşüm yap
                        $baslangic_str = str_replace(['T', 'Z'], [' ', ''], $baslangic_str);
                        if (strlen($baslangic_str) == 16) {
                            $baslangic_str .= ':00';
                        }
                    }
                }
                
                $baslangic_time = $baslangic_str ? strtotime($baslangic_str) : 0;

                // Eğer maç zamanı threshold_time'dan küçük veya eşitse SKIP
                if ($baslangic_time <= $threshold_time) {
                    $skippedCount++;
                    continue;
                }

                $evsahibi_isim = isset($mac['evsahibi_isim']) ? (string)$mac['evsahibi_isim'] : '';
                $misafir_isim = isset($mac['misafir_isim']) ? (string)$mac['misafir_isim'] : '';
                $lig_isim = isset($mac['lig_isim']) ? (string)$mac['lig_isim'] : '';
                $lig_id = isset($mac['lig_id']) ? (int)$mac['lig_id'] : 0;
                $ulke_isim = isset($mac['ulke_isim']) ? (string)$mac['ulke_isim'] : '';
                $ulke_id = isset($mac['ulke_id']) ? (int)$mac['ulke_id'] : 0;
                $ligresim = isset($mac['ligresim']) ? (int)$mac['ligresim'] : 0;
                $mackodu = isset($mac['mackodu']) ? (string)$mac['mackodu'] : '';
                $istatistik = isset($mac['istatistik']) ? (int)$mac['istatistik'] : 0;
                $oran_adet = isset($mac['oran_adet']) ? (int)$mac['oran_adet'] : 0;
                $tur = isset($mac['tur']) ? (string)$mac['tur'] : '';
                $tip = isset($mac['tip']) ? (int)$mac['tip'] : 0;
                $oran1 = isset($mac['oran1']) ? (float)$mac['oran1'] : 0.00;
                $oran0 = isset($mac['oran0']) ? (float)$mac['oran0'] : 0.00;
                $oran2 = isset($mac['oran2']) ? (float)$mac['oran2'] : 0.00;
                $sportid = isset($mac['sportid']) ? (int)$mac['sportid'] : 0;
                $mbs = isset($mac['mbs']) ? (int)$mac['mbs'] : 0;
                $countrySlug = isset($mac['countrySlug']) ? (string)$mac['countrySlug'] : '';

                // Veritabanına ekle veya güncelle
                $result = DB::table('bulten')->updateOrInsert(
                    ['eventid' => $eventid], // Unique key
                    [
                        'botid' => $botid,
                        'pluskod' => $pluskod,
                        'hitit_kod' => $hitit_kod,
                        'baslangic' => $baslangic_str,
                        'evsahibi_isim' => $evsahibi_isim,
                        'misafir_isim' => $misafir_isim,
                        'lig_isim' => $lig_isim,
                        'lig_id' => $lig_id,
                        'ulke_isim' => $ulke_isim,
                        'ulke_id' => $ulke_id,
                        'ligresim' => $ligresim,
                        'mackodu' => $mackodu,
                        'istatistik' => $istatistik,
                        'oran_adet' => $oran_adet,
                        'tur' => $tur,
                        'tip' => $tip,
                        'oran1' => $oran1,
                        'oran0' => $oran0,
                        'oran2' => $oran2,
                        'sportid' => $sportid,
                        'mbs' => $mbs,
                        'countrySlug' => $countrySlug,
                        'manuelmi' => 0
                    ]
                );

                if ($result) {
                    $updatedCount++;
                }
            }

            // Eşik zamanından küçük veya eşit olan maçları veritabanından sil
            $deletedCount = DB::table('bulten')
                ->where('baslangic', '<=', $threshold_datetime)
                ->delete();

            $response = [
                'success' => true,
                'message' => 'Maç güncelleme işlemi tamamlandı',
                'details' => [
                    'güncellenen_maç_sayısı' => $updatedCount,
                    'atlanan_maç_sayısı' => $skippedCount,
                    'silinen_maç_sayısı' => $deletedCount,
                    'eşik_zamanı' => $threshold_datetime,
                    'işlem_zamanı' => now()->format('Y-m-d H:i:s')
                ]
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
} 