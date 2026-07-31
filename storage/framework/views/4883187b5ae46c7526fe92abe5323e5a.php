<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', ($settings->site_adi ?? 'BetNow') . ' - Türkiye\'nin En Güvenilir Online Casino Platformu'); ?></title>
    
    <!-- Favicon -->
    <?php
        $settings = \App\Models\Ayarlar::getSettings();
        $faviconPath = $settings && $settings->favicon ? $settings->favicon : 'favicon.ico';
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset($faviconPath)); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset($faviconPath)); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset($faviconPath)); ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Custom CSS -->
    <style>
        .casino-gradient {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .winner-animation {
            animation: winner-glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes winner-glow {
            from { box-shadow: 0 0 20px rgba(251, 191, 36, 0.3); }
            to { box-shadow: 0 0 30px rgba(251, 191, 36, 0.6); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .game-card {
            transition: all 0.3s ease;
        }
        
        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
        }

        /* LiveChat Widget Gizleme - Tüm durumlar için */
        #chat-widget-container,
        [data-testid="chat-widget"],
        .lc-chat-widget,
        iframe[src*="livechatinc.com"],
        div[id*="livechat"],
        div[class*="livechat"],
        div[data-testid*="chat"],
        .livechat-widget,
        #livechat-widget,
        [id*="LiveChatWidget"],
        [class*="LiveChatWidget"],
        div[style*="position: fixed"][style*="bottom"],
        div[style*="position: fixed"][style*="right"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            transform: translateX(1000px) !important;
        }

        /* LiveChat Widget Gösterme - Sadece açık durumda */
        .show-livechat #chat-widget-container,
        .show-livechat [data-testid="chat-widget"],
        .show-livechat .lc-chat-widget,
        .show-livechat iframe[src*="livechatinc.com"],
        .show-livechat div[id*="livechat"],
        .show-livechat div[class*="livechat"],
        .show-livechat div[data-testid*="chat"],
        .show-livechat .livechat-widget,
        .show-livechat #livechat-widget,
        .show-livechat [id*="LiveChatWidget"],
        .show-livechat [class*="LiveChatWidget"] {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            transform: translateX(0) !important;
        }

        /* LiveChat görünürken alt-sağ sabit widget'ı gizleme KALDIRILDI */

        /* Yatay kaydırma scrollbar gizleme */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
        }

        /* Yatay kaydırma için ek stiller */
        .horizontal-scroll {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Oyun kartları için hover efektleri */
        .oyun-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }

        /* Payment scroll için özel stiller */
        .payment-scroll {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        .payment-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Temaya uygun custom scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #222 #111;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            background: #111;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #222;
            border-radius: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #444;
        }

        /* Sol menü için zarif efektler */
        .sidebar-menu-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transition: left 0.4s ease;
        }

        .sidebar-menu-item:hover::before {
            left: 100%;
        }

        .sidebar-menu-item:hover {
            transform: translateX(2px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .sidebar-icon-container {
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-menu-item:hover .sidebar-icon-container {
            transform: scale(1.05);
        }

        /* Status indicator - daha zarif */
        .status-indicator {
            position: relative;
            animation: subtle-pulse 3s ease-in-out infinite;
        }

        @keyframes subtle-pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Floating animasyonu - daha hafif */
        .floating-menu {
            animation: gentle-float 8s ease-in-out infinite;
        }

        @keyframes gentle-float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-2px); }
        }

        /* Zarif glass effect */
        .glass-enhanced {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        /* Smooth text transition */
        .text-glow {
            transition: all 0.3s ease;
        }

        .sidebar-menu-item:hover .text-glow {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Zarif ripple effect */
        .ripple-effect {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }

        .ripple-effect:hover::before {
            width: 200px;
            height: 200px;
        }
        /* VIP card - clean, no effects */
    </style>
</head>
<body class="bg-black text-white">
    <!-- Background Effect -->
    <div class="fixed inset-0 bg-[url('/assets/hero-pattern/background-effect.webp')] opacity-30 pointer-events-none"></div>
    
    <div class="flex min-h-screen flex-col relative bg-black/60 overflow-x-hidden">
        <!-- Header -->
        <nav class="fixed top-0 w-full z-[60] bg-black/80 backdrop-blur-sm border-b" style="border-color: rgba(235, 255, 0, 0.2);">
            <div class="mx-auto px-2 sm:px-4">
                <div class="flex items-center justify-between h-12 sm:h-14">
                    <a class="text-2xl font-bold inline-block relative z-30 w-fit" href="/" style="color: #ebff00;">
                        <?php
                            $settings = \App\Models\Ayarlar::getSettings();
                            $logoPath = $settings->logo ?? 'assets/logo/betnow.png';
                        ?>
                        <img alt="Betnow" loading="lazy" width="200" height="200" decoding="async" 
                             style="width: <?php echo e($settings->logo_size ?? 120); ?>px; height: auto;"
                             class="h-auto p-2" src="<?php echo e(asset($logoPath)); ?>"/>
                    </a>
                    <div class="flex items-center gap-1.5 sm:gap-4">
                        <!-- User Menu -->
                        <?php if(auth()->guard('admin')->check()): ?>
                        <div class="flex items-center space-x-3">
                            <div class="relative group">
                                <button type="button" class="px-3 py-1 rounded-lg font-bold text-sm flex items-center casino-gradient text-black shadow focus:outline-none">
                                    <?php echo e(Auth::guard('admin')->user()->parabirimi); ?><?php echo e(number_format(Auth::guard('admin')->user()->bakiye, 2)); ?>

                                </button>
                                <div class="absolute left-1/2 -translate-x-1/2 mt-2 min-w-max bg-zinc-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg border border-zinc-800 opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 pointer-events-none group-hover:pointer-events-auto group-focus-within:pointer-events-auto transition-all z-50">
                                    Çevrim Miktarı: <span class="font-bold"><?php echo e(Auth::guard('admin')->user()->cevrim ?? 0); ?></span>
                                </div>
                            </div>
                            <div class="relative group">
                                <button class="flex items-center space-x-2 px-3 py-1 bg-zinc-800/60 hover:bg-zinc-700/60 rounded-lg transition-colors">
                                    <div class="w-7 h-7 bg-red-500/20 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-red-400"><?php echo e(substr(Auth::guard('admin')->user()->username, 0, 1)); ?></span>
                                    </div>
                                    <span class="text-white font-medium text-sm"><?php echo e(Auth::guard('admin')->user()->username); ?></span>
                                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-56 bg-zinc-900 border border-zinc-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="py-2">
                                        <?php
                                            $adminUser = Auth::guard('admin')->user();
                                            $last30Deposit = 0;
                                            if ($adminUser) {
                                                $last30Deposit = \DB::table('parayatir')
                                                    ->where('uye', $adminUser->id)
                                                    ->where('durum', 1)
                                                    ->whereBetween('tarih', [now()->subDays(30), now()])
                                                    ->sum('miktar');
                                            }
                                            $vipLevels = [
                                                ['key'=>'kraliyet','name'=>'Kraliyet','min'=>120000,'gradient'=>'from-yellow-500 to-red-500'],
                                                ['key'=>'titan','name'=>'Titan','min'=>85000,'gradient'=>'from-red-500 to-rose-600'],
                                                ['key'=>'zumrut','name'=>'Zümrüt','min'=>60000,'gradient'=>'from-emerald-500 to-emerald-600'],
                                                ['key'=>'yakut','name'=>'Yakut','min'=>40000,'gradient'=>'from-rose-500 to-rose-600'],
                                                ['key'=>'elmas','name'=>'Elmas','min'=>25000,'gradient'=>'from-cyan-400 to-blue-500'],
                                                ['key'=>'platin','name'=>'Platin','min'=>15000,'gradient'=>'from-zinc-300 to-zinc-500'],
                                                ['key'=>'altin','name'=>'Altın','min'=>10000,'gradient'=>'from-amber-400 to-amber-500'],
                                                ['key'=>'gumus','name'=>'Gümüş','min'=>5000,'gradient'=>'from-slate-300 to-slate-400'],
                                                ['key'=>'bronz','name'=>'Bronz','min'=>0,'gradient'=>'from-amber-700 to-amber-800'],
                                            ];
                                            $currentVip = $vipLevels[count($vipLevels)-1];
                                            foreach ($vipLevels as $lvl) {
                                                if ($last30Deposit >= $lvl['min']) { $currentVip = $lvl; break; }
                                            }
                                        ?>
                                        <div class="px-3 pb-2">
                                            <?php
                                                $levels = $vipLevels;
                                                $currentIndex = 0;
                                                foreach ($levels as $i => $lvl) { if ($last30Deposit >= $lvl['min']) { $currentIndex = $i; break; } }
                                                $nextIndex = $currentIndex + 1;
                                                $currentMin = $levels[$currentIndex]['min'];
                                                $nextVip = $levels[$nextIndex] ?? null;
                                                $nextMin = $nextVip['min'] ?? null;
                                                if ($nextVip) {
                                                    $progress = ($last30Deposit - $currentMin) / max(1, ($nextMin - $currentMin));
                                                    $progress = max(0, min(1, $progress));
                                                    $remaining = max(0, $nextMin - $last30Deposit);
                                                } else {
                                                    $progress = 1; $remaining = 0;
                                                }
                                                $progressPercent = round($progress * 100);
                                            ?>
                                            <div class="p-3 rounded-lg border border-zinc-800 bg-zinc-900/70">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="relative w-10 h-10 rounded-md bg-gradient-to-br <?php echo e(''.$currentVip['gradient']); ?> flex items-center justify-center text-black font-extrabold text-[10px] ring-1 ring-white/10">
                                                            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M7 11l2-5 3 3 3-4 2 6 2-1v9H5v-9l2 1z"/></svg>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div class="text-sm text-white font-semibold tracking-wide"><?php echo e($currentVip['name']); ?></div>
                                                            <div class="text-[10px] text-zinc-300 bg-zinc-800/60 border border-zinc-700/60 rounded px-1.5 py-0.5">₺<?php echo e(number_format($last30Deposit, 0)); ?></div>
                                                        </div>
                                                        <div class="mt-2 h-2 rounded-full border border-zinc-700/60 bg-zinc-800 overflow-hidden">
                                                            <div class="h-full bg-red-500/80" style="width: <?php echo e($progressPercent); ?>%"></div>
                                                        </div>
                                                        <div class="mt-1.5 flex items-center justify-between text-[10px]">
                                                            <?php if($nextVip): ?>
                                                                <span class="text-zinc-300">Sonraki: <span class="text-white font-medium"><?php echo e($nextVip['name']); ?></span> (₺<?php echo e(number_format($nextMin,0)); ?>)</span>
                                                                <span class="text-zinc-300">Kalan: <span class="text-white font-medium">₺<?php echo e(number_format($remaining,0)); ?></span></span>
                                                            <?php else: ?>
                                                                <span class="text-white">En üst seviye! 🎉</span>
                                                                <span class="text-zinc-300">Tebrikler</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?php echo e(route('hesabim')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Hesabım
                                        </a>
                                        <a href="<?php echo e(route('para-yatir')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Para Yatır
                                        </a>
                                        <a href="<?php echo e(route('para-cek')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Para Çek
                                        </a>
                                        <hr class="border-zinc-800 my-1">
                                        <a href="<?php echo e(route('hesap-hareketleri')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Hesap Hareketleri
                                        </a>
                                        <a href="<?php echo e(route('bahis-gecmisi')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Bahis Geçmişi
                                        </a>
                                        <a href="<?php echo e(route('casino-gecmisi')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Casino Geçmişi
                                        </a>
                                        <a href="<?php echo e(route('aktif-bonuslarim')); ?>" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Aktif Bonuslarım
                                        </a>
                                        <hr class="border-zinc-800 my-1">
                                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-zinc-800 hover:text-red-300 transition-colors">
                                                Çıkış Yap
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo e(route('login')); ?>" onclick="console.log('Giriş Yap butonuna tıklandı');" class="px-4 py-2 text-zinc-300 hover:text-white transition-colors">Giriş Yap</a>
                            <a href="<?php echo e(route('register')); ?>" onclick="console.log('Kayıt Ol butonuna tıklandı');" class="px-4 py-2 rounded-lg transition-colors" style="position: relative; z-index: 1000; background-color: #ebff00; color: #000;">Kayıt Ol</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Left Sidebar -->
        <div class="hidden md:block fixed top-14 left-0 bottom-0 z-50 shadow-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent backdrop-blur-xl border-r border-zinc-800/50 w-64 overflow-y-auto scrollbar-hide custom-scrollbar">
            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-70"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/10 via-black/5 to-transparent"></div>
            <div class="py-6 px-4 flex-1 h-full overflow-y-auto">
                <!-- Casino Section -->
                <div class="mb-3 floating-menu">
                    <div class="space-y-3">
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="/casino">
                            <div class="sidebar-icon-container w-7 h-7 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/sloticon2.png" alt="Casino" class="w-6 h-6 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-zinc-300">Casino</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full bg-green-500/60 shadow-sm"></div>
                        </a>
                        <a class="sidebar-menu-item ripple-effect glow-effect group flex items-center gap-2 px-3 py-2 rounded-lg glass-enhanced border border-zinc-600/30" href="<?php echo e(route('live-casino')); ?>">
                            <div class="sidebar-icon-container w-7 h-7 rounded-lg flex items-center justify-center bg-gradient-to-br from-zinc-600 to-zinc-800 shadow-md">
                                <img src="/images/canlicasino.png" alt="Canlı Casino" class="w-6 h-6 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-white">Canlı Casino</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full shadow-sm" style="background-color: #ebff00;"></div>
                        </a>
                    </div>
                </div>

                <!-- Sports Section -->
                <div class="mb-3 floating-menu">
                    <div class="space-y-3">
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="<?php echo e(route('sports')); ?>">
                            <div class="sidebar-icon-container w-7 h-7 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/futbolicon2.png" alt="Spor Bahisleri" class="w-6 h-6 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-zinc-300">Spor Bahisleri</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full bg-green-500/60 shadow-sm"></div>
                        </a>
                        <!-- Live betting removed -->
                    </div>
                </div>

                <!-- Bonus Section -->
                <div class="mb-3 floating-menu">
                    <div class="space-y-3">
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="<?php echo e(route('bonus')); ?>">
                            <div class="sidebar-icon-container w-7 h-7 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/bonuslaricon.png" alt="Bonuslar" class="w-6 h-6 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-zinc-300">Bonuslar</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full bg-yellow-500/60 shadow-sm"></div>
                        </a>
                        
                        <!-- Promosyon Kodu (Desktop) -->
                        <div class="px-1 py-1 floating-menu">
                            <div class="relative rounded-xl overflow-hidden shadow-md border border-zinc-700/60 bg-black/30 group">
                                <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to top right, rgba(235, 255, 0, 0.1), transparent, transparent);"></div>
                                <div class="relative z-10 p-2 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] tracking-wider uppercase text-zinc-400">Promosyon Kodu</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded" style="color: rgba(235, 255, 0, 0.9); background-color: rgba(235, 255, 0, 0.1); border: 1px solid rgba(235, 255, 0, 0.2);">Hediye</span>
                                    </div>
                                    <form id="promoCodeForm" class="space-y-2">
                                        <?php echo csrf_field(); ?>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgba(235, 255, 0, 0.8);">
                                                    <rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/>
                                                    <path d="M8 6v12M16 6v12" stroke="currentColor" stroke-width="1.3"/>
                                                    <circle cx="12" cy="12" r="1.3" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <input type="text" name="code" placeholder="Bonus kodu gir" maxlength="20"
                                                   class="w-full bg-white/5 text-white text-xs pl-8 pr-2 py-1.5 rounded-lg border border-white/10 focus:outline-none placeholder-zinc-400/80 transition-all duration-200" style="focus:ring: 2px solid rgba(235, 255, 0, 0.4); focus:border: rgba(235, 255, 0, 0.4);"/>
                                        </div>
                                        <button type="submit" class="ripple-effect w-full text-white font-medium text-[11px] px-2 py-1.5 rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5" style="background-color: rgba(235, 255, 0, 0.8); border: 1px solid rgba(235, 255, 0, 0.4); color: #000;">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Kullan
                                        </button>
                                    </form>
                                    <div id="promoCodeMessage" class="mt-1 text-[11px] hidden text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>

                <!-- Canlı Destek ve Social Media Links -->
                    <div class="mt-auto space-y-4">
                    <!-- Canlı Destek -->
                    <div class="px-2 floating-menu">
                         <div id="desktopLiveChatBtn" onclick="openLiveChat(); return false;" class="w-full flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-300" role="button" tabindex="0" aria-label="Canlı Destek" style="position: relative; z-index: 1001;">
                             <img src="/images/canlidestek1.png" alt="Canlı Destek" class="object-contain" style="width: auto; height: auto; cursor: pointer; position: relative; z-index: 1002; pointer-events: auto;" onclick="openLiveChat(); return false;">
                        </div>
                </div>

                    <!-- Social Media Links -->
                    <?php
                        $settings = \App\Models\Ayarlar::getSettings();
                    ?>
                    <div class="flex justify-center space-x-3 px-2">
                        <?php if($settings && $settings->telegram): ?>
                            <a href="<?php echo e($settings->telegram); ?>" target="_blank" class="w-10 h-10 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="Telegram">
                                <img src="<?php echo e(asset('images/telegram.png')); ?>" alt="Telegram" class="w-6 h-6 object-contain">
                            </a>
                        <?php endif; ?>
                        <?php if($settings && $settings->whatsapp): ?>
                            <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $settings->whatsapp)); ?>" target="_blank" class="w-10 h-10 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="WhatsApp">
                                <img src="<?php echo e(asset('images/wp.png')); ?>" alt="WhatsApp" class="w-6 h-6 object-contain">
                            </a>
                        <?php endif; ?>
                        <?php if($settings && $settings->instagram): ?>
                            <a href="<?php echo e($settings->instagram); ?>" target="_blank" class="w-10 h-10 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="Instagram">
                                <img src="<?php echo e(asset('images/insta.png')); ?>" alt="Instagram" class="w-6 h-6 object-contain">
                            </a>
                        <?php endif; ?>
                        <?php if($settings && $settings->twitter): ?>
                            <a href="<?php echo e($settings->twitter); ?>" target="_blank" class="w-10 h-10 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="X (Twitter)">
                                <img src="<?php echo e(asset('images/x.png')); ?>" alt="X (Twitter)" class="w-6 h-6 object-contain">
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
                </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="md:hidden fixed top-14 left-0 bottom-0 z-50 shadow-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent backdrop-blur-xl border-r border-zinc-800/50 w-64 transform -translate-x-full transition-transform duration-300">
            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-70"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/10 via-black/5 to-transparent"></div>
            <div class="py-6 px-4 flex-1 h-full overflow-y-auto">
                <!-- Casino Section -->
                <div class="mb-3">
                    <div class="space-y-3">
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="/casino">
                            <div class="sidebar-icon-container w-6 h-6 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/sloticon2.png" alt="Casino" class="w-5 h-5 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-zinc-300">Casino</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full bg-green-500/60 shadow-sm"></div>
                        </a>
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="<?php echo e(route('live-casino')); ?>">
                            <div class="sidebar-icon-container w-6 h-6 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/canlicasino.png" alt="Canlı Casino" class="w-5 h-5 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-white">Canlı Casino</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full shadow-sm" style="background-color: #ebff00;"></div>
                        </a>
                    </div>
                </div>

                <!-- Sports Section -->
                <div class="mb-3">
                    <div class="space-y-3">
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="<?php echo e(route('sports')); ?>">
                            <div class="sidebar-icon-container w-6 h-6 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/futbolicon2.png" alt="Spor Bahisleri" class="w-5 h-5 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-zinc-300">Spor Bahisleri</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full bg-green-500/60 shadow-sm"></div>
                        </a>
                        <!-- Live betting removed -->
                    </div>
                </div>

                <!-- Bonus Section -->
                <div class="mb-3">
                    <div class="space-y-3">
                        <a class="sidebar-menu-item ripple-effect group flex items-center gap-2 px-3 py-2 rounded-lg" href="<?php echo e(route('bonus')); ?>">
                            <div class="sidebar-icon-container w-6 h-6 rounded-lg flex items-center justify-center bg-zinc-700/50">
                                <img src="/images/bonuslaricon.png" alt="Bonuslar" class="w-5 h-5 object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="text-glow text-xs font-medium text-zinc-300">Bonuslar</span>
                            </div>
                            <div class="status-indicator w-1.5 h-1.5 rounded-full bg-yellow-500/60 shadow-sm"></div>
                        </a>
                        
                        <!-- Promosyon Kodu (Mobile) -->
                        <div class="px-1 py-1 floating-menu">
                            <div class="relative rounded-xl overflow-hidden shadow-md border border-zinc-700/60 bg-black/30 group">
                                <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to top right, rgba(235, 255, 0, 0.1), transparent, transparent);"></div>
                                <div class="relative z-10 p-2 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] tracking-wider uppercase text-zinc-400">Promosyon</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded" style="color: rgba(235, 255, 0, 0.9); background-color: rgba(235, 255, 0, 0.1); border: 1px solid rgba(235, 255, 0, 0.2);">Hediye</span>
                                    </div>
                                    <form id="promoCodeFormMobile" class="space-y-2">
                                        <?php echo csrf_field(); ?>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgba(235, 255, 0, 0.8);">
                                                    <rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/>
                                                    <path d="M8 6v12M16 6v12" stroke="currentColor" stroke-width="1.3"/>
                                                    <circle cx="12" cy="12" r="1.3" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <input type="text" name="code" placeholder="Bonus kodu gir" maxlength="20" 
                                                   class="w-full bg-white/5 text-white text-xs pl-8 pr-2 py-1.5 rounded-lg border border-white/10 focus:outline-none placeholder-zinc-400/80 transition-all duration-200" style="focus:ring: 2px solid rgba(235, 255, 0, 0.4); focus:border: rgba(235, 255, 0, 0.4);"/>
                                        </div>
                                        <button type="submit" class="ripple-effect w-full text-white font-medium text-[11px] px-2 py-1.5 rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5" style="background-color: rgba(235, 255, 0, 0.8); border: 1px solid rgba(235, 255, 0, 0.4); color: #000;">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Kullan
                                        </button>
                                    </form>
                                    <div id="promoCodeMessageMobile" class="mt-1 text-[11px] hidden text-center"></div>
                                </div>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Canlı Destek ve Social Media Links -->
                    <div class="mt-auto space-y-4">
                    <!-- Canlı Destek -->
                    <div class="px-2">
                         <div id="mobileLiveChatBtn" onclick="openLiveChat(); return false;" class="w-full flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-300" role="button" tabindex="0" aria-label="Canlı Destek" style="position: relative; z-index: 1001;">
                             <img src="/images/canlidestek1.png" alt="Canlı Destek" class="object-contain" style="width: auto; height: auto; cursor: pointer; position: relative; z-index: 1002; pointer-events: auto;" onclick="openLiveChat(); return false;">
                        </div>
                    </div>
                    
                    <!-- Social Media Links -->
                    <?php
                        $settings = \App\Models\Ayarlar::getSettings();
                    ?>
                    <div class="flex justify-center space-x-3 px-2">
                        <?php if($settings && $settings->telegram): ?>
                            <a href="<?php echo e($settings->telegram); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="Telegram">
                                <img src="<?php echo e(asset('images/telegram.png')); ?>" alt="Telegram" class="w-4 h-4 object-contain">
                            </a>
                        <?php endif; ?>
                        <?php if($settings && $settings->whatsapp): ?>
                            <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $settings->whatsapp)); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="WhatsApp">
                                <img src="<?php echo e(asset('images/wp.png')); ?>" alt="WhatsApp" class="w-4 h-4 object-contain">
                            </a>
                        <?php endif; ?>
                        <?php if($settings && $settings->instagram): ?>
                            <a href="<?php echo e($settings->instagram); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="Instagram">
                                <img src="<?php echo e(asset('images/insta.png')); ?>" alt="Instagram" class="w-4 h-4 object-contain">
                            </a>
                        <?php endif; ?>
                        <?php if($settings && $settings->twitter): ?>
                            <a href="<?php echo e($settings->twitter); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-700/60 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md" style="hover:shadow: rgba(235, 255, 0, 0.2);" title="X (Twitter)">
                                <img src="<?php echo e(asset('images/x.png')); ?>" alt="X (Twitter)" class="w-4 h-4 object-contain">
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
    </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobileSidebarOverlay" class="md:hidden fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleSidebar()"></div>

        <!-- Right Sidebar - Removed for iframe sports -->

        <!-- Main Content -->
        <div class="flex-1 md:ml-64">
            <main class="pt-14 min-h-screen">
                
                <?php if(session('registration_success')): ?>
                <div id="registrationSuccessModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-[9999]">
                    <div class="relative max-w-md w-full mx-4">
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900 via-zinc-900/95 to-zinc-800 border shadow-2xl" style="border-color: rgba(235, 255, 0, 0.3);">
                            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                            <div class="relative p-6">
                                
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: linear-gradient(to bottom right, rgba(16, 185, 129, 0.3), rgba(16, 185, 129, 0.1)); border: 1px solid rgba(16, 185, 129, 0.3);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-2">Kayıt Başarılı! 🎉</h3>
                                    <p class="text-zinc-400 text-sm">Hesabınız oluşturuldu. Aşağıdaki bilgilerinizi not edin.</p>
                                </div>
                                
                                
                                <div class="space-y-3 mb-6">
                                    <div class="p-4 rounded-xl bg-zinc-800/50 border border-zinc-700/50">
                                        <label class="block text-xs text-zinc-400 mb-1">Kullanıcı Adınız</label>
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-white" id="regUsername"><?php echo e(session('registered_username')); ?></span>
                                            <button onclick="copyToClipboard('<?php echo e(session('registered_username')); ?>', this)" class="px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200" style="background-color: rgba(235, 255, 0, 0.1); border: 1px solid rgba(235, 255, 0, 0.2); color: #ebff00;">
                                                Kopyala
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-4 rounded-xl bg-zinc-800/50 border border-zinc-700/50">
                                        <label class="block text-xs text-zinc-400 mb-1">Şifreniz</label>
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-white"><?php echo e(session('registered_password')); ?></span>
                                            <button onclick="copyToClipboard('<?php echo e(session('registered_password')); ?>', this)" class="px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200" style="background-color: rgba(235, 255, 0, 0.1); border: 1px solid rgba(235, 255, 0, 0.2); color: #ebff00;">
                                                Kopyala
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="p-3 rounded-lg mb-6" style="background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2);">
                                    <div class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0 text-amber-400">
                                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        <p class="text-xs text-amber-200">Bu bilgileri kaydedin! Bu pencere kapatıldıktan sonra tekrar gösterilmeyecektir.</p>
                                    </div>
                                </div>
                                
                                
                                <button onclick="document.getElementById('registrationSuccessModal').remove();" class="w-full relative overflow-hidden rounded-lg py-3 px-6 transition-all duration-300 transform hover:scale-[1.02] group" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.25), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.3);" onmouseover="this.style.borderColor='rgba(235, 255, 0, 0.5)'; this.style.boxShadow='0 10px 25px rgba(235, 255, 0, 0.15)';" onmouseout="this.style.borderColor='rgba(235, 255, 0, 0.3)'; this.style.boxShadow='none';">
                                    <div class="relative flex items-center justify-center gap-2">
                                        <span class="text-white font-semibold">Anladım, Devam Et</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                function copyToClipboard(text, btn) {
                    navigator.clipboard.writeText(text).then(function() {
                        btn.textContent = 'Kopyalandı!';
                        btn.style.backgroundColor = 'rgba(16, 185, 129, 0.2)';
                        btn.style.borderColor = 'rgba(16, 185, 129, 0.4)';
                        btn.style.color = '#10b981';
                        setTimeout(function() {
                            btn.textContent = 'Kopyala';
                            btn.style.backgroundColor = 'rgba(235, 255, 0, 0.1)';
                            btn.style.borderColor = 'rgba(235, 255, 0, 0.2)';
                            btn.style.color = '#ebff00';
                        }, 2000);
                    });
                }
                </script>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
            
            <!-- Footer -->
            <?php
                $payments = \App\Models\Payment::getActivePayments();
            ?>
            <?php echo $__env->make('layouts.footer', ['payments' => $payments], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <!-- Mobile Bottom Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-black/90 backdrop-blur-sm border-t border-zinc-800">
            <div class="grid grid-cols-5 gap-0">
                <a href="<?php echo e(route('home')); ?>" class="flex flex-col items-center justify-center py-1 <?php echo e(request()->routeIs('home') ? 'text-red-500' : 'text-zinc-400'); ?> hover:text-white transition-colors">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10l9-7 9 7v8a2 2 0 01-2 2h-4a2 2 0 01-2-2v-3H9v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-8z"/>
                    </svg>
                    <span class="text-[10px] leading-none">Ana Sayfa</span>
                </a>
                <a href="<?php echo e(route('casino')); ?>" class="flex flex-col items-center justify-center py-1 <?php echo e(request()->routeIs('casino') ? 'text-red-500' : 'text-zinc-400'); ?> hover:text-white transition-colors">
                    <svg style="margin: 0.135rem;" class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" width="20px" height="20px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--emojione-monotone" preserveAspectRatio="xMidYMid meet"><path d="M58.384 8H5.614C3.619 8 2 10.011 2 12.493v39.012C2 53.987 3.619 56 5.614 56h52.77C60.381 56 62 53.987 62 51.505V12.493C62 10.011 60.381 8 58.384 8M21.646 52.584h-1.134c-.346-2.755-3.79-5.034-7.802-5.032c-4.005-.002-6.753 2.276-5.885 5.032H5.691a68.68 68.68 0 0 1 0-41.17h.785a70.641 70.641 0 0 0-1.203 4.468h15.059c.154-1.499.33-2.99.526-4.468h.787c-1.657 13.378-1.657 27.791.001 41.17m18.331 0h-1.132c.262-2.755-2.839-5.034-6.845-5.032c-4.008-.002-7.106 2.276-6.845 5.032h-1.133c-1.276-13.379-1.276-27.792 0-41.17h.787c-.128 1.478-.24 2.969-.339 4.468h15.059c-.099-1.499-.213-2.99-.339-4.468h.786c1.276 13.378 1.276 27.791.001 41.17m18.332 0h-1.134c.868-2.755-1.882-5.034-5.887-5.032c-4.01-.002-7.458 2.276-7.8 5.032h-1.134c1.657-13.379 1.657-27.792 0-41.17h.786c.197 1.478.373 2.969.525 4.468h15.061a69.99 69.99 0 0 0-1.205-4.468h.787a68.672 68.672 0 0 1 .001 41.17" fill="none"></path><path d="M56.234 26.942a7.428 7.428 0 0 1-.234.159c-.334-.238-.923-.632-2.077-1.028a6.71 6.71 0 0 0-2.183-.352c-1.478 0-2.828.434-3.736.819a.92.92 0 0 0-.678-.299h-1.354c-.518 0-.938.433-.938.969v5.543c0 .536.42.968.938.968h1.354a.942.942 0 0 0 .898-.692c.1-.345.397-1.391 2.504-1.391l.105.001c-2.779 2.909-3.319 6.395-3.344 6.566a.979.979 0 0 0 .217.776a.927.927 0 0 0 .71.336h6.634c.519 0 .938-.433.938-.969c0-6.042 3.45-9.594 3.727-9.868a.983.983 0 0 0 .322-.73c0-.535-.418-.969-.936-.969h-2.35a.936.936 0 0 0-.517.161M55.051 38.35h-6.634s.659-4.518 4.609-7.357c0 0-1.071-.322-2.298-.322c-1.364 0-2.921.398-3.402 2.084h-1.354v-5.543h1.354v.74s2.047-1.262 4.414-1.262c.617 0 1.257.086 1.887.303c1.807.623 1.986 1.17 2.336 1.17c.163 0 .364-.121.789-.411h2.346c0-.001-4.047 3.837-4.047 10.598" fill="#000000"></path><path d="M38.564 26.782h-2.35a.928.928 0 0 0-.518.16a7.428 7.428 0 0 1-.234.159c-.334-.238-.923-.632-2.077-1.028a6.71 6.71 0 0 0-2.183-.352c-1.478 0-2.828.434-3.736.819a.92.92 0 0 0-.678-.299h-1.354c-.518 0-.938.433-.938.969v5.543c0 .536.42.968.938.968h1.354a.942.942 0 0 0 .898-.692c.1-.345.397-1.391 2.504-1.391l.105.001c-2.779 2.909-3.319 6.395-3.345 6.566a.983.983 0 0 0 .218.776a.927.927 0 0 0 .71.336h6.634c.519 0 .938-.433.938-.969c0-6.042 3.45-9.594 3.727-9.868a.983.983 0 0 0 .322-.73c.001-.534-.417-.968-.935-.968m-4.05 11.568H27.88s.659-4.518 4.609-7.357c0 0-1.071-.322-2.298-.322c-1.364 0-2.921.398-3.402 2.084h-1.354v-5.543h1.354v.74s2.047-1.262 4.414-1.262c.617 0 1.257.086 1.887.303c1.807.623 1.986 1.17 2.336 1.17c.163 0 .364-.121.789-.411h2.346c0-.001-4.047 3.837-4.047 10.598" fill="#000000"></path><path d="M18.027 26.782h-2.35a.928.928 0 0 0-.518.16c-.046.033-.144.099-.234.159c-.334-.238-.923-.632-2.077-1.028a6.713 6.713 0 0 0-2.185-.352c-1.476 0-2.828.434-3.734.819a.92.92 0 0 0-.678-.299H4.898c-.518 0-.938.433-.938.969v5.543c0 .536.42.968.938.968h1.354a.942.942 0 0 0 .898-.692c.098-.345.397-1.391 2.504-1.391l.105.001c-2.779 2.909-3.319 6.395-3.345 6.566a.983.983 0 0 0 .218.776a.927.927 0 0 0 .71.336h6.634c.517 0 .938-.433.938-.969c0-6.042 3.45-9.594 3.727-9.868a.983.983 0 0 0 .322-.73c0-.534-.418-.968-.936-.968m-4.05 11.568H7.343s.659-4.518 4.609-7.357c0 0-1.071-.322-2.298-.322c-1.364 0-2.921.398-3.402 2.084H4.898v-5.543h1.354v.74s2.047-1.262 4.412-1.262c.619 0 1.259.086 1.889.303c1.807.623 1.986 1.17 2.336 1.17c.163 0 .364-.121.789-.411h2.346c-.001-.001-4.047 3.837-4.047 10.598" fill="#000000"></path></svg>
					
					<!--svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="6" width="18" height="10" rx="2" stroke-width="1.6"/>
                        <line x1="9" y1="6" x2="9" y2="16" stroke-width="1"/>
                        <line x1="15" y1="6" x2="15" y2="16" stroke-width="1"/>
                        <text x="6" y="13" font-size="6" font-weight="700" fill="currentColor">7</text>
                        <text x="10" y="13" font-size="6" font-weight="700" fill="currentColor">7</text>
                        <text x="14" y="13" font-size="6" font-weight="700" fill="currentColor">7</text>
                    </svg-->
                    <span class="text-[10px] leading-none">Casino</span>
                </a>
                <a href="<?php echo e(route('live-casino')); ?>" class="flex flex-col items-center justify-center py-1 <?php echo e(request()->routeIs('live-casino') ? 'text-red-500' : 'text-zinc-400'); ?> hover:text-white transition-colors">
                    <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="8" stroke-width="1.6"/>
                        <path d="M10 9l6 3-6 3V9z" fill="currentColor"/>
                    </svg>
                    <span class="text-[10px] leading-none">Canlı Casino</span>
                </a>
                <a href="#" onclick="openLiveChat(); return false;" class="flex flex-col items-center justify-center py-1 text-zinc-400 hover:text-white transition-colors">
					
					<svg style="margin: 3px;" class="w-4 h-4 mb-0.5" stroke="currentColor" fill="currentColor" width="12px" height="12px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin" class="jam jam-message-f"><path d='M3 .565h14a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-6.958l-6.444 4.808A1 1 0 0 1 2 18.57v-4.006a2 2 0 0 1-2-2v-9a3 3 0 0 1 3-3z' /></svg>
		
                    <!--svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="4" y="8" width="16" height="10" rx="2" stroke-width="1.6"/>
                        <line x1="12" y1="8" x2="12" y2="18" stroke-width="1.2"/>
                        <line x1="4" y1="13" x2="20" y2="13" stroke-width="1.2"/>
                    </svg-->
                    <span class="text-[10px] leading-none">Canlı Destek</span>
                </a>
                <button onclick="toggleSidebar()" class="flex flex-col items-center justify-center py-1 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <span class="text-[10px] leading-none">Menü</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Canlı Destek Kodu (Ayarlar tablosundan) -->
    <?php
        $settings = \App\Models\Ayarlar::getSettings();
        $liveChatCode = '';
        if($settings && $settings->canlidestek) {
            $licenseNumber = trim($settings->canlidestek);
            
            // Sadece rakam kontrolü
            if(preg_match('/^[0-9]+$/', $licenseNumber)) {
                // LiveChat kodunu otomatik oluştur
                $liveChatCode = '
<script>
    window.__lc = window.__lc || {};
    window.__lc.license = ' . $licenseNumber . ';
    window.__lc.integration_name = "manual_onboarding";
    window.__lc.product_name = "livechat";
    ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can\'t use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript><a href="https://www.livechat.com/chat-with/' . $licenseNumber . '/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>';
            }
        }
    ?>
    <?php if($liveChatCode): ?>
        <?php echo $liveChatCode; ?>

    <?php endif; ?>
    <!-- Canlı Destek Kodu Sonu -->

    <!-- JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        // Close sidebar when clicking on a link
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#mobileSidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    toggleSidebar();
                });
            });
        });
    </script>

    <script>
        // LiveChat widget'ını tamamen gizle
        document.addEventListener('DOMContentLoaded', function() {
            // CSS ile widget'ı gizle
            document.body.classList.remove('show-livechat');
            
            // Widget kapatıldığında dinle
            if (window.LiveChatWidget) {
                window.LiveChatWidget.on('visibility_changed', function(data) {
                    if (data.visibility === 'minimized' || data.visibility === 'hidden') {
                        document.body.classList.remove('show-livechat');
                    }
                });
            }
        });

         function openLiveChat() {
             try { document.body.classList.add('show-livechat'); } catch(e) {}
             setTimeout(function() {
                 if (window.LiveChatWidget && typeof window.LiveChatWidget.call === 'function') {
                     try {
                         window.LiveChatWidget.call('maximize');
                         window.LiveChatWidget.call('open_chat_window');
                         return;
                     } catch(e) {}
                 }
                 // Fallback: doğrudan canlı destek sayfasını aç
                 window.open('https://direct.lc.chat/19347577/', '_blank', 'noopener');
             }, 50);
         }

        // Yatay kaydırma için mouse wheel desteği
        document.addEventListener('DOMContentLoaded', function() {
            const horizontalScrolls = document.querySelectorAll('.horizontal-scroll');
            const paymentScrolls = document.querySelectorAll('.payment-scroll');
            
            // Ana sayfa yatay kaydırma
            horizontalScrolls.forEach(function(scrollContainer) {
                scrollContainer.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    scrollContainer.scrollLeft += e.deltaY;
                });
            });
            
            // Footer payments yatay kaydırma
            paymentScrolls.forEach(function(scrollContainer) {
                scrollContainer.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    scrollContainer.scrollLeft += e.deltaY;
                });
            });
            
            // Promosyon kodu formları
            const promoCodeForm = document.getElementById('promoCodeForm');
            const promoCodeFormMobile = document.getElementById('promoCodeFormMobile');
            
            if (promoCodeForm) {
                promoCodeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handlePromoCodeSubmit(this, 'promoCodeMessage');
                });
            }
            
            if (promoCodeFormMobile) {
                promoCodeFormMobile.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handlePromoCodeSubmit(this, 'promoCodeMessageMobile');
                });
            }
        });
        
        // Zarif hover effects
        function enhanceSidebarEffects() {
            const menuItems = document.querySelectorAll('.sidebar-menu-item');
            
            menuItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(2px)';
                    this.style.background = 'rgba(255, 255, 255, 0.08)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                    this.style.background = 'rgba(255, 255, 255, 0.03)';
                });
            });
        }
        
        // Initialize effects when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            enhanceSidebarEffects();
        });
        
        // Img tıklaması için ek bağlayıcı (capturing, stopPropagation)
        (function(){
            function wireImageClicks(rootId){
                var root = document.getElementById(rootId);
                if (!root) return;
                var img = root.querySelector('img');
                if (!img) return;
                var handler = function(e){
                    try { e.preventDefault(); e.stopPropagation(); } catch(_){}
                    openLiveChat();
                    return false;
                };
                ['click','touchstart'].forEach(function(ev){
                    img.addEventListener(ev, handler, { capture: true, passive: false });
                });
            }
            wireImageClicks('desktopLiveChatBtn');
            wireImageClicks('mobileLiveChatBtn');
        })();

        // Canlı destek tıklama bağlayıcıları (ek güvence - mobil/desktop)
        (function(){
            function bindLiveChat(el){
                if (!el) return;
                var handler = function(e){
                    // bazı tarayıcılarda default davranışı engelle
                    if (e) { try { e.preventDefault(); } catch(_){} }
                    openLiveChat();
                    return false;
                };
                el.addEventListener('click', handler, { passive: false });
                el.addEventListener('touchstart', handler, { passive: false });
                el.addEventListener('keydown', function(e){
                    if (e.key === 'Enter' || e.key === ' ') { handler(e); }
                });
                // İçteki img için de bağla
                var img = el.querySelector('img');
                if (img) {
                    img.addEventListener('click', handler, { passive: false });
                    img.addEventListener('touchstart', handler, { passive: false });
                }
            }
            bindLiveChat(document.getElementById('desktopLiveChatBtn'));
            bindLiveChat(document.getElementById('mobileLiveChatBtn'));
        })();
        
        function handlePromoCodeSubmit(form, messageId) {
            const formData = new FormData(form);
            const messageDiv = document.getElementById(messageId);
            const submitButton = form.querySelector('button[type="submit"]');
            // Try to find input with name 'promo_code' or 'code'
            let input = form.querySelector('input[name="promo_code"]');
            if (!input) {
                input = form.querySelector('input[name="code"]');
            }
            
            // Debug: Form verilerini kontrol et
            console.log('Form data:', formData.get('code'));
            console.log('Input value:', input ? input.value : 'Input not found');
            
            // Button'u devre dışı bırak
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Mesaj alanını temizle
            messageDiv.innerHTML = '';
            messageDiv.className = 'mt-2 text-xs hidden';
            
            fetch('/api/promo/use', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('Network response was not ok: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = data.message;
                    messageDiv.className = 'mt-2 text-xs text-green-400';
                    if(input) {
                        input.value = '';
                    }
                    
                    // Başarı mesajını 3 saniye sonra gizle
                    setTimeout(() => {
                        messageDiv.className = 'mt-2 text-xs hidden';
                    }, 3000);
                } else {
                    messageDiv.innerHTML = data.message;
                    messageDiv.className = 'mt-2 text-xs text-red-400';
                    
                    // Hata mesajını 5 saniye sonra gizle
                    setTimeout(() => {
                        messageDiv.className = 'mt-2 text-xs hidden';
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Promo code error:', error);
                messageDiv.innerHTML = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                messageDiv.className = 'mt-2 text-xs text-red-400';
                
                setTimeout(() => {
                    messageDiv.className = 'mt-2 text-xs hidden';
                }, 5000);
            })
            .finally(() => {
                // Button'u tekrar aktif et
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-ticket"></i>';
            });
        }
    </script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <?php if(auth()->guard('admin')->check()): ?>
    <div id="zeroBalanceModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-[9999]">
        <div class="relative max-w-sm w-full mx-4">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900 via-zinc-900/95 to-zinc-800 border shadow-2xl" style="border-color: rgba(235, 255, 0, 0.3);">
                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                <div class="relative p-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4 mt-2" style="background: linear-gradient(to bottom right, rgba(245, 158, 11, 0.3), rgba(245, 158, 11, 0.1)); border: 1px solid rgba(245, 158, 11, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Bakiye Yetersiz!</h3>
                    <p class="text-zinc-400 text-sm mb-6">Oyunu başlatmak için yeterli bakiyeniz bulunmuyor. Lütfen bakiye yükleyiniz.</p>
                    <div class="flex flex-col gap-3 pb-2">
                        <a href="<?php echo e(route('para-yatir')); ?>" class="w-full relative overflow-hidden rounded-lg py-3 px-6 transition-all duration-300 transform hover:scale-[1.02] group block" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.8), rgba(235, 255, 0, 0.6)); border: 1px solid rgba(235, 255, 0, 0.9);">
                            <span class="text-black font-bold">Bakiye Ekle</span>
                        </a>
                        <button onclick="document.getElementById('zeroBalanceModal').classList.add('hidden');" class="w-full py-2 text-zinc-400 hover:text-white transition-colors text-sm font-medium">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var userBalance = <?php echo e(Auth::guard('admin')->user()->bakiye ?? 0); ?>;
            
            document.body.addEventListener('click', function(e) {
                var link = e.target.closest('a');
                if (link && link.href) {
                    var isGameLink = link.href.includes('/GameLaunch/') || 
                                     link.href.includes('/K10GameLaunch/') ||
                                     link.href.includes('/drakon-direct-launch');
                    
                    if (isGameLink && userBalance <= 0) {
                        e.preventDefault();
                        document.getElementById('zeroBalanceModal').classList.remove('hidden');
                    }
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/layouts/app.blade.php ENDPATH**/ ?>