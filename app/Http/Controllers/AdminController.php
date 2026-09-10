<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Yonetici;
use App\Models\Game;
use App\Models\Provider;
use App\Models\Bonus;
use App\Models\Parayatir;
use App\Models\Paracek;
use App\Models\Transaction;
use App\Models\Slider;
use App\Models\Duyuru;
use App\Models\CasinoOyunlari;
use App\Models\CanliCasino;
use App\Models\Oyunlar;
use App\Models\BonusClaim;
use App\Models\Ayarlar;
use App\Models\Payment;
use App\Models\ProviderPhoto;
use App\Models\HomeSection;
use App\Models\BottomBannerImage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = Admin::count();
        $activeUsers = Admin::where('durum', 1)->count();
        $totalBalance = Admin::sum('bakiye');
        $totalDeposits = ParaYatir::where('durum', 1)->sum('miktar');
        $totalWithdrawals = ParaCek::where('durum', 1)->sum('miktar');
        $totalGames = Game::count();
        $totalProviders = Provider::count();
        
        // Bugünkü işlemler - tarih sütununu kullan
        $todayDeposits = ParaYatir::where('durum', 1)
            ->whereDate('tarih', today())
            ->sum('miktar');
        $todayWithdrawals = ParaCek::where('durum', 1)
            ->whereDate('tarih', today())
            ->sum('miktar');
        
        // Aktif oyunlar ve sağlayıcılar - status sütununu kullan
        $activeGames = Game::where('status', '1')->count();
        $activeProviders = Provider::where('status', 1)->count();
        
        // Casino Limit hesaplama
        $settings = Ayarlar::first();
        $casinoLimit = $settings ? $settings->casinolimit : 0;
        
        // Bet'lerin toplamı (düşülecek)
        $totalBets = Transaction::where('type', 'bet')->sum('amount');
        
        // Win'lerin toplamı (eklenecek)  
        $totalWins = Transaction::where('type', 'win')->sum('amount');
        
        // Kalan limit hesaplama: (başlangıç limit + winler) - betler
        $remainingLimit = ($casinoLimit + $totalWins) - $totalBets;
        
        // Son aktiviteler - Gerçek veriler
        $recentActivities = collect();
        
        // 1. Yeni oyuncu kayıtları (son 5)
        $newUsers = Admin::whereNotNull('kayit_tarih')
            ->orderBy('kayit_tarih', 'desc')
            ->take(5)
            ->get()
            ->map(function($user) {
                return [
                    'icon' => 'user-plus',
                    'title' => 'Yeni oyuncu kaydı',
                    'description' => $user->username . ' kullanıcısı sisteme kayıt oldu',
                    'time' => $user->kayit_tarih ? $user->kayit_tarih->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $user->kayit_tarih
                ];
            });
        $recentActivities = $recentActivities->merge($newUsers);
        
        // 2. Para yatırma işlemleri (durum=1 olanlar)
        $deposits = ParaYatir::where('durum', 1)
            ->whereNotNull('tarih')
            ->with('user')
            ->orderBy('tarih', 'desc')
            ->take(5)
            ->get()
            ->map(function($deposit) {
                $username = $deposit->user ? $deposit->user->username : 'Bilinmeyen';
                return [
                    'icon' => 'trending-up',
                    'title' => 'Para yatırma işlemi',
                    'description' => $username . ' ₺' . number_format($deposit->miktar, 2) . ' yatırım onaylandı',
                    'time' => $deposit->tarih ? $deposit->tarih->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $deposit->tarih
                ];
            });
        $recentActivities = $recentActivities->merge($deposits);
        
        // 3. Para çekme işlemleri (durum=1 olanlar)
        $withdrawals = ParaCek::where('durum', 1)
            ->whereNotNull('tarih')
            ->with('user')
            ->orderBy('tarih', 'desc')
            ->take(5)
            ->get()
            ->map(function($withdrawal) {
                $username = $withdrawal->user ? $withdrawal->user->username : 'Bilinmeyen';
                return [
                    'icon' => 'trending-down',
                    'title' => 'Para çekme işlemi',
                    'description' => $username . ' ₺' . number_format($withdrawal->miktar, 2) . ' çekim onaylandı',
                    'time' => $withdrawal->tarih ? $withdrawal->tarih->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $withdrawal->tarih
                ];
            });
        $recentActivities = $recentActivities->merge($withdrawals);
        
        // 4. Casino kazançları (type='win' ve amount>=1000)
        $casinoWins = Transaction::where('type', 'win')
            ->where('amount', '>=', 1000)
            ->whereNotNull('created_at')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($transaction) {
                $username = $transaction->user ? $transaction->user->username : 'Bilinmeyen';
                return [
                    'icon' => 'trophy',
                    'title' => 'Casino kazancı',
                    'description' => $username . ' ₺' . number_format($transaction->amount, 2) . ' kazandı',
                    'time' => $transaction->created_at ? $transaction->created_at->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $transaction->created_at
                ];
            });
        $recentActivities = $recentActivities->merge($casinoWins);
        
        // 5. Büyük bahisler (type='bet' ve amount>=500)
        $bigBets = Transaction::where('type', 'bet')
            ->where('amount', '>=', 500)
            ->whereNotNull('created_at')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($transaction) {
                $username = $transaction->user ? $transaction->user->username : 'Bilinmeyen';
                return [
                    'icon' => 'target',
                    'title' => 'Büyük bahis',
                    'description' => $username . ' ₺' . number_format($transaction->amount, 2) . ' bahis aldı',
                    'time' => $transaction->created_at ? $transaction->created_at->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $transaction->created_at
                ];
            });
        $recentActivities = $recentActivities->merge($bigBets);
        
        // Tüm aktiviteleri tarihe göre sırala ve son 5'ini al
        $recentActivities = $recentActivities->sortByDesc('timestamp')->take(5)->values();
        
        // Yeni oyuncular (son 5 kayıt)
        $newUsers = Admin::whereNotNull('kayit_tarih')
            ->orderBy('kayit_tarih', 'desc')
            ->take(5)
            ->get()
            ->map(function($user) {
                return [
                    'icon' => 'user-plus',
                    'title' => 'Yeni oyuncu kaydı',
                    'description' => $user->username . ' kullanıcısı sisteme kayıt oldu',
                    'time' => $user->kayit_tarih ? $user->kayit_tarih->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $user->kayit_tarih
                ];
            });
        
        // Son işlemler (para yatırma ve çekme)
        $recentTransactions = collect();
        
        // Para yatırma işlemleri
        $deposits = ParaYatir::where('durum', 1)
            ->whereNotNull('tarih')
            ->with('user')
            ->orderBy('tarih', 'desc')
            ->take(5)
            ->get()
            ->map(function($deposit) {
                $username = $deposit->user ? $deposit->user->username : 'Bilinmeyen';
                return [
                    'icon' => 'trending-up',
                    'title' => 'Para yatırma işlemi',
                    'description' => $username . ' ₺' . number_format($deposit->miktar, 2) . ' yatırım onaylandı',
                    'time' => $deposit->tarih ? $deposit->tarih->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $deposit->tarih
                ];
            });
        $recentTransactions = $recentTransactions->merge($deposits);
        
        // Para çekme işlemleri
        $withdrawals = ParaCek::where('durum', 1)
            ->whereNotNull('tarih')
            ->with('user')
            ->orderBy('tarih', 'desc')
            ->take(5)
            ->get()
            ->map(function($withdrawal) {
                $username = $withdrawal->user ? $withdrawal->user->username : 'Bilinmeyen';
                return [
                    'icon' => 'trending-down',
                    'title' => 'Para çekme işlemi',
                    'description' => $username . ' ₺' . number_format($withdrawal->miktar, 2) . ' çekim onaylandı',
                    'time' => $withdrawal->tarih ? $withdrawal->tarih->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $withdrawal->tarih
                ];
            });
        $recentTransactions = $recentTransactions->merge($withdrawals);
        
        // Tüm işlemleri tarihe göre sırala ve son 5'ini al
        $recentTransactions = $recentTransactions->sortByDesc('timestamp')->take(5)->values();
        
        // Casino işlemleri (son 5)
        $casinoTransactions = Transaction::whereIn('type', ['win', 'bet'])
            ->whereNotNull('created_at')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($transaction) {
                $username = $transaction->user ? $transaction->user->username : 'Bilinmeyen';
                $icon = $transaction->type === 'win' ? 'trophy' : 'target';
                $title = $transaction->type === 'win' ? 'Casino kazancı' : 'Casino bahsi';
                $description = $username . ' ₺' . number_format($transaction->amount, 2) . 
                              ($transaction->type === 'win' ? ' kazandı' : ' bahis aldı');
                
                return [
                    'icon' => $icon,
                    'title' => $title,
                    'description' => $description,
                    'time' => $transaction->created_at ? $transaction->created_at->diffForHumans() : 'Bilinmeyen',
                    'timestamp' => $transaction->created_at
                ];
            });

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers', 
            'totalBalance',
            'totalDeposits',
            'totalWithdrawals',
            'totalGames',
            'totalProviders',
            'todayDeposits',
            'todayWithdrawals',
            'activeGames',
            'activeProviders',
            'casinoLimit',
            'totalBets',
            'totalWins',
            'remainingLimit',
            'recentActivities',
            'newUsers',
            'recentTransactions',
            'casinoTransactions'
        ));
    }

    public function users(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('durum', $request->status);
        }

        $users = $query->orderBy('kayit_tarih', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function exportUsers()
    {
        $users = Admin::select('username', 'name', 'email', 'telefon', 'il', 'bakiye', 'kayit_tarih')
                     ->orderBy('kayit_tarih', 'desc')
                     ->get();

        $filename = 'oyuncular_listesi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function() use($users) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM ekleme (Excel'de Türkçe karakterler için)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Başlık satırı
            fputcsv($file, [
                'KULLANICI ADI',
                'AD SOYAD', 
                'E-POSTA',
                'TELEFON',
                'İL',
                'BAKİYE (TL)',
                'KAYIT TARİHİ'
            ], ';');

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->username,
                    $user->name,
                    $user->email,
                    $user->telefon ?: 'Belirtilmemiş',
                    $user->il ?: 'Belirtilmemiş',
                    number_format($user->bakiye, 2, ',', '.') . ' ₺',
                    $user->kayit_tarih ? $user->kayit_tarih->format('d.m.Y H:i:s') : 'Belirtilmemiş'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function userDetails($id)
    {
        $user = Admin::findOrFail($id);
        $transactions = Transaction::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $deposits = ParaYatir::where('uye', $id)->orderBy('tarih', 'desc')->get();
        $withdrawals = ParaCek::where('user_id', $id)->orderBy('tarih', 'desc')->get();
        $bonusClaims = BonusClaim::where('user_id', $id)->orderBy('claimed_at', 'desc')->get();

        return view('admin.user-details', compact('user', 'transactions', 'deposits', 'withdrawals', 'bonusClaims'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    public function deposits(Request $request)
    {
        $query = ParaYatir::with('user');

        if ($request->filled('status')) {
            $query->where('durum', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('uye', $request->user_id);
        }

        $deposits = $query->orderBy('tarih', 'desc')->paginate(20);

        return view('admin.deposits', compact('deposits'));
    }

    public function withdrawals(Request $request)
    {
        $query = ParaCek::with('user');

        if ($request->filled('status')) {
            $query->where('durum', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $withdrawals = $query->orderBy('tarih', 'desc')->paginate(20);

        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function approveDeposit($id)
    {
        $deposit = ParaYatir::findOrFail($id);
        
        // Eğer zaten onaylanmışsa işlem yapma
        if ($deposit->durum == 1) {
            return back()->with('error', 'Bu işlem zaten onaylanmış.');
        }
        
        $deposit->update(['durum' => 1]);

        // Kullanıcının bakiyesini güncelle
        $user = Admin::find($deposit->uye);
        if ($user) {
            $user->increment('bakiye', $deposit->miktar);

            // Affiliate komisyon hesapla ve bakiyeye ekle
            $this->processAffiliateCommission($user, $deposit->miktar);
        }

        // Gateway Webhook Bildirimi
        if ($deposit->note && strpos($deposit->note, 'gateway_source_user_id:') !== false) {
            preg_match('/gateway_source_user_id:(\d+)\|ref:(.+)/', $deposit->note, $matches);
            if (count($matches) >= 3) {
                $sourceUserId = $matches[1];
                $txn = $matches[2];
                $postData = [
                    'source_user_id' => $sourceUserId,
                    'amount' => $deposit->miktar,
                    'txn' => $txn,
                    'transaction_id' => $deposit->id
                ];
                $webhookUrl = env('MAIN_WEBHOOK_URL', 'http://localhost');
                
                // Cloudflare bypass: doğrudan sunucu IP'sine bağlan
                $parsedUrl = parse_url($webhookUrl);
                $webhookHost = $parsedUrl['host'] ?? '';
                $webhookPort = ($parsedUrl['scheme'] ?? 'https') === 'https' ? 443 : 80;
                $originIp = env('MAIN_ORIGIN_IP', '45.90.99.60');
                
                $ch = curl_init($webhookUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Gateway-Secret: AUTO_TRANSFER_SECRET_12345',
                    'Host: ' . $webhookHost
                ]);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                // Cloudflare'ı bypass et: DNS çözümlemesini doğrudan origin IP'ye yönlendir
                curl_setopt($ch, CURLOPT_RESOLVE, [
                    "{$webhookHost}:{$webhookPort}:{$originIp}"
                ]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                $webhookResponse = curl_exec($ch);
                $webhookHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $webhookError = curl_error($ch);
                curl_close($ch);
                
                \Log::info("GATEWAY WEBHOOK SENT", [
                    'url' => $webhookUrl,
                    'origin_ip' => $originIp,
                    'http_code' => $webhookHttpCode,
                    'response' => substr($webhookResponse ?: '', 0, 500),
                    'curl_error' => $webhookError,
                    'post_data' => $postData
                ]);
            }
        }

        return back()->with('success', 'Para yatırma işlemi onaylandı.');
    }

    public function rejectDeposit($id)
    {
        $deposit = ParaYatir::findOrFail($id);
        
        // Eğer zaten reddedilmişse işlem yapma
        if ($deposit->durum == 2) {
            return back()->with('error', 'Bu işlem zaten reddedilmiş.');
        }
        
        $deposit->update(['durum' => 2]);

        return back()->with('success', 'Para yatırma işlemi reddedildi.');
    }

    public function approveWithdrawal($id)
    {
        $withdrawal = ParaCek::findOrFail($id);

        // Eğer zaten onaylanmışsa işlem yapma
        if ($withdrawal->durum == 1) {
            return back()->with('error', 'Bu işlem zaten onaylanmış.');
        }

        // Ayarlar ve API anahtarlarını config veya .env'den al
        $ayarlar = Ayarlar::getSettings();

        $uye = Admin::find($withdrawal->user_id);
        if (!$uye) {
            return back()->with('error', 'Kullanıcı bulunamadı.');
        }

        $apiKey = $apiPass = $methodId = $bankID = null;

        // Helper function to get payment setting from DB
        $getPaymentSetting = function($key, $default = '') {
            $setting = \DB::table('payment_settings')->where('setting_key', $key)->first();
            // Boş, '-' veya sadece boşluk içeren değerleri geçersiz say ve default kullan
            if ($setting && !empty(trim($setting->setting_value)) && trim($setting->setting_value) !== '-') {
                return trim($setting->setting_value);
            }
            return $default;
        };

        switch ($withdrawal->turu) {
            case 'HMEN_PAROLAPARA':
                $apiKey = $getPaymentSetting('hemen_parolapara_api_key', '6777a612e26a6c803c7a9af2');
                $apiPass = $getPaymentSetting('hemen_parolapara_api_pass', '6a6c803c7a9af3');
                $methodId = $getPaymentSetting('hemen_parolapara_method_id', '63763a78c01cfa7964c61ad7');
                break;
            case 'HMEN_PAYFIX':
                $apiKey = $getPaymentSetting('hemen_payfix_api_key', '6777a612e26a6c803c7a9af5');
                $apiPass = $getPaymentSetting('hemen_payfix_api_pass', '6a6c803c7a9af6');
                $methodId = $getPaymentSetting('hemen_payfix_method_id', '65452cd6530a432522de49c6');
                break;
            case 'HMEN_PAPARA':
                // DB'de 'papara_api_key' olarak kayıtlı
                $apiKey = $getPaymentSetting('papara_api_key', '6777a612e26a6c803c7a9ae6');
                $apiPass = $getPaymentSetting('hemen_papara_api_pass', '6a6c803c7a9ae7');
                $methodId = $getPaymentSetting('hemen_papara_method_id', '633417394f3595f9463f558f');
                break;
            case 'HMEN_MEFETE':
                // DB'de 'mefete_api_key' olarak kayıtlı
                $apiKey = $getPaymentSetting('mefete_api_key', '6777a612e26a6c803c7a9ae0');
                $apiPass = $getPaymentSetting('hemen_mefete_api_pass', '6a6c803c7a9ae1');
                $methodId = $getPaymentSetting('hemen_mefete_method_id', '633416ad4f3595f9463f5585');
                break;
            case 'HMEN_HAVALE':
                $apiKey = $getPaymentSetting('hemen_havale_api_key', '688e72197b1e6568e8810e1d');
                $apiPass = $getPaymentSetting('hemen_havale_api_pass', '1e6568e8810e1e');
                $methodId = $getPaymentSetting('hemen_havale_method_id', '633416cf4f3595f9463f5589');
                $bankID = $getPaymentSetting('hemen_havale_bank_id', '5f748545bffe7236203e56b0');
                break;
            case 'EXTRA_HAVALE_EFT':
                $apiKey = $getPaymentSetting('extra_api_key', 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f');
                // DB'de 'extra_secret' olarak kayıtlı
                $apiPass = $getPaymentSetting('extra_secret', '0dc93978-17e5-4580-b78e-39fc3e014ee7');
                $methodId = 'havaleeft';
                break;
            case 'EXTRA_HAVALE_FAST':
                $apiKey = $getPaymentSetting('extra_api_key', 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f');
                $apiPass = $getPaymentSetting('extra_secret', '0dc93978-17e5-4580-b78e-39fc3e014ee7');
                $methodId = 'havalefast';
                break;
            case 'EXTRA_PAPARA':
                $apiKey = $getPaymentSetting('extra_api_key', 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f');
                $apiPass = $getPaymentSetting('extra_secret', '0dc93978-17e5-4580-b78e-39fc3e014ee7');
                $methodId = 'papara';
                break;
            case 'EXTRA_PAPARA_IBAN':
                $apiKey = $getPaymentSetting('extra_api_key', 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f');
                $apiPass = $getPaymentSetting('extra_secret', '0dc93978-17e5-4580-b78e-39fc3e014ee7');
                $methodId = 'paparaiban';
                break;
            case 'EXTRA_PAYCO':
                $apiKey = $getPaymentSetting('extra_api_key', 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f');
                $apiPass = $getPaymentSetting('extra_secret', '0dc93978-17e5-4580-b78e-39fc3e014ee7');
                $methodId = 'payco';
                break;
            case 'EXTRA_PARAZULA':
                $apiKey = $getPaymentSetting('extra_api_key', 'apikey-65a095a3-b2dc-4c73-abda-349c9416ba4f');
                $apiPass = $getPaymentSetting('extra_secret', '0dc93978-17e5-4580-b78e-39fc3e014ee7');
                $methodId = 'parazula';
                break;
            default:
                return back()->with('error', 'Geçersiz çekim yöntemi!');
        }

        if (!$apiKey || !$apiPass || !$methodId) {
            return back()->with('error', 'API bilgileri eksik!');
        }

        $isExtraCuzdan = strpos($withdrawal->turu, 'EXTRA_') === 0;

        if ($isExtraCuzdan) {
            $requestData = [
                'referenceno' => 'd-' . $withdrawal->id,
                'player_un' => $uye->username,
                'player_id' => $uye->id,
                'player_name' => $uye->name,
                'player_accountno' => $withdrawal->hesap,
                'price' => $withdrawal->miktar
            ];

            if (in_array($methodId, ['havaleeft', 'havalefast'])) {
                $bankaMapping = [
                    'Ziraat Bankası' => 'ziraat-bankasi',
                    'VakıfBank' => 'vakifbank',
                    'Halkbank' => 'halkbank',
                    'Türkiye İş Bankası' => 'is-bankasi',
                    'Garanti BBVA' => 'garanti-bankasi',
                    'Akbank' => 'akbank',
                    'Yapı Kredi' => 'yapi-kredi',
                    'QNB Finansbank' => 'qnb-finansbank',
                    'TEB' => 'teb',
                    'DenizBank' => 'denizbank',
                    'ING Bank' => 'ing-bank',
                    'Şekerbank' => 'sekerbank',
                    'HSBC' => 'hsbc',
                    'Burgan Bank' => 'burgan-bank',
                    'Fibabanka' => 'fibabanka'
                ];

                $bankaAdi = $withdrawal->banka ?? 'Garanti BBVA';
                $bankaCode = $bankaMapping[$bankaAdi] ?? 'garanti-bankasi';
                $requestData['bank_name'] = $bankaCode;
            }

            if ($methodId === 'kripto') {
                $requestData['network'] = 'trx';
            }

            $apiUrl = "https://apiws.extracuzdan.com/withdraw/{$methodId}";

            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'EXTRA_CUZDAN_WITHDRAW',
                'method' => $methodId,
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $uye->id,
                'username' => $uye->username,
                'amount' => $withdrawal->miktar,
                'api_url' => $apiUrl,
                'request_data' => $requestData,
                'headers' => [
                    "{$apiKey}: {$apiPass}",
                    "Content-Type: application/x-www-form-urlencoded"
                ]
            ];
            file_put_contents(storage_path('logs/withdraw_api.log'), json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n", FILE_APPEND | LOCK_EX);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "{$apiKey}: {$apiPass}",
                "Content-Type: application/x-www-form-urlencoded",
                "Accept: application/json",
                "User-Agent: RobinoBet/1.0",
                "Connection: close"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($ch);
            if ($response === false) {
                $err = curl_error($ch);
                curl_close($ch);
                return back()->with('error', 'API isteği başarısız: ' . $err);
            }
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $responseLog = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'EXTRA_CUZDAN_RESPONSE',
                'withdrawal_id' => $withdrawal->id,
                'http_code' => $httpCode,
                'response' => $response,
                'response_array' => json_decode($response, true)
            ];
            file_put_contents(storage_path('logs/withdraw_api.log'), json_encode($responseLog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n", FILE_APPEND | LOCK_EX);

            // Cloudflare bloğu kontrolü
            if ($httpCode === 403 && strpos($response, 'cloudflare') !== false) {
                return back()->with('error', 'Extra Cüzdan API Cloudflare tarafından engellendi. Sunucu IP adresinizin Extra Cüzdan whitelist\'e eklenmesi gerekiyor. Lütfen Extra Cüzdan destek ekibiyle iletişime geçin.');
            }

            $respArr = json_decode($response, true);
            if (isset($respArr["success"]) && $respArr["success"] === true) {
                $withdrawal->update(['durum' => 2]);
                return back()->with('success', 'Para çekme işlemi onaylandı.');
            } else {
                $errorMsg = $respArr["message"] ?? (strlen($response) > 200 ? 'API bilinmeyen hata döndürdü (HTTP ' . $httpCode . ')' : $response);
                return back()->with('error', 'API hatası: ' . $errorMsg);
            }
        } else {
            $requestData = [
                "Key" => $apiKey,
                "PlayerID" => $uye->id,
                "PlayerUserName" => $uye->username,
                "PlayerFullName" => $uye->name,
                "PlayerIdentityNumber" => $uye->tc ?? "11111111111",
                "PlayerPhoneNumber" => $uye->telefon ?? "5555555555",
                "PlayerEmail" => $uye->email ?? "mail@mail.com",
                "TraderTransactionID" => $withdrawal->id,
                "AccountNumber" => $withdrawal->hesap,
                "Amount" => $withdrawal->miktar,
                "PaymentMethodID" => $methodId
            ];
            if ($bankID) {
                $requestData["BankID"] = $bankID;
            }

            $rawString = "";
            if ($bankID) {
                $rawString .= "AccountNumber{$requestData['AccountNumber']}Amount{$requestData['Amount']}BankID{$requestData['BankID']}Key{$requestData['Key']}PaymentMethodID{$requestData['PaymentMethodID']}PlayerEmail{$requestData['PlayerEmail']}PlayerFullName{$requestData['PlayerFullName']}PlayerID{$requestData['PlayerID']}PlayerIdentityNumber{$requestData['PlayerIdentityNumber']}PlayerPhoneNumber{$requestData['PlayerPhoneNumber']}PlayerUserName{$requestData['PlayerUserName']}TraderTransactionID{$requestData['TraderTransactionID']}" . $apiPass;
            } else {
                $rawString .= "AccountNumber{$requestData['AccountNumber']}Amount{$requestData['Amount']}Key{$requestData['Key']}PaymentMethodID{$requestData['PaymentMethodID']}PlayerEmail{$requestData['PlayerEmail']}PlayerFullName{$requestData['PlayerFullName']}PlayerID{$requestData['PlayerID']}PlayerIdentityNumber{$requestData['PlayerIdentityNumber']}PlayerPhoneNumber{$requestData['PlayerPhoneNumber']}PlayerUserName{$requestData['PlayerUserName']}TraderTransactionID{$requestData['TraderTransactionID']}" . $apiPass;
            }
            $requestData["checksum"] = md5($rawString);

            switch ($withdrawal->turu) {
                case 'HMEN_PAROLAPARA':
                    $apiUrl = "https://api1.vipparola.com/trader/set-withdraw";
                    break;
                case 'HMEN_PAYFIX':
                    $apiUrl = "https://api.vippayfx.com/trader/set-withdraw";
                    break;
                case 'HMEN_PAPARA':
                    $apiUrl = "https://api.hmnpay.com/trader/set-withdraw";
                    break;
                case 'HMEN_MEFETE':
                    $apiUrl = "https://api.vipmefete.com/trader/set-withdraw";
                    break;
                case 'HMEN_HAVALE':
                    $apiUrl = "https://api.hemenode.biz/trader/set-withdraw";
                    break;
                default:
                    $apiUrl = "https://api.hmnpay.com/trader/set-withdraw";
                    break;
            }

            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'HEMENPAY_WITHDRAW',
                'method' => $withdrawal->turu,
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $uye->id,
                'username' => $uye->username,
                'amount' => $withdrawal->miktar,
                'api_url' => $apiUrl,
                'request_data' => $requestData,
                'headers' => ["Content-Type: application/json"]
            ];
            file_put_contents(storage_path('logs/withdraw_api.log'), json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n", FILE_APPEND | LOCK_EX);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json",
                "User-Agent: RobinoBet/1.0"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($ch);
            if ($response === false) {
                $err = curl_error($ch);
                curl_close($ch);
                return back()->with('error', 'API isteği başarısız: ' . $err);
            }
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $responseLog = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'HEMENPAY_RESPONSE',
                'withdrawal_id' => $withdrawal->id,
                'http_code' => $httpCode,
                'response' => $response,
                'response_array' => json_decode($response, true)
            ];
            file_put_contents(storage_path('logs/withdraw_api.log'), json_encode($responseLog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n", FILE_APPEND | LOCK_EX);

            // Cloudflare bloğu kontrolü
            if ($httpCode === 403 && stripos($response, 'cloudflare') !== false) {
                return back()->with('error', 'HemenPay API Cloudflare tarafından engellendi. Sunucu IP adresinizin whitelist\'e eklenmesi gerekiyor.');
            }

            $respArr = json_decode($response, true);
            if (isset($respArr["HasError"]) && $respArr["HasError"] === false) {
                $withdrawal->update(['durum' => 2]);
                return back()->with('success', 'Para çekme işlemi onaylandı.');
            } else {
                $errorMsg = $respArr["Description"] ?? (strlen($response) > 200 ? 'API bilinmeyen hata döndürdü (HTTP ' . $httpCode . ')' : $response);
                return back()->with('error', 'API hatası: ' . $errorMsg);
            }
        }
    }

    public function rejectWithdrawal($id)
    {
        $withdrawal = ParaCek::findOrFail($id);
        
        // Eğer zaten reddedilmişse işlem yapma
        if ($withdrawal->durum == 2) {
            return back()->with('error', 'Bu işlem zaten reddedilmiş.');
        }
        
        $withdrawal->update(['durum' => 2]);

        // Kullanıcının bakiyesini geri ver
        $user = Admin::find($withdrawal->user_id);
        if ($user) {
            $user->increment('bakiye', $withdrawal->miktar);
        }

        return back()->with('success', 'Para çekme işlemi reddedildi.');
    }

    public function settings()
    {
        $settings = Ayarlar::getSettings();
        $sliders = Slider::all();
        $bonuses = Bonus::all();
        $duyurular = Duyuru::all();
        $casinoGames = CasinoOyunlari::all();
        $canliCasino = CanliCasino::all();
        $oyunlar = Oyunlar::all();

        return view('admin.settings', compact(
            'settings',
            'sliders',
            'bonuses',
            'duyurular',
            'casinoGames',
            'canliCasino',
            'oyunlar'
        ));
    }

    public function updateSettings(Request $request)
    {
        // Form doğrulama
        $request->validate([
            'site_adi' => 'required|string|max:255',
            'site_durum' => 'required|in:0,1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:4096',
            'logo_size' => 'nullable|integer|min:20|max:200',
            'telegram' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'canlidestek' => 'nullable|regex:/^[0-9]{1,10}$/',
        ], [
            'site_adi.required' => 'Site adı zorunludur.',
            'logo.image' => 'Logo dosyası geçerli bir resim formatında olmalıdır.',
            'logo.max' => 'Logo dosyası maksimum 2MB olabilir.',
            'favicon.image' => 'Favicon dosyası geçerli bir resim formatında olmalıdır.',
            'favicon.max' => 'Favicon dosyası maksimum 1MB olabilir.',
            'telegram.url' => 'Telegram linki geçerli bir URL olmalıdır.',
            'instagram.url' => 'Instagram linki geçerli bir URL olmalıdır.',
            'twitter.url' => 'Twitter linki geçerli bir URL olmalıdır.',
            'canlidestek.regex' => 'LiveChat numarası sadece 1-10 haneli rakam olabilir.',
        ]);

        $settings = Ayarlar::getSettings();
        
        // Form verilerini al
        $data = $request->except(['logo', 'favicon', 'smsadet']);
        
        // Logo dosyası yükleme
        if ($request->hasFile('logo')) {
            // Eski logo dosyasını sil
            if ($settings->logo) {
                $settings->deleteOldFile($settings->logo);
            }
            
            $logoFile = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            
            // Dosyayı public/images klasörüne taşı
            $logoFile->move(public_path('images'), $logoName);
            $data['logo'] = '/images/' . $logoName;
        }
        
        // Favicon dosyası yükleme
        if ($request->hasFile('favicon')) {
            // Eski favicon dosyasını sil
            if ($settings->favicon) {
                $settings->deleteOldFile($settings->favicon);
            }
            
            $faviconFile = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
            
            // Dosyayı public/images klasörüne taşı
            $faviconFile->move(public_path('images'), $faviconName);
            $data['favicon'] = '/images/' . $faviconName;
        }
        
        $settings->update($data);

        return back()->with('success', 'Ayarlar başarıyla güncellendi. Logo ve favicon dosyaları public/images/ klasörüne kaydedildi.');
    }

    public function systemStats()
    {
        $totalUsers = Admin::count();
        $activeUsers = Admin::where('durum', 1)->count();
        $totalBalance = Admin::sum('bakiye');
        $totalDeposits = ParaYatir::where('durum', 1)->sum('miktar');
        $totalWithdrawals = ParaCek::where('durum', 1)->sum('miktar');
        $totalGames = Game::count();
        $totalProviders = Provider::count();

        return view('admin.system-stats', compact(
            'totalUsers',
            'activeUsers',
            'totalBalance',
            'totalDeposits',
            'totalWithdrawals',
            'totalGames',
            'totalProviders'
        ));
    }

    // Yeni eklenen metotlar
    public function games()
    {
        $games = Game::with('provider')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.games', compact('games'));
    }

    public function tournaments()
    {
        $tournaments = Game::where('turnuva', '1')->with('provider')->paginate(20);
        return view('admin.tournaments', compact('tournaments'));
    }

    public function paymentMethods()
    {
        return view('admin.payment-methods');
    }

    public function promos()
    {
        return view('admin.promos');
    }

    public function promoCodes()
    {
        $promoCodes = \DB::table('promo_codes')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($promoCode) {
                $usedCount = \DB::table('promo_code_usages')
                    ->where('promo_code_id', $promoCode->id)
                    ->count();
                
                $promoCode->used_count = $usedCount;
                return $promoCode;
            });
            
        return view('admin.promo-codes', compact('promoCodes'));
    }

    public function bonuses()
    {
        $bonuses = Bonus::orderBy('created_at', 'desc')->get();
        return view('admin.bonuses', compact('bonuses'));
    }

    public function affiliates(Request $request)
    {
        // AJAX alt üyeleri listeleme
        if ($request->get('load') === 'subs' && $request->filled('aff_id')) {
            $affId = (int)$request->get('aff_id');
            $subs = Admin::where('bayisi', $affId)
                ->orderBy('kayit_tarih', 'desc')
                ->get(['id', 'username', 'name', 'email', 'kayit_tarih']);

            // Bu alt üyelerin onaylı yatırımları ve çekimlerini getir
            $subIds = $subs->pluck('id')->all();
            $approvedDeposits = [];
            $approvedWithdrawals = [];
            if (!empty($subIds)) {
                $approvedDeposits = \DB::table('parayatir')
                    ->whereIn('uye', $subIds)
                    ->where('durum', 1)
                    ->select('uye', \DB::raw('SUM(miktar) as total'))
                    ->groupBy('uye')
                    ->pluck('total', 'uye')
                    ->toArray();

                $approvedWithdrawals = \DB::table('paracek')
                    ->whereIn('user_id', $subIds)
                    ->where('durum', 1)
                    ->select('user_id', \DB::raw('SUM(miktar) as total'))
                    ->groupBy('user_id')
                    ->pluck('total', 'user_id')
                    ->toArray();
            }

            $subsOut = $subs->map(function($u) use ($approvedDeposits, $approvedWithdrawals) {
                return [
                    'id' => $u->id,
                    'username' => $u->username,
                    'name' => $u->name,
                    'email' => $u->email,
                    'kayit_tarih' => optional($u->kayit_tarih)->format('d.m.Y H:i'),
                    'deposit_total' => (float)($approvedDeposits[$u->id] ?? 0),
                    'withdraw_total' => (float)($approvedWithdrawals[$u->id] ?? 0)
                ];
            });

            return response()->json(['subs' => $subsOut]);
        }

        // Üst aff hesapları: admin.aff = 1 olanlar
        $affiliatesQuery = Admin::where('aff', 1);

        if ($request->filled('search')) {
            $s = $request->get('search');
            $affiliatesQuery->where(function($q) use ($s) {
                $q->where('username', 'like', "%$s%")
                  ->orWhere('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }

        $affiliates = $affiliatesQuery->orderBy('kayit_tarih', 'desc')->paginate(20);

        // Alt üyeler: admin.bayisi = affiliate.id olanlar (her aff için sayıyı hızlı göstermek adına)
        $affiliateIds = $affiliates->pluck('id')->all();
        $subCounts = [];
        $depositTotals = [];
        $withdrawTotals = [];
        if (!empty($affiliateIds)) {
            // Alt üye adetleri
            $subCounts = \DB::table('admin')
                ->select('bayisi', \DB::raw('COUNT(*) as cnt'))
                ->whereIn('bayisi', $affiliateIds)
                ->groupBy('bayisi')
                ->pluck('cnt', 'bayisi')
                ->toArray();

            // Alt üyelerin onaylı yatırımlarının toplamı (parayatir.durum=1)
            $depositTotals = \DB::table('parayatir')
                ->join('admin', 'admin.id', '=', 'parayatir.uye')
                ->whereIn('admin.bayisi', $affiliateIds)
                ->where('parayatir.durum', 1)
                ->groupBy('admin.bayisi')
                ->select('admin.bayisi as aff_id', \DB::raw('SUM(parayatir.miktar) as total'))
                ->pluck('total', 'aff_id')
                ->toArray();

            // Alt üyelerin onaylı çekimlerinin toplamı (paracek.durum=1)
            $withdrawTotals = \DB::table('paracek')
                ->join('admin', 'admin.id', '=', 'paracek.user_id')
                ->whereIn('admin.bayisi', $affiliateIds)
                ->where('paracek.durum', 1)
                ->groupBy('admin.bayisi')
                ->select('admin.bayisi as aff_id', \DB::raw('SUM(paracek.miktar) as total'))
                ->pluck('total', 'aff_id')
                ->toArray();
        }

        return view('admin.affiliates', compact('affiliates', 'subCounts', 'depositTotals', 'withdrawTotals'));
    }

    public function storeBonus(Request $request)
    {
        $request->validate([
            'bonus_name' => 'required|string|max:255',
            'bonus_description' => 'nullable|string',
            'bonus_amount' => 'nullable|numeric|min:0',
            'yuzde' => 'nullable|numeric|min:0',
            'maxtutar' => 'nullable|numeric|min:0',
            'cevrim' => 'nullable|integer|min:0',
            'aktif' => 'required|in:0,1',
            'deneme' => 'nullable|boolean',
            'hosgeldin' => 'nullable|boolean',
            'yatirim' => 'nullable|boolean',
            'kayip' => 'nullable|boolean',
            'bonus_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        $data = [
            'bonus_name' => $request->bonus_name,
            'bonus_description' => $request->bonus_description,
            'bonus_amount' => $request->bonus_amount ?? 0,
            'yuzde' => $request->yuzde ?? 0,
            'maxtutar' => $request->maxtutar ?? 0,
            'cevrim' => $request->cevrim ?? 0,
            'aktif' => $request->aktif,
            'deneme' => $request->has('deneme') ? 1 : 0,
            'hosgeldin' => $request->has('hosgeldin') ? 1 : 0,
            'yatirim' => $request->has('yatirim') ? 1 : 0,
            'kayip' => $request->has('kayip') ? 1 : 0,
        ];
        
        // Görsel yüklendiyse
        if ($request->hasFile('bonus_image')) {
            $image = $request->file('bonus_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['bonus_image'] = '/images/' . $imageName;
        }
        
        Bonus::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Bonus başarıyla oluşturuldu.'
        ]);
    }

    public function updateBonus(Request $request, $id)
    {
        try {
            $bonus = Bonus::findOrFail($id);
            
            $request->validate([
                'bonus_name' => 'required|string|max:255',
                'bonus_description' => 'nullable|string',
                'bonus_amount' => 'nullable|numeric|min:0',
                'yuzde' => 'nullable|numeric|min:0',
                'maxtutar' => 'nullable|numeric|min:0',
                'cevrim' => 'nullable|integer|min:0',
                'aktif' => 'required|in:0,1',
                'deneme' => 'nullable|boolean',
                'hosgeldin' => 'nullable|boolean',
                'yatirim' => 'nullable|boolean',
                'kayip' => 'nullable|boolean',
                'bonus_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
            
            $data = [
                'bonus_name' => $request->bonus_name,
                'bonus_description' => $request->bonus_description,
                'bonus_amount' => $request->bonus_amount ?? 0,
                'yuzde' => $request->yuzde ?? 0,
                'maxtutar' => $request->maxtutar ?? 0,
                'cevrim' => $request->cevrim ?? 0,
                'aktif' => $request->aktif,
                'deneme' => $request->has('deneme') ? 1 : 0,
                'hosgeldin' => $request->has('hosgeldin') ? 1 : 0,
                'yatirim' => $request->has('yatirim') ? 1 : 0,
                'kayip' => $request->has('kayip') ? 1 : 0,
            ];
            
            // Yeni görsel yüklendiyse
            if ($request->hasFile('bonus_image')) {
                $image = $request->file('bonus_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $data['bonus_image'] = '/images/' . $imageName;
            }
            
            $bonus->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Bonus başarıyla güncellendi.',
                'bonus' => $bonus->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bonus güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteBonus($id)
    {
        $bonus = Bonus::findOrFail($id);
        $bonus->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Bonus başarıyla silindi.'
        ]);
    }

    public function toggleBonusStatus($id)
    {
        $bonus = Bonus::findOrFail($id);
        $bonus->update(['aktif' => $bonus->aktif == 1 ? 0 : 1]);
        
        return response()->json([
            'success' => true,
            'message' => 'Bonus durumu güncellendi.',
            'new_status' => $bonus->fresh()->aktif
        ]);
    }

    public function banners()
    {
        $sliders = Slider::orderBy('sira', 'asc')->get();
        $settings = Ayarlar::getSettings();
        $bottomImages = BottomBannerImage::orderBy('sira','asc')->get();
        
        return view('admin.banners', compact('sliders', 'settings', 'bottomImages'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:500',
            'sira' => 'required|integer|min:1',
            'gorsel' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:min_width=800,min_height=400,max_width=1920,max_height=1080',
        ]);
        
        $data = [
            'url' => $request->url,
            'sira' => $request->sira,
            'status' => 1, // Varsayılan olarak aktif
        ];
        
        // Görsel yükleme
        if ($request->hasFile('gorsel')) {
            $image = $request->file('gorsel');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['gorsel'] = '/images/' . $imageName;
        }
        
        Slider::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Banner başarıyla eklendi.'
        ]);
    }

    public function duyurular()
    {
        $duyurular = Duyuru::orderBy('created_at', 'desc')->get();
        
        return view('admin.duyurular', compact('duyurular'));
    }

    public function storeDuyuru(Request $request)
    {
        $request->validate([
            'konu' => 'required|string|max:255',
            'aciklama' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
            'resim' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=400,min_height=200,max_width=800,max_height=600',
        ]);
        
        $data = [
            'konu' => $request->konu,
            'aciklama' => $request->aciklama,
            'url' => $request->url,
            'status' => $request->status,
        ];
        
        // Görsel yüklendiyse
        if ($request->hasFile('resim')) {
            $image = $request->file('resim');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['resim'] = '/images/' . $imageName;
        }
        
        Duyuru::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Duyuru başarıyla oluşturuldu.'
        ]);
    }

    public function updateDuyuru(Request $request, $id)
    {
        $duyuru = Duyuru::findOrFail($id);
        
        $request->validate([
            'konu' => 'required|string|max:255',
            'aciklama' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
            'resim' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        $data = [
            'konu' => $request->konu,
            'aciklama' => $request->aciklama,
            'url' => $request->url,
            'status' => $request->status,
        ];
        
        // Yeni görsel yüklendiyse
        if ($request->hasFile('resim')) {
            $image = $request->file('resim');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['resim'] = '/images/' . $imageName;
        }
        
        $duyuru->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Duyuru başarıyla güncellendi.',
            'duyuru' => $duyuru->fresh()
        ]);
    }

    public function deleteDuyuru($id)
    {
        $duyuru = Duyuru::findOrFail($id);
        $duyuru->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Duyuru başarıyla silindi.'
        ]);
    }

    public function toggleDuyuruStatus($id)
    {
        $duyuru = Duyuru::findOrFail($id);
        $duyuru->update(['status' => $duyuru->status == 1 ? 0 : 1]);
        
        return response()->json([
            'success' => true,
            'message' => 'Duyuru durumu güncellendi.',
            'new_status' => $duyuru->fresh()->status
        ]);
    }

    public function updateBanner(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        
        $request->validate([
            'url' => 'required|string|max:500',
            'sira' => 'required|integer|min:1',
            'gorsel' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        $data = [
            'url' => $request->url,
            'sira' => $request->sira,
        ];
        
        // Yeni görsel yüklendiyse
        if ($request->hasFile('gorsel')) {
            $image = $request->file('gorsel');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['gorsel'] = '/images/' . $imageName;
        }
        
        $slider->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Banner başarıyla güncellendi.',
            'banner' => $slider->fresh()
        ]);
    }

    public function deleteBanner($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Banner başarıyla silindi.'
        ]);
    }

    public function toggleBannerStatus($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->update(['status' => $slider->status == 1 ? 0 : 1]);
        
        return response()->json([
            'success' => true,
            'message' => 'Banner durumu güncellendi.',
            'new_status' => $slider->fresh()->status
        ]);
    }

    // Alt banner (slider altı) görseli güncelle
    public function updateBottomBanner(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'url' => 'nullable|string|max:500',
            'active' => 'nullable|in:0,1',
            'mobile_grid' => 'nullable|in:0,1'
        ]);
        
        $settings = Ayarlar::getSettings();
        if (!$settings) {
            return back()->with('error', 'Ayarlar bulunamadı.');
        }
        
        $data = [];
        
        // Görsel yüklendiyse
        if ($request->hasFile('image')) {
            // Eski dosyayı sil
            if (!empty($settings->bottom_banner_image) && file_exists(public_path($settings->bottom_banner_image))) {
                @unlink(public_path($settings->bottom_banner_image));
            }
            $image = $request->file('image');
            $imageName = 'bottom_banner_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['bottom_banner_image'] = '/images/' . $imageName;
        }
        
        if ($request->filled('url')) {
            $data['bottom_banner_url'] = $request->url;
        }
        
        if ($request->has('active')) {
            $data['bottom_banner_active'] = (int)$request->active;
        }
        $data['bottom_below_mobile_grid'] = $request->has('mobile_grid') ? 1 : 0;
        
        if (!empty($data)) {
            $settings->update($data);
        }
        
        return back()->with('success', 'Alt banner alanı güncellendi.');
    }

    // Alt banner altı görseller
    public function storeBottomImage(Request $request)
    {
        $request->validate([
            'gorsel' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'url' => 'nullable|string|max:500',
            'sira' => 'nullable|integer|min:1',
            'mobile_grid' => 'nullable|in:0,1'
        ]);

        $data = [
            'url' => $request->url,
            'sira' => $request->sira ?: ((BottomBannerImage::max('sira') ?? 0) + 1),
            'aktif' => 1,
        ];

        if ($request->hasFile('gorsel')) {
            $image = $request->file('gorsel');
            $imageName = 'bottom_below_' . time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['gorsel'] = '/images/' . $imageName;
        }

        $item = BottomBannerImage::create($data);
        // Save global layout preference if provided
        if ($request->has('mobile_grid')) {
            $settings = Ayarlar::getSettings();
            if ($settings) {
                $settings->update(['bottom_below_mobile_grid' => (int)$request->mobile_grid]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Görsel eklendi.', 'item' => $item]);
    }

    public function updateBottomImage(Request $request, $id)
    {
        $item = BottomBannerImage::findOrFail($id);

        $request->validate([
            'gorsel' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'url' => 'nullable|string|max:500',
            'sira' => 'nullable|integer|min:1',
            'aktif' => 'nullable|in:0,1',
            'mobile_grid' => 'nullable|in:0,1'
        ]);

        $data = [];
        if ($request->filled('url')) $data['url'] = $request->url;
        if ($request->filled('sira')) $data['sira'] = (int)$request->sira;
        if ($request->has('aktif')) $data['aktif'] = (int)$request->aktif;

        if ($request->hasFile('gorsel')) {
            if ($item->gorsel && file_exists(public_path($item->gorsel))) @unlink(public_path($item->gorsel));
            $image = $request->file('gorsel');
            $imageName = 'bottom_below_' . time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $data['gorsel'] = '/images/' . $imageName;
        }

        if (!empty($data)) $item->update($data);
        if ($request->has('mobile_grid')) {
            $settings = Ayarlar::getSettings();
            if ($settings) {
                $settings->update(['bottom_below_mobile_grid' => (int)$request->mobile_grid]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Görsel güncellendi.', 'item' => $item->fresh()]);
    }

    public function deleteBottomImage($id)
    {
        $item = BottomBannerImage::findOrFail($id);
        if ($item->gorsel && file_exists(public_path($item->gorsel))) @unlink(public_path($item->gorsel));
        $item->delete();
        return response()->json(['success' => true, 'message' => 'Görsel silindi.']);
    }

    public function toggleBottomImage($id)
    {
        $item = BottomBannerImage::findOrFail($id);
        $item->aktif = $item->aktif ? 0 : 1;
        $item->save();
        return response()->json(['success' => true, 'message' => 'Durum güncellendi.', 'new_status' => $item->aktif]);
    }

    // Alt banner altı: mobil grid (2x2) layout toggle
    public function updateBottomBelowLayout(Request $request)
    {
        $request->validate([
            'mobile_grid' => 'required|in:0,1'
        ]);
        $settings = Ayarlar::getSettings();
        if (!$settings) {
            return response()->json(['success' => false, 'message' => 'Ayarlar bulunamadı.'], 404);
        }
        $settings->update(['bottom_below_mobile_grid' => (int)$request->mobile_grid]);
        return response()->json(['success' => true, 'message' => 'Görünüm ayarı güncellendi.']);
    }

    public function socialButtons()
    {
        return view('admin.social-buttons');
    }

    public function admins()
    {
        $admins = Admin::orderBy('kayit_tarih', 'desc')->paginate(20);
        return view('admin.admins', compact('admins'));
    }

    public function yoneticiler()
    {
        $yoneticiler = Yonetici::orderBy('olusturulma_tarihi', 'desc')->paginate(20);
        return view('admin.yoneticiler', compact('yoneticiler'));
    }

    public function toggleYoneticiStatus($id)
    {
        $yonetici = Yonetici::findOrFail($id);
        $yonetici->update(['durum' => $yonetici->durum == 1 ? 0 : 1]);
        
        return back()->with('success', 'Yönetici durumu güncellendi.');
    }

    public function updateYonetici(Request $request, $id)
    {
        $yonetici = Yonetici::findOrFail($id);
        
        $request->validate([
            'kullanici_adi' => 'required|string|max:255|unique:yoneticiler,kullanici_adi,' . $id,
            'eposta' => 'nullable|email',
            'telefon' => 'nullable|string|max:20',
            'yetki' => 'required|in:admin,moderator',
            'durum' => 'required|in:0,1',
            'yeni_sifre' => 'nullable|string|min:6',
            'yeni_sifre_tekrar' => 'nullable|same:yeni_sifre',
        ], [
            'yeni_sifre.min' => 'Şifre en az 6 karakter olmalıdır.',
            'yeni_sifre_tekrar.same' => 'Şifre tekrarı eşleşmiyor.',
        ]);
        
        // Temel bilgileri güncelle
        $yonetici->update($request->only(['kullanici_adi', 'eposta', 'telefon', 'yetki', 'durum']));
        
        // Şifre değiştirme kontrolü
        if ($request->filled('yeni_sifre')) {
            $yonetici->update(['sifre' => md5($request->yeni_sifre)]);
            $message = 'Yönetici bilgileri ve şifre güncellendi.';
        } else {
            $message = 'Yönetici bilgileri güncellendi.';
        }
        
        return back()->with('success', $message);
    }

    public function deleteYonetici($id)
    {
        $yonetici = Yonetici::findOrFail($id);
        
        // Kendini silmeye çalışıyorsa engelle
        if (auth()->guard('yonetici')->check() && auth()->guard('yonetici')->id() == $id) {
            return back()->with('error', 'Kendinizi silemezsiniz!');
        }
        
        $yonetici->delete();
        
        return back()->with('success', 'Yönetici silindi.');
    }

    public function providers()
    {
        $providers = Provider::orderBy('id', 'desc')->paginate(20);
        return view('admin.providers', compact('providers'));
    }

    public function toggleProviderStatus($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            $provider->status = $provider->status == 1 ? 0 : 1;
            $provider->save();

            return response()->json([
                'success' => true,
                'message' => 'Sağlayıcı durumu başarıyla güncellendi!',
                'new_status' => $provider->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function gamesList(Request $request)
    {
        $query = \App\Models\Game::with('provider');

        // Arama filtresi
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('game_name', 'like', '%' . $request->search . '%')
                  ->orWhere('game_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('provider', function($providerQuery) use ($request) {
                      $providerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sağlayıcı filtresi
        if ($request->filled('provider')) {
            $query->where('provider_id', $request->provider);
        }

        // Sıralama filtresi
        $sort = $request->get('sort', 'created_at_desc');
        switch ($sort) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('game_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('game_name', 'desc');
                break;
            case 'created_at_desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $games = $query->paginate(50); // Sayfa başına 50 oyun göster
        
        // Sağlayıcı listesi için
        $providers = \App\Models\Provider::orderBy('name')->get();

        return view('admin.games-list', compact('games', 'providers'));
    }

    public function updateGame(Request $request, $id)
    {
        $game = \App\Models\Game::findOrFail($id);
        
        $rules = [
            'game_name' => 'required|string|max:255',
            'game_code' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ];
        $settings = Ayarlar::getSettings();
        if ($settings && (int)($settings->fakeapi ?? 0) === 1) {
            $rules['ikincilmi'] = 'nullable|in:0,1';
            $rules['url'] = 'nullable|string|max:255';
            $rules['vendorcode'] = 'nullable|string|max:255';
        }
        
        $validated = $request->validate($rules);
        
        $updateData = [
            'game_name' => $validated['game_name'],
            'game_code' => $validated['game_code'],
            'status' => $validated['status'],
        ];
        if ($settings && (int)($settings->fakeapi ?? 0) === 1) {
            if ($request->has('ikincilmi')) {
                $updateData['ikincilmi'] = (int)$request->ikincilmi;
            }
            if ($request->has('url')) {
                $cleanUrl = $request->filled('url') ? ltrim(trim($request->url), '/') : null;
                $updateData['url'] = $cleanUrl; // baştaki '/' kaldırılarak kaydedilir
            }
            if ($request->has('vendorcode')) {
                $updateData['vendorcode'] = $request->filled('vendorcode') ? trim($request->vendorcode) : null;
            }
        }
        
        $game->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Oyun başarıyla güncellendi.',
            'game' => $game->fresh()
        ]);
    }

    public function toggleGameStatus($id)
    {
        $game = \App\Models\Game::findOrFail($id);
        $game->update(['status' => $game->status == 1 ? 0 : 1]);
        
        return response()->json([
            'success' => true,
            'message' => 'Oyun durumu güncellendi.',
            'new_status' => $game->fresh()->status
        ]);
    }

    public function logs()
    {
        return view('admin.logs');
    }

    public function backup()
    {
        return view('admin.backup');
    }

    public function toggleUserStatus($id)
    {
        $user = Admin::findOrFail($id);
        $user->update(['durum' => $user->durum == 1 ? 0 : 1]);
        
        return back()->with('success', 'Kullanıcı durumu güncellendi.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = Admin::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email,' . $id,
            'telefon' => 'nullable|string|max:20',
            'bakiye' => 'required|numeric|min:0',
            'durum' => 'required|in:0,1',
            'yeni_sifre' => 'nullable|string|min:6',
            'yeni_sifre_tekrar' => 'nullable|same:yeni_sifre',
            'aff' => 'nullable|in:0,1',
            'afforani' => 'nullable|numeric|min:0|max:100',
        ], [
            'yeni_sifre.min' => 'Şifre en az 6 karakter olmalıdır.',
            'yeni_sifre_tekrar.same' => 'Şifre tekrarı eşleşmiyor.',
        ]);
        
        $data = $request->only(['name', 'email', 'telefon', 'bakiye', 'durum']);
        $data['aff'] = $request->has('aff') ? 1 : 0;
        // Affiliate oranı (yalnızca aff açıksa set et; değilse 0 yap)
        if ($data['aff'] === 1) {
            $data['afforani'] = $request->filled('afforani') ? (float)$request->afforani : ($user->afforani ?? 0);
        } else {
            $data['afforani'] = 0;
        }
        $user->update($data);
        
        if ($request->filled('yeni_sifre')) {
            $user->update(['password' => md5($request->yeni_sifre)]);
        }
        
        return back()->with('success', 'Kullanıcı bilgileri güncellendi.');
    }

    public function updateUserBalance(Request $request, $id)
    {
        $user = Admin::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:add,subtract',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);
        
        $amount = $request->amount;
        $action = $request->action;
        $description = $request->description;
        
        if ($action === 'add') {
            $user->increment('bakiye', $amount);
            $message = "Bakiye eklendi: +{$amount} TL";
        } else {
            // Bakiye çıkarma işlemi - bakiye yeterli mi kontrol et
            if ($user->bakiye < $amount) {
                return response()->json(['success' => false, 'message' => 'Yetersiz bakiye!']);
            }
            $user->decrement('bakiye', $amount);
            $message = "Bakiye çıkarıldı: -{$amount} TL";
        }
        
        // Transaction log (eğer Transaction modeli varsa)
        // Transaction::create([
        //     'user_id' => $user->id,
        //     'type' => $action === 'add' ? 'balance_add' : 'balance_subtract',
        //     'amount' => $amount,
        //     'description' => $description,
        //     'admin_id' => auth()->guard('admin')->id() ?? auth()->guard('yonetici')->id(),
        // ]);
        
        return response()->json([
            'success' => true, 
            'message' => $message,
            'new_balance' => $user->fresh()->bakiye
        ]);
    }

    public function visualSettings()
    {
        $oyunlar = Oyunlar::orderBy('sira', 'asc')->get();
        $casinoOyunlari = CasinoOyunlari::orderBy('sira', 'asc')->get();
        $canliCasino = CanliCasino::orderBy('sira', 'asc')->get();

        return view('admin.visual-settings', compact('oyunlar', 'casinoOyunlari', 'canliCasino'));
    }

    public function updateVisualOrder(Request $request)
    {
        $type = $request->input('type');
        $items = $request->input('items');

        foreach ($items as $index => $item) {
            $id = $item['id'];
            $newOrder = $index + 1;

            switch ($type) {
                case 'oyunlar':
                    Oyunlar::where('id', $id)->update(['sira' => $newOrder]);
                    break;
                case 'casino_oyunlari':
                    CasinoOyunlari::where('id', $id)->update(['sira' => $newOrder]);
                    break;
                case 'canli_casino':
                    CanliCasino::where('id', $id)->update(['sira' => $newOrder]);
                    break;
            }
        }

        return response()->json(['success' => true]);
    }

    public function deleteVisualItem($type, $id)
    {
        switch ($type) {
            case 'oyunlar':
                Oyunlar::find($id)->delete();
                break;
            case 'casino_oyunlari':
                CasinoOyunlari::find($id)->delete();
                break;
            case 'canli_casino':
                CanliCasino::find($id)->delete();
                break;
        }

        return response()->json(['success' => true]);
    }

    public function uploadVisualItem(Request $request)
    {
        $request->validate([
            'type' => 'required|in:oyunlar,casino_oyunlari,canli_casino',
            'gorsel' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'required|string|max:500'
        ]);

        $type = $request->input('type');
        $url = $request->input('url');

        // Upload image
        $image = $request->file('gorsel');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images'), $imageName);
        $gorselUrl = '/images/' . $imageName;

        // Get next order number
        switch ($type) {
            case 'oyunlar':
                $maxSira = Oyunlar::max('sira') ?? 0;
                Oyunlar::create([
                    'gorsel' => $gorselUrl,
                    'url' => $url,
                    'sira' => $maxSira + 1
                ]);
                break;
            case 'casino_oyunlari':
                $maxSira = CasinoOyunlari::max('sira') ?? 0;
                CasinoOyunlari::create([
                    'gorsel' => $gorselUrl,
                    'url' => $url,
                    'sira' => $maxSira + 1
                ]);
                break;
            case 'canli_casino':
                $maxSira = CanliCasino::max('sira') ?? 0;
                CanliCasino::create([
                    'gorsel' => $gorselUrl,
                    'url' => $url,
                    'sira' => $maxSira + 1
                ]);
                break;
        }

        return response()->json(['success' => true]);
    }

    public function updateVisualItem(Request $request, $type, $id)
    {
        try {
            // Debug için log ekle
            \Log::info('Visual item update request', [
                'type' => $type,
                'id' => $id,
                'url' => $request->url,
                'has_file' => $request->hasFile('gorsel')
            ]);

            $request->validate([
                'url' => 'required|string|max:500',
                'gorsel' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            // Modeli bul
            switch ($type) {
                case 'oyunlar':
                    $item = Oyunlar::findOrFail($id);
                    break;
                case 'casino_oyunlari':
                    $item = CasinoOyunlari::findOrFail($id);
                    break;
                case 'canli_casino':
                    $item = CanliCasino::findOrFail($id);
                    break;
                default:
                    \Log::error('Invalid type provided', ['type' => $type]);
                    return response()->json(['success' => false, 'message' => 'Geçersiz tip']);
            }
            
            // URL'yi güncelle
            $item->url = $request->url;
            
            // Eğer yeni görsel yüklendiyse
            if ($request->hasFile('gorsel')) {
                // Eski dosyayı sil
                if ($item->gorsel && file_exists(public_path($item->gorsel))) {
                    unlink(public_path($item->gorsel));
                }
                
                // Yeni dosyayı kaydet
                $file = $request->file('gorsel');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = '/images/' . $fileName;
                $file->move(public_path('images'), $fileName);
                
                $item->gorsel = $filePath;
            }
            
            $result = $item->save();
            
            \Log::info('Visual item updated successfully', [
                'item_id' => $item->id,
                'new_url' => $item->url,
                'save_result' => $result
            ]);

            return response()->json(['success' => true, 'message' => 'Başarıyla güncellendi']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in visual item update', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json(['success' => false, 'message' => 'Validasyon hatası: ' . implode(', ', $e->errors()['url'] ?? ['Bilinmeyen hata'])]);
        } catch (\Exception $e) {
            \Log::error('Error updating visual item', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
        }
    }

    public function clearCache()
    {
        try {
            // Laravel cache'ini temizle
            \Artisan::call('cache:clear');
            
            // Config cache'ini temizle
            \Artisan::call('config:clear');
            
            // Route cache'ini temizle
            \Artisan::call('route:clear');
            
            // View cache'ini temizle
            \Artisan::call('view:clear');
            
            // Compiled class files'ı temizle
            \Artisan::call('clear-compiled');
            
            // Application cache'ini temizle
            \Artisan::call('optimize:clear');
            
            return back()->with('success', 'Tüm cache dosyaları başarıyla temizlendi!');
        } catch (\Exception $e) {
            return back()->with('error', 'Cache temizlenirken hata oluştu: ' . $e->getMessage());
        }
    }

    public function footerPayments()
    {
        $payments = Payment::orderBy('sira')->get();
        return view('admin.footer-payments', compact('payments'));
    }

    public function storeFooterPayment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order' => 'required|integer|min:1',
        ]);

        // Klasör yoksa oluştur
        $uploadPath = public_path('payments/images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Resmi yükle
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move($uploadPath, $imageName);
        $imagePath = 'payments/images/' . $imageName;

        Payment::create([
            'name' => $request->name,
            'gorsel' => $imagePath,
            'sira' => $request->order,
            'status' => 1,
        ]);

        return redirect()->back()->with('success', 'Ödeme yöntemi başarıyla eklendi!');
    }

    public function updateFooterPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'sira' => $request->order,
        ];

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($payment->gorsel && file_exists(public_path($payment->gorsel))) {
                unlink(public_path($payment->gorsel));
            }
            
            // Klasör yoksa oluştur
            $uploadPath = public_path('payments/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Yeni resmi yükle
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($uploadPath, $imageName);
            $data['gorsel'] = 'payments/images/' . $imageName;
        }

        $payment->update($data);

        return redirect()->back()->with('success', 'Ödeme yöntemi başarıyla güncellendi!');
    }

    public function deleteFooterPayment($id)
    {
        $payment = Payment::findOrFail($id);
        
        // Resmi sil
        if ($payment->gorsel && file_exists(public_path($payment->gorsel))) {
            unlink(public_path($payment->gorsel));
        }
        
        $payment->delete();

        return redirect()->back()->with('success', 'Ödeme yöntemi başarıyla silindi!');
    }

    public function toggleFooterPaymentStatus($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => !$payment->status]);

        $status = $payment->status ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "Ödeme yöntemi {$status} hale getirildi!");
    }

    // Provider Photos Methods
    public function providerPhotos()
    {
        $providers = ProviderPhoto::orderBy('sira', 'asc')->get();
        return view('admin.provider-photos', compact('providers'));
    }

    public function storeProviderPhoto(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'order' => 'required|integer|min:1'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'assets/providers/' . $imageName;
            
            // Klasör yoksa oluştur
            $uploadPath = public_path('assets/providers');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
        }

        ProviderPhoto::create([
            'name' => $request->name,
            'gorsel' => $imagePath,
            'link' => $request->link,
            'sira' => $request->order,
            'aktif' => true
        ]);

        return redirect()->back()->with('success', 'Sağlayıcı görseli başarıyla eklendi.');
    }

    public function updateProviderPhoto(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'link' => 'nullable|url|max:255',
            'order' => 'required|integer|min:1'
        ]);

        $provider = ProviderPhoto::findOrFail($id);
        
        $imagePath = $provider->gorsel;
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($provider->gorsel && file_exists(public_path($provider->gorsel))) {
                unlink(public_path($provider->gorsel));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'assets/providers/' . $imageName;
            
            // Klasör yoksa oluştur
            $uploadPath = public_path('assets/providers');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
        }

        $provider->update([
            'name' => $request->name,
            'gorsel' => $imagePath,
            'link' => $request->link,
            'sira' => $request->order
        ]);

        return redirect()->back()->with('success', 'Sağlayıcı görseli başarıyla güncellendi.');
    }

    public function deleteProviderPhoto($id)
    {
        $provider = ProviderPhoto::findOrFail($id);
        
        // Resmi sil
        if ($provider->gorsel && file_exists(public_path($provider->gorsel))) {
            unlink(public_path($provider->gorsel));
        }
        
        $provider->delete();
        
        return redirect()->back()->with('success', 'Sağlayıcı görseli başarıyla silindi.');
    }

    public function toggleProviderPhotoStatus($id)
    {
        $provider = ProviderPhoto::findOrFail($id);
        $provider->aktif = !$provider->aktif;
        $provider->save();
        
        return redirect()->back()->with('success', 'Sağlayıcı görseli durumu güncellendi.');
    }



    public function storePromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'amount' => 'required|numeric|min:1',
            'max_uses' => 'required|integer|min:1',
            'turnover_multiplier' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date|after:now'
        ], [
            'amount.required' => 'Bakiye miktarı zorunludur.',
            'amount.numeric' => 'Bakiye miktarı sayısal olmalıdır.',
            'turnover_multiplier.required' => 'Çevrim miktarı zorunludur.',
            'turnover_multiplier.numeric' => 'Çevrim miktarı sayısal olmalıdır.',
        ]);

        \DB::table('promo_codes')->insert([
            'code' => strtoupper($request->code),
            'balance_amount' => $request->amount,
            'max_uses' => $request->max_uses,
            'turnover_amount' => $request->turnover_multiplier,
            'expires_at' => $request->expires_at,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.promo-codes')->with('success', 'Promosyon kodu başarıyla oluşturuldu!');
    }

    public function updatePromoCode(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $id,
            'amount' => 'required|numeric|min:1',
            'max_uses' => 'required|integer|min:1',
            'turnover_multiplier' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date'
        ], [
            'amount.required' => 'Bakiye miktarı zorunludur.',
            'amount.numeric' => 'Bakiye miktarı sayısal olmalıdır.',
            'turnover_multiplier.required' => 'Çevrim miktarı zorunludur.',
            'turnover_multiplier.numeric' => 'Çevrim miktarı sayısal olmalıdır.',
        ]);

        \DB::table('promo_codes')->where('id', $id)->update([
            'code' => strtoupper($request->code),
            'balance_amount' => $request->amount,
            'max_uses' => $request->max_uses,
            'turnover_amount' => $request->turnover_multiplier,
            'expires_at' => $request->expires_at,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.promo-codes')->with('success', 'Promosyon kodu başarıyla güncellendi!');
    }

    public function deletePromoCode($id)
    {
        \DB::table('promo_codes')->where('id', $id)->delete();
        return redirect()->route('admin.promo-codes')->with('success', 'Promosyon kodu başarıyla silindi!');
    }

    public function togglePromoCodeStatus($id)
    {
        $promoCode = \DB::table('promo_codes')->where('id', $id)->first();
        $newStatus = !$promoCode->is_active;
        
        \DB::table('promo_codes')->where('id', $id)->update([
            'is_active' => $newStatus,
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Durum başarıyla güncellendi!');
    }

    // Anasayfa Bölüm Yönetimi
    public function homeSections()
    {
        $sections = HomeSection::orderBy('order', 'asc')->get();
        return view('admin.home-sections', compact('sections'));
    }

    public function updateHomeSectionOrder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*' => 'required|integer|exists:home_sections,id'
        ]);

        HomeSection::updateOrder($request->sections);

        return response()->json([
            'success' => true,
            'message' => 'Bölüm sırası başarıyla güncellendi.'
        ]);
    }

    public function toggleHomeSectionStatus($id)
    {
        $section = HomeSection::toggleActive($id);
        
        if ($section) {
            return response()->json([
                'success' => true,
                'message' => 'Bölüm durumu güncellendi.',
                'new_status' => $section->is_active
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bu bölüm zorunlu olduğu için kapatılamaz.'
        ], 400);
    }

    public function updateHomeSection(Request $request, $id)
    {
        $section = HomeSection::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string'
        ]);

        $section->update([
            'title' => $request->title,
            'icon' => $request->icon
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bölüm başarıyla güncellendi.',
            'section' => $section->fresh()
        ]);
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

            // Affiliate'i bul (aff=1 olmalı)
            $affiliate = Admin::where('id', $affiliateId)->where('aff', 1)->first();
            if (!$affiliate) {
                return;
            }

            $commissionRate = (float)($affiliate->afforani ?? 0);
            if ($commissionRate <= 0) {
                return;
            }

            // Komisyon hesapla
            $commission = round(($depositAmount * $commissionRate) / 100, 2);
            if ($commission <= 0) {
                return;
            }

            // Affiliate bakiyesine ekle
            $affiliate->increment('bakiye', $commission);

            \Log::info("COMMISSION: Affiliate {$affiliate->username} earned {$commission} TL ({$commissionRate}% of {$depositAmount} TL)");

            // Affiliate'e Telegram bildirimi gönder
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

    /**
     * Affiliate Telegram Chat ID kaydet
     */
    public function saveTelegramChatId(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:admin,id',
            'telegram_chat_id' => 'nullable|string|max:50',
        ]);

        $user = Admin::findOrFail($request->user_id);

        if ($user->aff != 1) {
            return response()->json(['success' => false, 'message' => 'Bu kullanıcı affiliate değil.']);
        }

        $user->update(['telegram_chat_id' => $request->telegram_chat_id ?: null]);

        return response()->json(['success' => true, 'message' => 'Telegram Chat ID kaydedildi.']);
    }
}
