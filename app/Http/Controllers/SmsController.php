<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ayarlar;
use App\Models\SmsHistory;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function index()
    {
        $settings = Ayarlar::getSettings();
        $recentSms = SmsHistory::latest()->take(10)->get();
        
        return view('admin.sms-send', compact('settings', 'recentSms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:160'
        ]);

        // Ayarlardan SMS kredisini al
        $settings = Ayarlar::getSettings();
        $smsCredit = $settings->smsadet ?? 0;

        // Telefon numaralarını ayır ve temizle
        $phoneNumbersRaw = array_filter(array_map('trim', explode(',', $request->phone)));
        $phoneNumbers = [];
        
        foreach ($phoneNumbersRaw as $phone) {
            $phoneNumbers[] = $this->normalizePhone($phone);
        }

        $smsNeeded = count($phoneNumbers);

        // SMS kredisi kontrolü
        if ($smsCredit < $smsNeeded) {
            return redirect()->back()
                ->with('error', "SMS kredisi yetersiz. {$smsNeeded} kişiye SMS göndermek için {$smsNeeded} kredi gerekiyor. Mevcut kredi: {$smsCredit}")
                ->withInput();
        }

        // SMS API bilgileri
        $sms_api_url = "https://customersms/SendSmsV2";
        $sms_api_key = "2344";

        $successCount = 0;
        $failedCount = 0;

        foreach ($phoneNumbers as $phone) {
            // SMS API verisi hazırla
            $data = [
                'apiToken' => $sms_api_key,
                'messageType' => '1',
                'messageEncoding' => '1',
                'destinationAddress' => $phone,
                'sourceAddress' => 'INFO',
                'messageText' => $request->message
            ];

            try {
                // cURL isteği gönder
                $response = Http::timeout(30)->post($sms_api_url, [$data]);
                $responseData = $response->json();

                // API yanıtını kontrol et
                if ($response->successful() && isset($responseData[0])) {
                    if (isset($responseData[0]['error_code']) && $responseData[0]['error_code'] !== null && $responseData[0]['error_code'] !== "0") {
                        // Başarısız
                        SmsHistory::create([
                            'phone' => $phone,
                            'message' => $request->message,
                            'status' => 'failed',
                            'response' => json_encode($responseData[0])
                        ]);
                        $failedCount++;
                    } else {
                        // Başarılı
                        SmsHistory::create([
                            'phone' => $phone,
                            'message' => $request->message,
                            'status' => 'success',
                            'response' => json_encode($responseData[0])
                        ]);
                        $successCount++;
                    }
                } else {
                    // Başarısız
                    SmsHistory::create([
                        'phone' => $phone,
                        'message' => $request->message,
                        'status' => 'failed',
                        'response' => json_encode($responseData)
                    ]);
                    $failedCount++;
                }
            } catch (\Exception $e) {
                // Hata durumunda
                SmsHistory::create([
                    'phone' => $phone,
                    'message' => $request->message,
                    'status' => 'failed',
                    'response' => $e->getMessage()
                ]);
                $failedCount++;
            }
        }

        // SMS kredisini güncelle
        $newSmsCount = max(0, $smsCredit - $successCount);
        Ayarlar::where('id', $settings->id)->update(['smsadet' => $newSmsCount]);

        $message = "SMS gönderimi tamamlandı. Başarılı: {$successCount}, Başarısız: {$failedCount}";
        
        if ($failedCount > 0) {
            return redirect()->back()->with('error', $message);
        } else {
            return redirect()->back()->with('success', $message);
        }
    }

    private function normalizePhone($phone)
    {
        $phone = trim($phone);
        // + ile başlıyorsa olduğu gibi bırak
        if (strpos($phone, '+') === 0) {
            return $phone;
        }
        // 0 ile başlıyorsa 90 ile değiştir
        if (substr($phone, 0, 1) === '0') {
            return '90' . substr($phone, 1);
        }
        // Başında hiçbir şey yoksa 90 ekle
        return '90' . $phone;
    }
}
