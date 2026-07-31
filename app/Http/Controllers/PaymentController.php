<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function paraYatir()
    {
        $user = auth('admin')->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Aktif ödeme yöntemlerini al
        $paymentMethods = \App\Models\PaymentMethod::active()->ordered()->get();
        
        return view('para-yatir', compact('user', 'paymentMethods'));
    }

    public function paymentSettings()
    {
        $user = auth('yonetici')->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Mevcut ayarları al
        $settings = DB::table('payment_settings')->pluck('setting_value', 'setting_key')->toArray();
        
        return view('admin.payment-settings', compact('settings'));
    }

    public function updatePaymentSettings(Request $request)
    {
        $user = auth('yonetici')->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Gelen verileri doğrula
        $request->validate([
            'hemen_havale_api_key' => 'required|string',
            'mefete_api_key' => 'required|string',
            'papara_api_key' => 'required|string',
            'hemen_parolapara_api_key' => 'required|string',
            'kredi_karti_api_key' => 'required|string',
            'hemen_kripto_api_key' => 'required|string',
            'extra_api_key' => 'required|string',
            'extra_secret' => 'required|string',
            'oley_api_key' => 'nullable|string',
            'oley_secret' => 'nullable|string',
        ]);

        // Ayarları güncelle
        $settings = [
            'hemen_havale_api_key' => $request->hemen_havale_api_key,
            'mefete_api_key' => $request->mefete_api_key,
            'papara_api_key' => $request->papara_api_key,
            'hemen_parolapara_api_key' => $request->hemen_parolapara_api_key,
            'kredi_karti_api_key' => $request->kredi_karti_api_key,
            'hemen_kripto_api_key' => $request->hemen_kripto_api_key,
            'extra_api_key' => $request->extra_api_key,
            'extra_secret' => $request->extra_secret,
            'oley_api_key' => $request->oley_api_key ?? '',
            'oley_secret' => $request->oley_secret ?? '',
        ];

        foreach ($settings as $key => $value) {
            DB::table('payment_settings')
                ->where('setting_key', $key)
                ->update(['setting_value' => $value]);
        }

        return redirect()->route('admin.payment-settings')
            ->with('success', 'Ödeme sistemi ayarları başarıyla güncellendi!');
    }

    // Veritabanından ayar alma yardımcı fonksiyonu
    private function getPaymentSetting($key, $default = '')
    {
        $setting = DB::table('payment_settings')
            ->where('setting_key', $key)
            ->first();
        
        return $setting ? $setting->setting_value : $default;
    }
    
    public function payget(Request $request)
    {
        // Log gelen veriyi
        \Log::info('Payget POST data:', $request->all());

        // Sadece POST metodunda çalışsın
        if ($request->method() !== 'POST') {
            return response('Geçersiz istek.', 400);
        }

        // POST parametrelerini al
        $apiUrl = $request->input('apiurl');
        $methodId = $request->input('method_id');
        $apiKey = $request->input('api_key');
        $secret = $request->input('secret');
        $provider = $request->input('provider');
        
        if (empty($apiUrl) || empty($methodId) || empty($apiKey)) {
            return response('URL, Method ID veya API Key eksik.', 400);
        }

        $user = auth('yonetici')->user();
        if (!$user) {
            return response('Kullanıcı bulunamadı.', 400);
        }

        try {
            // Eski format için method objesi oluştur
            $method = [
                'apiurl' => $apiUrl,
                'method_id' => $methodId,
                'api_key' => $apiKey
            ];
            
            if ($provider === 'extra') {
                $method['secret'] = $secret;
                $method['provider'] = 'extra';
                return $this->handleExtraWallet($method, $user);
            } else {
                return $this->handleHemenPay($method, $user);
            }
        } catch (\Exception $e) {
            \Log::error('Payment error: ' . $e->getMessage());
            return response('Ödeme işlemi sırasında hata oluştu.', 500);
        }
    }
    



    
    public function getPaymentMethod(Request $request): JsonResponse
    {
        $paymentMethod = $request->input('paymentMethod');
        
        if (!$paymentMethod) {
            return response()->json([
                'success' => false,
                'message' => 'Ödeme yöntemi belirtilmedi'
            ]);
        }
        
        $methods = config('payment.methods');
        
        if (!isset($methods[$paymentMethod])) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz ödeme yöntemi'
            ]);
        }
        
        $method = $methods[$paymentMethod];
        
        // Config'deki fonksiyonları çalıştır
        if (is_callable($method['api_key'])) {
            $method['api_key'] = $method['api_key']();
        }
        if (isset($method['secret']) && is_callable($method['secret'])) {
            $method['secret'] = $method['secret']();
        }
        
        // Bonus kontrolü
        $user = auth('admin')->user();
        if ($user && $user->yasakbonus && $user->bakiye >= 50) {
            return response()->json([
                'success' => false,
                'message' => 'Almış Olduğunuz Promosyon/Bonus Bulunmaktadır. Bonusun iptali ve yatırım yapmak için Bakiyeniz 50 TL\'nin altında olması gerekmektedir'
            ]);
        }
        
        // API isteği gönder
        try {
            if (isset($method['provider']) && $method['provider'] === 'extra') {
                return $this->handleExtraWallet($method, $user);
            } else {
                return $this->handleHemenPay($method, $user);
            }
        } catch (\Exception $e) {
            \Log::error('Payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ödeme sayfası açılamadı. Lütfen tekrar deneyin.'
            ]);
        }
    }
    
    private function handleOleyPaymentIframe($method, $user)
    {
        $apiKey = $method['api_key'];
        $secret = $method['secret'];
        $apiUrl = $method['apiurl'];
        
        // API key ve secret kontrolü
        if (empty($apiKey) || empty($secret)) {
            return response()->json([
                'success' => false,
                'message' => 'OleyPayment API ayarları eksik. Lütfen yöneticinizle iletişime geçin.'
            ]);
        }
        
        // Daha benzersiz transaction hash oluştur - microtime ve uniqid kullanarak
        $microtime = microtime(true);
        $uniqid = uniqid('', true);
        $random = mt_rand(100000, 999999);
        $txn = 'oley-iframe-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        
        // Transaction ID'nin daha önce kullanılıp kullanılmadığını kontrol et
        $existingTransaction = \App\Models\Parayatir::where('note', 'like', '%' . $txn . '%')->first();
        if ($existingTransaction) {
            // Eğer varsa yeni bir tane oluştur
            $microtime = microtime(true);
            $uniqid = uniqid('', true);
            $random = mt_rand(100000, 999999);
            $txn = 'oley-iframe-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        }
        
        // Request body'den miktar al
        $request = request();
        $amount = $request->input('amount', '1000'); // Default 1000 TL
        
        // Miktar validasyonu
        if (!is_numeric($amount) || $amount < 100 || $amount > 100000) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz miktar. Minimum 100₺, Maximum 100.000₺ olmalıdır.'
            ]);
        }
        
        // Encrypted hash: SHA512(userid_txn_SECRET)
        $hashString = $user->id . '_' . $txn . '_' . $secret;
        $encryptedhash = hash('sha512', $hashString);
        
        // İframe API için return URL
        $returnUrl = url('/hesabim?payment=success');
        
        $postData = [
            'client' => [
                'username' => $user->username ?? $user->name,
                'fullname' => $user->name ?? 'User ' . $user->id,
                'userid' => (string)$user->id,
            ],
            'transaction' => [
                'txn' => $txn,
                'amount' => (string)$amount,
                'return_url' => $returnUrl,
            ],
            'encryptedhash' => $encryptedhash
        ];
        
        // Debug için transaction ID'yi logla
        \Log::info('OleyPayment Iframe Transaction ID Generated:', [
            'txn' => $txn,
            'user_id' => $user->id,
            'amount' => $amount,
            'method' => $method['method_id']
        ]);
        
        $headers = [
            'apikey: ' . $apiKey,
            'Content-Type: application/json'
        ];
        
        // API isteği gönder
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('OleyPayment Iframe cURL error: ' . $error);
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı hatası: ' . $error
            ]);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info('OleyPayment Iframe API Response: ' . $response);
        
        $responseData = json_decode($response, true);
        
        // Debug için response'u detaylı logla
        \Log::info('OleyPayment Iframe Response Data:', $responseData);
        
        if ($httpCode === 200 && isset($responseData['status']) && $responseData['status'] === 'success') {
            // Başarılı işlem - veritabanına kaydet
            $redirectUrl = $responseData['data']['redirect_url'] ?? $responseData['redirect_url'] ?? null;
            $transactionId = $responseData['data']['transaction_id'] ?? null;
            
            // Debug için transaction ID'yi logla
            \Log::info('OleyPayment Transaction ID Debug:', [
                'response_data' => $responseData,
                'extracted_transaction_id' => $transactionId,
                'data_section' => $responseData['data'] ?? 'no data section'
            ]);
            
            if (!$redirectUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ödeme URL\'si alınamadı.'
                ]);
            }
            
            // Para yatırma kaydı oluştur
            \App\Models\Parayatir::create([
                'uye' => $user->id,
                'miktar' => (int)$amount,
                'tur' => $method['method_id'],
                'aciklama' => 'OleyPayment Iframe - TXN: ' . $txn,
                'durum' => 0, // pending
                'tarih' => now(),
                'islemno' => $transactionId, // API'den gelen transaction_id'yi kaydet
                'note' => 'oleypayment|' . $txn
            ]);
            
            return response()->json([
                'success' => true,
                'url' => $redirectUrl,
                'txn' => $txn,
                'transaction_id' => $transactionId,
                'type' => 'iframe', // Frontend'e iframe tipi olduğunu belirt
                'message' => 'Ödeme sayfasına yönlendiriliyorsunuz...'
            ]);
        } else {
            $message = $responseData['message'] ?? 'Bilinmeyen hata';
            \Log::error('OleyPayment Iframe API Error: ' . $message . ' - HTTP Code: ' . $httpCode);
            return response()->json([
                'success' => false,
                'message' => 'Ödeme hatası: ' . $message
            ]);
        }
    }

    private function handleOleyPaymentBankTransferIframe($method, $user)
    {
        $apiKey = $method['api_key'];
        $secret = $method['secret'];
        $apiUrl = $method['apiurl'];
        
        // API key ve secret kontrolü
        if (empty($apiKey) || empty($secret)) {
            return response()->json([
                'success' => false,
                'message' => 'OleyPayment API ayarları eksik. Lütfen yöneticinizle iletişime geçin.'
            ]);
        }
        
        // Daha benzersiz transaction hash oluştur - microtime ve uniqid kullanarak
        $microtime = microtime(true);
        $uniqid = uniqid('', true);
        $random = mt_rand(100000, 999999);
        $txn = 'oley-banktransfer-iframe-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        
        // Transaction ID'nin daha önce kullanılıp kullanılmadığını kontrol et
        $existingTransaction = \App\Models\Parayatir::where('note', 'like', '%' . $txn . '%')->first();
        if ($existingTransaction) {
            // Eğer varsa yeni bir tane oluştur
            $microtime = microtime(true);
            $uniqid = uniqid('', true);
            $random = mt_rand(100000, 999999);
            $txn = 'oley-banktransfer-iframe-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        }
        
        // Request body'den miktar al
        $request = request();
        $amount = $request->input('amount', '1000'); // Default 1000 TL
        
        // Miktar validasyonu
        if (!is_numeric($amount) || $amount < 100 || $amount > 100000) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz miktar. Minimum 100₺, Maximum 100.000₺ olmalıdır.'
            ]);
        }
        
        // Encrypted hash: SHA512(userid_txn_SECRET)
        $hashString = $user->id . '_' . $txn . '_' . $secret;
        $encryptedhash = hash('sha512', $hashString);
        
        // Bank Transfer iframe API için request body (dökümana göre)
        $postData = [
            'client' => [
                'username' => $user->username ?? $user->name,
                'fullname' => $user->name ?? 'User ' . $user->id,
                'userid' => (string)$user->id,
            ],
            'transaction' => [
                'txn' => $txn,
                'amount' => (string)$amount,
            ],
            'encryptedhash' => $encryptedhash
        ];
        
        // Debug için transaction ID'yi logla
        \Log::info('OleyPayment Bank Transfer Iframe Transaction ID Generated:', [
            'txn' => $txn,
            'user_id' => $user->id,
            'amount' => $amount,
            'method' => $method['method_id']
        ]);
        
        $headers = [
            'apikey: ' . $apiKey,
            'Content-Type: application/json'
        ];
        
        // API isteği gönder
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('OleyPayment Bank Transfer Iframe cURL error: ' . $error);
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı hatası: ' . $error
            ]);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info('OleyPayment Bank Transfer Iframe API Response: ' . $response);
        
        $responseData = json_decode($response, true);
        
        // Debug için response'u detaylı logla
        \Log::info('OleyPayment Bank Transfer Iframe Response Data:', $responseData);
        
        if ($httpCode === 200 && isset($responseData['status']) && $responseData['status'] === 'success') {
            // Başarılı işlem - veritabanına kaydet
            $redirectUrl = $responseData['redirect_url'] ?? null;
            $transactionId = $responseData['transaction_id'] ?? null;
            
            // Debug için transaction ID'yi logla
            \Log::info('OleyPayment Bank Transfer Transaction ID Debug:', [
                'response_data' => $responseData,
                'extracted_transaction_id' => $transactionId,
                'redirect_url' => $redirectUrl
            ]);
            
            if (!$redirectUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ödeme URL\'si alınamadı.'
                ]);
            }
            
            // Para yatırma kaydı oluştur
            \App\Models\Parayatir::create([
                'uye' => $user->id,
                'miktar' => (int)$amount,
                'tur' => $method['method_id'],
                'aciklama' => 'OleyPayment Bank Transfer Iframe - TXN: ' . $txn,
                'durum' => 0, // pending
                'tarih' => now(),
                'islemno' => $transactionId, // API'den gelen transaction_id'yi kaydet
                'note' => 'oleypayment|' . $txn
            ]);
            
            return response()->json([
                'success' => true,
                'url' => $redirectUrl,
                'txn' => $txn,
                'transaction_id' => $transactionId,
                'type' => 'iframe', // Frontend'e iframe tipi olduğunu belirt
                'message' => 'Ödeme sayfasına yönlendiriliyorsunuz...'
            ]);
        } else {
            $message = $responseData['message'] ?? 'Bilinmeyen hata';
            \Log::error('OleyPayment Bank Transfer Iframe API Error: ' . $message . ' - HTTP Code: ' . $httpCode);
            return response()->json([
                'success' => false,
                'message' => 'Ödeme hatası: ' . $message
            ]);
        }
    }

    private function handleOleyPaymentRest($method, $user)
    {
        $apiKey = $method['api_key'];
        $secret = $method['secret'];
        $apiUrl = $method['apiurl'];
        
        // API key ve secret kontrolü
        if (empty($apiKey) || empty($secret)) {
            return response()->json([
                'success' => false,
                'message' => 'OleyPayment API ayarları eksik. Lütfen yöneticinizle iletişime geçin.'
            ]);
        }
        
        // Daha benzersiz transaction hash oluştur - microtime ve uniqid kullanarak
        $microtime = microtime(true);
        $uniqid = uniqid('', true);
        $random = mt_rand(100000, 999999);
        $txn = 'oley-rest-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        
        // Transaction ID'nin daha önce kullanılıp kullanılmadığını kontrol et
        $existingTransaction = \App\Models\Parayatir::where('note', 'like', '%' . $txn . '%')->first();
        if ($existingTransaction) {
            // Eğer varsa yeni bir tane oluştur
            $microtime = microtime(true);
            $uniqid = uniqid('', true);
            $random = mt_rand(100000, 999999);
            $txn = 'oley-rest-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        }
        
        // Request body'den miktar ve bank bilgisini al
        $request = request();
        $amount = $request->input('amount', '1000'); // Default 1000 TL
        $bankId = $request->input('bank_id', '1'); // Default bank ID
        
        // Miktar validasyonu
        if (!is_numeric($amount) || $amount < 100 || $amount > 100000) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz miktar. Minimum 100₺, Maximum 100.000₺ olmalıdır.'
            ]);
        }
        
        // Encrypted hash: SHA512(userid_txn_SECRET)
        $hashString = $user->id . '_' . $txn . '_' . $secret;
        $encryptedhash = hash('sha512', $hashString);
        
        $postData = [
            'client' => [
                'username' => $user->username ?? $user->name,
                'fullname' => $user->name ?? 'User ' . $user->id,
                'userid' => (string)$user->id,
            ],
            'transaction' => [
                'txn' => $txn,
                'bankid' => (string)$bankId, // Dökümana göre bankid olmalı
                'amount' => (string)$amount,
            ],
            'encryptedhash' => $encryptedhash
        ];
        
        // Debug için transaction ID'yi logla
        \Log::info('OleyPayment Rest Transaction ID Generated:', [
            'txn' => $txn,
            'user_id' => $user->id,
            'amount' => $amount,
            'method' => $method['method_id']
        ]);
        
        $headers = [
            'apikey: ' . $apiKey,
            'Content-Type: application/json'
        ];
        
        // API isteği gönder
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('OleyPayment Rest cURL error: ' . $error);
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı hatası: ' . $error
            ]);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info('OleyPayment Rest API Response: ' . $response);
        
        $responseData = json_decode($response, true);
        
        if ($httpCode === 200 && isset($responseData['status']) && $responseData['status'] === 'success') {
            // Başarılı işlem - REST API'de transaction_id yok, bank bilgileri var
            $accountHolder = $responseData['accountholder'] ?? null;
            $accountIban = $responseData['accountiban'] ?? null;
            
            // Para yatırma kaydı oluştur
            \App\Models\Parayatir::create([
                'uye' => $user->id,
                'miktar' => (int)$amount,
                'tur' => $method['method_id'],
                'aciklama' => 'OleyPayment REST - Bank: ' . $accountHolder . ' | IBAN: ' . $accountIban,
                'durum' => 0, // pending
                'tarih' => now(),
                'islemno' => null, // REST API'de transaction_id yok
                'note' => 'oleypayment|' . $txn
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Para yatırma işlemi başlatıldı. Banka bilgileri: ' . $accountHolder . ' - ' . $accountIban,
                'txn' => $txn,
                'account_holder' => $accountHolder,
                'account_iban' => $accountIban
            ]);
        } else {
            $message = $responseData['message'] ?? 'Bilinmeyen hata';
            \Log::error('OleyPayment Rest API Error: ' . $message . ' - HTTP Code: ' . $httpCode);
            return response()->json([
                'success' => false,
                'message' => 'Ödeme hatası: ' . $message
            ]);
        }
    }
    
    private function handleOleyPaymentDirect($method, $user)
    {
        $apiKey = $method['api_key'];
        $secret = $method['secret'];
        $apiUrl = $method['apiurl'];
        
        // API key ve secret kontrolü
        if (empty($apiKey) || empty($secret)) {
            return response()->json([
                'success' => false,
                'message' => 'OleyPayment API ayarları eksik. Lütfen yöneticinizle iletişime geçin.'
            ]);
        }
        
        // Daha benzersiz transaction hash oluştur - microtime ve uniqid kullanarak
        $microtime = microtime(true);
        $uniqid = uniqid('', true);
        $random = mt_rand(100000, 999999);
        $txn = 'oley-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        
        // Transaction ID'nin daha önce kullanılıp kullanılmadığını kontrol et
        $existingTransaction = \App\Models\Parayatir::where('note', 'like', '%' . $txn . '%')->first();
        if ($existingTransaction) {
            // Eğer varsa yeni bir tane oluştur
            $microtime = microtime(true);
            $uniqid = uniqid('', true);
            $random = mt_rand(100000, 999999);
            $txn = 'oley-' . $user->id . '-' . $microtime . '-' . $uniqid . '-' . $random;
        }
        
        // Request body'den miktar ve bank bilgisini al
        $request = request();
        $amount = $request->input('amount', '1000'); // Default 1000 TL
        $bankId = $request->input('bank_id', '1'); // Default bank ID
        
        // Miktar validasyonu
        if (!is_numeric($amount) || $amount < 100 || $amount > 100000) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz miktar. Minimum 100₺, Maximum 100.000₺ olmalıdır.'
            ]);
        }
        
        // Encrypted hash: SHA512(userid_txn_SECRET)
        $hashString = $user->id . '_' . $txn . '_' . $secret;
        $encryptedhash = hash('sha512', $hashString);
        
        $postData = [
            'client' => [
                'username' => $user->username ?? $user->name,
                'fullname' => $user->name ?? 'User ' . $user->id,
                'userid' => (string)$user->id,
                'account_no' => $user->hesap_no ?? '', // Kullanıcı hesap numarası
            ],
            'transaction' => [
                'txn' => $txn,
                'amount' => (string)$amount,
            ],
            'encryptedhash' => $encryptedhash
        ];
        
        // Debug için transaction ID'yi logla
        \Log::info('OleyPayment Direct Transaction ID Generated:', [
            'txn' => $txn,
            'user_id' => $user->id,
            'amount' => $amount,
            'method' => $method['method_id']
        ]);
        
        $headers = [
            'apikey: ' . $apiKey,
            'Content-Type: application/json'
        ];
        
        // API isteği gönder
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('OleyPayment cURL error: ' . $error);
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı hatası: ' . $error
            ]);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info('OleyPayment API Response: ' . $response);
        
        $responseData = json_decode($response, true);
        
        if ($httpCode === 200 && isset($responseData['status']) && $responseData['status'] === 'success') {
            // Başarılı işlem - veritabanına kaydet
            $transactionId = $responseData['data']['transaction_id'] ?? null;
            
            // Para yatırma kaydı oluştur
            \App\Models\Parayatir::create([
                'uye' => $user->id,
                'miktar' => (int)$amount,
                'tur' => $method['method_id'], // use method_id from config
                'aciklama' => 'OleyPayment - Transaction ID: ' . $transactionId,
                'durum' => 0, // pending
                'tarih' => now(),
                'islemno' => $transactionId, // save transaction_id here
                'note' => 'oleypayment|' . $txn
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Para yatırma işlemi başlatıldı. Ödeme talimatlarını takip edin.',
                'transaction_id' => $transactionId,
                'txn' => $txn
            ]);
        } else {
            $message = $responseData['message'] ?? 'Bilinmeyen hata';
            \Log::error('OleyPayment API Error: ' . $message . ' - HTTP Code: ' . $httpCode);
            return response()->json([
                'success' => false,
                'message' => 'Ödeme hatası: ' . $message
            ]);
        }
    }
    
    private function handleExtraWallet($method, $user)
    {
        // Benzersiz referans numarası oluştur
        $referenceno = 'ref-' . $user->id . '-' . time() . '-' . rand(1000, 9999);
        
        $fullname = $user->name ?? 'user';
        $cleanName = strtolower(str_replace(
            ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç'], 
            ['i','g','u','s','o','c','i','g','u','s','o','c'], 
            trim($fullname)
        ));
        
        $parts = explode(' ', $cleanName);
        if (count($parts) > 1) {
            $lastName = preg_replace('/[^a-z0-9]/', '', array_pop($parts));
            $firstName = preg_replace('/[^a-z0-9]/', '', implode('', $parts));
        } else {
            $firstName = preg_replace('/[^a-z0-9]/', '', $parts[0]);
            $lastName = '';
        }

        $seed = md5($firstName . $lastName . $user->id);
        
        $num1 = hexdec(substr($seed, 0, 4)) % 90 + 10; // 10-99 arası
        $num2 = hexdec(substr($seed, 4, 4)) % 900 + 100; // 100-999 arası

        if ($lastName != '') {
            $patterns = [
                $firstName . $lastName . $num1,
                $firstName . '.' . $lastName . $num2,
                substr($firstName, 0, 1) . $lastName . $num1,
                $lastName . $firstName . $num2,
                $firstName . '_' . $lastName . $num1,
            ];
        } else {
            $patterns = [
                $firstName . $num1,
                $firstName . $num2,
                $firstName . '_' . $num1,
            ];
        }

        $patternIndex = hexdec(substr($seed, 8, 4)) % count($patterns);
        $dynamicPlayerUn = $patterns[$patternIndex];

        $domains = ['@gmail.com', '@hotmail.com', '@outlook.com', '@yahoo.com'];
        $domainIndex = hexdec(substr($seed, 12, 4)) % count($domains);
        $dynamicEmail = str_replace(['_', '.'], '', $dynamicPlayerUn) . $domains[$domainIndex];

        $year = 1970 + (hexdec(substr($seed, 16, 4)) % 35); // 1970 - 2004
        $month = 1 + (hexdec(substr($seed, 20, 2)) % 12); // 1 - 12
        $day = 1 + (hexdec(substr($seed, 22, 2)) % 28); // 1 - 28
        $dynamicBirthdate = sprintf('%04d-%02d-%02d', $year, $month, $day);

        $phonePrefixes = ['530', '531', '532', '533', '534', '535', '536', '537', '538', '539', '541', '542', '543', '544', '545', '546', '552', '553', '554', '555'];
        $phonePrefix = $phonePrefixes[hexdec(substr($seed, 24, 2)) % count($phonePrefixes)];
        $phoneSuffix = str_pad(hexdec(substr($seed, 26, 6)) % 10000000, 7, '0', STR_PAD_LEFT);
        $dynamicPhone = $phonePrefix . $phoneSuffix;

        $dynamicPlayerId = hexdec(substr($seed, 28, 4)) % 900000 + 100000;

        // Extra Cüzdan için temel data (dokümana göre TÜM YÖNTEMLER için alanlar zorunlu)
        $data = [
            'referenceno' => $referenceno,
            'player_un' => $dynamicPlayerUn,
            'player_id' => (string)$dynamicPlayerId,
            'player_name' => $fullname,
            'player_identityno' => $user->tc ?? '11111111111',
            'player_telephone' => $dynamicPhone,
            'player_email' => $dynamicEmail,
            'player_birthdate' => $dynamicBirthdate
        ];

        // Kripto için istekte iletilirse network parametresi ekle (docs'a uygun)
        $req = request();
        $network = $req->input('network');
        if (!empty($network) && strpos($method['apiurl'], '/deposit/kripto') !== false) {
            $data['network'] = $network;
        }
        
        \Illuminate\Support\Facades\Log::info("PaymentController - Sending request to Extra Cuzdan", ['url' => $method['apiurl'], 'data' => $data]);

        // cURL
        $ch = curl_init($method['apiurl']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        // multipart/form-data için dizi gönder (docs: --form ...)
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // Header key: apikey-XYZ (DB'de apikey- ile başlıyorsa aynen kullan; değilse prefix ekle)
        $apiHeaderKey = (strpos($method['api_key'], 'apikey-') === 0) ? $method['api_key'] : ('apikey-' . $method['api_key']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            $apiHeaderKey . ': ' . $method['secret']
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        // Execute
        $response = curl_exec($ch);
        
        // cURL Error?
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Illuminate\Support\Facades\Log::error("PaymentController - Extra Cuzdan Curl Error", ['error' => $error]);
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı hatası: ' . $error
            ]);
        }
        
        curl_close($ch);
        
        \Illuminate\Support\Facades\Log::info("PaymentController - Extra Cuzdan Response", ['response' => $response]);
        
        // Extra dokümana göre bazı durumlarda JSON değil doğrudan HREF dönebilir
        $responseData = json_decode($response, true);
        if (is_array($responseData)) {
            if (!empty($responseData['href'])) {
                return response()->json(['success' => true, 'url' => $responseData['href']]);
            }
            if (!empty($responseData['redirect_url'])) {
                return response()->json(['success' => true, 'url' => $responseData['redirect_url']]);
            }
            $errorMessage = $responseData['message'] ?? 'Bilinmeyen hata';
            return response()->json(['success' => false, 'message' => 'Extra Cüzdan Hatası: ' . $errorMessage]);
        }
        // JSON değilse ve içinde http varsa direkt yönlendirme kabul et
        if (stripos($response, 'http') !== false) {
            // metinden ilk URL'i çekmeye çalış
            if (preg_match('/https?:\/\/[^\s"\']+/i', $response, $m)) {
                return response()->json(['success' => true, 'url' => $m[0]]);
            }
        }
        return response()->json(['success' => false, 'message' => 'Extra Cüzdan geçersiz yanıt: ' . $response]);
    }
    
    private function handleHemenPay($method, $user)
    {
        // Gönderilecek data
        $data = [
            'Key' => $method['api_key'],
            'PlayerID' => $user->id,
            'PlayerFullName' => $user->name,
            'PlayerUserName' => $user->username,
            'PaymentMethodID' => $method['method_id'],
            'TraderTransactionID' => '4550', // Örnek ID
            'PlayerRegisteredDate' => '',
            'PlayerEmail' => $user->email ?? 'user@example.com',
            'SuccessUrl' => url('/'),
            'CancelUrl' => url('/hesabim')
        ];
        
        // checksum hesaplama
        $dataString = $data['Key']
                    . $data['PlayerID']
                    . $data['PlayerFullName']
                    . $data['PlayerUserName']
                    . $data['TraderTransactionID']
                    . $data['PlayerRegisteredDate']
                    . $data['PlayerEmail']
                    . $data['PaymentMethodID'];

        $data['checksum'] = md5($dataString);

        // cURL
        $ch = curl_init($method['apiurl']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        // JSON_UNESCAPED_UNICODE ile Türkçe karakterler bozulmuyor.
        $postFields = json_encode($data, JSON_UNESCAPED_UNICODE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        // Execute
        $response = curl_exec($ch);

        // cURL Error?
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı hatası: ' . $error
            ]);
        }
        
        curl_close($ch);
        
        // Parse response
        $responseData = json_decode($response, true);
        
        // Data içinde URL var mı?
        if (isset($responseData['Data']) && !empty($responseData['Data'])) {
            $depositUrl = $responseData['Data'];
            return response()->json(['success' => true, 'url' => $depositUrl]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ödeme URL\'si bulunamadı.'
            ]);
        }
    }

    public function oleyPaymentWebhook(Request $request)
    {
        \Log::info('OleyPayment Webhook received:', $request->all());
        
        // Webhook verilerini al
        $transactionHash = $request->input('transactionhash');
        $username = $request->input('username');
        $userId = $request->input('userid');
        $amount = $request->input('amount');
        $status = $request->input('status'); // confirmed/declined
        $encryptedHash = $request->input('encryptedhash');
        
        if (!$transactionHash || !$userId || !$amount || !$status || !$encryptedHash) {
            \Log::error('OleyPayment Webhook: Eksik parametreler');
            return response('Missing parameters', 400);
        }
        
        // Konfigürasyondan secret al
        $methods = config('payment.methods');
        if (!isset($methods['KOLAYHAVALE'])) {
            \Log::error('OleyPayment Webhook: KOLAYHAVALE konfigürasyonu bulunamadı');
            return response('Configuration error', 500);
        }
        
        $method = $methods['KOLAYHAVALE'];
        $secret = is_callable($method['secret']) ? $method['secret']() : $method['secret'];
        
        if (empty($secret)) {
            \Log::error('OleyPayment Webhook: Secret bulunamadı');
            return response('Secret not found', 500);
        }
        
        // Hash doğrulama: SHA512([transactionhash]_[username]_[userid]_[amount]_[status]_[apisecret])
        $expectedHash = hash('sha512', $transactionHash . '_' . $username . '_' . $userId . '_' . $amount . '_' . $status . '_' . $secret);
        
        if ($encryptedHash !== $expectedHash) {
            \Log::error('OleyPayment Webhook: Hash doğrulaması başarısız', [
                'expected' => $expectedHash,
                'received' => $encryptedHash
            ]);
            return response('Invalid hash', 400);
        }
        
        // Para yatırma kaydını bul - daha kapsamlı arama
        $deposit = \App\Models\Parayatir::where(function($query) use ($transactionHash) {
            $query->where('note', 'like', '%' . $transactionHash . '%')
                  ->orWhere('islemno', $transactionHash)
                  ->orWhere('note', 'like', '%' . $transactionHash . '%');
        })->first();
        
        if (!$deposit) {
            \Log::error('OleyPayment Webhook: Para yatırma kaydı bulunamadı: ' . $transactionHash);
            return response('Transaction not found', 404);
        }
        
        // Kullanıcıyı bul
        $user = \App\Models\Admin::find($userId);
        if (!$user) {
            \Log::error('OleyPayment Webhook: Kullanıcı bulunamadı: ' . $userId);
            return response('User not found', 404);
        }
        
        // Status'a göre işlem yap
        if ($status === 'confirmed') {
            // Para yatırma onaylandı
            $deposit->update([
                'durum' => 1, // approved
                'miktar' => floatval($amount)
            ]);
            
            // Kullanıcı bakiyesini artır
            $user->bakiye = ($user->bakiye ?? 0) + floatval($amount);
            $user->save();

            // Affiliate komisyon hesapla ve bakiyeye ekle
            $this->processAffiliateCommission($user, floatval($amount));
            
            // Gateway Webhook Bildirimi
            if ($deposit->note && strpos($deposit->note, 'gateway_source_user_id:') !== false) {
                preg_match('/gateway_source_user_id:(\d+)\|ref:(.+)/', $deposit->note, $matches);
                if (count($matches) >= 3) {
                    $sourceUserId = $matches[1];
                    $txn = $matches[2];
                    $postData = [
                        'source_user_id' => $sourceUserId,
                        'amount' => $amount,
                        'txn' => $txn,
                        'transaction_id' => $deposit->id
                    ];
                    $webhookUrl = env('MAIN_WEBHOOK_URL', 'http://localhost');
                    $ch = curl_init($webhookUrl); // Sunucu adresi veya IP ile yapilandirilabilir
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Gateway-Secret: AUTO_TRANSFER_SECRET_12345'
                    ]);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_exec($ch);
                    curl_close($ch);
                }
            }

            \Log::info('OleyPayment Webhook: Para yatırma onaylandı', [
                'user_id' => $userId,
                'amount' => $amount,
                'transaction' => $transactionHash
            ]);
            
        } elseif ($status === 'declined') {
            // Para yatırma reddedildi
            $deposit->update([
                'durum' => 2 // rejected
            ]);
            
            \Log::info('OleyPayment Webhook: Para yatırma reddedildi', [
                'user_id' => $userId,
                'transaction' => $transactionHash
            ]);
        }
        
        return response('OK', 200);
    }

    public function getBankList(): JsonResponse
    {
        $methods = config('payment.methods');
        
        if (!isset($methods['KOLAYHAVALE'])) {
            return response()->json([
                'success' => false,
                'message' => 'KOLAYHAVALE konfigürasyonu bulunamadı'
            ]);
        }
        
        $method = $methods['KOLAYHAVALE'];
        
        // Config'deki fonksiyonları çalıştır
        if (is_callable($method['api_key'])) {
            $method['api_key'] = $method['api_key']();
        }
        
        if (empty($method['api_key'])) {
            return response()->json([
                'success' => false,
                'message' => 'API anahtarı bulunamadı'
            ]);
        }
        
        $apiUrl = 'https://api.oleypayment.com/bank/list';
        
        $headers = [
            'apikey: ' . $method['api_key'],
            'Content-Type: application/json'
        ];
        
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('OleyPayment Bank List cURL error: ' . $error);
            return response()->json([
                'success' => false,
                'message' => 'Banka listesi alınamadı: ' . $error
            ]);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        
        if ($httpCode === 200 && isset($responseData['banks'])) {
            return response()->json([
                'success' => true,
                'banks' => $responseData['banks'],
                'count' => $responseData['count'] ?? count($responseData['banks'])
            ]);
        } else {
            \Log::error('OleyPayment Bank List API Error: ' . $response);
            return response()->json([
                'success' => false,
                'message' => 'Banka listesi alınamadı'
            ]);
        }
    }

    /**
     * Affiliate komisyon hesapla ve bakiyeye otomatik ekle
     */
    private function processAffiliateCommission($user, $depositAmount)
    {
        try {
            $affiliateId = (int)($user->bayisi ?? 0);
            if ($affiliateId <= 0) {
                return;
            }

            $affiliate = \App\Models\Admin::where('id', $affiliateId)->where('aff', 1)->first();
            if (!$affiliate) {
                return;
            }

            $commissionRate = (float)($affiliate->afforani ?? 0);
            if ($commissionRate <= 0) {
                return;
            }

            $commission = round(($depositAmount * $commissionRate) / 100, 2);
            if ($commission <= 0) {
                return;
            }

            $affiliate->increment('bakiye', $commission);

            \Log::info("COMMISSION: Affiliate {$affiliate->username} earned {$commission} TL ({$commissionRate}% of {$depositAmount} TL)");

            // Telegram bildirimi
            if (!empty($affiliate->telegram_chat_id)) {
                $username = $user->username ?? $user->name ?? 'Bilinmiyor';
                $now = now()->format('d.m.Y H:i:s');
                $newBalance = $affiliate->fresh()->bakiye;

                $message = "💰 <b>Komisyon Bildirimi</b>\n\n"
                         . "👤 <b>Üye:</b> {$username}\n"
                         . "💵 <b>Yatırım:</b> {$depositAmount} TL\n"
                         . "📊 <b>Oran:</b> %{$commissionRate}\n"
                         . "✅ <b>Komisyon:</b> {$commission} TL\n"
                         . "💰 <b>Yeni Bakiye:</b> {$newBalance} TL\n"
                         . "🕒 <b>Tarih:</b> {$now}";

                $token = env('TELEGRAM_BOT_TOKEN');
                if ($token) {
                    $url = "https://api.telegram.org/bot{$token}/sendMessage";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                        'chat_id'    => $affiliate->telegram_chat_id,
                        'text'       => $message,
                        'parse_mode' => 'HTML',
                    ]));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_exec($ch);
                    curl_close($ch);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Affiliate commission error: ' . $e->getMessage());
        }
    }
} 