<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BetNow - Türkiye\'nin En Güvenilir Online Casino Platformu')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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

        /* LiveChat minimize durumunda gizleme */
        .show-livechat div[style*="position: fixed"][style*="bottom"]:not([style*="height: 100%"]),
        .show-livechat div[style*="position: fixed"][style*="right"]:not([style*="height: 100%"]) {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
    </style>
</head>
<body class="bg-black text-white">
    <!-- Background Effect -->
    <div class="fixed inset-0 bg-[url('/assets/hero-pattern/background-effect.webp')] opacity-30 pointer-events-none"></div>
    
    <div class="flex min-h-screen flex-col relative bg-black/60">
        <!-- Header -->
        <nav class="fixed top-0 w-full z-50 bg-black/80 backdrop-blur-sm border-b border-red-500/20">
            <div class="mx-auto px-2 sm:px-4">
                <div class="flex items-center justify-between h-12 sm:h-14">
                    <a class="text-2xl font-bold text-red-500" href="{{ route('home') }}">
                        @php
                            $settings = \App\Models\Ayarlar::getSettings();
                            $logoPath = $settings->logo ?? 'assets/logo/betnow.png';
                        @endphp
                        <img alt="Betnow" loading="lazy" width="200" height="200" decoding="async" class="w-24 sm:w-40 md:w-48 h-auto p-2" src="{{ asset($logoPath) }}"/>
                    </a>
                    <div class="flex items-center gap-1.5 sm:gap-4">
                        <!-- User Menu -->
                        @auth('admin')
                        <div class="flex items-center space-x-3">
                            <div class="relative group">
                                <button type="button" class="px-3 py-1 rounded-lg font-bold text-sm flex items-center casino-gradient text-black shadow focus:outline-none">
                                    {{ Auth::guard('admin')->user()->parabirimi }}{{ number_format(Auth::guard('admin')->user()->bakiye, 2) }}
                                </button>
                                <div class="absolute left-1/2 -translate-x-1/2 mt-2 min-w-max bg-zinc-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg border border-zinc-800 opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 pointer-events-none group-hover:pointer-events-auto group-focus-within:pointer-events-auto transition-all z-50">
                                    Çevrim Miktarı: <span class="font-bold">{{ Auth::guard('admin')->user()->cevrim ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="relative group">
                                <button class="flex items-center space-x-2 px-3 py-1 bg-zinc-800/60 hover:bg-zinc-700/60 rounded-lg transition-colors">
                                    <div class="w-7 h-7 bg-red-500/20 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-red-400">{{ substr(Auth::guard('admin')->user()->username, 0, 1) }}</span>
                                    </div>
                                    <span class="text-white font-medium text-sm">{{ Auth::guard('admin')->user()->username }}</span>
                                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-zinc-900 border border-zinc-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="py-2">
                                        <a href="{{ route('hesabim') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Hesabım
                                        </a>
                                        <a href="{{ route('para-yatir') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Para Yatır
                                        </a>
                                        <a href="{{ route('para-cek') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Para Çek
                                        </a>
                                        <hr class="border-zinc-800 my-1">
                                        <a href="{{ route('hesap-hareketleri') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Hesap Hareketleri
                                        </a>
                                        <a href="{{ route('bahis-gecmisi') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Bahis Geçmişi
                                        </a>
                                        <a href="{{ route('casino-gecmisi') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Casino Geçmişi
                                        </a>
                                        <a href="{{ route('aktif-bonuslarim') }}" class="block px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            Aktif Bonuslarım
                                        </a>
                                        <hr class="border-zinc-800 my-1">
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-zinc-800 hover:text-red-300 transition-colors">
                                                Çıkış Yap
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-zinc-300 hover:text-white transition-colors">Giriş Yap</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">Kayıt Ol</a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Left Sidebar -->
        <div class="hidden md:block fixed top-14 left-0 bottom-0 z-40 shadow-xl bg-black/95 backdrop-blur-sm border-r border-zinc-800 w-64">
            <div class="py-3 px-1 flex-1 h-full overflow-y-auto">
                <!-- Casino Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 px-3 py-2 mb-2">
                        <div class="w-8 h-8 rounded-sm flex items-center justify-center bg-zinc-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dice3 lucide-dice-3 w-5 h-5 text-muted-foreground" aria-hidden="true">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                <path d="M16 8h.01"></path>
                                <path d="M12 12h.01"></path>
                                <path d="M8 16h.01"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-muted-foreground uppercase">Casino</span>
                    </div>
                    <div class="space-y-1">
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('casino') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-play w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <path d="M10 7.75a.75.75 0 0 1 1.142-.638l3.664 2.249a.75.75 0 0 1 0 1.278l-3.664 2.25a.75.75 0 0 1-1.142-.64z"></path>
                                    <path d="M12 17v4"></path>
                                    <path d="M8 21h8"></path>
                                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Casino</span>
                        </a>
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('live-casino') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-video w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <path d="m22 8-6 4 6 4V8Z"></path>
                                    <rect width="14" height="12" x="2" y="6" rx="2" ry="2"></rect>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Canlı Casino</span>
                        </a>
                    </div>
                </div>

                <!-- Sports Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 px-3 py-2 mb-2">
                        <div class="w-8 h-8 rounded-sm flex items-center justify-center bg-zinc-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target w-5 h-5 text-muted-foreground" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-muted-foreground uppercase">Spor</span>
                    </div>
                    <div class="space-y-1">
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('betnow-sports') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="12" r="6"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Spor Bahisleri</span>
                        </a>
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('live-betting') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-radio w-5 h-5 text-muted-foreground' aria-hidden='true'>
                                    <path d='M4.9 19.1C1 15.2 1 8.8 4.9 4.9'></path>
                                    <path d='M7.8 16.2c-2.8-2.8-2.8-7.3 0-10.1'></path>
                                    <circle cx='12' cy='12' r='2'></circle>
                                    <path d='M16.2 7.8c2.8 2.8 2.8 7.3 0 10.1'></path>
                                    <path d='M19.1 4.9C23 8.8 23 15.1 19.1 19'></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Canlı Bahisler</span>
                        </a>
                    </div>
                </div>

                <!-- Other Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 px-3 py-2 mb-2">
                        <div class="w-8 h-8 rounded-sm flex items-center justify-center bg-zinc-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gift w-5 h-5 text-muted-foreground" aria-hidden="true">
                                <rect x="3" y="8" width="18" height="4" rx="1"></rect>
                                <path d="M12 8v13"></path>
                                <path d="M19 12v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-9"></path>
                                <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-muted-foreground uppercase">Bonus</span>
                    </div>
                    <div class="space-y-1">
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('bonus') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gift w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <rect x="3" y="8" width="18" height="4" rx="1"></rect>
                                    <path d="M12 8v13"></path>
                                    <path d="M19 12v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-9"></path>
                                    <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Bonuslar</span>
                        </a>
                    </div>
                </div>

                <!-- Canlı Destek ve Social Media Links -->
                <div class="mt-auto px-3 py-4">
                    <!-- Canlı Destek -->
                    <div class="mb-4">
                        <button onclick="openLiveChat()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600/80 hover:bg-green-600 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"></path>
                                    <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3"></path>
                                </svg>
                            </div>
                            <span class="text-white font-medium text-sm">Canlı Destek</span>
                        </button>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="text-zinc-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="md:hidden fixed top-14 left-0 bottom-0 z-40 shadow-xl bg-black/95 backdrop-blur-sm border-r border-zinc-800 w-64 transform -translate-x-full transition-transform duration-300">
            <div class="py-3 px-1 flex-1 h-full overflow-y-auto">
                <!-- Casino Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 px-3 py-2 mb-2">
                        <div class="w-8 h-8 rounded-sm flex items-center justify-center bg-zinc-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dice3 lucide-dice-3 w-5 h-5 text-muted-foreground" aria-hidden="true">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                <path d="M16 8h.01"></path>
                                <path d="M12 12h.01"></path>
                                <path d="M8 16h.01"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-muted-foreground uppercase">Casino</span>
                    </div>
                    <div class="space-y-1">
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('casino') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-play w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <path d="M10 7.75a.75.75 0 0 1 1.142-.638l3.664 2.249a.75.75 0 0 1 0 1.278l-3.664 2.25a.75.75 0 0 1-1.142-.64z"></path>
                                    <path d="M12 17v4"></path>
                                    <path d="M8 21h8"></path>
                                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Casino</span>
                        </a>
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('live-casino') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-video w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <path d="m22 8-6 4 6 4V8Z"></path>
                                    <rect width="14" height="12" x="2" y="6" rx="2" ry="2"></rect>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Canlı Casino</span>
                        </a>
                    </div>
                </div>

                <!-- Sports Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 px-3 py-2 mb-2">
                        <div class="w-8 h-8 rounded-sm flex items-center justify-center bg-zinc-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target w-5 h-5 text-muted-foreground" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-muted-foreground uppercase">Spor</span>
                    </div>
                    <div class="space-y-1">
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('betnow-sports') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="12" r="6"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Spor Bahisleri</span>
                        </a>
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('live-betting') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-radio w-5 h-5 text-muted-foreground' aria-hidden='true'>
                                    <path d='M4.9 19.1C1 15.2 1 8.8 4.9 4.9'></path>
                                    <path d='M7.8 16.2c-2.8-2.8-2.8-7.3 0-10.1'></path>
                                    <circle cx='12' cy='12' r='2'></circle>
                                    <path d='M16.2 7.8c2.8 2.8 2.8 7.3 0 10.1'></path>
                                    <path d='M19.1 4.9C23 8.8 23 15.1 19.1 19'></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Canlı Bahisler</span>
                        </a>
                    </div>
                </div>

                <!-- Other Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-2 px-3 py-2 mb-2">
                        <div class="w-8 h-8 rounded-sm flex items-center justify-center bg-zinc-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gift w-5 h-5 text-muted-foreground" aria-hidden="true">
                                <rect x="3" y="8" width="18" height="4" rx="1"></rect>
                                <path d="M12 8v13"></path>
                                <path d="M19 12v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-9"></path>
                                <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-muted-foreground uppercase">Bonus</span>
                    </div>
                    <div class="space-y-1">
                        <a class="flex uppercase items-center gap-2 px-3 py-1.5 mx-2 rounded-sm border active:scale-[0.98] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] text-gray-400 hover:bg-muted/20 hover:text-white" href="{{ route('bonus') }}">
                            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors duration-200 bg-zinc-800/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gift w-5 h-5 text-muted-foreground" aria-hidden="true">
                                    <rect x="3" y="8" width="18" height="4" rx="1"></rect>
                                    <path d="M12 8v13"></path>
                                    <path d="M19 12v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-9"></path>
                                    <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-muted-foreground">Bonuslar</span>
                        </a>
                    </div>
                </div>

                <!-- Canlı Destek ve Social Media Links -->
                <div class="mt-auto px-3 py-4">
                    <!-- Canlı Destek -->
                    <div class="mb-4">
                        <button onclick="openLiveChat()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600/80 hover:bg-green-600 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"></path>
                                    <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3"></path>
                                </svg>
                            </div>
                            <span class="text-white font-medium text-sm">Canlı Destek</span>
                        </button>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="text-zinc-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-zinc-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobileSidebarOverlay" class="md:hidden fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleSidebar()"></div>

        <!-- Right Sidebar -->
        @if(request()->routeIs('betnow-sports'))
            <!-- Sports Coupon Sidebar -->
            <div class="hidden lg:block fixed top-14 right-0 bottom-0 z-40 shadow-xl bg-black/95 backdrop-blur-sm border-l border-zinc-800 w-80">
                <div class="py-3 px-4 flex-1 h-full overflow-y-auto">
                    <div class="p-3 border-b border-gray-800">
                        <h3 class="text-sm font-bold text-white">Kupon</h3>
                    </div>
                    
                    <div class="p-3" id="couponContainer">
                        <!-- Selected Bets -->
                        <div id="selectedBets" class="space-y-2 mb-4">
                            <div class="text-xs text-gray-400 text-center py-4">Henüz bahis seçilmedi</div>
                        </div>

                        <!-- Total Odds -->
                        <div class="bg-black/30 rounded p-2 mb-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-300">Toplam Oran:</span>
                                <span id="totalOdds" class="text-sm font-bold text-white">1.00</span>
                            </div>
                        </div>

                        <!-- Bet Amount -->
                        <div class="mb-3">
                            <label class="block text-xs text-gray-300 mb-1">Bahis Miktarı (TL)</label>
                            <input type="number" id="betAmount" min="1" step="1" value="10" 
                                   class="w-full bg-black/30 border border-gray-700 rounded px-2 py-1 text-white text-sm focus:outline-none focus:border-red-500">
                        </div>

                        <!-- Potential Win -->
                        <div class="bg-black/30 rounded p-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-300">Potansiyel Kazanç:</span>
                                <span id="potentialWin" class="text-sm font-bold text-green-400">10.00 TL</span>
                            </div>
                        </div>

                        <!-- Place Bet Button -->
                        <button id="placeBetBtn" onclick="{{ auth('admin')->check() ? 'placeBet()' : 'showLoginMessage()' }}" {{ auth('admin')->check() ? 'disabled' : '' }}
                                class="w-full {{ auth('admin')->check() ? 'bg-red-600/80 hover:bg-red-600 disabled:bg-gray-600 disabled:cursor-not-allowed' : 'bg-blue-600/80 hover:bg-blue-600' }} text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200">
                            {{ auth('admin')->check() ? 'Bahis Yap' : 'Giriş Yap' }}
                        </button>

                        <!-- Clear Coupon -->
                        <button onclick="clearCoupon()" 
                                class="w-full bg-gray-600/80 hover:bg-gray-600 text-white font-medium py-1.5 px-4 rounded text-sm transition-colors duration-200 mt-2">
                            Kupondan Temizle
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- Default Right Sidebar -->
            <div class="hidden lg:block fixed top-14 right-0 bottom-0 z-40 shadow-xl bg-black/95 backdrop-blur-sm border-l border-zinc-800 w-80">
                <div class="py-3 px-4 flex-1 h-full overflow-y-auto">
                    <!-- Recent Winners -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">Son Kazananlar</h3>
                        <div class="space-y-3">
                            @if(isset($recentWinners) && $recentWinners->count() > 0)
                                @foreach($recentWinners as $winner)
                                <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        @if($winner->game && $winner->game->cover)
                                            <div class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center">
                                                <img src="{{ $winner->game->cover }}" alt="{{ $winner->game->name ?? 'Oyun' }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-red-500/20 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-red-400">{{ substr($winner->user->username ?? 'U', 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            @php
                                                $username = $winner->user->username ?? 'Kullanıcı';
                                                $displayName = strlen($username) > 2 ? substr($username, 0, -2) . '**' : $username;
                                            @endphp
                                            <p class="text-white text-sm">{{ $displayName }}</p>
                                            <p class="text-zinc-400 text-xs">Kazanç</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-green-400 font-medium">₺{{ number_format($winner->amount, 0) }}</p>
                                        <p class="text-zinc-400 text-xs">{{ \Carbon\Carbon::parse($winner->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-zinc-400 text-sm">Henüz kazanan yok</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Live Support -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">Canlı Destek</h3>
                        <div class="space-y-2">
                            <a href="#" class="flex items-center space-x-3 p-3 bg-zinc-800/50 rounded-lg hover:bg-zinc-700/50 transition-colors">
                                <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Telegram</p>
                                    <p class="text-zinc-400 text-sm">7/24 Canlı Destek</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-center space-x-3 p-3 bg-zinc-800/50 rounded-lg hover:bg-zinc-700/50 transition-colors">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">E-posta</p>
                                    <p class="text-zinc-400 text-sm">destek@betnow.com</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="flex-1 md:ml-64 lg:mr-80">
            <main class="pt-14">
                @yield('content')
            </main>
            
            <!-- Footer -->
            @include('layouts.footer')
        </div>

        <!-- Mobile Bottom Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-black/95 backdrop-blur-sm border-t border-zinc-800">
            <div class="flex items-center justify-around py-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Ana Sayfa</span>
                </a>
                
                <a href="{{ route('casino') }}" class="flex flex-col items-center p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs">Casino</span>
                </a>
                
                <a href="{{ route('live-casino') }}" class="flex flex-col items-center p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs">Canlı</span>
                </a>
                
                <a href="{{ route('bonus') }}" class="flex flex-col items-center p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    <span class="text-xs">Bonus</span>
                </a>
                
                <button onclick="toggleSidebar()" class="flex flex-col items-center p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="text-xs">Menü</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Canlı Destek Kodu (Ayarlar tablosundan) -->
    @php
        $settings = \App\Models\Ayarlar::getSettings();
    @endphp
    @if($settings && $settings->canlidestek)
        {!! $settings->canlidestek !!}
    @endif
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
            // CSS ile widget'ı göster ve maksimize et
            document.body.classList.add('show-livechat');
            
            // Widget'ı maksimize et
            setTimeout(function() {
                if (window.LiveChatWidget) {
                    window.LiveChatWidget.call('maximize');
                }
            }, 100);
        }
    </script>
</body>
</html>
