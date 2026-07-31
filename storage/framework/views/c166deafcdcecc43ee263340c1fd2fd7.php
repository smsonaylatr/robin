<?php $__env->startSection('title', 'Casino Oyunları - ' . ($settings->site_adi ?? 'BetNow')); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen">
    <!-- Slider Section -->
    <?php if($sliders->count() > 0): ?>
    <section class="relative overflow-hidden max-w-[1400px] mx-auto px-4" style="position: relative !important; z-index: 10 !important;">
        <div class="swiper casino-slider">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <?php if($slider->url): ?>
                    <a href="<?php echo e($slider->url); ?>" class="block">
                    <?php endif; ?>
                    <div class="relative w-full overflow-hidden">
                        <img src="<?php echo e(asset($slider->gorsel)); ?>" alt="Slider" class="block w-full h-auto object-contain">
                    </div>
                    <?php if($slider->url): ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Provider Filter - Kompakt Yatay Tasarım -->
    <?php if($providers->count() > 0): ?>
    <section class="py-6 px-4 relative" style="z-index: 1;">
        <div class="max-w-7xl mx-auto">
            <!-- Başlık ve Sağlayıcı Arama -->
            <div class="mb-6">
                <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-4">
                    <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-70"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500/5 via-transparent to-transparent" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                    <div class="relative flex items-center justify-between gap-4">
                        <!-- Sol taraf - Başlık -->
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                <div class="relative p-2 rounded-lg border" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border-color: rgba(235, 255, 0, 0.2);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                        <path d="M9 9h.01"></path>
                                        <path d="M15 9h.01"></path>
                                        <path d="M12 15h.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">Oyun Sağlayıcıları</h2>
                            </div>
                        </div>
                        
                        <!-- Sağ taraf - Arama kutusu -->
                        <div class="relative max-w-xs">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="provider-search" placeholder="Sağlayıcı ara..." 
                                   class="w-full pl-10 pr-4 py-2 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200 text-sm" style="focus:border: #ebff00; focus:ring: 1px solid #ebff00;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağlayıcı Yatay Scroll -->
            <div class="provider-scroll-container overflow-x-auto" id="providers-container">
                <div class="flex gap-3 min-w-max px-2 py-2">
                    <!-- Tümü Kartı -->
                    <div class="provider-card group cursor-pointer flex-shrink-0" data-provider="all">
                        <div class="relative overflow-hidden rounded-lg border px-4 py-3 transition-all duration-300 transform hover:scale-105 hover:shadow-lg min-w-[120px]" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.2), rgba(235, 255, 0, 0.1), transparent); border-color: rgba(235, 255, 0, 0.3);" onmouseover="this.style.borderColor='rgba(235, 255, 0, 0.5)'; this.style.boxShadow='0 10px 25px rgba(235, 255, 0, 0.2)';" onmouseout="this.style.borderColor='rgba(235, 255, 0, 0.3)'; this.style.boxShadow='none';">
                            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                            <div class="relative flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center border flex-shrink-0" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border-color: rgba(235, 255, 0, 0.2);">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="color: rgba(235, 255, 0, 0.8);">
                                        <circle cx="12" cy="12" r="10" fill="currentColor" fill-opacity="0.1"/>
                                        <path d="M7 12h10M12 7v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-semibold text-sm truncate">Tümü</h3>
                                    <p class="text-zinc-400 text-xs"><?php echo e($providers->count()); ?> sağlayıcı</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Öncelikli Sağlayıcılar (pragmatic, egtdigital, amusnet) -->
                    <?php
                        $priorityCodes = ['pragmatic', 'egtdigital', 'amusnet'];
                        $priorityProviders = $providers->filter(function($p) use ($priorityCodes) {
                            return in_array(strtolower(trim($p->code)), array_map('strtolower', $priorityCodes));
                        })->sortBy(function($p) use ($priorityCodes) {
                            return array_search(strtolower(trim($p->code)), array_map('strtolower', $priorityCodes));
                        });
                        $otherProviders = $providers->filter(function($p) use ($priorityCodes) {
                            return !in_array(strtolower(trim($p->code)), array_map('strtolower', $priorityCodes));
                        })->sortBy('name');
                    ?>

                    <?php $__currentLoopData = $priorityProviders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="provider-card group cursor-pointer flex-shrink-0" data-provider="<?php echo e($provider->code); ?>" data-name="<?php echo e(strtolower($provider->name)); ?>">
                        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-zinc-800/50 via-zinc-800/30 to-transparent border border-zinc-700/50 px-3 py-3 hover:border-zinc-600/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-zinc-800/50 min-w-[100px]">
                            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                            <div class="relative text-center">
                                <!-- Sağlayıcı İkonu/Logosu -->
                                <div class="w-20 h-6 mx-auto bg-gradient-to-br from-zinc-700/50 to-zinc-800/50 rounded-lg flex items-center justify-center border border-zinc-600/30 overflow-hidden">
                                    <div class="w-full h-full bg-gradient-to-r from-zinc-600 to-zinc-700 rounded-sm flex items-center justify-center px-1">
                                        <span class="text-white text-xs font-bold whitespace-nowrap"><?php echo e(strtoupper($provider->name)); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Sadece oyun sayısı -->
                                <div class="mt-2 text-center">
                                    <p class="text-zinc-400 text-xs"><?php echo e($provider->count); ?> oyun</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Diğer Sağlayıcı Kartları -->
                    <?php $__currentLoopData = $otherProviders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="provider-card group cursor-pointer flex-shrink-0" data-provider="<?php echo e($provider->code); ?>" data-name="<?php echo e(strtolower($provider->name)); ?>">
                        <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-zinc-800/50 via-zinc-800/30 to-transparent border border-zinc-700/50 px-3 py-3 hover:border-zinc-600/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-zinc-800/50 min-w-[100px]">
                            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                            <div class="relative text-center">
                                <!-- Sağlayıcı İkonu/Logosu -->
                                <div class="w-20 h-6 mx-auto bg-gradient-to-br from-zinc-700/50 to-zinc-800/50 rounded-lg flex items-center justify-center border border-zinc-600/30 overflow-hidden">
                                    <div class="w-full h-full bg-gradient-to-r from-zinc-600 to-zinc-700 rounded-sm flex items-center justify-center px-1">
                                        <span class="text-white text-xs font-bold whitespace-nowrap"><?php echo e(strtoupper($provider->name)); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Sadece oyun sayısı -->
                                <div class="mt-2 text-center">
                                    <p class="text-zinc-400 text-xs"><?php echo e($provider->count); ?> oyun</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Sağlayıcı Bulunamadı Mesajı -->
            <div id="no-providers-found" class="hidden text-center py-8">
                <div class="max-w-md mx-auto">
                    <div class="w-12 h-12 mx-auto mb-3 bg-zinc-800/50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-zinc-300 mb-2">Sağlayıcı bulunamadı</h3>
                    <p class="text-zinc-500">Aradığınız sağlayıcı bulunamadı. Farklı bir arama yapmayı deneyin.</p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Search Section -->
    <section class="py-4 px-4 relative" style="z-index: 1;">
        <div class="max-w-7xl mx-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="game-search" placeholder="Oyun ara..." 
                       class="w-full pl-10 pr-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200" style="focus:border: #ebff00; focus:ring: 1px solid #ebff00;">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button id="clear-search" class="text-zinc-400 hover:text-white transition-colors hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Casino Games -->
    <section class="py-8 px-4 relative" style="z-index: 1;">
        <div class="max-w-7xl mx-auto">
            
            <div id="games-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2">
                <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="game-card group relative overflow-hidden rounded-xl hover:bg-zinc-700/50 transition-all duration-300 transform hover:scale-105 min-h-[220px]" data-provider="<?php echo e($game->vendorcode); ?>">
                    <div class="relative">
                        <!-- Loading Skeleton with Shimmer Effect -->
                        <div class="image-skeleton w-full h-40 bg-gradient-to-br from-zinc-700 to-zinc-800 rounded-t-xl relative overflow-hidden animate-pulse transition-opacity duration-300">
                            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                            
                            <!-- Shimmer Animation -->
                            <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/40 to-transparent" style="width: 50%;"></div>
                            <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent to-transparent" style="background: linear-gradient(to right, transparent, rgba(235, 255, 0, 0.2), transparent); animation-delay: -0.75s; width: 50%;"></div>
                            
                            <!-- Loading Spinner -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="relative">
                                    <div class="w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.3); border-top-color: #ebff00;"></div>
                                    <div class="absolute inset-0 w-8 h-8 border-2 rounded-full animate-spin" style="border-color: rgba(235, 255, 0, 0.1); border-top-color: rgba(235, 255, 0, 0.5); animation-delay: -0.5s;"></div>
                                </div>
                            </div>
                        </div>
                        

                        
                        <!-- Actual Image (hidden initially) -->
                        <img src="<?php echo e($game->cover); ?>?v=<?php echo e(time()); ?>" alt="<?php echo e($game->game_name); ?>" class="w-full h-40 object-cover opacity-0 transition-opacity duration-500" 
                             loading="eager" decoding="sync"
                             onload="setTimeout(() => { 
                                 this.style.opacity='1'; 
                                 const skeleton = this.parentElement.querySelector('.image-skeleton');
                                 if(skeleton) {
                                     skeleton.style.opacity='0';
                                     setTimeout(() => skeleton.style.display='none', 300);
                                 }
                             }, 200);">
                        
                        <!-- Hover overlay -->
                        <div class="absolute top-0 left-0 right-0 h-40 bg-gradient-to-b from-black/90 via-black/80 to-black/90 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center backdrop-blur-sm">
                            <!-- Game Name with better styling -->
                            <div class="text-center mb-3 px-3">
                                <h3 class="text-white font-semibold text-xs leading-tight drop-shadow-lg"><?php echo e($game->game_name); ?></h3>
                            </div>
                            
                            <!-- Play Button with login page style -->
                            <?php
                                $settings = \App\Models\Ayarlar::getSettings();
                                $fakeApiActive = $settings->fakeapi ?? 0;
                            ?>
                            <?php if($fakeApiActive == 1 && isset($game->ikincilmi) && $game->ikincilmi == 1): ?>
                                <?php
                                    $vendorSeg = trim($game->vendorcode ?? '', '/');
                                    $urlSeg = ltrim($game->url ?? '', '/');
                                    $k10Url = '/K10GameLaunch/' . ($vendorSeg ? ($vendorSeg . '/') : '') . $urlSeg;
                                ?>
                                <a href="<?php echo e($k10Url); ?>" 
                                   class="relative overflow-hidden rounded-lg bg-gradient-to-br border py-2 px-4 hover:border-yellow-500/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/20 group">
                                    <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500/10 via-yellow-500/5 to-transparent"></div>
                                    <div class="relative flex items-center justify-center gap-2">
                                        <span class="text-white font-semibold text-xs">OYNA</span>
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" class="text-yellow-400 group-hover:translate-x-1 transition-transform duration-200">
                                            <path d="M8 5v14l11-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="/GameLaunch/<?php echo e($game->game_code ?? $game->id); ?>" 
                                   class="relative overflow-hidden rounded-lg bg-gradient-to-br border py-2 px-4 hover:border-yellow-500/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/20 group">
                                    <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500/10 via-yellow-500/5 to-transparent"></div>
                                    <div class="relative flex items-center justify-center gap-2">
                                        <span class="text-white font-semibold text-xs">OYNA</span>
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" class="text-yellow-400 group-hover:translate-x-1 transition-transform duration-200">
                                            <path d="M8 5v14l11-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Game badges -->
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <?php if($game->is_new): ?>
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-semibold">YENİ</span>
                            <?php endif; ?>
                            <?php if($game->is_hot): ?>
                            <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-semibold">HOT</span>
                            <?php endif; ?>
                            <?php if($game->is_featured): ?>
                            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-semibold">ÖNE ÇIKAN</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Game Info -->
                    <div class="p-3 bg-zinc-800/30 border-t border-zinc-700/50">
                        <h3 class="text-white text-sm font-medium truncate"><?php echo e($game->game_name); ?></h3>
                        <p class="text-zinc-400 text-xs mt-1"><?php echo e($game->provider->name ?? 'Casino'); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Loading Spinner -->
            <div id="loading-spinner" class="hidden text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                <p class="text-zinc-400 mt-2">Oyunlar yükleniyor...</p>
            </div>

            <!-- No More Games -->
            <div id="no-more-games" class="hidden text-center py-8">
                <p class="text-zinc-400">Tüm oyunlar yüklendi!</p>
            </div>
        </div>
    </section>
</div>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script>
// Initialize Swiper
document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.casino-slider', {
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
        // Slider'ın pozisyonunu sabitle
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

    // Scroll pozisyonunu sürekli takip et
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
    swiper.on('slideChange', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 10);
    });

    // Tüm transition event'lerini yakala
    swiper.on('slideChangeTransitionStart', function() {
        saveScrollPosition();
    });

    swiper.on('slideChangeTransitionEnd', function() {
        setTimeout(restoreScrollPosition, 50);
    });

    swiper.on('transitionStart', function() {
        saveScrollPosition();
    });

    swiper.on('transitionEnd', function() {
        setTimeout(restoreScrollPosition, 50);
    });

    // Loop event'lerini yakala
    swiper.on('reachEnd', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 100);
    });

    swiper.on('reachBeginning', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 100);
    });

    swiper.on('loopFix', function() {
        saveScrollPosition();
        setTimeout(restoreScrollPosition, 150);
    });

    // Autoplay event'lerini yakala
    swiper.on('autoplayStart', function() {
        saveScrollPosition();
    });

    swiper.on('autoplayStop', function() {
        setTimeout(restoreScrollPosition, 100);
    });

    // DOM mutation observer ile slider değişimlerini yakala
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                if (target.classList.contains('swiper-slide-active') || 
                    target.classList.contains('swiper-slide-prev') || 
                    target.classList.contains('swiper-slide-next')) {
                    saveScrollPosition();
                    setTimeout(restoreScrollPosition, 50);
                }
            }
        });
    });

    // Slider slide'larını observe et
    const slides = document.querySelectorAll('.casino-slider .swiper-slide');
    slides.forEach(function(slide) {
        observer.observe(slide, {
            attributes: true,
            attributeFilter: ['class']
        });
    });

    // Sayfa kapanmadan önce interval'i temizle
    window.addEventListener('beforeunload', function() {
        if (scrollInterval) {
            clearInterval(scrollInterval);
        }
    });

    // Slider'ın autoplay başladığında scroll pozisyonunu koru
    swiper.on('autoplayStart', function() {
        const currentScrollPos = window.pageYOffset || document.documentElement.scrollTop;
        sessionStorage.setItem('casinoSliderScrollPos', currentScrollPos);
    });

    // Slider'ın autoplay durduğunda scroll pozisyonunu geri yükle
    swiper.on('autoplayStop', function() {
        const savedScrollPos = sessionStorage.getItem('casinoSliderScrollPos');
        if (savedScrollPos) {
            window.scrollTo(0, parseInt(savedScrollPos));
        }
    });
    
    // Slider'ın pozisyonunu kontrol et
    window.addEventListener('scroll', function() {
        const sliderSection = document.querySelector('.casino-slider').closest('section');
        if (sliderSection) {
            const rect = sliderSection.getBoundingClientRect();
            // Slider viewport'tan çıktığında pozisyonunu sıfırla
            if (rect.bottom < 0) {
                sliderSection.style.position = 'relative';
                sliderSection.style.zIndex = '10';
            }
        }
    });
});

let currentPage = 2; // first 50 are server-rendered, start fetching from page 2
let isLoading = false;
let hasMoreGames = true;
let currentProvider = 'all';
let currentSearch = '';

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('game-search');
    const clearButton = document.getElementById('clear-search');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        currentSearch = this.value.trim();
        
        // Show/hide clear button
        if (currentSearch) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
        
        // Debounce search
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            hasMoreGames = true;
            document.getElementById('games-container').innerHTML = '';
            document.getElementById('no-more-games').classList.add('hidden');
            loadGames();
        }, 500);
    });

    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        currentSearch = '';
        clearButton.classList.add('hidden');
        currentPage = 1;
        hasMoreGames = true;
        document.getElementById('games-container').innerHTML = '';
        document.getElementById('no-more-games').classList.add('hidden');
        loadGames();
    });
});

// Provider card functionality
document.querySelectorAll('.provider-card').forEach(card => {
    card.addEventListener('click', function() {
        const provider = this.dataset.provider;
        currentProvider = provider;
        currentPage = 1;
        hasMoreGames = true;
        
        // Update active card
        document.querySelectorAll('.provider-card').forEach(c => {
            const cardDiv = c.querySelector('div');
            cardDiv.classList.remove('border-yellow-500/50', 'bg-gradient-to-br', 'from-yellow-500/20', 'via-yellow-500/10');
            cardDiv.classList.add('border-zinc-700/50', 'bg-gradient-to-br', 'from-zinc-800/50', 'via-zinc-800/30');
        });
        
        // Highlight selected card
        const selectedCardDiv = this.querySelector('div');
        selectedCardDiv.classList.remove('border-zinc-700/50', 'border-zinc-600/50', 'from-zinc-800/50', 'via-zinc-800/30');
        selectedCardDiv.classList.add('border-yellow-500/50', 'from-yellow-500/20', 'via-yellow-500/10');
        
        // Clear container and load games
        document.getElementById('games-container').innerHTML = '';
        document.getElementById('no-more-games').classList.add('hidden');
        loadGames();
    });
});

// Provider search functionality
document.addEventListener('DOMContentLoaded', function() {
    const providerSearch = document.getElementById('provider-search');
    const providersContainer = document.getElementById('providers-container');
    const noProvidersFound = document.getElementById('no-providers-found');
    
    if (providerSearch) {
        providerSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const providerCards = providersContainer.querySelectorAll('.provider-card');
            let visibleCount = 0;
            
            providerCards.forEach(card => {
                const providerName = card.dataset.name || '';
                const isAllCard = card.dataset.provider === 'all';
                
                if (searchTerm === '' || isAllCard || providerName.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0 || (visibleCount === 1 && searchTerm !== '')) {
                noProvidersFound.classList.remove('hidden');
                providersContainer.classList.add('hidden');
            } else {
                noProvidersFound.classList.add('hidden');
                providersContainer.classList.remove('hidden');
            }
        });
    }
});

// Infinite scroll
function loadGames() {
    if (isLoading || !hasMoreGames) return;
    
    isLoading = true;
    document.getElementById('loading-spinner').classList.remove('hidden');
    
    const url = new URL('/api/casino-games', window.location.origin);
    url.searchParams.append('page', currentPage);
    url.searchParams.append('provider', currentProvider);
    if (currentSearch) {
        url.searchParams.append('search', currentSearch);
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.games.length > 0) {
                const container = document.getElementById('games-container');
                
                data.games.forEach(game => {
                    const gameCard = createGameCard(game);
                    container.appendChild(gameCard);
                });
                
                currentPage++;
            } else {
                hasMoreGames = false;
                if (currentPage === 1) {
                    // No results found
                    const container = document.getElementById('games-container');
                    container.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-zinc-300 mb-2">Oyun bulunamadı</h3>
                            <p class="text-zinc-500">"${currentSearch}" için sonuç bulunamadı. Farklı bir arama yapmayı deneyin.</p>
                        </div>
                    `;
                } else {
                    document.getElementById('no-more-games').classList.remove('hidden');
                }
            }
        })
        .catch(error => {
            console.error('Error loading games:', error);
        })
        .finally(() => {
            isLoading = false;
            document.getElementById('loading-spinner').classList.add('hidden');
        });
}

function createGameCard(game) {
    const card = document.createElement('div');
    card.className = 'game-card group relative overflow-hidden rounded-xl hover:bg-zinc-700/50 transition-all duration-300 transform hover:scale-105 min-h-[220px]';
    card.dataset.provider = game.vendorcode;

    // Create badges HTML
    let badgesHtml = '';
    if (game.is_new) badgesHtml += '<span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-semibold">YENİ</span>';
    if (game.is_hot) badgesHtml += '<span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-semibold">HOT</span>';
    if (game.is_featured) badgesHtml += '<span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-semibold">ÖNE ÇIKAN</span>';

    // Get fakeapi setting from PHP
    const fakeApiActive = <?php echo e(\App\Models\Ayarlar::getSettings()->fakeapi ?? 0); ?>;

    // Determine game URL based on fakeapi setting and ikincilmi field
    let gameUrl;
    if (fakeApiActive == 1 && game.ikincilmi && game.ikincilmi == 1) {
        const vendorSeg = (game.vendorcode || '').replace(/^\/+|\/+$/g, '');
        const urlSeg = (game.url || '').replace(/^\/+/, '');
        gameUrl = `/K10GameLaunch/${vendorSeg ? vendorSeg + '/' : ''}${urlSeg}`;
    } else {
        gameUrl = `/GameLaunch/${game.game_code || game.id}`;
    }

    const providerName = (game.provider && game.provider.name) ? game.provider.name : 'Casino';

    card.innerHTML = `
        <div class="relative">
            <!-- Loading Skeleton with Shimmer Effect -->
            <div class="image-skeleton w-full h-40 bg-gradient-to-br from-zinc-700 to-zinc-800 rounded-t-xl relative overflow-hidden animate-pulse transition-opacity duration-300">
                <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>

                <!-- Shimmer Animation -->
                <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-white/40 to-transparent" style="width: 50%;"></div>
                <div class="absolute inset-0 -translate-x-full animate-shimmer bg-gradient-to-r from-transparent via-yellow-500/20 to-transparent" style="animation-delay: -0.75s; width: 50%;"></div>

                <!-- Loading Spinner -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative">
                        <div class="w-8 h-8 border-2 border-red-500/30 border-t-red-500 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 w-8 h-8 border-2 border-red-500/10 border-t-red-500/50 rounded-full animate-spin" style="animation-delay: -0.5s;"></div>
                    </div>
                </div>
            </div>

            <!-- Actual Image (hidden initially) -->
            <img src="${game.cover}?v=${Date.now()}" alt="${game.game_name}" class="w-full h-40 object-cover opacity-0 transition-opacity duration-500"
                 loading="eager" decoding="sync"
                 onload="setTimeout(() => {
                     this.style.opacity='1';
                     const skeleton = this.previousElementSibling;
                     if (skeleton) {
                         skeleton.style.opacity='0';
                         setTimeout(() => skeleton.style.display='none', 300);
                     }
                 }, 200);">

            <!-- Hover overlay -->
            <div class="absolute top-0 left-0 right-0 h-40 bg-gradient-to-b from-black/90 via-black/80 to-black/90 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center backdrop-blur-sm">
                <div class="text-center mb-3 px-3">
                    <h3 class="text-white font-semibold text-xs leading-tight drop-shadow-lg">${game.game_name}</h3>
                </div>
                <a href="${gameUrl}"
                   class="relative overflow-hidden rounded-lg bg-gradient-to-br border py-2 px-4 hover:border-yellow-500/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/20 group">
                    <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-500/10 via-yellow-500/5 to-transparent"></div>
                    <div class="relative flex items-center justify-center gap-2">
                        <span class="text-white font-semibold text-xs">OYNA</span>
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" class="text-yellow-400 group-hover:translate-x-1 transition-transform duration-200">
                            <path d="M8 5v14l11-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Game badges -->
            <div class="absolute top-2 left-2 flex flex-col gap-1">${badgesHtml}</div>
        </div>

        <!-- Game Info -->
        <div class="p-3 bg-zinc-800/30 border-t border-zinc-700/50">
            <h3 class="text-white text-sm font-medium truncate">${game.game_name}</h3>
            <p class="text-zinc-400 text-xs mt-1">${providerName}</p>
        </div>
    `;

    return card;
}

// Intersection Observer for infinite scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && hasMoreGames && !isLoading) {
            loadGames();
        }
    });
}, {
    rootMargin: '100px'
});

// Observe loading spinner
const loadingSpinner = document.getElementById('loading-spinner');
if (loadingSpinner) {
    observer.observe(loadingSpinner);
}

// Initial load
document.addEventListener('DOMContentLoaded', function() {
    // Load more games when user scrolls near bottom
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            loadGames();
        }
    });
});
</script>

<style>
.provider-scroll-container::-webkit-scrollbar {
    height: 6px;
}

.provider-scroll-container::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 3px;
}

.provider-scroll-container::-webkit-scrollbar-thumb {
    background: #6B7280;
    border-radius: 3px;
}

.provider-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #9CA3AF;
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
    animation: shimmer 1.5s infinite;
}

.swiper-button-next,
.swiper-button-prev {
    color: white !important;
}

.swiper-pagination-bullet {
    background: white !important;
}

.swiper-pagination-bullet-active {
    background: #EF4444 !important;
}

/* Slider pozisyonunu sabitle */
.casino-slider {
    position: relative !important;
    z-index: 10 !important;
}

.casino-slider .swiper-wrapper {
    position: relative !important;
}

.casino-slider .swiper-slide {
    position: relative !important;
}

/* Diğer içeriklerin slider üstünde kalmasını engelle */
.provider-scroll-container,
#games-container {
    position: relative;
    z-index: 1;
}
</style>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/casino.blade.php ENDPATH**/ ?>