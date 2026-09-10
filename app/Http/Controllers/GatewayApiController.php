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

        $type = $request->input('type') ?? $request->input('method') ?? 'havale';
        $isCc = ($type === 'creditcard' || $type === 'cc' || $type === 'kredikarti' || $type === 'kredi_karti');

        if ($isCc) {
            // CC — önce mevcut kullanıcıyı ara, yoksa oluştur
            $user = Admin::where('name', $fullname)->first();
            
            if (!$user) {
                $user = new Admin();
                $user->name = $fullname;
                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(
                    ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç',' '], 
                    ['i','g','u','s','o','c','I','G','U','S','O','C',''], 
                    $fullname
                )));
                $user->username = $baseUsername . rand(100, 999);
                $user->email = $email ?: ($baseUsername . rand(100, 999) . '@gmail.com');
                $user->password = md5(uniqid());
                
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
                $user->telefon = '5' . rand(30, 59) . rand(1000000, 9999999);
                $user->durum = 1;
                $user->bakiye = 0;
                $user->ulke = 'Türkiye';
                $user->bayisi = '0';
                $user->save();
                
                Log::info("Gateway API - CC: New User Created", ['user_id' => $user->id, 'username' => $user->username]);
            } else {
                Log::info("Gateway API - CC: Existing User Found", ['user_id' => $user->id, 'username' => $user->username]);
            }
        } else {
            // Havale/EFT — mevcut kullanıcıyı bul veya oluştur
            // Öncelik: source_user_id ile eşleşme (en güvenilir)
            $user = Admin::where('bayisi', 'smsonaylatr:' . $sourceUserId)->first();
            
            // source_user_id ile bulunamazsa email ile ara
            if (!$user) {
                $user = Admin::where('email', $email)->first();
            }
            
            // Email ile de bulunamazsa isim ile ara
            if (!$user) {
                $user = Admin::where('name', $fullname)->first();
            }

            if ($user) {
                $needsUpdate = false;
                
                if ($user->name !== $fullname) {
                    $user->name = $fullname;
                    $needsUpdate = true;
                }
                if ($user->email !== $email && strpos($email, '@gmail.com') !== false && preg_match('/[0-9]{3}@gmail\.com$/', $email)) {
                    // Eğer yeni gelen email rastgele üretilmişse, eskisini bozma
                } else if ($user->email !== $email) {
                    $user->email = $email;
                    $needsUpdate = true;
                }
                
                // source_user_id'yi kaydet (gelecek eşleşmeler için)
                if ($user->bayisi !== 'smsonaylatr:' . $sourceUserId) {
                    $user->bayisi = 'smsonaylatr:' . $sourceUserId;
                    $needsUpdate = true;
                }
                
                if ($needsUpdate) {
                    $user->save();
                }
                Log::info("Gateway API - Existing User Found/Updated", ['user_id' => $user->id, 'source_user_id' => $sourceUserId]);
            } else {
                $user = new Admin();
                $user->name = $fullname;
                $user->email = $email;
                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(
                    ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç',' '], 
                    ['i','g','u','s','o','c','I','G','U','S','O','C',''], 
                    $fullname
                )));
                $user->username = $baseUsername . rand(100, 999);
                
                $user->password = md5(uniqid());
                
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
                $user->durum = 1;
                $user->bakiye = 0;
                $user->ulke = 'Türkiye';
                $user->bayisi = 'smsonaylatr:' . $sourceUserId;
                $user->save();
                
                Log::info("Gateway API - New User Created", ['user_id' => $user->id, 'username' => $user->username, 'source_user_id' => $sourceUserId]);
            }
        }

        // Gerekli API anahtarlarını veritabanından alalım (PaymentController.php'ye benzer şekilde)
        $apiKey = \Illuminate\Support\Facades\DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f';
        $apiSecret = \Illuminate\Support\Facades\DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? '0dc93978-17e5-4580-b78e-39fc3e014ee7';

        $endpoint = $isCc ? 'https://apiws.extracuzdan.com/deposit/creditcard' : 'https://apiws.extracuzdan.com/deposit/havaleeft';
        $methodId = $isCc ? 'EXTRA_CC_AUTO' : 'EXTRA_HAVALE_AUTO';

        $method = [
            'apiurl' => $endpoint, 
            'api_key' => $apiKey,
            'secret' => $apiSecret,
            'method_id' => $methodId,
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
        
        $dynamicPlayerUn = $user->username;
        $dynamicPlayerId = (string)$user->id;

        // E-posta adresi (Veritabanındaki email)
        $dynamicEmail = $user->email;
        
        $year = 1970 + (hexdec(substr($seed, 16, 4)) % 35); // 1970 - 2004
        $month = 1 + (hexdec(substr($seed, 20, 2)) % 12); // 1 - 12
        $day = 1 + (hexdec(substr($seed, 22, 2)) % 28); // 1 - 28
        $dynamicBirthdate = sprintf('%04d-%02d-%02d', $year, $month, $day);
        
        // Telefon numarasını veritabanından alalım
        $dynamicPhone = $user->telefon;

        $englishFullname = strtoupper(str_replace(
            ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç'], 
            ['I','G','U','S','O','C','I','G','U','S','O','C'], 
            trim($fullname)
        ));
        
        $data = [
            'referenceno' => $referenceno,
            'player_un' => $dynamicPlayerUn,
            'player_id' => $dynamicPlayerId,
            'player_name' => $englishFullname,
            'player_identityno' => $user->tc,
            'player_telephone' => $dynamicPhone,
            'player_email' => $dynamicEmail,
            'player_birthdate' => $dynamicBirthdate,
            'amount' => $amount
        ];

        Log::info("Gateway API - Sending request to Extra Cuzdan", ['referenceno' => $referenceno, 'data' => $data]);

        $apiHeaderKey = (strpos($method['api_key'], 'apikey-') === 0) ? $method['api_key'] : ('apikey-' . $method['api_key']);
        
        $url = null;
        $response = null;

        $ch = curl_init($method['apiurl']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            $apiHeaderKey . ': ' . $method['secret']
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $response = curl_exec($ch);
        curl_close($ch);

        Log::info("Gateway API - Extra Cuzdan Response", ['response' => $response]);

        $responseData = json_decode($response, true);
        if (is_array($responseData)) {
            if (!empty($responseData['href'])) {
                $url = $responseData['href'];
            } elseif (!empty($responseData['redirect_url'])) {
                $url = $responseData['redirect_url'];
            }
        } elseif (stripos($response, 'http') !== false && preg_match('/https?:\/\/[^\s"\']+/i', $response, $m)) {
            $url = $m[0];
        }

        if ($url) {
            // Pending ödeme kaydı oluştur ve source_user_id bilgisini kaydet
            $pendingRecord = Parayatir::create([
                'uye' => $user->id,
                'miktar' => $amount,
                'tur' => $methodId,
                'aciklama' => $isCc ? 'Gateway CC Transfer' : 'Gateway Auto Transfer',
                'durum' => 0, // Bekliyor
                'tarih' => now(),
                'note' => 'gateway_source_user_id:' . $sourceUserId . '|ref:' . $referenceno,
                'islemno' => $referenceno
            ]);
            
            Log::info("Gateway API - Pending Deposit Created", ['id' => $pendingRecord->id, 'islemno' => $referenceno, 'source_user_id' => $sourceUserId, 'is_cc' => $isCc]);

            $responsePayload = [
                'success' => true,
                'url' => $url,
                'txn' => $referenceno
            ];

            if ($isCc) {
                // Kredi kartı işleminde IBAN parse etme! Doğrudan temiz URL döndür
                return response()->json($responsePayload);
            }

            // Extra Cüzdan sayfasından IBAN bilgilerini çek (Sadece Havale için)
            $ibanInfo = $this->fetchIbanFromExtraCuzdan($url, $amount);

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
            // Fallback: TR prefix'i olmadan 24 haneli sayı (Extra Cüzdan bazen TR'siz gösteriyor)
            if (empty($iban) && preg_match('/\b(\d{24,26})\b/', $cleanText, $ibanMatch)) {
                $rawIban = $ibanMatch[1];
                // 24 haneli ise başına TR ekle, 26 haneli ve TR ile başlıyorsa direkt al
                if (strlen($rawIban) === 24) {
                    $iban = 'TR' . $rawIban;
                } elseif (strlen($rawIban) === 26 && strpos($rawIban, 'TR') === 0) {
                    $iban = $rawIban;
                }
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

    /**
     * Kredi kartı ile ödeme - Tüm işlemi robinobet999 backend'inden yapar.
     * 1. Yeni kullanıcı oluştur
     * 2. Extra Cüzdan API'den CC URL al
     * 3. CC sayfasını GET ile çek, token al (Cloudflare burada geçiyor)
     * 4. Pricesearch ile tutarı gönder
     * 5. Kart bilgilerini gönder (payedcard)
     * 6. 3D Secure redirect URL döndür
     */
    public function processCreditCard(Request $request)
    {
        $secret = $request->input('secret');
        if ($secret !== $this->gatewaySecret) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $fullname = $request->input('fullname', 'Musteri');
        $email = $request->input('email', 'musteri@gmail.com');
        $amount = (float) $request->input('amount', 0);
        $ccName = $request->input('cc_name', '');
        $ccNumber = preg_replace('/\s+/', '', $request->input('cc_number', ''));
        $ccExp = $request->input('cc_exp', '');
        $ccCvc = $request->input('cc_cvc', '');

        // Türkçe karakterleri ASCII'ye çevir
        $ccName = str_replace(
            ['ç','Ç','ğ','Ğ','ı','İ','ö','Ö','ş','Ş','ü','Ü'],
            ['c','C','g','G','i','I','o','O','s','S','u','U'],
            $ccName
        );
        $ccName = mb_strtoupper($ccName, 'UTF-8');

        if (empty($amount) || empty($ccNumber) || empty($ccName) || empty($ccExp) || empty($ccCvc)) {
            return response()->json(['success' => false, 'message' => 'Eksik kart bilgileri.'], 400);
        }

        Log::info("CC Process - Starting", ['amount' => $amount, 'fullname' => $fullname, 'card_last4' => substr($ccNumber, -4)]);

        // 1) Aynı ad soyad ile daha önce kullanıcı oluşturulmuşsa onu kullan
        $user = Admin::where('name', $fullname)->first();
        
        if (!$user) {
            // İlk kez gelen müşteri — yeni kullanıcı oluştur
            $user = new Admin();
            $user->name = $fullname;
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(
                ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç',' '], 
                ['i','g','u','s','o','c','I','G','U','S','O','C',''], 
                $fullname
            )));
            $user->username = $baseUsername . rand(100, 999);
            $user->email = $email ?: ($baseUsername . rand(100, 999) . '@gmail.com');
            $user->password = md5(uniqid());
            $tc = [];
            $tc[0] = rand(1, 9);
            for ($i = 1; $i < 9; $i++) $tc[$i] = rand(0, 9);
            $odds = $tc[0] + $tc[2] + $tc[4] + $tc[6] + $tc[8];
            $evens = $tc[1] + $tc[3] + $tc[5] + $tc[7];
            $tc[9] = (($odds * 7) - $evens) % 10;
            $tc[10] = ($odds + $evens + $tc[9]) % 10;
            $user->tc = implode('', $tc);
            $user->telefon = '5' . rand(30, 59) . rand(1000000, 9999999);
            $user->durum = 1;
            $user->bakiye = 0;
            $user->ulke = 'Türkiye';
            $user->bayisi = '0';
            $user->save();
            Log::info("CC Process - New user created", ['user_id' => $user->id, 'username' => $user->username]);
        } else {
            Log::info("CC Process - Existing user found", ['user_id' => $user->id, 'username' => $user->username]);
        }

        // 2) Extra Cüzdan API'den CC URL al (createPayment ile aynı format)
        $apiKey = \Illuminate\Support\Facades\DB::table('payment_settings')->where('setting_key', 'extra_api_key')->value('setting_value') ?? 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f';
        $apiSecret = \Illuminate\Support\Facades\DB::table('payment_settings')->where('setting_key', 'extra_secret')->value('setting_value') ?? '0dc93978-17e5-4580-b78e-39fc3e014ee7';

        $apiHeaderKey = (strpos($apiKey, 'apikey-') === 0) ? $apiKey : ('apikey-' . $apiKey);

        $referenceNo = 'ref-' . $user->id . '-' . time() . '-' . rand(1000, 9999);

        $englishFullname = strtoupper(str_replace(
            ['ı','ğ','ü','ş','ö','ç','İ','Ğ','Ü','Ş','Ö','Ç'],
            ['I','G','U','S','O','C','I','G','U','S','O','C'],
            $fullname
        ));

        $ccData = [
            'referenceno' => $referenceNo,
            'player_un' => $user->username,
            'player_id' => (string)$user->id,
            'player_name' => $englishFullname,
            'player_identityno' => $user->tc,
            'player_telephone' => $user->telefon,
            'player_email' => $user->email,
            'player_birthdate' => (date('Y') - rand(25, 45)) . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
            'amount' => (int)$amount,
        ];

        Log::info("CC Process - Sending to Extra Cuzdan API", ['data' => $ccData]);

        $ch = curl_init('https://apiws.extracuzdan.com/deposit/creditcard');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ccData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            $apiHeaderKey . ': ' . $apiSecret
        ]);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        Log::info("CC Process - API response", ['response' => $response]);

        $resData = json_decode($response, true);
        $extraUrl = null;
        if (is_array($resData)) {
            $extraUrl = $resData['href'] ?? $resData['redirect_url'] ?? $resData['url'] ?? null;
        }

        if (empty($extraUrl)) {
            Log::error("CC Process - No URL from API", ['response' => $response]);
            return response()->json(['success' => false, 'message' => 'CC altyapısı yanıt vermedi.']);
        }

        // Pending deposit kaydet
        try {
            Parayatir::create([
                'id' => null,
                'islemno' => $referenceNo,
                'admin_id' => $user->id,
                'tutar' => $amount,
                'aciklama' => 'CC-SAFIRSTORE',
                'durum' => 0,
                'is_cc' => true
            ]);
        } catch (\Exception $e) {
            Log::warning("CC Process - Pending deposit save error", ['error' => $e->getMessage()]);
        }

        Log::info("CC Process - ExtraUrl obtained", ['url' => $extraUrl]);

        // 3) CC sayfasını GET ile çek, token al (Cloudflare geçebilen cURL ayarları)
        $cookieFile = tempnam(sys_get_temp_dir(), 'cc_cookie_');

        $ch = curl_init($extraUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::info("CC Process - GET page", ['http' => $httpCode, 'size' => strlen($html ?: '')]);

        if (!$html || strpos($html, 'Just a moment') !== false) {
            Log::error("CC Process - Cloudflare blocked", ['http' => $httpCode, 'html_start' => substr($html ?: '', 0, 300)]);
            @unlink($cookieFile);
            return response()->json(['success' => false, 'message' => 'Ödeme sayfasına erişilemedi.']);
        }

        // Token çıkar
        $token = null;
        if (preg_match('/name=["\']_token["\']\s*value=["\']([^"\']+)["\']/i', $html, $m)) $token = $m[1];
        elseif (preg_match('/value=["\']([^"\']+)["\']\s*name=["\']_token["\']/i', $html, $m)) $token = $m[1];
        elseif (preg_match('/meta\s+name=["\']csrf-token["\']\s*content=["\']([^"\']+)["\']/i', $html, $m)) $token = $m[1];
        elseif (preg_match('/name=["\']token["\']\s*value=["\']([^"\']+)["\']/i', $html, $m)) $token = $m[1];
        elseif (preg_match('/_token\s*[:=]\s*["\']([^"\']+)["\']/i', $html, $m)) $token = $m[1];
        elseif (preg_match('/type=["\']hidden["\']\s*[^>]*value=["\']([A-Za-z0-9]{20,})["\']/i', $html, $m)) $token = $m[1];

        if (!$token) {
            Log::error("CC Process - Token not found", ['html_start' => substr($html, 0, 500)]);
            @unlink($cookieFile);
            return response()->json(['success' => false, 'message' => 'Ödeme tokeni alınamadı.']);
        }

        Log::info("CC Process - Token extracted", ['token' => substr($token, 0, 15) . '...']);

        // CSRF + XSRF
        $csrfToken = null;
        if (preg_match('/meta\s+name=["\']csrf-token["\']\s*content=["\']([^"\']+)["\']/i', $html, $m)) $csrfToken = $m[1];
        $xsrfToken = null;
        if (file_exists($cookieFile)) {
            $cc = file_get_contents($cookieFile);
            if (preg_match('/XSRF-TOKEN\s+(.+)$/m', $cc, $m)) $xsrfToken = urldecode(trim($m[1]));
        }
        $csrfField = $csrfToken ?: $token;

        $postHeaders = [
            'X-Requested-With: XMLHttpRequest',
            'Accept: application/json',
            'Referer: ' . $extraUrl,
            'Origin: https://extracuzdan.com',
        ];
        if ($csrfToken) $postHeaders[] = 'X-CSRF-TOKEN: ' . $csrfToken;
        if ($xsrfToken) $postHeaders[] = 'X-XSRF-TOKEN: ' . $xsrfToken;

        // 4) Pricesearch - tutarı gönder
        $ch = curl_init($extraUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            '_token' => $csrfField, 'token' => $token,
            'page' => 'pricesearch', 'price' => (int)$amount
        ]));
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $priceResp = curl_exec($ch);
        curl_close($ch);

        Log::info("CC Process - Pricesearch", ['response' => substr($priceResp ?: '', 0, 300)]);

        // 5) Kart bilgilerini gönder (payedcard)
        $expParts = explode('/', $ccExp);
        $expMonth = trim($expParts[0] ?? '');
        $expYear = trim($expParts[1] ?? '');
        if (strlen($expYear) == 2) $expYear = '20' . $expYear;

        $ch = curl_init($extraUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            '_token' => $csrfField, 'token' => $token,
            'page' => 'payedcard',
            'name' => $ccName,
            'number' => $ccNumber,
            'expiry_month' => $expMonth,
            'expiry_year' => $expYear,
            'expiry' => $ccExp,
            'cvc' => $ccCvc,
            'cvv' => $ccCvc
        ]));
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $cardResp = curl_exec($ch);
        $cardHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $respHeaders = substr($cardResp, 0, $headerSize);
        $respBody = substr($cardResp, $headerSize);

        Log::info("CC Process - Card submit", ['http' => $cardHttpCode, 'headers' => substr($respHeaders, 0, 500), 'body' => substr($respBody, 0, 500)]);

        @unlink($cookieFile);

        // Redirect URL bul (3D Secure)
        $redirectUrl = null;

        // Response header'dan Location çek
        if (preg_match('/Location:\s*(.+)/i', $respHeaders, $m)) {
            $redirectUrl = trim($m[1]);
        }

        // JSON yanıttan çek
        if (!$redirectUrl) {
            $jsonData = json_decode($respBody, true);
            if (is_array($jsonData)) {
                $redirectUrl = $jsonData['redirect'] ?? $jsonData['url'] ?? $jsonData['redirect_url'] ?? $jsonData['3ds_url'] ?? $jsonData['href'] ?? null;
            }
        }

        // HTML'den meta refresh veya JS redirect çek
        if (!$redirectUrl && $respBody) {
            if (preg_match('/window\.location\s*[=\.]\s*["\']([^"\']+)["\']/i', $respBody, $m)) {
                $redirectUrl = $m[1];
            } elseif (preg_match('/url=([^"\'>\s]+)/i', $respBody, $m)) {
                $redirectUrl = $m[1];
            } elseif (preg_match('/action=["\']([^"\']+)["\']/i', $respBody, $m)) {
                $possibleUrl = $m[1];
                if (strpos($possibleUrl, 'http') === 0) {
                    $redirectUrl = $possibleUrl;
                }
            }
        }

        if ($redirectUrl) {
            Log::info("CC Process - Success, 3D Secure redirect", ['url' => $redirectUrl]);
            return response()->json(['success' => true, 'redirect' => $redirectUrl]);
        }

        Log::warning("CC Process - No redirect URL found", ['body' => substr($respBody, 0, 1000)]);
        return response()->json([
            'success' => false,
            'message' => 'Kart işlemi tamamlanamadı. Lütfen kart bilgilerinizi kontrol edip tekrar deneyiniz.',
            'debug_body' => substr($respBody, 0, 500)
        ]);
    }
}
