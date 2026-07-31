<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchOddsController extends Controller
{
    public function showMatchOdds($id)
    {
        return view('match-odds', ['id' => $id]);
    }

    public function getMatchOdds($eventid): JsonResponse
    {
        // Hata raporlamayı tamamen aç
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        // EventID'yi pozitif tam sayı olarak doğrula
        if (!is_numeric($eventid) || $eventid < 1) {
            return response()->json([
                'success' => false,
                'error' => 'Geçerli bir `eventid` belirtmelisiniz. Örnek: /MatchOdds/1021176435'
            ], 400);
        }

        try {
            // API URL ve token tanımı
            $apiToken = 'QMyGD2MXGZaXBzQutcJ4hStV4';
            $apiUrl = sprintf(
                'https://betsapi.tech/api/presportsodds.php?token=%s&matchid=%d',
                $apiToken,
                $eventid
            );

            // cURL seçenekleri
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);

            $response = curl_exec($ch);
            
            if ($response === false) {
                $curlErr = curl_error($ch);
                curl_close($ch);
                return response()->json([
                    'success' => false,
                    'error' => "API isteği sırasında hata oluştu: {$curlErr}"
                ], 502);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return response()->json([
                    'success' => false,
                    'error' => "API isteği başarısız oldu. HTTP Durum Kodu: {$httpCode}",
                    'raw_response' => $response
                ], $httpCode);
            }

            // JSON'u çöz
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'error' => 'JSON verisi çözümlenirken hata: ' . json_last_error_msg(),
                    'raw_response' => $response
                ], 502);
            }

            // API yanıtında özel bir `error` alanı varsa kullanıcıya ilet
            if (isset($data['error'])) {
                return response()->json([
                    'success' => false,
                    'error' => $data['error']
                ], 502);
            }

            // Başarılı yanıtı JSON olarak dön
            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Beklenmeyen hata: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLiveOdds($eventid)
    {
        // Hata raporlamayı tamamen aç
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        // EventID'yi pozitif tam sayı olarak doğrula
        if (!is_numeric($eventid) || $eventid < 1) {
            return response('Geçerli bir eventid belirtmelisiniz.', 400);
        }

        try {
            // API URL ve token tanımı - liveodds.php endpoint'ini kullan
            $apiToken = 'QMyGD2MXGZaXBzQutcJ4hStV4';
            $apiUrl = sprintf(
                'https://betsapi.tech/api/liveodds.php?token=%s&matchid=%d',
                $apiToken,
                $eventid
            );

            // cURL seçenekleri
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);

            $response = curl_exec($ch);
            
            if ($response === false) {
                $curlErr = curl_error($ch);
                curl_close($ch);
                return response("API isteği sırasında hata oluştu: {$curlErr}", 502);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return response("API isteği başarısız oldu. HTTP Durum Kodu: {$httpCode}", $httpCode);
            }

            // API'den gelen veri zaten XML formatında, direkt olarak döndür
            return response($response, 200, [
                'Content-Type' => 'application/xml; charset=utf-8'
            ]);

        } catch (\Exception $e) {
            return response('Beklenmeyen hata: ' . $e->getMessage(), 500);
        }
    }

    private function convertJsonToXml($data)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<MatchDetails>' . "\n";
        
        if (isset($data['results']) && is_array($data['results'])) {
            foreach ($data['results'] as $match) {
                $xml .= '  <E Home="' . htmlspecialchars($match['home_name'] ?? '') . '" Away="' . htmlspecialchars($match['away_name'] ?? '') . '">' . "\n";
                
                if (isset($match['odds']) && is_array($match['odds'])) {
                    foreach ($match['odds'] as $betType) {
                        $xml .= '    <G Name="' . htmlspecialchars($betType['name'] ?? '') . '">' . "\n";
                        
                        if (isset($betType['odds']) && is_array($betType['odds'])) {
                            foreach ($betType['odds'] as $odd) {
                                $xml .= '      <R Name="' . htmlspecialchars($odd['name'] ?? '') . '" O0="' . ($odd['odds'] ?? '0') . '" GameIsVisible="1" />' . "\n";
                            }
                        }
                        
                        $xml .= '    </G>' . "\n";
                    }
                }
                
                $xml .= '  </E>' . "\n";
            }
        }
        
        $xml .= '</MatchDetails>';
        
        return $xml;
    }
} 