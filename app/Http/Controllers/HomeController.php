<?php

namespace App\Http\Controllers;

use App\Models\Ayarlar;
use App\Models\Bonus;
use App\Models\CanliCasino;
use App\Models\CasinoOyunlari;
use App\Models\Duyuru;
use App\Models\Game;
use App\Models\Oyunlar;
use App\Models\Provider;
use App\Models\Slider;
use App\Models\HomeSection;
use App\Models\BottomBannerImage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private function maskUsername($username)
    {
        if (strlen($username) <= 4) {
            return str_repeat('*', strlen($username));
        }
        
        $firstChar = substr($username, 0, 1);
        $lastThreeChars = substr($username, -3);
        $middleLength = strlen($username) - 4;
        
        return $firstChar . str_repeat('*', $middleLength) . $lastThreeChars;
    }

    private function getRecentWinners()
    {
        $winners = \App\Models\Transaction::where('type', 'win')
            ->where('amount', '>=', 200)
            ->with(['user'])
            ->join('games', 'transactions.gameid', '=', 'games.game_code')
            ->select('transactions.*', 'games.game_name', 'games.cover')
            ->orderBy('transactions.created_at', 'desc')
            ->limit(15)
            ->get();
            
        // Username'leri maskele
        foreach ($winners as $winner) {
            if ($winner->user && $winner->user->username) {
                $winner->masked_username = $this->maskUsername($winner->user->username);
            } else {
                $winner->masked_username = 'Anonim';
            }
        }
        
        return $winners;
    }

    private function getUpcomingMatches()
    {
        $now = now();
        $nextDay = now()->addDay();
        
        return \DB::table('bulten')
            ->where('tur', 'Futbol')
            ->where('baslangic', '>=', $now)
            ->where('baslangic', '<=', $nextDay)
            ->orderBy('baslangic', 'asc')
            ->limit(4)
            ->get();
    }

    public function index()
    {
        $settings = Ayarlar::getSettings();
        $sliders = Slider::active()->ordered()->get();
        // $games = Game::where('status', '1')->limit(25)->get(); // eski
        
        // Yeni: Oyunlar, CasinoOyunlari, CanliCasino
        $oyunlar = \App\Models\Oyunlar::ordered()->active()->get();
        $casinoOyunlari = \App\Models\CasinoOyunlari::ordered()->active()->get();
        $canliCasino = \App\Models\CanliCasino::ordered()->active()->get();
        
        // Aktif duyurular
        $duyurular = Duyuru::where('status', 1)->orderBy('created_at', 'desc')->get();
        
        // Son kazananlar (sadece homewin = 1 ise)
        $recentWinners = collect();
        if ($settings->homewin == 1) {
            $recentWinners = $this->getRecentWinners();
        }
        
        // Yaklaşan maçlar (sadece homespor = 1 ise)
        $upcomingMatches = collect();
        if ($settings->homespor == 1) {
            $upcomingMatches = $this->getUpcomingMatches();
        }

        // Aktif bölümleri al
        $homeSections = HomeSection::getActiveSections();
        $bottomBannerBelowImages = collect();
        if (Schema::hasTable('bottom_banner_images')) {
            $bottomBannerBelowImages = BottomBannerImage::active()->ordered()->get();
        }

        return view('home', compact(
            'settings',
            'sliders',
            'oyunlar',
            'casinoOyunlari',
            'canliCasino',
            'duyurular',
            'recentWinners',
            'upcomingMatches',
            'homeSections',
            'bottomBannerBelowImages'
        ));
    }

    public function getGames(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 25;
        $offset = ($page - 1) * $perPage;

        $games = Game::where('status', '1')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        return response()->json([
            'games' => $games,
            'hasMore' => $games->count() === $perPage
        ]);
    }

    public function getCasinoGames(Request $request)
    {
        $page = $request->get('page', 1);
        $provider = $request->get('provider', 'all');
        $search = $request->get('search', '');
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $query = Game::where('status', '1')
            ->where('game_type', 'slots')
            ->with('provider');

        // Add search functionality
        if (!empty($search)) {
            $query->where('game_name', 'LIKE', '%' . $search . '%');
        }

        if (!empty($provider) && $provider !== 'all') {
            $normalized = strtolower(trim($provider));
            $query->whereRaw('LOWER(TRIM(vendorcode)) = ?', [$normalized]);
        } else {
            $query->orderByDesc('is_featured')
                  ->orderByDesc('views')
                  ->orderBy('game_name', 'asc');
        }

        $total = $query->count();
        $games = $query->offset($offset)
            ->limit($perPage)
            ->get();

        return response()->json([
            'games' => $games,
            'total' => $total,
            'hasMore' => $games->count() === $perPage,
            'currentPage' => $page
        ]);
    }

    public function getLiveCasinoGames(Request $request)
    {
        $page = $request->get('page', 1);
        $provider = $request->get('provider', 'all');
        $search = $request->get('search', '');
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $query = Game::where('status', '1')
            ->where(function($q){
                $q->whereRaw("LOWER(COALESCE(game_type,'')) <> 'slots'")
                  ->orWhere('has_tables', 1)
                  ->orWhere('game_type', 'LIKE', '%live%');
            })
            ->with('provider');

        // Add search functionality
        if (!empty($search)) {
            $query->where('game_name', 'LIKE', '%' . $search . '%');
        }

        if (!empty($provider) && $provider !== 'all') {
            $normalized = strtolower(trim($provider));
            $query->whereRaw('LOWER(TRIM(vendorcode)) = ?', [$normalized]);
        }

        $total = $query->count();
        $games = $query->offset($offset)
            ->limit($perPage)
            ->get();

        return response()->json([
            'games' => $games,
            'total' => $total,
            'hasMore' => $games->count() === $perPage,
            'currentPage' => $page
        ]);
    }

    public function casino()
    {
        $settings = Ayarlar::getSettings();
        $sliders = Slider::active()->ordered()->get();
        
        // Ilk ekranda 50 oyun: belirli game_id'lerini �ne al, ardindan �ne �ikan + g�r�nt�lenme + isim
        $priorityGameIds = [23724,23003,23846,24144,23002,24097,24000,23007,27756,23639,24237,23525,24095,23662,23991];
        $games = Game::where('status', '1')
            ->where('game_type', 'slots')
            ->with('provider')
            ->orderByRaw('CASE WHEN game_id IN (' . implode(',', $priorityGameIds) . ') THEN 0 ELSE 1 END')
            ->orderByRaw('FIELD(game_id, ' . implode(',', $priorityGameIds) . ')')
            ->orderByDesc('is_featured')
            ->orderByDesc('views')
            ->orderBy('game_name', 'asc')
            ->limit(50)
            ->get();
            
        // Saglayicilar: vendorcode'a g�re otomatik
        $allProviders = \DB::table('games')
            ->select('vendorcode', \DB::raw('COUNT(*) as cnt'))
            ->where('status', '1')
            ->where('game_type', 'slots')
            ->whereNotNull('vendorcode')
            ->where('vendorcode', '!=', '')
            ->groupBy('vendorcode')
            ->get()
            ->map(function($row){
                return (object)[
                    'code' => $row->vendorcode,
                    'name' => $row->vendorcode,
                    'count' => (int)$row->cnt,
                ];
            });
        
        // �ncelikli saglayicilar: pragmatic, egtdigital, amusnet
        $priorityCodes = ['pragmatic', 'egtdigital', 'amusnet'];
        $priorityProviders = collect();
        $otherProviders = collect();
        
        foreach ($allProviders as $provider) {
            $vendorLower = strtolower(trim($provider->code));
            $priorityIndex = array_search($vendorLower, array_map('strtolower', $priorityCodes));
            
            if ($priorityIndex !== false) {
                $priorityProviders->push((object)[
                    'provider' => $provider,
                    'priority' => $priorityIndex
                ]);
            } else {
                $otherProviders->push($provider);
            }
        }
        
        // �ncelikli saglayicilari sirala ve digerlerini alfabetik sirala
        $prioritySorted = $priorityProviders->sortBy('priority')->pluck('provider');
        $otherSorted = $otherProviders->sortBy('name');
        
        $providers = $prioritySorted->merge($otherSorted)->values();
            
        $totalGames = Game::where('status', '1')->where('game_type', 'slots')->count();

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('casino', compact('settings', 'sliders', 'games', 'providers', 'totalGames', 'recentWinners'));
    }

    public function slots()
    {
        $settings = Ayarlar::getSettings();
        $games = Game::where('status', '1')->where('type', 'slot')->paginate(24);
        $providers = Provider::where('status', 1)->get();

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('slots', compact('settings', 'games', 'providers', 'recentWinners'));
    }

    public function liveCasino()
    {
        $settings = Ayarlar::getSettings();
        $sliders = Slider::active()->ordered()->get();
        
        // Canli oyunlar
        $games = Game::where('status', '1')
            ->where(function($q){
                $q->whereRaw("LOWER(COALESCE(game_type,'')) <> 'slots'")
                  ->orWhere('has_tables', 1)
                  ->orWhere('game_type', 'LIKE', '%live%');
            })
            ->with('provider')
            ->orderByDesc('is_featured')
            ->orderByDesc('views')
            ->orderBy('game_name', 'asc')
            ->limit(50)
            ->get();
            
        // Canli saglayicilar: vendorcode'a g�re otomatik
        $providers = \DB::table('games')
            ->select('vendorcode', \DB::raw('COUNT(*) as cnt'))
            ->where('status', '1')
            ->where(function($q){
                $q->whereRaw("LOWER(COALESCE(game_type,'')) <> 'slots'")
                  ->orWhere('has_tables', 1)
                  ->orWhere('game_type', 'LIKE', '%live%');
            })
            ->whereNotNull('vendorcode')
            ->where('vendorcode', '!=', '')
            ->groupBy('vendorcode')
            ->orderBy('vendorcode')
            ->get()
            ->map(function($row){
                return (object)[
                    'code' => $row->vendorcode,
                    'name' => $row->vendorcode,
                    'count' => (int)$row->cnt,
                ];
            });
            
        $totalGames = Game::where('status', '1')
            ->where(function($q){
                $q->whereRaw("LOWER(COALESCE(game_type,'')) <> 'slots'")
                  ->orWhere('has_tables', 1)
                  ->orWhere('game_type', 'LIKE', '%live%');
            })
            ->count();

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('live-casino', compact('settings', 'sliders', 'games', 'providers', 'totalGames', 'recentWinners'));
    }

    public function otherGames()
    {
        $settings = Ayarlar::getSettings();
        $oyunlar = Oyunlar::active()->ordered()->get();

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('other-games', compact('settings', 'oyunlar', 'recentWinners'));
    }

    public function sports(Request $request)
    {
        $settings = Ayarlar::getSettings();
        $recentWinners = $this->getRecentWinners();

        // Tüm spor türlerini çek
        $sports = \DB::table('bulten')
            ->select('tur')
            ->distinct()
            ->orderBy('tur')
            ->pluck('tur');

        $selectedSport = $request->get('tur');
        if (!$selectedSport || !$sports->contains($selectedSport)) {
            $selectedSport = $sports->contains('Futbol') ? 'Futbol' : $sports->first();
        }

        // Seçili sporun maçlarını çek
        $matches = \DB::table('bulten')
            ->where('tur', $selectedSport)
            ->orderBy('baslangic')
            ->get();

        return view('sports', compact('settings', 'recentWinners', 'sports', 'selectedSport', 'matches'));
    }

    // Live betting method removed

    // Place bet method removed

    public function sportsIframe()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        // Get sports API configuration
        $sportsApi = \App\Models\SportsApi::getActiveApi();
        
        if (!$sportsApi) {
            abort(500, 'Spor API yapılandırması bulunamadı');
        }
        
        return view('sports-iframe', compact('settings', 'user', 'sportsApi'));
    }


    public function bonus()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        // Tüm aktif bonusları al
        $bonuses = Bonus::where('aktif', '1')->get();
        
        // Bonus claim bilgilerini ekle
        $bonusClaims = [];
        if ($user) {
            $bonusClaims = \App\Models\BonusClaim::where('user_id', $user->id)
                ->get()
                ->keyBy('bonus_id');
        }

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('bonus', compact('settings', 'bonuses', 'recentWinners', 'bonusClaims'));
    }

    // Static pages
    public function helpCenter()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('help-center', compact('settings', 'recentWinners'));
    }

    public function liveSupport()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('live-support', compact('settings', 'recentWinners'));
    }

    public function faq()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('faq', compact('settings', 'recentWinners'));
    }

    public function contact()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('contact', compact('settings', 'recentWinners'));
    }

    public function terms()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('terms', compact('settings', 'recentWinners'));
    }

    public function privacy()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('privacy', compact('settings', 'recentWinners'));
    }

    public function responsibleGaming()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('responsible-gaming', compact('settings', 'recentWinners'));
    }

    public function amlPolicy()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('aml-policy', compact('settings', 'recentWinners'));
    }

    public function license()
    {
        $settings = Ayarlar::getSettings();
        
        // Son kazananlar
        $recentWinners = $this->getRecentWinners();
        
        return view('license', compact('settings', 'recentWinners'));
    }

    // User account methods
    public function hesabim()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        // Son işlemler
        $recentTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Para yatırma istekleri - uye sütunu kullan
        $deposits = \App\Models\Parayatir::where('uye', $user->id)
            ->orderBy('tarih', 'desc')
            ->limit(5)
            ->get();
            
        // Para çekme istekleri - user_id sütunu kullan (varsa)
        $withdrawals = \App\Models\Paracek::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('uye', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('hesabim', compact('settings', 'user', 'recentTransactions', 'deposits', 'withdrawals', 'recentWinners'));
    }

    public function affiliatePanel(Request $request)
    {
        // Kullanıcı girişi ve aff kontrolü
        if (!auth('admin')->check()) {
            return redirect()->route('login');
        }
        $user = auth('admin')->user();
        if ((int)($user->aff ?? 0) !== 1) {
            return redirect()->route('home');
        }

        $settings = Ayarlar::getSettings();

        // Tarih filtresi
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Alt üyeler
        $subs = \App\Models\Admin::where('bayisi', $user->id)
            ->orderBy('kayit_tarih', 'desc')
            ->get(['id','username','name','email','kayit_tarih']);
        $subIds = $subs->pluck('id')->all();

        // Toplam yatırımlar (onaylı) - tarih filtresi ile
        $totalDeposits = 0;
        $totalWithdrawals = 0;
        $perUserDeposits = [];
        $perUserWithdrawals = [];
        if (!empty($subIds)) {
            $perUserDeposits = \DB::table('parayatir')
                ->whereIn('uye', $subIds)
                ->where('durum', 1)
                ->whereBetween('tarih', [$startDate, $endDate])
                ->select('uye', \DB::raw('SUM(miktar) as total'))
                ->groupBy('uye')
                ->pluck('total', 'uye')
                ->toArray();
            $totalDeposits = array_sum($perUserDeposits);

            $perUserWithdrawals = \DB::table('paracek')
                ->whereIn('user_id', $subIds)
                ->where('durum', 1)
                ->whereBetween('tarih', [$startDate, $endDate])
                ->select('user_id', \DB::raw('SUM(miktar) as total'))
                ->groupBy('user_id')
                ->pluck('total', 'user_id')
                ->toArray();
            $totalWithdrawals = array_sum($perUserWithdrawals);
        }

        // Bugün ve bu ay yatırımları (filtre dışında)
        $todayDeposits = 0; $todayWithdrawals = 0; $monthDeposits = 0; $monthWithdrawals = 0;
        if (!empty($subIds)) {
            $todayDeposits = (float) (\DB::table('parayatir')->whereIn('uye', $subIds)->where('durum',1)->whereDate('tarih', today())->sum('miktar'));
            $todayWithdrawals = (float) (\DB::table('paracek')->whereIn('user_id', $subIds)->where('durum',1)->whereDate('tarih', today())->sum('miktar'));
            $monthDeposits = (float) (\DB::table('parayatir')->whereIn('uye', $subIds)->where('durum',1)->whereMonth('tarih', now()->month)->whereYear('tarih', now()->year)->sum('miktar'));
            $monthWithdrawals = (float) (\DB::table('paracek')->whereIn('user_id', $subIds)->where('durum',1)->whereMonth('tarih', now()->month)->whereYear('tarih', now()->year)->sum('miktar'));
        }

        // Affiliate oranı (yüzde)
        $commissionRate = (float)($user->afforani ?? 0);
        // Komisyonlar artık otomatik olarak bakiyeye ekleniyor, eski hesaplama pasif
        $commissionTotal = 0;
        $perUserCommission = [];

        return view('affiliate.panel', compact(
            'settings',
            'user',
            'subs',
            'perUserDeposits',
            'perUserWithdrawals',
            'totalDeposits',
            'totalWithdrawals',
            'todayDeposits','todayWithdrawals','monthDeposits','monthWithdrawals',
            'commissionRate','commissionTotal','perUserCommission'
        ));
    }

    public function paraYatir()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        // Ödeme yöntemleri
        $paymentMethods = [
            ['id' => 'bank', 'name' => 'Banka Transferi', 'icon' => '🏦'],
            ['id' => 'crypto', 'name' => 'Kripto Para', 'icon' => '₿'],
            ['id' => 'card', 'name' => 'Kredi Kartı', 'icon' => '💳'],
        ];

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('para-yatir', compact('settings', 'user', 'paymentMethods', 'recentWinners'));
    }

    public function paraYatirPost(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|in:bank,crypto,card',
            'reference' => 'nullable|string|max:255',
        ]);

        $user = auth('admin')->user();
        
        \App\Models\Parayatir::create([
            'uye' => $user->id,
            'miktar' => $request->amount,
            'tur' => $request->payment_method,
            'aciklama' => $request->reference,
            'durum' => 0, // pending
            'tarih' => now(),
        ]);

        return redirect()->route('hesabim')->with('success', 'Para yatırma talebiniz alındı. En kısa sürede işleme alınacaktır.');
    }

    public function paraCek()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        // Kullanıcı bilgileri
        $user_id = $user->id;
        $ad_soyad = $user->name ?? '';
        $telefon = $user->telefon ?? '';
        $email = $user->email ?? '';
        $bakiye = floatval($user->bakiye ?? 0);
        $cevrim = floatval($user->cevrim ?? 0);

        // Mesajlar
        $successMessage = "";
        $errorMessage = "";

        // Bekleyen talep kontrolü
        $pendingCount = \App\Models\Paracek::where('durum', 0)
            ->where('user_id', $user_id)
            ->count();

        // Form devre dışı bırakma kontrolü
        $disableForm = false;

        // Çevrim bakiyesi veya bekleyen talep varsa formu kapat
        if ($cevrim > 0) {
            $disableForm = true;
            $errorMessage = "Kalan Çevrim Miktarınız: {$cevrim} TL";
        } elseif ($pendingCount > 0) {
            $disableForm = true;
            $errorMessage = "Bekleyen çekim talebiniz mevcut, yeni talep oluşturamazsınız.";
        }

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('para-cek', compact('settings', 'user', 'recentWinners', 'bakiye', 'cevrim', 'disableForm', 'errorMessage', 'successMessage', 'ad_soyad', 'telefon', 'email', 'user_id'));
    }

    public function paraCekPost(Request $request)
    {
        $user = auth('admin')->user();
        
        // Kullanıcı bilgileri
        $user_id = $user->id;
        $ad_soyad = $user->name ?? '';
        $telefon = $user->telefon ?? '';
        $email = $user->email ?? '';
        $bakiye = floatval($user->bakiye ?? 0);
        $cevrim = floatval($user->cevrim ?? 0);

        // Bekleyen talep kontrolü
        $pendingCount = \App\Models\Paracek::where('durum', 1)
            ->where('user_id', $user_id)
            ->count();

        // Form devre dışı bırakma kontrolü
        $disableForm = false;
        $errorMessage = "";

        // Çevrim bakiyesi veya bekleyen talep varsa formu kapat
        if ($cevrim > 0) {
            $disableForm = true;
            $errorMessage = "Kalan Çevrim Miktarınız: {$cevrim} TL";
        } elseif ($pendingCount > 0) {
            $disableForm = true;
            $errorMessage = "Bekleyen çekim talebiniz mevcut, yeni talep oluşturamazsınız.";
        }

        if ($disableForm) {
            return redirect()->route('para-cek')->with('error', $errorMessage);
        }

        $withdraw_method = trim($request->input('withdraw_method'));
        $miktar = floatval($request->input('miktar', 0));

        // Temel kontroller
        if ($miktar < 50) {
            return redirect()->route('para-cek')->with('error', 'Minimum çekim tutarı 50 TL\'dir!');
        } elseif ($miktar % 50 != 0) {
            return redirect()->route('para-cek')->with('error', 'Sadece 50 TL\'nin katları çekilebilir! (50,100,150...)');
        } elseif ($miktar > $bakiye) {
            return redirect()->route('para-cek')->with('error', "Bakiyeniz yetersiz! Mevcut bakiye: {$bakiye} TL");
        }

        $banka = "";
        $turu = "";
        $hesap = "";
        $aciklama = "";

        $tarih = now();
        $created_at = $tarih;
        $durum = 0; // Talep eklendi (beklemede)

        switch ($withdraw_method) {
            case 'HAVALE':
                $banka = "HMEN_HAVALE";
                $turu = "HMEN_HAVALE";
                $hesap = trim($request->input('hesap_no', ''));
                if (empty($request->input('banka_adi')) || !$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Banka adı ve IBAN boş olamaz!');
                }
                break;
            case 'PAPARA':
                $banka = "HMEN_PAPARA";
                $turu = "HMEN_PAPARA";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Papara Numarası boş olamaz!');
                }
                break;
            case 'MEFETE':
                $banka = "HMEN_MEFETE";
                $turu = "HMEN_MEFETE";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Mefete hesap/ID boş olamaz!');
                }
                break;
            case 'PAROLAPARA':
                $banka = "HMEN_PAROLAPARA";
                $turu = "HMEN_PAROLAPARA";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Parolapara hesap/ID boş olamaz!');
                }
                break;
            case 'EXTRA_PAPARA':
                $banka = "EXTRA_PAPARA";
                $turu = "EXTRA_PAPARA";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Papara numarası boş olamaz!');
                }
                break;
            case 'EXTRA_HAVALE_EFT':
                $banka = "EXTRA_HAVALE_EFT";
                $turu = "EXTRA_HAVALE_EFT";
                $hesap = trim($request->input('hesap_no', ''));
                if (empty($request->input('banka_adi')) || !$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Banka adı ve IBAN boş olamaz!');
                } else {
                    $banka = trim($request->input('banka_adi')); // Banka adını kaydet
                }
                break;
            case 'EXTRA_HAVALE_FAST':
                $banka = "EXTRA_HAVALE_FAST";
                $turu = "EXTRA_HAVALE_FAST";
                $hesap = trim($request->input('hesap_no', ''));
                if (empty($request->input('banka_adi')) || !$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Banka adı ve IBAN boş olamaz!');
                } else {
                    $banka = trim($request->input('banka_adi')); // Banka adını kaydet
                }
                break;
            case 'EXTRA_PAPARA_IBAN':
                $banka = "EXTRA_PAPARA_IBAN";
                $turu = "EXTRA_PAPARA_IBAN";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Papara IBAN boş olamaz!');
                }
                break;
            case 'EXTRA_PAYCO':
                $banka = "EXTRA_PAYCO";
                $turu = "EXTRA_PAYCO";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Payco hesap/ID boş olamaz!');
                }
                break;
            case 'EXTRA_PARAZULA':
                $banka = "EXTRA_PARAZULA";
                $turu = "EXTRA_PARAZULA";
                $hesap = trim($request->input('hesap_no', ''));
                if (!$hesap) {
                    return redirect()->route('para-cek')->with('error', 'Parazula hesap/ID boş olamaz!');
                }
                break;
            default:
                return redirect()->route('para-cek')->with('error', 'Geçersiz yöntem!');
        }

        // Veritabanı ekleme
        try {
            \App\Models\Paracek::create([
                'uye' => $user_id,
                'banka' => $banka,
                'miktar' => $miktar,
                'tarih' => $tarih,
                'durum' => $durum,
                'aciklama' => $aciklama,
                'hesap' => $hesap,
                'created_at' => $created_at,
                'user_id' => $user_id,
                'name' => $ad_soyad,
                'telefon' => $telefon,
                'email' => $email,
                'turu' => $turu
            ]);

            // Bakiye düş
            $newBalance = $bakiye - $miktar;
            $user->bakiye = $newBalance;
            $user->save();

            // Affiliate kullanıcı çekim talebi oluşturduğunda Telegram bildirimi gönder
            if ($user->aff == 1) {
                $this->sendAffiliateWithdrawNotification($user, $miktar, $banka, $hesap);
            }

            return redirect()->route('para-cek')->with('success', "Çekim talebiniz oluşturuldu. ({$miktar} TL) Bakiyeniz güncellendi.");

        } catch (\Exception $e) {
            return redirect()->route('para-cek')->with('error', 'Veritabanı hatası: ' . $e->getMessage());
        }
    }

    public function hesapHareketleri()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        $transactions = \App\Models\Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('hesap-hareketleri', compact('settings', 'user', 'transactions', 'recentWinners'));
    }

    public function bahisGecmisi()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        $kuponlar = \App\Models\Kupon::where('userid', $user->id)
            ->with('kuponMaclar')
            ->orderBy('tarih', 'desc')
            ->paginate(20);

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('bahis-gecmisi', compact('settings', 'user', 'kuponlar', 'recentWinners'));
    }

    public function casinoGecmisi()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();
        
        $casinoTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->whereIn('type', ['win', 'bet'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('casino-gecmisi', compact('settings', 'user', 'casinoTransactions', 'recentWinners'));
    }

    public function aktifBonuslarim()
    {
        $settings = Ayarlar::getSettings();
        $user = auth('admin')->user();

        // Sadece son bonusu al
        $latestBonusClaim = \App\Models\BonusClaim::where('user_id', $user->id)
            ->with('bonus')
            ->orderBy('claimed_at', 'desc')
            ->first();

        $bonusProgress = 0;
        $totalBetsAfterBonus = 0;
        $requiredWager = 0;

        if ($latestBonusClaim && $latestBonusClaim->bonus) {
            // Bonus tarihinden sonra para yatırma işlemi yapılmış mı kontrol et
            $depositAfterBonus = \App\Models\Parayatir::where('uye', $user->id)
                ->where('durum', 1) // Onaylanmış yatırımlar
                ->where('tarih', '>', $latestBonusClaim->claimed_at)
                ->exists();

            // Eğer bonus tarihinden sonra para yatırma yapılmışsa bonus iptal edildi
            if ($depositAfterBonus) {
                $latestBonusClaim = null;
            } else {
                // Bonus tarihinden sonraki toplam bahisleri hesapla
                $totalBetsAfterBonus = \App\Models\Transaction::where('user_id', $user->id)
                    ->where('type', 'bet')
                    ->where('created_at', '>', $latestBonusClaim->claimed_at)
                    ->sum('amount');

                // Gerekli çevrim tutarını hesapla (Bonus miktarı × Çevrim katı)
                $requiredWager = $latestBonusClaim->bonus_amount * $latestBonusClaim->bonus->cevrim;

                // İlerleme yüzdesini hesapla
                if ($requiredWager > 0) {
                    $bonusProgress = ($totalBetsAfterBonus / $requiredWager) * 100;
                    // %100'ü geçmesin
                    $bonusProgress = min($bonusProgress, 100);
                }
            }
        }

        // Son kazananlar
        $recentWinners = $this->getRecentWinners();

        return view('aktif-bonuslarim', compact('settings', 'user', 'latestBonusClaim', 'bonusProgress', 'totalBetsAfterBonus', 'requiredWager', 'recentWinners'));
    }

    public function claimBonus(Request $request)
    {
        // Kullanıcı girişi kontrolü
        if (!auth('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Lütfen giriş yapınız.']);
        }

        $user = auth('admin')->user();
        $bonusId = $request->input('bonus_id');

        // Bonus kontrolü
        $bonus = Bonus::where('id', $bonusId)->where('aktif', '1')->first();
        if (!$bonus) {
            return response()->json(['success' => false, 'message' => 'Bonus bulunamadı veya aktif değil.']);
        }

        // Son yatırım bilgisini al
        $lastDeposit = \App\Models\Parayatir::where('uye', $user->id)
            ->where('durum', 1) // Onaylanmış yatırımlar
            ->orderBy('tarih', 'desc')
            ->first();

        // Deneme bonusu kontrolü - sadece 1 kez alınabilir
        if ($bonus->deneme == '1') {
            $existingClaim = \App\Models\BonusClaim::where('user_id', $user->id)
                ->where('bonus_id', $bonusId)
                ->first();
            
            if ($existingClaim) {
                return response()->json(['success' => false, 'message' => 'Bu deneme bonusunu daha önce almışsınız.']);
            }
        }

        // Hoşgeldin bonusu kontrolü - sadece 1 kez alınabilir ve başka bonus alınmamış olmalı
        if ($bonus->hosgeldin == '1') {
            $existingWelcomeClaim = \App\Models\BonusClaim::where('user_id', $user->id)
                ->whereHas('bonus', function($query) {
                    $query->where('hosgeldin', '1');
                })
                ->first();
            
            if ($existingWelcomeClaim) {
                return response()->json(['success' => false, 'message' => 'Hoşgeldin bonusunu daha önce almışsınız.']);
            }
            
            // Daha önce başka bonus alınmış mı kontrol et (deneme bonusu hariç)
            $hasOtherBonus = \App\Models\BonusClaim::where('user_id', $user->id)
                ->whereHas('bonus', function($query) {
                    $query->where('deneme', '!=', '1')
                          ->where('hosgeldin', '!=', '1');
                })
                ->exists();
                
            if ($hasOtherBonus) {
                return response()->json(['success' => false, 'message' => 'Hoşgeldin bonusu sadece ilk yatırımınızda alınabilir. Daha önce başka bonus almışsınız.']);
            }
        }

        // Hoşgeldin bonusu aldıktan sonra yatırım kontrolü (deneme bonusu hariç)
        if ($bonus->deneme != '1') {
            $hasWelcomeBonus = \App\Models\BonusClaim::where('user_id', $user->id)
                ->whereHas('bonus', function($query) {
                    $query->where('hosgeldin', '1');
                })
                ->exists();
                
            if ($hasWelcomeBonus) {
                // Hoşgeldin bonusu aldıktan sonra yatırım yapılmış mı kontrol et
                $welcomeClaim = \App\Models\BonusClaim::where('user_id', $user->id)
                    ->whereHas('bonus', function($query) {
                        $query->where('hosgeldin', '1');
                    })
                    ->first();
                    
                $depositAfterWelcome = \App\Models\Parayatir::where('uye', $user->id)
                    ->where('durum', 1)
                    ->where('tarih', '>', $welcomeClaim->claimed_at)
                    ->exists();
                    
                if (!$depositAfterWelcome) {
                    return response()->json(['success' => false, 'message' => 'Hoşgeldin bonusu aldıktan sonra yeni yatırım yapmanız gerekmektedir.']);
                }
            }
        }

        // Yatırım bonusu kontrolü
        if ($bonus->yatirim == '1') {
            // Son yatırım kontrolü
            $recentDeposit = \App\Models\Parayatir::where('uye', $user->id)
                ->where('durum', 1)
                ->orderBy('tarih', 'desc')
                ->first();

            if (!$recentDeposit) {
                return response()->json(['success' => false, 'message' => 'Yatırım bonusu alabilmek için önce yatırım yapmanız gerekmektedir.']);
            }

            // Son yatırımdan sonra oyun oynama kontrolü
            $gameActivityAfterLastDeposit = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'bet')
                ->where('created_at', '>', $recentDeposit->tarih)
                ->exists();

            if ($gameActivityAfterLastDeposit) {
                return response()->json(['success' => false, 'message' => 'Son yatırımınızdan sonra oyun oynadığınız için yatırım bonusu alamazsınız.']);
            }

            // Son yatırımdan sonra bonus alma kontrolü (yatırım bonusu hariç)
            $bonusAfterLastDeposit = \App\Models\BonusClaim::where('user_id', $user->id)
                ->where('claimed_at', '>', $recentDeposit->tarih)
                ->whereHas('bonus', function($query) {
                    $query->where('yatirim', '!=', '1');
                })
                ->exists();

            if ($bonusAfterLastDeposit) {
                return response()->json(['success' => false, 'message' => 'Son yatırımınızdan sonra başka bonus aldığınız için yatırım bonusu alamazsınız.']);
            }
        }

        // Kayıp bonusu kontrolü
        if ($bonus->kayip == '1') {
            // Bakiye 5 TL altında mı?
            if ($user->bakiye >= 5) {
                return response()->json(['success' => false, 'message' => 'Kayıp bonusu alabilmek için bakiyenizin 5 TL altında olması gerekmektedir.']);
            }

            // Son yatırım kontrolü
            if (!$lastDeposit) {
                return response()->json(['success' => false, 'message' => 'Kayıp bonusu alabilmek için önce yatırım yapmanız gerekmektedir.']);
            }

            // Son yatırımdan sonra herhangi bir bonus alınmış mı kontrol et
            $bonusAfterLastDeposit = \App\Models\BonusClaim::where('user_id', $user->id)
                ->where('claimed_at', '>', $lastDeposit->tarih) // > kullan, >= değil
                ->exists();

            if ($bonusAfterLastDeposit) {
                return response()->json(['success' => false, 'message' => 'Son yatırımınızdan sonra bonus aldığınız için kayıp bonusu alamazsınız.']);
            }
        }

        try {
            \DB::beginTransaction();

            $bonusAmount = 0;

            // Bonus tutarını hesapla
            if ($bonus->yuzde > 0) {
                // Yüzdelik bonus - son yatırım miktarının yüzdesi
                if ($bonus->yatirim == '1') {
                    // Yatırım bonusu için son 24 saat içindeki yatırım
                    $recentDeposit = \App\Models\Parayatir::where('uye', $user->id)
                        ->where('durum', 1)
                        ->where('tarih', '>=', now()->subHours(24))
                        ->orderBy('tarih', 'desc')
                        ->first();
                } elseif ($bonus->kayip == '1') {
                    // Kayıp bonusu için son yatırım (zaman sınırı yok)
                    $recentDeposit = \App\Models\Parayatir::where('uye', $user->id)
                        ->where('durum', 1)
                        ->orderBy('tarih', 'desc')
                        ->first();
                } elseif ($bonus->hosgeldin == '1') {
                    // Hoşgeldin bonusu için son yatırım (zaman sınırı yok)
                    $recentDeposit = \App\Models\Parayatir::where('uye', $user->id)
                        ->where('durum', 1)
                        ->orderBy('tarih', 'desc')
                        ->first();
                } else {
                    // Diğer yüzdelik bonuslar için son yatırım
                    $recentDeposit = \App\Models\Parayatir::where('uye', $user->id)
                        ->where('durum', 1)
                        ->orderBy('tarih', 'desc')
                        ->first();
                }
                
                if ($recentDeposit) {
                    $bonusAmount = ($recentDeposit->miktar * $bonus->yuzde) / 100;
                    
                    // Maksimum tutar kontrolü
                    if ($bonus->maxtutar > 0 && $bonusAmount > $bonus->maxtutar) {
                        $bonusAmount = $bonus->maxtutar;
                    }
                } else {
                    // Yatırım bulunamadıysa hata
                    \DB::rollback();
                    return response()->json(['success' => false, 'message' => 'Bonus hesaplanabilmesi için önce yatırım yapmanız gerekmektedir.']);
                }
            } else {
                // Sabit bonus tutarı
                $bonusAmount = $bonus->bonus_amount;
            }

            if ($bonusAmount <= 0) {
                \DB::rollback();
                return response()->json(['success' => false, 'message' => 'Bonus tutarı hesaplanamadı.']);
            }

            // Kullanıcının bakiyesine bonus tutarını ekle
            $user->bakiye += $bonusAmount;
            
            // Çevirme şartını ekle
            $user->cevrim += ($bonusAmount * $bonus->cevrim);
            
            $user->save();

            // Bonus claim kaydı oluştur
            \App\Models\BonusClaim::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'bonus_id' => $bonus->id,
                'bonus_name' => $bonus->bonus_name,
                'bonus_amount' => $bonusAmount,
                'claimed_at' => now(),
            ]);

            \DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Bonus başarıyla hesabınıza eklendi!',
                'newBalance' => $user->bakiye,
                'bonusAmount' => $bonusAmount,
                'cevrimAmount' => ($bonusAmount * $bonus->cevrim)
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function usePromoCode(Request $request)
    {
        // Kullanıcı authentication middleware ile kontrol edildi
        $user = auth('admin')->user();

        // Debug: Gelen verileri kontrol et
        \Log::info('Promo code request data:', [
            'all_data' => $request->all(),
            'code_field' => $request->input('code'),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method()
        ]);

        $request->validate([
            'code' => 'required|string'
        ]);

        $code = strtoupper(trim($request->code));

        // Promosyon kodunu kontrol et
        $promoCode = \DB::table('promo_codes')
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$promoCode) {
            return response()->json(['success' => false, 'message' => 'Geçersiz promosyon kodu']);
        }

        // Süre kontrolü
        // if ($promoCode->expires_at && now()->gt($promoCode->expires_at)) {
        //     return response()->json(['success' => false, 'message' => 'Promosyon kodunun süresi dolmuş']);
        // }

        // Kullanım limiti kontrolü
        $usedCount = \DB::table('promo_code_usages')
            ->where('promo_code_id', $promoCode->id)
            ->count();
            
        if ($usedCount >= $promoCode->max_uses) {
            return response()->json(['success' => false, 'message' => 'Promosyon kodu kullanım limiti dolmuş']);
        }

        // Kullanıcının daha önce kullanıp kullanmadığını kontrol et
        $alreadyUsed = \DB::table('promo_code_usages')
            ->where('promo_code_id', $promoCode->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json(['success' => false, 'message' => 'Bu promosyon kodunu daha önce kullandınız']);
        }

        try {
            \DB::beginTransaction();

            // Kullanıcının bakiyesini güncelle
            \DB::table('admin')
                ->where('id', $user->id)
                ->update([
                    'bakiye' => \DB::raw('bakiye + ' . $promoCode->balance_amount),
                    'cevrim' => \DB::raw('cevrim + ' . ($promoCode->balance_amount * $promoCode->turnover_amount))
                ]);

            // Kullanım kaydı oluştur
            \DB::table('promo_code_usages')->insert([
                'promo_code_id' => $promoCode->id,
                'user_id' => $user->id,
                // 'amount_received' => $promoCode->balance_amount,
                // 'turnover_required' => $promoCode->balance_amount * $promoCode->turnover_amount,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);

            // Kullanım sayısını artır (used_count sütunu yok, sadece updated_at güncelle)
            \DB::table('promo_codes')
                ->where('id', $promoCode->id)
                ->update([
                    'updated_at' => now()
                ]);

            \DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Promosyon kodu başarıyla kullanıldı! ₺' . number_format($promoCode->balance_amount, 2) . ' bakiye eklendi.',
                'amount' => $promoCode->balance_amount,
                'turnover' => $promoCode->balance_amount * $promoCode->turnover_amount
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Affiliate kullanıcı çekim talebi oluşturduğunda Telegram bildirimi gönder
     */
    private function sendAffiliateWithdrawNotification($user, $miktar, $banka, $hesap)
    {
        try {
            $token  = env('TELEGRAM_AFF_WITHDRAW_BOT_TOKEN');
            $chatId = env('TELEGRAM_AFF_WITHDRAW_CHAT_ID');

            if (empty($token) || empty($chatId)) {
                return;
            }

            $now = now()->format('d.m.Y H:i:s');

            $message = "🔔 <b>Affiliate Çekim Talebi</b>\n\n"
                     . "👤 <b>Kullanıcı:</b> {$user->username} ({$user->name})\n"
                     . "🆔 <b>ID:</b> {$user->id}\n"
                     . "💸 <b>Miktar:</b> {$miktar} TL\n"
                     . "🏦 <b>Yöntem:</b> {$banka}\n"
                     . "📋 <b>Hesap:</b> {$hesap}\n"
                     . "💰 <b>Kalan Bakiye:</b> {$user->bakiye} TL\n"
                     . "🕒 <b>Tarih:</b> {$now}\n\n"
                     . "⚠️ Affiliate üyesi çekim talebi oluşturdu!";

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
            curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            \Log::error('Affiliate withdraw notification failed: ' . $e->getMessage());
        }
    }
} 