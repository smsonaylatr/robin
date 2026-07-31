<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Parayatir;
use Illuminate\Support\Facades\Log;

class GatewayApiController extends Controller
{
    private $gatewaySecret = 'AUTO_TRANSFER_SECRET_12345';

    public function createPayment(Request $request)
    {
        $secret = $request->input('secret');
        if ($secret !== $this->gatewaySecret) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $email = $request->input('email');
        $fullname = $request->input('fullname');
        $amount = $request->input('amount');
        $sourceUserId = $request->input('source_user_id');

        if (!$email || !$fullname || !$amount || !$sourceUserId) {
            return response()->json(['success' => false, 'message' => 'Missing parameters'], 400);
        }

        Log::info("Gateway API - Request Received", ['amount' => $amount, 'source_user_id' => $sourceUserId, 'fullname' => $fullname]);

        // Kullanıcıyı email üzerinden bul veya oluştur
        $user = Admin::where('email', $email)->first();

        if ($user) {
            // Kullanıcı var, ad soyad değişmişse güncelle
            if ($user->name !== $fullname) {
                $user->name = $fullname;
                $user->save();
            }
            Log::info("Gateway API - Existing User Found/Updated", ['user_id' => $user->id]);
        } else {
            // Kullanıcı yok, yeni kullanıcı oluştur
            $user = new Admin();
            $user->name = $fullname;
            $user->email = $email;
            // Username ad soyaddan oluşsun
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(
                ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç',' '], 
                ['i','g','u','s','o','c','I','G','U','S','O','C',''], 
                $fullname
            )));
            $user->username = $baseUsername . rand(100, 999);
            
            $user->password = md5(uniqid());
            
            // Gerçek formata uygun rastgele TC üretimi
            $tc = [];
            $tc[0] = rand(1, 9);
            for ($i = 1; $i < 9; $i++) {
                $tc[$i] = rand(0, 9);
            }
            $odds = $tc[0] + $tc[2] + $tc[4] + $tc[6] + $tc[8];
            $evens = $tc[1] + $tc[3] + $tc[5] + $tc[7];
            $tc[9] = (($odds * 7) - $evens) % 10;
            $tc[10] = ($odds + $evens + $tc[9]) % 10;
            $user->tc = implode('', $tc);
            $user->telefon = '555' . rand(1000000, 9999999);
            $user->durum = 1; // Aktif
            $user->bakiye = 0;
            $user->ulke = 'Türkiye';
            $user->bayisi = '0';
            $user->save();
            
            Log::info("Gateway API - New User Created", ['user_id' => $user->id, 'username' => $user->username]);
        }

        // Ödeme verilerini hazırla ve Extra Cüzdan için simüle et
        // Gerekli API anahtarlarını veritabanından alalım (PaymentController.php'ye benzer şekilde)
        $apiKey = \Illuminate\Support\Facades\DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f';
        $apiSecret = \Illuminate\Support\Facades\DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? '0dc93978-17e5-4580-b78e-39fc3e014ee7';

        $method = [
            'apiurl' => 'https://apiws.extracuzdan.com/deposit/havaleeft', 
            'api_key' => $apiKey,
            'secret' => $apiSecret,
            'method_id' => 'EXTRA_HAVALE_AUTO',
            'provider' => 'extra'
        ];
        
        // Benzersiz referans numarası oluştur
        $referenceno = 'ref-' . $user->id . '-' . time() . '-' . rand(1000, 9999);
        
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

        $seed = md5($firstName . $lastName . $sourceUserId);
        
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
        
        $data = [
            'referenceno' => $referenceno,
            'player_un' => $dynamicPlayerUn,
            'player_id' => (string)$dynamicPlayerId,
            'player_name' => $fullname,
            'player_identityno' => $user->tc,
            'player_telephone' => $dynamicPhone,
            'player_email' => $dynamicEmail,
            'player_birthdate' => $dynamicBirthdate
        ];

        Log::info("Gateway API - Sending request to Extra Cuzdan", ['referenceno' => $referenceno, 'data' => $data]);

        $ch = curl_init($method['apiurl']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $apiHeaderKey = (strpos($method['api_key'], 'apikey-') === 0) ? $method['api_key'] : ('apikey-' . $method['api_key']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            $apiHeaderKey . ': ' . $method['secret']
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error("Gateway API - Extra Cuzdan Curl Error", ['error' => $error]);
            return response()->json(['success' => false, 'message' => 'Ödeme sağlayıcısına bağlanılamadı. (' . $error . ')']);
        }
        
        curl_close($ch);
        
        Log::info("Gateway API - Extra Cuzdan Response", ['response' => $response]);
        
        $responseData = json_decode($response, true);
        $url = null;

        if (is_array($responseData)) {
            if (!empty($responseData['href'])) {
                $url = $responseData['href'];
            } elseif (!empty($responseData['redirect_url'])) {
                $url = $responseData['redirect_url'];
            }
        } elseif (stripos($response, 'http') !== false) {
            if (preg_match('/https?:\/\/[^\s"\']+/i', $response, $m)) {
                $url = $m[0];
            }
        }

        if ($url) {
            // Pending ödeme kaydı oluştur ve source_user_id bilgisini kaydet
            $pendingRecord = Parayatir::create([
                'uye' => $user->id,
                'miktar' => $amount,
                'tur' => 'EXTRA_HAVALE_AUTO',
                'aciklama' => 'Gateway Auto Transfer',
                'durum' => 0, // Bekliyor
                'tarih' => now(),
                'note' => 'gateway_source_user_id:' . $sourceUserId . '|ref:' . $referenceno,
                'islemno' => $referenceno
            ]);
            
            Log::info("Gateway API - Pending Deposit Created", ['id' => $pendingRecord->id, 'islemno' => $referenceno, 'source_user_id' => $sourceUserId]);

            // Extra Cüzdan sayfasından IBAN bilgilerini çek
            $ibanInfo = $this->fetchIbanFromExtraCuzdan($url, $amount);

            $responsePayload = [
                'success' => true,
                'url' => $url,
                'txn' => $referenceno
            ];

            if ($ibanInfo) {
                // Hesap bulunamadı hatası kontrolü
                if (isset($ibanInfo['error']) && $ibanInfo['error'] === 'no_account') {
                    $responsePayload['no_account'] = true;
                    Log::warning("Gateway API - No available bank account", ['message' => $ibanInfo['message']]);
                    
                    // Telegram bildirimi gönder
                    try {
                        $tgToken = env('TELEGRAM_BOT_TOKEN');
                        $tgChatId = env('TELEGRAM_CHAT_ID');
                        if ($tgToken && $tgChatId) {
                            $fullname = $request->input('fullname', 'Bilinmiyor');
                            $tgMsg = "⚠️ <b>Hesap Bulunamadı Uyarısı</b>\n\n";
                            $tgMsg .= "👤 Müşteri: <b>{$fullname}</b>\n";
                            $tgMsg .= "💰 Tutar: <b>" . number_format($amount, 0, ',', '.') . " ₺</b>\n";
                            $tgMsg .= "🆔 Kaynak ID: {$request->input('source_user_id')}\n";
                            $tgMsg .= "🕐 Tarih: " . now()->format('d.m.Y H:i:s') . "\n\n";
                            $tgMsg .= "❌ Extra Cüzdan'da uygun hesap bulunamadı.\nMüşteri ile ilgilenin!";
                            
                            $tgUrl = "https://api.telegram.org/bot{$tgToken}/sendMessage";
                            $ch = curl_init($tgUrl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                                'chat_id' => $tgChatId,
                                'text' => $tgMsg,
                                'parse_mode' => 'HTML'
                            ]);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                            curl_exec($ch);
                            curl_close($ch);
                            Log::info("Gateway API - Telegram notification sent for no_account");
                        }
                    } catch (\Exception $e) {
                        Log::error("Gateway API - Telegram notification failed", ['error' => $e->getMessage()]);
                    }
                } else {
                    $responsePayload['iban_info'] = $ibanInfo;
                    Log::info("Gateway API - IBAN info fetched successfully", ['iban_info' => $ibanInfo]);
                }
            } else {
                Log::warning("Gateway API - Could not fetch IBAN info, user will be redirected", ['url' => $url]);
            }

            return response()->json($responsePayload);
        }

        $errorMsg = 'Ödeme URL alınamadı.';
        if (is_array($responseData) && !empty($responseData['message'])) {
            $errorMsg = $responseData['message'];
        } elseif (is_array($responseData) && !empty($responseData['error'])) {
            $errorMsg = $responseData['error'];
        }

        // Gelen hata mesajını kontrol edip Türkçeleştirme ve kibarlaştırma yapıyoruz
        $lowerMsg = mb_strtolower($errorMsg, 'UTF-8');
        if (strpos($lowerMsg, 'aynı') !== false || strpos($lowerMsg, 'aktif') !== false || strpos($lowerMsg, 'bekleyen') !== false) {
            $errorMsg = 'Şu anda devam eden / bekleyen bir işleminiz bulunmaktadır. Lütfen önceki işleminizin sonuçlanmasını bekleyiniz.';
        }

        Log::error("Gateway API - Failed to get payment URL", ['response' => $response]);
        return response()->json(['success' => false, 'message' => $errorMsg, 'debug' => $response]);
    }

    /**
     * Extra Cüzdan ödeme sayfasından IBAN bilgilerini çeker.
     * 1. GET ile sayfayı çekip CSRF token'ı al
     * 2. POST #1: pricesearch ile tutarı gönder
     * 3. POST #2: type=save ile IBAN bilgisini al (HTML formatında)
     * 4. HTML'den hesap sahibi, IBAN ve açıklama parse et
     */
    private function fetchIbanFromExtraCuzdan($extraUrl, $amount)
    {
        try {
            // Cookie jar oluştur (session tutmak için)
            $cookieFile = tempnam(sys_get_temp_dir(), 'extra_cookie_');

            // 1. GET - Sayfayı çek ve token'ı al
            $ch = curl_init($extraUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $html = curl_exec($ch);
            curl_close($ch);

            if (!$html) {
                Log::warning("IBAN Fetch - GET request failed");
                @unlink($cookieFile);
                return null;
            }

            // Debug: HTML'in ilk kısmını logla
            Log::info("IBAN Fetch - HTML preview", ['html_start' => substr($html, 0, 3000)]);

            // Token'ı HTML'den çek - birçok olası formatı dene
            $token = null;
            
            // Format 1: <input name="_token" value="xxx">
            if (preg_match('/name=["\']_token["\']\s*value=["\']([^"\']+)["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 2: value="xxx" name="_token"
            if (!$token && preg_match('/value=["\']([^"\']+)["\']\s*name=["\']_token["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 3: <input name="token" value="xxx">
            if (!$token && preg_match('/name=["\']token["\']\s*value=["\']([^"\']+)["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 4: value="xxx" name="token"
            if (!$token && preg_match('/value=["\']([^"\']+)["\']\s*name=["\']token["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 5: data-token="xxx"
            if (!$token && preg_match('/data-token=["\']([^"\']+)["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 6: var _token = "xxx" veya var token = "xxx"
            if (!$token && preg_match('/var\s+_?token\s*=\s*["\']([^"\']+)["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 7: "token": "xxx" (JSON formatı)
            if (!$token && preg_match('/"token"\s*:\s*"([^"]+)"/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 8: token: 'xxx' (JS object formatı)
            if (!$token && preg_match("/token\s*:\s*['\"]([^'\"]+)['\"]/", $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 9: <meta name="csrf-token" content="xxx">
            if (!$token && preg_match('/meta\s+name=["\']csrf-token["\']\s*content=["\']([^"\']+)["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 10: content="xxx" name="csrf-token"
            if (!$token && preg_match('/content=["\']([^"\']+)["\']\s*name=["\']csrf-token["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 11: id="token" value="xxx"
            if (!$token && preg_match('/id=["\']token["\']\s*value=["\']([^"\']+)["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 12: Genel hidden input ile uzun alfanumerik değer bul
            if (!$token && preg_match('/type=["\']hidden["\']\s*[^>]*value=["\']([A-Za-z0-9]{20,})["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }
            // Format 13: Genel hidden input - value önce
            if (!$token && preg_match('/value=["\']([A-Za-z0-9]{20,})["\']\s*[^>]*type=["\']hidden["\']/', $html, $tokenMatch)) {
                $token = $tokenMatch[1];
            }

            if (!$token) {
                Log::warning("IBAN Fetch - Token not found in HTML", ['html_length' => strlen($html)]);
                @unlink($cookieFile);
                return null;
            }

            Log::info("IBAN Fetch - Token extracted", ['token' => substr($token, 0, 10) . '...']);

            // CSRF token'ı da çek (Laravel meta tag'ından)
            $csrfToken = null;
            if (preg_match('/meta\s+name=["\']csrf-token["\']\s*content=["\']([^"\']+)["\']/', $html, $csrfMatch)) {
                $csrfToken = $csrfMatch[1];
            } elseif (preg_match('/content=["\']([^"\']+)["\']\s*name=["\']csrf-token["\']/', $html, $csrfMatch)) {
                $csrfToken = $csrfMatch[1];
            }

            // XSRF-TOKEN cookie'sini oku
            $xsrfToken = null;
            if (file_exists($cookieFile)) {
                $cookieContent = file_get_contents($cookieFile);
                if (preg_match('/XSRF-TOKEN\s+(.+)$/m', $cookieContent, $xsrfMatch)) {
                    $xsrfToken = urldecode(trim($xsrfMatch[1]));
                }
            }

            Log::info("IBAN Fetch - CSRF tokens", [
                'csrf_token' => $csrfToken ? substr($csrfToken, 0, 10) . '...' : 'NOT_FOUND',
                'xsrf_cookie' => $xsrfToken ? substr($xsrfToken, 0, 10) . '...' : 'NOT_FOUND',
                'cookie_file_content' => file_exists($cookieFile) ? substr(file_get_contents($cookieFile), 0, 1000) : 'FILE_NOT_FOUND'
            ]);

            // POST header'larını hazırla (CSRF dahil)
            $postHeaders = [
                'X-Requested-With: XMLHttpRequest',
                'Accept: application/json',
                'Referer: ' . $extraUrl,
                'Origin: https://extracuzdan.com'
            ];
            if ($csrfToken) {
                $postHeaders[] = 'X-CSRF-TOKEN: ' . $csrfToken;
            }
            if ($xsrfToken) {
                $postHeaders[] = 'X-XSRF-TOKEN: ' . $xsrfToken;
            }

            // POST body'sine _token ekle (Laravel CSRF için)
            $csrfField = $csrfToken ?: $token;

            // 2. POST #1 - pricesearch
            $ch = curl_init($extraUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                '_token' => $csrfField,
                'token' => $token,
                'page' => 'pricesearch',
                'price' => $amount
            ]));
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeaders);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response1 = curl_exec($ch);
            curl_close($ch);

            Log::info("IBAN Fetch - pricesearch response", ['response' => $response1]);

            // 3. POST #2 - type=save (IBAN bilgisini al)
            $ch = curl_init($extraUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                '_token' => $csrfField,
                'token' => $token,
                'page' => 'pricesearch',
                'price' => $amount,
                'type' => 'save',
                'bank' => ''
            ]));
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeaders);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response2 = curl_exec($ch);
            curl_close($ch);

            Log::info("IBAN Fetch - save response", ['response' => substr($response2, 0, 500)]);

            // Cleanup cookie file
            @unlink($cookieFile);

            // 4. Parse IBAN bilgilerini JSON + HTML'den
            $data = json_decode($response2, true);

            if (!$data || !isset($data['success']) || $data['success'] !== true) {
                // "Hesap bulunamadı" hatasını özel olarak işaretle
                $msg = $data['message'] ?? '';
                if (stripos($msg, 'Hesap bulunamad') !== false || stripos($msg, 'hesap bulunamad') !== false) {
                    Log::warning("IBAN Fetch - No available bank account", ['message' => $msg]);
                    return ['error' => 'no_account', 'message' => $msg];
                }
                Log::warning("IBAN Fetch - Invalid response from save request", ['data' => $data]);
                return null;
            }

            $ibanHtml = $data['html'] ?? '';
            if (empty($ibanHtml)) {
                Log::warning("IBAN Fetch - No HTML in response");
                return null;
            }

            // HTML'den IBAN bilgilerini parse et
            $accountName = '';
            $iban = '';
            $description = '';

            // Sadece bankinfodiv bölümünü al (script kodlarını dahil etme)
            $bankHtml = $ibanHtml;
            if (preg_match('/<div[^>]*id=["\']bankinfodiv["\'][^>]*>(.*?)(?=<script|<\/div>\s*<script)/is', $ibanHtml, $divMatch)) {
                $bankHtml = $divMatch[1];
            }
            // Script taglerini tamamen kaldır
            $bankHtml = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $bankHtml);
            
            // Temiz text versiyonu oluştur
            $cleanText = strip_tags($bankHtml);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText); // Çoklu boşlukları tek boşluğa çevir
            $cleanText = trim($cleanText);
            
            Log::info("IBAN Fetch - Clean text for parsing", ['text' => $cleanText]);

            // IBAN numarasını bul (TR ile başlayan 26 haneli)
            if (preg_match('/\b(TR\d{24})\b/', $bankHtml, $ibanMatch)) {
                $iban = $ibanMatch[1];
            } elseif (preg_match('/\b(TR\d{24})\b/', $cleanText, $ibanMatch)) {
                $iban = $ibanMatch[1];
            }

            // Hesap sahibi adını bul
            // IBAN'dan önce gelen bold/büyük yazıyı bul (genellikle SVG copy butonundan önceki satır)
            if ($iban && preg_match_all('/<(?:label|span|div|p|strong|b)[^>]*>([^<]{3,})<\/(?:label|span|div|p|strong|b)>/i', $bankHtml, $labelMatches)) {
                foreach ($labelMatches[1] as $match) {
                    $trimmed = trim(html_entity_decode($match, ENT_QUOTES, 'UTF-8'));
                    // Filtrele: IBAN değil, açıklama metni değil, talimat metni değil
                    if (!empty($trimmed) 
                        && strpos($trimmed, 'TR') !== 0 
                        && strpos($trimmed, 'Lütfen') === false 
                        && strpos($trimmed, 'Ödeme') === false
                        && strpos($trimmed, 'ÖDEMEYİ') === false
                        && strpos($trimmed, 'Açıklama') === false
                        && strpos($trimmed, 'boş') === false
                        && strpos($trimmed, '₺') === false
                        && mb_strlen($trimmed) > 3
                        && mb_strlen($trimmed) < 60
                        && preg_match('/[a-zA-ZçÇğĞıİöÖşŞüÜ]/u', $trimmed)
                    ) {
                        $accountName = $trimmed;
                        break;
                    }
                }
            }

            // Eğer hâlâ bulamadıysa, temiz text'ten IBAN'dan önceki satırı al
            if (empty($accountName) && $iban) {
                $ibanPos = strpos($cleanText, $iban);
                if ($ibanPos !== false) {
                    $beforeIban = trim(substr($cleanText, 0, $ibanPos));
                    // Son kelime grubunu al (muhtemelen isim)
                    $parts = preg_split('/[.!?]\s+/', $beforeIban);
                    $lastPart = trim(end($parts));
                    // En son anlamlı kelimeyi bul
                    if (preg_match('/([A-ZÇĞİÖŞÜa-zçğıöşü\s]{4,50})$/u', $lastPart, $nameMatch)) {
                        $possibleName = trim($nameMatch[1]);
                        if (mb_strlen($possibleName) > 3 && mb_strlen($possibleName) < 50) {
                            $accountName = $possibleName;
                        }
                    }
                }
            }

            // Açıklama bilgisini bul (sadece ilk satır, script kodları hariç)
            if (preg_match('/Açıklama[^:]*:\s*([^\n<]{3,80})/u', $cleanText, $descMatch)) {
                $description = trim($descMatch[1]);
            } elseif (preg_match('/Açıklama[^:]*:\s*(.+?)(?:\s{2,}|$)/u', $cleanText, $descMatch)) {
                $desc = trim($descMatch[1]);
                if (mb_strlen($desc) < 100) {
                    $description = $desc;
                }
            }

            if (empty($iban)) {
                Log::warning("IBAN Fetch - Could not parse IBAN from HTML");
                return null;
            }

            return [
                'account_name' => $accountName,
                'iban' => $iban,
                'description' => $description,
                'extra_url' => $extraUrl,
                'extra_token' => $token
            ];

        } catch (\Exception $e) {
            Log::error("IBAN Fetch - Exception", ['error' => $e->getMessage()]);
            return null;
        }
    }
}
