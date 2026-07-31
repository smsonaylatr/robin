@extends('layouts.app')

@section('title', $settings->site_adi ?? 'BetNow - Türkiye\'nin En Güvenilir Online Casino Platformu')

@section('content')
<div class="min-h-screen">
    @foreach($homeSections as $section)
        @switch($section->name)
            @case('slider')
                <!-- Slider Section -->
                @if($sliders->count() > 0)
                <section class="relative overflow-hidden max-w-[1400px] mx-auto px-4">
                    <div class="swiper home-slider">
                        <div class="swiper-wrapper">
                            @foreach($sliders as $slider)
                            <div class="swiper-slide">
                                @if($slider->url)
                                <a href="{{ $slider->url }}" class="block">
                                @endif
                                <div class="relative w-full overflow-hidden">
                                    <img src="{{ asset($slider->gorsel) }}" alt="Slider" class="block w-full h-auto object-contain" loading="eager">
                                </div>
                                @if($slider->baslik)
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 md:p-6">
                                    <h2 class="text-white text-lg md:text-xl lg:text-2xl font-bold">{{ $slider->baslik }}</h2>
                                    @if($slider->aciklama)
                                    <p class="text-gray-200 text-sm md:text-base mt-2">{{ $slider->aciklama }}</p>
                                    @endif
                                </div>
                                @endif
                                @if($slider->url)
                                </a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </section>

                @if(($settings->bottom_banner_active ?? 0) == 1 && !empty($settings->bottom_banner_image))
                <section class="px-4 mt-3">
                    <div class="max-w-7xl mx-auto">
                        <div class="relative w-full rounded-xl overflow-hidden border border-zinc-800/60 shadow-sm">
                            @if(!empty($settings->bottom_banner_url))
                                <a href="{{ $settings->bottom_banner_url }}" target="_blank" rel="noopener" class="block">
                                    <img src="{{ asset($settings->bottom_banner_image) }}" alt="Alt Banner" class="w-full h-auto object-cover">
                                </a>
                            @else
                                <img src="{{ asset($settings->bottom_banner_image) }}" alt="Alt Banner" class="w-full h-auto object-cover">
                            @endif
                        </div>
                        @if(isset($bottomBannerBelowImages) && $bottomBannerBelowImages->count() > 0)
                        <div class="mt-3">
                            @if(($settings->bottom_below_mobile_grid ?? 0) == 1)
                                <div class="grid gap-2" style="grid-template-columns: repeat({{ $bottomBannerBelowImages->count() }}, minmax(0, 1fr));">
                                    @foreach($bottomBannerBelowImages as $bb)
                                        @if($bb->url)
                                            <a href="{{ $bb->url }}" target="_blank" rel="noopener" class="block">
                                                <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="w-full h-auto rounded-lg border border-zinc-800/60" loading="lazy">
                                            </a>
                                        @else
                                            <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="block w-full h-auto rounded-lg border border-zinc-800/60" loading="lazy">
                                        @endif
                                    @endforeach
                                </div>
                                <style>
                                    @media (max-width: 640px) {
                                        .grid[style*="grid-template-columns"] {
                                            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                                        }
                                    }
                                </style>
                            @else
                                <div class="flex gap-2">
                                    @php $__bbCount = max(1, $bottomBannerBelowImages->count()); $__gap = 8; @endphp
                                    @foreach($bottomBannerBelowImages as $bb)
                                        @php $__width = 'calc((100% - ' . ($__bbCount - 1) * $__gap . 'px)/' . $__bbCount . ')'; @endphp
                                        @if($bb->url)
                                            <a href="{{ $bb->url }}" target="_blank" rel="noopener" class="block" style="width: {{ $__width }};">
                                                <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="w-full h-auto rounded-lg border border-zinc-800/60" loading="lazy">
                                            </a>
                                        @else
                                            <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="block w-full h-auto rounded-lg border border-zinc-800/60" style="width: {{ $__width }};" loading="lazy">
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </section>
                @endif
                @endif
                @break

            @case('son_kazananlar')
                <!-- Son Kazananlar Section -->
                @if($recentWinners->count() > 0)
                <section class="py-2 px-4 bg-gradient-to-r from-zinc-900/20 via-zinc-900/10 to-zinc-900/20">
                    <div class="max-w-7xl mx-auto">
                        <!-- Başlık -->
                        <div class="mb-4">
                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-3 sm:p-4">
                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="relative">
                                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                        <div class="relative p-1.5 sm:p-2 rounded-lg" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2);">
                                            @if($section->icon)
                                                {!! $section->icon !!}
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;" class="sm:w-5 sm:h-5">
                                                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                                    <path d="M4 22h16"></path>
                                                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-base sm:text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">{{ $section->title }}</h2>
                                        <p class="text-zinc-400 text-sm">Büyük kazançlar yaşanıyor!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kazananlar Scroll Container -->
                        <div class="relative overflow-hidden">
                            <div class="recent-winners-scroll flex gap-2 md:gap-3 animate-scroll-winners">
                                @foreach($recentWinners as $winner)
                                <!-- Winner Card -->
                                <div class="winner-card group flex-shrink-0 bg-gradient-to-br from-zinc-900/90 via-zinc-800/80 to-zinc-900/70 rounded-lg border border-zinc-700/50 px-1 py-0.5 w-auto hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-red-500/20 backdrop-blur-sm">
                                    <div class="flex items-center gap-1.5">
                                        <a href="/GameLaunch/{{ $winner->gameid }}" target="_blank" class="block">
                                            <div class="w-12 h-12 md:w-16 md:h-16 rounded-md overflow-hidden border border-zinc-600/30 bg-zinc-900/60 flex items-center justify-center relative">
                                                @if($winner->cover)
                                                    <img src="{{ asset($winner->cover) }}" alt="Kazanan oyun" class="w-full h-full object-contain">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-zinc-500">
                                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                                            <path d="M9 9h.01"></path>
                                                            <path d="M15 9h.01"></path>
                                                            <path d="M12 15h.01"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <!-- Transparent Play Icon Overlay -->
                                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    <div class="w-7 h-7 md:w-9 md:h-9 rounded-full bg-black/40 flex items-center justify-center">
                                                        <svg viewBox="0 0 24 24" class="w-4 h-4 md:w-5 md:h-5" fill="currentColor">
                                                            <path d="M8 5v14l11-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="min-w-0 flex flex-col">
                                            <span class="text-white text-xs font-medium truncate">{{ $winner->masked_username }}</span>
                                            <span class="text-yellow-400 font-bold text-[11px] md:text-xs whitespace-nowrap">{{ number_format($winner->amount, 0) }}₺</span>
                                            <span class="text-zinc-500 text-[10px] md:text-xs whitespace-nowrap">{{ $winner->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                @endif
                @break

            @case('yaklasan_maclar')
                <!-- Spor Bahisleri Section -->
                @if($upcomingMatches->count() > 0)
                <section class="py-3 px-4 bg-gradient-to-r from-zinc-900/10 via-zinc-900/5 to-zinc-900/10">
                    <div class="max-w-7xl mx-auto">
                        <!-- Başlık -->
                        <div class="mb-3">
                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-3">
                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="relative">
                                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                        <div class="relative p-1.5 sm:p-2 rounded-lg" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2);">
                                            @if($section->icon)
                                                {!! $section->icon !!}
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;" class="sm:w-4 sm:h-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="m9 12 2 2 4-4"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-base sm:text-lg font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">{{ $section->title }}</h2>
                                        <p class="text-zinc-400 text-xs">En yüksek oranlar burada!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Maçlar Grid -->
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($upcomingMatches as $match)
                            <div class="match-card bg-gradient-to-br from-zinc-900/80 via-zinc-900/60 to-zinc-900/40 rounded-lg border border-zinc-800/50 p-2 hover:scale-102 transition-all duration-300 shadow-lg hover:shadow-red-500/10">
                                <!-- Match Header -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-1">
                                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></div>
                                        <span class="text-zinc-400 text-xs truncate">{{ Str::limit($match->lig_isim, 12) }}</span>
                                    </div>
                                    <span class="text-zinc-500 text-xs">{{ Str::limit($match->ulke_isim, 8) }}</span>
                                </div>

                                <!-- Teams -->
                                <div class="mb-2">
                                    <div class="flex items-center justify-center gap-1 mb-1">
                                        <h3 class="text-white font-medium text-xs truncate flex-1 text-center">{{ Str::limit($match->evsahibi_isim, 12) }}</h3>
                                        <span class="text-zinc-400 text-xs">vs</span>
                                        <h3 class="text-white font-medium text-xs truncate flex-1 text-center">{{ Str::limit($match->misafir_isim, 12) }}</h3>
                                    </div>
                                    
                                    <!-- Match Time -->
                                    <div class="text-center">
                                        <p class="text-red-400 font-medium text-xs">
                                            {{ \Carbon\Carbon::parse($match->baslangic)->format('d.m H:i') }}
                                        </p>
                                        <p class="text-zinc-500 text-xs">
                                            {{ \Carbon\Carbon::parse($match->baslangic)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Odds -->
                                <div class="grid grid-cols-3 gap-0.5">
                                    <!-- Home Win -->
                                    <a href="/sports" class="bg-zinc-800/50 rounded-sm p-1.5 text-center hover:bg-zinc-700/50 transition-colors cursor-pointer border border-zinc-700/30 hover:border-red-500/30 block">
                                        <p class="text-zinc-400 text-xs mb-0.5">1</p>
                                        <p class="text-white font-semibold text-xs">{{ number_format($match->oran1, 2) }}</p>
                                    </a>
                                    
                                    <!-- Draw -->
                                    <a href="/sports" class="bg-zinc-800/50 rounded-sm p-1.5 text-center hover:bg-zinc-700/50 transition-colors cursor-pointer border border-zinc-700/30 hover:border-red-500/30 block">
                                        <p class="text-zinc-400 text-xs mb-0.5">X</p>
                                        <p class="text-white font-semibold text-xs">{{ number_format($match->oran0, 2) }}</p>
                                    </a>
                                    
                                    <!-- Away Win -->
                                    <a href="/sports" class="bg-zinc-800/50 rounded-sm p-1.5 text-center hover:bg-zinc-700/50 transition-colors cursor-pointer border border-zinc-700/30 hover:border-red-500/30 block">
                                        <p class="text-zinc-400 text-xs mb-0.5">2</p>
                                        <p class="text-white font-semibold text-xs">{{ number_format($match->oran2, 2) }}</p>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif
                @break

            @case('oyunlar')
                <!-- Games Section -->
                <section class="py-2 px-4">
                    <div class="max-w-7xl mx-auto">
                        <!-- Başlık - Oyunlar -->
                        <div class="mb-3">
                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-3 sm:p-4">
                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="relative">
                                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                        <div class="relative p-1.5 sm:p-2 rounded-lg" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2);">
                                            @if($section->icon)
                                                {!! $section->icon !!}
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;" class="sm:w-5 sm:h-5">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                                    <path d="M9 9h.01"></path>
                                                    <path d="M15 9h.01"></path>
                                                    <path d="M12 15h.01"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-base sm:text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">{{ $section->title }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="horizontal-scroll flex overflow-x-auto gap-2 pb-4 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($oyunlar as $oyun)
                                <div class="oyun-card bg-zinc-900/90 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 border border-zinc-800 flex-shrink-0 relative">
                                    <a href="{{ $oyun->url ?? '#' }}" target="_blank" class="block">
                                        <div class="w-[140px] h-[250px] overflow-hidden rounded-2xl relative">
                                            <!-- Loading Skeleton with Shimmer Effect -->
                                            <div class="image-skeleton w-full h-full bg-gradient-to-br from-zinc-700 to-zinc-800 relative overflow-hidden">
                                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                                                
                                                <!-- Shimmer Animation -->
                                                <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                                
                                                <!-- Loading Spinner -->
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="relative">
                                                        <div class="w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.3); border-top-color: #ebff00;"></div>
                                                        <div class="absolute inset-0 w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.1); border-top-color: rgba(235, 255, 0, 0.5); animation-delay: -0.5s;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Actual Image (hidden initially) -->
                                            <img src="{{ asset($oyun->gorsel) }}" alt="oyun" class="w-full h-full object-cover opacity-0 transition-opacity duration-500 absolute inset-0" 
                                                 onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @break

            @case('casino_oyunlari')
                <!-- Casino Oyunları Section -->
                <section class="py-2 px-4">
                    <div class="max-w-7xl mx-auto">
                        <!-- Başlık - Casino Oyunları -->
                        <div class="mb-3">
                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-3 sm:p-4">
                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="relative">
                                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                        <div class="relative p-1.5 sm:p-2 rounded-lg" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2);">
                                            @if($section->icon)
                                                {!! $section->icon !!}
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;" class="sm:w-5 sm:h-5">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                                    <path d="M9 9h.01"></path>
                                                    <path d="M15 9h.01"></path>
                                                    <path d="M12 15h.01"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-base sm:text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">{{ $section->title }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="horizontal-scroll flex overflow-x-auto gap-2 pb-4 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($casinoOyunlari as $oyun)
                                <div class="oyun-card bg-zinc-900/90 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 border border-zinc-800 flex-shrink-0 relative">
                                    <a href="{{ $oyun->url ?? '#' }}" target="_blank" class="block">
                                        <div class="w-[140px] h-[250px] overflow-hidden rounded-2xl relative">
                                            <!-- Loading Skeleton with Shimmer Effect -->
                                            <div class="image-skeleton w-full h-full bg-gradient-to-br from-zinc-700 to-zinc-800 relative overflow-hidden">
                                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                                                
                                                <!-- Shimmer Animation -->
                                                <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                                
                                                <!-- Loading Spinner -->
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="relative">
                                                        <div class="w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.3); border-top-color: #ebff00;"></div>
                                                        <div class="absolute inset-0 w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.1); border-top-color: rgba(235, 255, 0, 0.5); animation-delay: -0.5s;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Actual Image (hidden initially) -->
                                            <img src="{{ asset($oyun->gorsel) }}" alt="casino oyun" class="w-full h-full object-cover opacity-0 transition-opacity duration-500 absolute inset-0" 
                                                 onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @break

            @case('canli_casinolar')
                <!-- Canlı Casinolar Section -->
                <section class="py-2 px-4">
                    <div class="max-w-7xl mx-auto">
                        <!-- Başlık - Canlı Casinolar -->
                        <div class="mb-3">
                            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-3 sm:p-4">
                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                                <div class="relative flex items-center gap-3">
                                    <div class="relative">
                                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                        <div class="relative p-1.5 sm:p-2 rounded-lg" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2);">
                                            @if($section->icon)
                                                {!! $section->icon !!}
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;" class="sm:w-5 sm:h-5">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                                    <path d="M9 9h.01"></path>
                                                    <path d="M15 9h.01"></path>
                                                    <path d="M12 15h.01"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-base sm:text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">{{ $section->title }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="horizontal-scroll flex overflow-x-auto gap-2 pb-4 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($canliCasino as $oyun)
                                <div class="oyun-card bg-zinc-900/90 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 border border-zinc-800 flex-shrink-0 relative">
                                    <a href="{{ $oyun->url ?? '#' }}" target="_blank" class="block">
                                        <div class="w-[140px] h-[250px] overflow-hidden rounded-2xl relative">
                                            <!-- Loading Skeleton with Shimmer Effect -->
                                            <div class="image-skeleton w-full h-full bg-gradient-to-br from-zinc-700 to-zinc-800 relative overflow-hidden">
                                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                                                
                                                <!-- Shimmer Animation -->
                                                <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                                
                                                <!-- Loading Spinner -->
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="relative">
                                                        <div class="w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.3); border-top-color: #ebff00;"></div>
                                                        <div class="absolute inset-0 w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.1); border-top-color: rgba(235, 255, 0, 0.5); animation-delay: -0.5s;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Actual Image (hidden initially) -->
                                            <img src="{{ asset($oyun->gorsel) }}" alt="canlı casino" class="w-full h-full object-cover opacity-0 transition-opacity duration-500 absolute inset-0" 
                                                 onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @break

        @endswitch
    @endforeach
</div>

<!-- Duyuru Modal -->
@if($duyurular->count() > 0)
<div id="duyuruModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="relative max-w-md w-full mx-4">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 shadow-2xl">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
            
            <div class="relative p-6">
                <!-- Close Button -->
                <button onclick="closeDuyuruModal()" class="absolute top-4 right-4 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Duyuru Content -->
                <div class="text-center mb-6">
                    
                    @foreach($duyurular as $duyuru)
                    <div class="duyuru-content" data-duyuru-id="{{ $duyuru->id }}">
                        @if($duyuru->resim)
                        <div class="mb-4">
                            <img src="{{ asset($duyuru->resim) }}" alt="Duyuru" class="w-full max-h-64 object-contain rounded-lg bg-zinc-800/30">
                        </div>
                        @endif
                        
                        <h3 class="text-xl font-bold bg-gradient-to-r from-red-500 via-red-400 to-red-500 bg-clip-text text-transparent mb-3">{{ $duyuru->konu }}</h3>
                        
                        @if($duyuru->aciklama)
                        <div class="text-zinc-300 text-sm leading-relaxed mb-6">
                            {!! nl2br(e($duyuru->aciklama)) !!}
                        </div>
                        @endif
                        
                        @if($duyuru->url)
                        <a href="{{ $duyuru->url }}" class="inline-block mb-4 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-400 hover:to-red-500 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 text-sm">
                            Detayları Gör
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button onclick="dontShowAgain()" class="flex-1 px-3 py-2 bg-zinc-800/50 hover:bg-zinc-700/50 text-zinc-300 hover:text-white rounded-lg transition-all duration-300 border border-zinc-700/50 text-sm">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                        Bir Daha Gösterme
                    </button>
                    <button onclick="closeDuyuruModal()" class="flex-1 px-3 py-2 bg-gradient-to-br from-red-500/20 via-red-500/10 to-transparent border border-red-500/30 hover:border-red-500/50 text-white rounded-lg transition-all duration-300 text-sm">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
// Initialize Swiper
document.addEventListener('DOMContentLoaded', function() {
    const swiperContainer = document.querySelector('.home-slider');
    if (swiperContainer && swiperContainer.querySelectorAll('.swiper-slide').length > 1) {
        // Scroll pozisyonunu korumak için global değişken
        let savedScrollPosition = 0;
        let isScrolling = false;

        const swiper = new Swiper('.home-slider', {
            loop: false, // Loop'u devre dışı bırak
            autoplay: {
                delay: 4000, // 4 saniye
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
                stopOnLastSlide: true // Son slide'da dur
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'slide',
            speed: 600,
            // Slider'ın sayfa kaydırılırken üstte kalmasını engelle
            observer: true,
            observeParents: true,
            // Slider'ın container'ının pozisyonunu kontrol et
            watchOverflow: true,
            // Slider'ın otomatik değişiminde sayfayı en üste atmasını engelle
            preventInteractionOnTransition: false,
            // Slider'ın pozisyonunu sabitle
            allowTouchMove: true,
            // Slider'ın responsive davranışını iyileştir
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                1024: {
                    slidesPerView: 1,
                    spaceBetween: 0
                }
            }
        });



        // Basit scroll pozisyonu koruma sistemi
        let currentScrollPos = 0;

        // Scroll pozisyonunu kaydet
        function saveScrollPos() {
            currentScrollPos = window.pageYOffset || document.documentElement.scrollTop;
        }

        // Scroll pozisyonunu geri yükle
        function restoreScrollPos() {
            if (currentScrollPos > 0) {
                window.scrollTo(0, currentScrollPos);
            }
        }

        // Slider değişiminde pozisyonu koru
        swiper.on('slideChange', function() {
            saveScrollPos();
            setTimeout(restoreScrollPos, 100);
        });

        // Manuel loop geçişinde pozisyonu koru
        swiper.on('slideChangeTransitionEnd', function() {
            setTimeout(restoreScrollPos, 50);
        });

        // Autoplay başladığında pozisyonu kaydet
        swiper.on('autoplayStart', function() {
            saveScrollPos();
        });

        // Autoplay durduğunda pozisyonu geri yükle
        swiper.on('autoplayStop', function() {
            setTimeout(restoreScrollPos, 100);
        });


    }


    
    // Initialize duyuru check
    checkDuyuru();
});

// Global Duyuru Modal Functions
function showDuyuruModal() {
    const modal = document.getElementById('duyuruModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('animate-fade-in');
    }
}

function closeDuyuruModal() {
    const modal = document.getElementById('duyuruModal');
    if (modal) {
        modal.classList.add('hidden');
        // Set session storage to not show again this session
        sessionStorage.setItem('duyuruClosed', 'true');
    }
}

function dontShowAgain() {
    const modal = document.getElementById('duyuruModal');
    if (modal) {
        modal.classList.add('hidden');
        // Set localStorage to never show again
        localStorage.setItem('duyuruDontShow', 'true');
    }
}

// Check if duyuru should be shown
function checkDuyuru() {
    const dontShow = localStorage.getItem('duyuruDontShow');
    const sessionClosed = sessionStorage.getItem('duyuruClosed');
    
    if (!dontShow && !sessionClosed) {
        // Show duyuru after 2 seconds
        setTimeout(() => {
            showDuyuruModal();
        }, 2000);
    }
}
</script>

<style>
.swiper-container,
.home-slider {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: transparent;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100% !important;
    height: 100%;
    overflow: hidden;
}

.swiper-button-next,
.swiper-button-prev {
    color: #ef4444;
}

.swiper-pagination-bullet {
    background: #ef4444;
}

.swiper-pagination-bullet-active {
    background: #ef4444;
}

.horizontal-scroll::-webkit-scrollbar {
    display: none;
}
.horizontal-scroll {
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.betting-card {
    box-shadow: 0 2px 12px 0 #000a, 0 1.5px 4px 0 #2228;
    border-radius: 18px;
    border: 1.5px solid #232323;
    background: linear-gradient(135deg, #18181b 80%, #232323 100%);
    transition: box-shadow 0.2s, transform 0.2s;
}
.betting-card:hover {
    box-shadow: 0 4px 24px 0 #ef4444cc, 0 2px 8px 0 #222a;
    border-color: #ef4444;
}
.aspect-w-16.aspect-h-9 {
    aspect-ratio: 16/9;
}
.oyun-card {
    min-width: 140px;
    max-width: 140px;
    height: 250px;
    border-radius: 22px;
    box-shadow: 0 2px 12px 0 #000a, 0 1.5px 4px 0 #2228;
    border: 1.5px solid #232323;
    background: linear-gradient(135deg, #18181b 80%, #232323 100%);
    transition: box-shadow 0.2s, transform 0.2s;
}
.oyun-card:hover {
    box-shadow: 0 4px 24px 0 #ef4444cc, 0 2px 8px 0 #222a;
    border-color: #ef4444;
}

/* Shimmer Animation */
@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

.animate-shimmer {
    animation: shimmer 2s infinite;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Swiper Styles */
.home-slider .swiper-wrapper {
    display: flex;
}

.home-slider .swiper-slide {
    flex-shrink: 0;
    width: 100%;
    height: 100%;
}

.swiper-pagination-bullet {
    background: #ef4444 !important;
}

.swiper-pagination-bullet-active {
    background: #ef4444 !important;
}
@media (max-width: 640px) {
    .oyun-card {
        min-width: 90px;
        max-width: 90px;
        height: 160px;
    }
    .oyun-card > a > div {
        width: 90px !important;
        height: 160px !important;
    }
}

/* Son Kazananlar Scroll Animation */
@keyframes scroll-winners {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-scroll-winners {
    animation: scroll-winners 45s linear infinite;
    animation-play-state: running;
}

.animate-scroll-winners:hover {
    animation-play-state: paused;
}

/* Recent Winners Container */
.recent-winners-scroll {
    width: max-content;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Home slider initializing...');
    console.log('Slider element:', document.querySelector('.home-slider'));
    
    // Swiper initialization for home slider (casino style)
    const homeSlider = new Swiper('.home-slider', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
            waitForTransition: true
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'slide',
        speed: 600,
        observer: true,
        observeParents: true,
        watchOverflow: true,
        preventInteractionOnTransition: false,
        allowTouchMove: true,
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 0
            },
            768: {
                slidesPerView: 1,
                spaceBetween: 0
            },
            1024: {
                slidesPerView: 1,
                spaceBetween: 0
            }
        }
    });

    console.log('Home slider initialized:', homeSlider);
    console.log('Slider slides count:', homeSlider.slides.length);

    // Manual test için 3 saniye sonra bir sonraki slide'a geç
    setTimeout(() => {
        console.log('Testing slide change...');
        homeSlider.slideNext();
    }, 3000);

    // Scroll pozisyonunu sürekli takip et (casino style)
    let lastScrollPosition = 0;
    let scrollInterval;

    function saveScrollPosition() {
        lastScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    }

    function restoreScrollPosition() {
        if (lastScrollPosition > 0) {
            window.scrollTo({
                top: lastScrollPosition,
                behavior: 'instant'
            });
        }
    }

    // Her 100ms'de scroll pozisyonunu kaydet
    scrollInterval = setInterval(saveScrollPosition, 100);

    // Slider'ın otomatik değişiminde sayfayı en üste atmasını engelle
    homeSlider.on('slideChange', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 10);
    });

    // Tüm transition event'lerini yakala
    homeSlider.on('slideChangeTransitionStart', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 50);
    });

    homeSlider.on('slideChangeTransitionEnd', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 50);
    });

    // Sayfa kapanmadan önce interval'i temizle
    window.addEventListener('beforeunload', function() {
        if (scrollInterval) {
            clearInterval(scrollInterval);
        }
    });
});
</script>
@endsection 