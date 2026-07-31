<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Yonetici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class AuthController extends Controller
{
    public function showLogin()
    {
        $settings = \App\Models\Ayarlar::getSettings();
        return view('auth.login', compact('settings'));
    }

    public function showRegister(Request $request)
    {
        $settings = \App\Models\Ayarlar::getSettings();
        
        // Referans ID'sini al ve session'a kaydet
        $refId = $request->get('ref');
        if ($refId) {
            $referrer = \App\Models\Admin::where('id', $refId)->where('aff', 1)->first();
            if ($referrer) {
                session(['referral_id' => $refId]);
            }
        }
        
        return view('auth.register', compact('settings'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            \Log::info('Login attempt for: ' . $request->username);
            
            // Önce yonetici tablosunda kontrol et (admin paneline giriş)
            $yonetici = Yonetici::where('kullanici_adi', $request->username)->first();
            
            \Log::info('Yonetici found: ' . ($yonetici ? 'Yes' : 'No'));
            
            if ($yonetici) {
                \Log::info('Yonetici password check: ' . (md5($request->password) === $yonetici->sifre ? 'Match' : 'No match'));
                
                if (md5($request->password) === $yonetici->sifre) {
                    Auth::guard('yonetici')->login($yonetici);
                    \Log::info('Yonetici login successful - redirecting to admin panel');
                    return redirect()->route('admin.dashboard');
                }
            }

            // Yonetici değilse admin tablosunda kontrol et (normal site girişi)
            $admin = Admin::where('username', $request->username)
                ->orWhere('email', $request->username)
                ->first();
            
            \Log::info('Admin found: ' . ($admin ? 'Yes' : 'No'));
            
            if ($admin) {
                \Log::info('Admin password check: ' . (md5($request->password) === $admin->password ? 'Match' : 'No match'));
                
                if (md5($request->password) === $admin->password) {
                    Auth::guard('admin')->login($admin);
                    \Log::info('Admin login successful');
                    // Affiliate ise direkt affiliate panele yönlendir
                    if ((int)($admin->aff ?? 0) === 1) {
                        return redirect()->route('affiliate.panel');
                    }
                    return redirect()->route('home');
                }
            }

            \Log::info('Login failed - no valid credentials');
            return back()->withErrors([
                'username' => 'Kullanıcı adı/email veya şifre hatalı.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors([
                'username' => 'Giriş yapılırken bir hata oluştu: ' . $e->getMessage(),
            ]);
        }
    }

    public function register(Request $request)
    {
        // Dinamik validation rules oluştur
        $validationRules = [
            'password' => 'required|min:6|confirmed',
        ];
        
        $activeFields = \App\Models\RegistrationSettings::getActiveFields();
        
        foreach ($activeFields as $field) {
            $rules = [];
            
            // Zorunlu alan kontrolü
            if ($field->is_required) {
                $rules[] = 'required';
            }
            
            // Alan tipine göre özel kurallar
            switch ($field->field_name) {
                case 'username':
                    $rules[] = 'unique:admin,username';
                    $rules[] = 'min:6';
                    break;
                case 'email':
                    $rules[] = 'email';
                    $rules[] = 'unique:admin,email';
                    break;
                case 'phoneNumber':
                    $rules[] = 'unique:admin,telefon';
                    break;
                case 'tc':
                    $rules[] = 'digits:11';
                    $rules[] = 'unique:admin,tc';
                    break;
                case 'birthDate':
                    $rules[] = 'date';
                    break;
                case 'postakodu':
                    $rules[] = 'digits:5';
                    break;
                case 'parabirimi':
                    $rules[] = 'in:₺,€,$';
                    break;
            }
            
            if (!empty($rules)) {
                $validationRules[$field->field_name] = implode('|', $rules);
            }
        }
        
        $request->validate($validationRules);

        // Dinamik olarak user data oluştur
        $userData = [
            'password' => md5($request->password),
            'bakiye' => 0,
            'durum' => 1,
            'spor' => 0,
            'casino' => 0,
            'cekim' => 0,
            '2factor' => 0,
            'aff' => 0,
            'bayisi' => session('referral_id', 0),
            'songirisi' => '',
            'kayit_ip' => $request->ip(),
            'kayit_tarih' => now(),
            'cevrim' => 0,
            'songiris' => '',
            'songirisip' => '',
            'ulke' => 'Türkiye',
        ];
        
        // Aktif alanlardan veri al
        foreach ($activeFields as $field) {
            if ($field->is_active && $request->has($field->field_name)) {
                switch ($field->field_name) {
                    case 'firstName':
                    case 'lastName':
                        if ($field->field_name == 'firstName') {
                            $firstName = $request->input('firstName', '');
                            $lastName = $request->input('lastName', '');
                            $userData['name'] = trim($firstName . ' ' . $lastName);
                        }
                        break;
                    case 'username':
                        $userData['username'] = $request->input($field->field_name);
                        break;
                    case 'email':
                        $userData['email'] = $request->input($field->field_name);
                        break;
                    case 'phoneNumber':
                        $userData['telefon'] = $request->input($field->field_name);
                        break;
                    case 'tc':
                        $userData['tc'] = $request->input($field->field_name);
                        break;
                    case 'birthDate':
                        $userData['dt'] = $request->input($field->field_name);
                        break;
                    case 'il':
                        $userData['il'] = $request->input($field->field_name);
                        break;
                    case 'ilce':
                        $userData['ilce'] = $request->input($field->field_name);
                        break;
                    case 'postakodu':
                        $userData['postakodu'] = $request->input($field->field_name) ?: null;
                        break;
                    case 'parabirimi':
                        $userData['parabirimi'] = $request->input($field->field_name);
                        break;
                }
            }
        }
        
        $user = Admin::create($userData);

        // Session'dan referans ID'sini temizle
        $referralId = session('referral_id', 0);
        session()->forget('referral_id');

        // Telegram bildirim gönder
        $this->sendRegistrationTelegram($user, $userData['username'] ?? '', $userData['name'] ?? '', $referralId, now());

        Auth::guard('admin')->login($user);

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        Auth::guard('yonetici')->logout();
        
        return redirect()->route('home');
    }

    public function showForgotPassword()
    {
        $settings = \App\Models\Ayarlar::getSettings();
        return view('auth.forgot', compact('settings'));
    }

    public function handleForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        // Her durumda aynı mesaj (güvenlik): eğer varsa mail gönderildi deriz
        $genericResponse = back()->with('status', 'Eğer e-posta kayıtlıysa şifre sıfırlama bilgileri gönderildi.');

        if (!$admin) {
            return $genericResponse;
        }

        // Yeni random şifre oluştur (8-10 karakter karma)
        $plainPassword = substr(bin2hex(random_bytes(8)), 0, 10);
        $admin->password = md5($plainPassword);
        $admin->save();

        // E-posta gönder (fallback'lı)
        $sent = false;
        $mailLog = [];
        $send = function(array $config) use (&$mailLog, $admin, $plainPassword) {
            $m = new PHPMailer(true);
            $m->SMTPDebug = 0;
            $m->Debugoutput = function($str) use (&$mailLog) { $mailLog[] = trim($str); };
            $m->CharSet = 'UTF-8';
            $m->Encoding = 'base64';
            $m->isSMTP();
            $m->Host = $config['host'];
            $m->SMTPAuth = true;
            $m->Username = 'destek@betedor101.com';
            $m->Password = 'CvR123+3be';
            $m->SMTPSecure = $config['secure'];
            $m->Port = $config['port'];
            $m->Timeout = 20;
            $m->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
            // Anti-spam alignment
            $m->Hostname = 'betedor101.com';
            $m->Sender = 'destek@betedor101.com';
            $m->addReplyTo('destek@betedor101.com', 'Betedor Destek');
            $m->MessageID = '<' . bin2hex(random_bytes(8)) . '@betedor101.com>';
            $m->XMailer = ' '; // Hide PHP mailer signature

            // DKIM (if key exists at storage/app/dkim_private.key)
            $dkimPath = storage_path('app/dkim_private.key');
            if (file_exists($dkimPath)) {
                $m->DKIM_domain = 'betedor101.com';
                $m->DKIM_private = $dkimPath;
                $m->DKIM_selector = 'default';
                $m->DKIM_passphrase = '';
                $m->DKIM_identity = $m->From;
            }
            $m->setFrom('destek@betedor101.com', 'Betedor Destek');
            $m->addAddress($admin->email, $admin->username ?? '');
            $m->isHTML(true);
            $m->Subject = 'Şifre Sıfırlama Bilgileri';
            $m->Body = view('emails.password-reset', [
                'username' => $admin->username,
                'password' => $plainPassword,
            ])->render();
            $m->AltBody = 'Kullanıcı Adı: ' . ($admin->username ?? '') . "\nYeni Şifre: " . $plainPassword;
            $m->send();
        };

        try {
            // 1) SMTPS 465 + IPv4
            $send([
                'host' => gethostbyname('mail.betedor101.com'),
                'secure' => PHPMailer::ENCRYPTION_SMTPS,
                'port' => 465,
            ]);
            $sent = true;
        } catch (\Throwable $e1) {
            \Log::warning('Password reset mail try1 failed: ' . $e1->getMessage());
            try {
                // 2) STARTTLS 587 (autoTLS)
                $send([
                    'host' => 'mail.betedor101.com',
                    'secure' => PHPMailer::ENCRYPTION_STARTTLS,
                    'port' => 587,
                ]);
                $sent = true;
            } catch (\Throwable $e2) {
                \Log::error('Password reset mail try2 failed: ' . $e2->getMessage());
                \Log::error('PHPMailer debug: ' . implode(' | ', $mailLog));
            }
        }

        return $genericResponse;
    }

    /**
     * Geçerli formatta rastgele TC kimlik no oluştur (11 haneli)
     * TC algoritmasına uygun: ilk hane 0 olamaz, 10. ve 11. hane kontrol basamağı
     */
    private function generateRandomTc(): string
    {
        // İlk 9 haneyi rastgele oluştur (ilk hane 1-9 arası)
        $digits = [];
        $digits[0] = rand(1, 9);
        for ($i = 1; $i < 9; $i++) {
            $digits[$i] = rand(0, 9);
        }

        // 10. hane: ((d1+d3+d5+d7+d9)*7 - (d2+d4+d6+d8)) mod 10
        $oddSum  = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $evenSum = $digits[1] + $digits[3] + $digits[5] + $digits[7];
        $digits[9] = (($oddSum * 7) - $evenSum) % 10;
        if ($digits[9] < 0) $digits[9] += 10;

        // 11. hane: (d1+d2+...+d10) mod 10
        $totalSum = 0;
        for ($i = 0; $i < 10; $i++) {
            $totalSum += $digits[$i];
        }
        $digits[10] = $totalSum % 10;

        return implode('', $digits);
    }

    /**
     * Yeni kayıt bildirimini Telegram'a gönder
     */
    private function sendRegistrationTelegram($user, $username, $fullName, $referralId, $now)
    {
        try {
            // Affiliate bilgisini al
            $affiliateInfo = 'Direkt Kayıt (Referans Yok)';
            if ($referralId > 0) {
                $affiliate = Admin::find($referralId);
                if ($affiliate) {
                    $affiliateInfo = $affiliate->username . ' (ID: ' . $affiliate->id . ')';
                }
            }

            $message = "🆕 <b>Yeni Üye Kaydı</b>\n\n"
                     . "👤 <b>Ad Soyad:</b> {$fullName}\n"
                     . "🔑 <b>Kullanıcı Adı:</b> <code>{$username}</code>\n"
                     . "🆔 <b>Üye ID:</b> {$user->id}\n"
                     . "👥 <b>Affiliate:</b> {$affiliateInfo}\n"
                     . "🌐 <b>IP:</b> {$user->kayit_ip}\n"
                     . "🕒 <b>Tarih:</b> {$now->format('d.m.Y H:i:s')}\n\n"
                     . "✅ Üyelik başarıyla oluşturuldu.";

            $this->sendTelegram($message);
        } catch (\Exception $e) {
            \Log::error('Telegram registration notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Telegram mesajı gönder
     */
    private function sendTelegram($message)
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (empty($token) || empty($chatId)) {
            \Log::warning('Telegram credentials not set in .env');
            return;
        }

        $url  = "https://api.telegram.org/bot{$token}/sendMessage";
        $data = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
} 