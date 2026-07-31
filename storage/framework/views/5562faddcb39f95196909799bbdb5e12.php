<footer class="w-full bg-gradient-to-b from-black/30 via-black/70 to-black/95 border-t border-zinc-800/60 max-w-[1400px] mx-auto px-4 md:px-6 pb-20 md:pb-8">
    <!-- Provider Photos -->
    <div class="hidden md:block container mx-auto py-10 border-b border-zinc-800/60">
        <div class="relative overflow-hidden">
            <div class="flex items-center gap-6 animate-scroll-providers">
                <?php
                    $providers = \App\Models\ProviderPhoto::active()->ordered()->get();
                ?>
                <?php if($providers->count() > 0): ?>
                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <?php if($provider->link): ?>
                            <a href="<?php echo e($provider->link); ?>" target="_blank">
                                <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                            </a>
                        <?php else: ?>
                            <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!-- Duplicate for seamless loop -->
                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <?php if($provider->link): ?>
                            <a href="<?php echo e($provider->link); ?>" target="_blank">
                                <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                            </a>
                        <?php else: ?>
                            <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <!-- Fallback provider logos -->
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="NetEnt" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/netent.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Microgaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/microgaming.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Playtech" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/playtech.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Evolution Gaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/evolution.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Pragmatic Play" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/pragmatic.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Betsoft" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/betsoft.webp')); ?>"/>
                    </div>
                    <!-- Duplicate fallback providers for seamless loop -->
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="NetEnt" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/netent.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Microgaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/microgaming.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Playtech" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/playtech.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Evolution Gaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/evolution.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Pragmatic Play" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/pragmatic.webp')); ?>"/>
                    </div>
                    <div class="relative h-16 w-40 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                        <img alt="Betsoft" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/betsoft.webp')); ?>"/>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Links -->
    <div class="hidden md:block container mx-auto lg:px-12 py-14">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 md:gap-10">
            <div class="space-y-4 sm:space-y-5">
                <h3 class="text-base sm:text-lg font-semibold text-white tracking-tight flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="6" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><line x1="9" y1="6" x2="9" y2="16" stroke="currentColor" stroke-width="1"/><line x1="15" y1="6" x2="15" y2="16" stroke="currentColor" stroke-width="1"/></svg>
                    Casino
                </h3>
                <ul class="space-y-2.5 sm:space-y-3">
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('casino')); ?>">Slotlar</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('live-casino')); ?>">Canlı Casino</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('live-casino')); ?>">Blackjack</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('live-casino')); ?>">Rulet</a></li>
                </ul>
            </div>
            <div class="space-y-4 sm:space-y-5">
                <h3 class="text-base sm:text-lg font-semibold text-white tracking-tight flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/></svg>
                    Spor
                </h3>
                <ul class="space-y-2.5 sm:space-y-3">
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('sports')); ?>">Futbol</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('sports')); ?>">Basketbol</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('sports')); ?>">Tenis</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="<?php echo e(route('sports')); ?>">E-Spor</a></li>
                </ul>
            </div>
            <div class="space-y-4 sm:space-y-5">
                <h3 class="text-base sm:text-lg font-semibold text-white tracking-tight flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7l-3 3V6a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.5"/></svg>
                    Destek
                </h3>
                <ul class="space-y-2.5 sm:space-y-3">
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="/help-center">Yardım Merkezi</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="/live-support">Canlı Destek</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="/faq">SSS</a></li>
                    <li><a class="text-sm sm:text-base text-muted-foreground hover:text-primary transition-colors duration-200" href="/contact">İletişim</a></li>
                </ul>
            </div>
            <div class="col-span-2 space-y-6">
                <div class="flex gap-6 sm:gap-8">
                    <a target="_blank" class="opacity-80 hover:opacity-100 transition-all duration-300" href="/license">
                        <img alt="Curacao" loading="lazy" width="160" height="80" decoding="async" class="rounded-sm h-14 w-auto shrink-0" style="color:transparent" src="<?php echo e(asset('assets/logo/gcb.webp')); ?>"/>
                    </a>
                    <a target="_blank" class="opacity-80 hover:opacity-100 transition-all duration-300" href="https://www.dmca.com">
                        <img alt="DMCA" loading="lazy" width="160" height="80" decoding="async" class="h-16 w-auto shrink-0" style="color:transparent" src="<?php echo e(asset('assets/logo/_dmca_premi_badge_2.png')); ?>"/>
                    </a>
                </div>
                <a target="_blank" class="opacity-80 hover:opacity-100 transition-all duration-300 block w-full" href="https://www.gambleaware.org">
                    <img alt="GambleAware" loading="lazy" width="446" height="46" decoding="async" class="w-full h-auto" style="color:transparent" src="<?php echo e(asset('assets/logo/gambleaware.webp')); ?>"/>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Footer -->
    <div class="md:hidden container mx-auto py-10">
        <!-- Mobile Social Media Icons -->
        <?php
            $settings = \App\Models\Ayarlar::getSettings();
        ?>
        <?php if($settings && ($settings->telegram || $settings->instagram || $settings->twitter)): ?>
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-white/90 mb-3 text-center">Bizi Takip Edin</h3>
                <div class="flex justify-center gap-3">
                     <?php if($settings->telegram): ?>
                         <a href="<?php echo e($settings->telegram); ?>" target="_blank" class="relative w-10 h-10 rounded-lg bg-gradient-to-br from-zinc-800/50 to-zinc-900/50 border border-zinc-600/30 hover:border-zinc-500/50 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md overflow-hidden backdrop-blur-sm" title="Telegram">
                             <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), transparent, transparent);"></div>
                             <img src="<?php echo e(asset('images/telegram.png')); ?>" alt="Telegram" class="w-5 h-5 object-contain relative z-10">
                         </a>
                     <?php endif; ?>
                    <?php if($settings->whatsapp): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $settings->whatsapp)); ?>" target="_blank" class="relative w-10 h-10 rounded-lg bg-gradient-to-br from-zinc-800/50 to-zinc-900/50 border border-zinc-600/30 hover:border-zinc-500/50 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md overflow-hidden backdrop-blur-sm" title="WhatsApp">
                             <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), transparent, transparent);"></div>
                             <img src="<?php echo e(asset('images/wp.png')); ?>" alt="WhatsApp" class="w-5 h-5 object-contain relative z-10">
                         </a>
                     <?php endif; ?>
                     <?php if($settings->instagram): ?>
                         <a href="<?php echo e($settings->instagram); ?>" target="_blank" class="relative w-10 h-10 rounded-lg bg-gradient-to-br from-zinc-800/50 to-zinc-900/50 border border-zinc-600/30 hover:border-zinc-500/50 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md overflow-hidden backdrop-blur-sm" title="Instagram">
                             <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), transparent, transparent);"></div>
                             <img src="<?php echo e(asset('images/insta.png')); ?>" alt="Instagram" class="w-5 h-5 object-contain relative z-10">
                         </a>
                     <?php endif; ?>
                     <?php if($settings->twitter): ?>
                         <a href="<?php echo e($settings->twitter); ?>" target="_blank" class="relative w-10 h-10 rounded-lg bg-gradient-to-br from-zinc-800/50 to-zinc-900/50 border border-zinc-600/30 hover:border-zinc-500/50 flex items-center justify-center transition-all duration-300 transform hover:scale-110 shadow-md overflow-hidden backdrop-blur-sm" title="X (Twitter)">
                             <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), transparent, transparent);"></div>
                             <img src="<?php echo e(asset('images/x.png')); ?>" alt="X (Twitter)" class="w-5 h-5 object-contain relative z-10">
                         </a>
                     <?php endif; ?>
                 </div>
            </div>
        <?php endif; ?>

        <!-- Mobile Provider Photos -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-white/90 mb-3 text-center">Oyun Sağlayıcıları</h3>
            <div class="relative overflow-hidden">
                <div class="flex items-center gap-4 animate-scroll-providers-mobile">
                    <?php
                        $providers = \App\Models\ProviderPhoto::active()->ordered()->get();
                    ?>
                    <?php if($providers->count() > 0): ?>
                        <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <?php if($provider->link): ?>
                                <a href="<?php echo e($provider->link); ?>" target="_blank">
                                    <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                                </a>
                            <?php else: ?>
                                <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- Duplicate for seamless loop -->
                        <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <?php if($provider->link): ?>
                                <a href="<?php echo e($provider->link); ?>" target="_blank">
                                    <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                                </a>
                            <?php else: ?>
                                <img alt="<?php echo e($provider->name); ?>" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset($provider->gorsel)); ?>"/>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Fallback provider logos for mobile -->
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="NetEnt" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/netent.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Microgaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/microgaming.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Playtech" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/playtech.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Evolution Gaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/evolution.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Pragmatic Play" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/pragmatic.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Betsoft" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/betsoft.webp')); ?>"/>
                        </div>
                        <!-- Duplicate fallback providers for seamless loop -->
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="NetEnt" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/netent.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Microgaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/microgaming.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Playtech" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/playtech.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Evolution Gaming" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/evolution.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Pragmatic Play" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/pragmatic.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-28 opacity-70 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Betsoft" loading="lazy" decoding="async" class="object-contain" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" sizes="100vw" src="<?php echo e(asset('assets/providers/betsoft.webp')); ?>"/>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile License Badges -->
        <div class="flex flex-col items-center gap-4 mb-8">
            <h3 class="text-sm font-semibold text-white/90 mb-3 text-center">Lisanslarımız</h3>
            <div class="flex gap-4 justify-center items-center">
                <a target="_blank" class="opacity-80 hover:opacity-100 transition-all duration-300" href="/license">
                    <img alt="Curacao" loading="lazy" width="120" height="60" decoding="async" class="rounded-sm h-12 w-auto shrink-0" style="color:transparent" src="<?php echo e(asset('assets/logo/gcb.webp')); ?>"/>
                </a>
                <a target="_blank" class="opacity-80 hover:opacity-100 transition-all duration-300" href="https://www.dmca.com">
                    <img alt="DMCA" loading="lazy" width="120" height="60" decoding="async" class="h-12 w-auto shrink-0" style="color:transparent" src="<?php echo e(asset('assets/logo/_dmca_premi_badge_2.png')); ?>"/>
                </a>
                <a target="_blank" class="opacity-80 hover:opacity-100 transition-all duration-300" href="https://www.gambleaware.org">
                    <img alt="GambleAware" loading="lazy" width="120" height="30" decoding="async" class="h-8 w-auto shrink-0" style="color:transparent" src="<?php echo e(asset('assets/logo/gambleaware.webp')); ?>"/>
                </a>
            </div>
        </div>

        <!-- Mobile Payment Methods -->
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-white/90 mb-3 text-center">Ödeme Yöntemleri</h3>
            <div class="relative overflow-hidden">
                <div class="flex items-center gap-4 animate-scroll-payments-mobile">
                    <?php if(isset($payments) && $payments->count() > 0): ?>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="<?php echo e($payment->name); ?>" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset($payment->gorsel)); ?>"/>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- Duplicate for seamless loop -->
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="<?php echo e($payment->name); ?>" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset($payment->gorsel)); ?>"/>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Fallback ödeme yöntemleri for mobile -->
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kredi Kartı" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kredi-karti.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Payco" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/payco.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Parazula" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/parazula.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Papara" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/papara.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kripto" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kripto.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Paypay" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/paypay.webp')); ?>"/>
                        </div>
                        <!-- Duplicate fallback payments for seamless loop -->
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kredi Kartı" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kredi-karti.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Payco" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/payco.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Parazula" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/parazula.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Papara" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/papara.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kripto" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kripto.webp')); ?>"/>
                        </div>
                        <div class="relative h-10 w-24 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Paypay" loading="lazy" width="96" height="32" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/paypay.webp')); ?>"/>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Payment Methods -->
    <div class="hidden md:block border-t border-zinc-800/60">
        <div class="container mx-auto lg:px-12 py-10">
            <div class="relative overflow-hidden">
                <div class="flex items-center gap-6 animate-scroll-payments">
                    <?php if(isset($payments) && $payments->count() > 0): ?>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="<?php echo e($payment->name); ?>" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset($payment->gorsel)); ?>"/>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- Duplicate for seamless loop -->
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="<?php echo e($payment->name); ?>" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset($payment->gorsel)); ?>"/>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Fallback ödeme yöntemleri -->
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kredi Kartı" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kredi-karti.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Payco" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/payco.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Parazula" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/parazula.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Papara" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/papara.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kripto" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kripto.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Paypay" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/paypay.webp')); ?>"/>
                        </div>
                        <!-- Duplicate fallback payments for seamless loop -->
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kredi Kartı" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kredi-karti.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Payco" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/payco.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Parazula" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/parazula.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Papara" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/papara.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Kripto" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/kripto.webp')); ?>"/>
                        </div>
                        <div class="relative h-12 w-32 opacity-90 hover:opacity-100 transition-all duration-300 flex-shrink-0">
                            <img alt="Paypay" loading="lazy" width="120" height="40" decoding="async" class="h-full w-auto object-contain" style="color:transparent" src="<?php echo e(asset('assets/payments/paypay.webp')); ?>"/>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="border-t border-zinc-800/60 bg-black/90">
        <div class="container mx-auto lg:px-12 py-6">
            <div class="flex flex-col items-center gap-2 text-zinc-400">
                <?php
                    $settings = \App\Models\Ayarlar::getSettings();
                ?>
                <?php if($settings && $settings->footer_desc): ?>
                    <div class="text-sm text-muted-foreground text-center">
                        <?php echo nl2br(e($settings->footer_desc)); ?>

                    </div>
                <?php else: ?>
                    <p class="text-sm text-muted-foreground text-center">© 2025 <?php echo e($settings->site_adi ?? 'BetNow'); ?>. Tüm hakları saklıdır.</p>
                    <p class="text-sm text-muted-foreground text-center"><?php echo e($settings->site_adi ?? 'BetNow'); ?>, Curacao eGaming tarafından lisanslanmıştır.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<style>
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}

/* Auto-scroll animations */
@keyframes scroll-providers {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@keyframes scroll-providers-mobile {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@keyframes scroll-payments {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@keyframes scroll-payments-mobile {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-scroll-providers {
    animation: scroll-providers 60s linear infinite;
}

.animate-scroll-providers:hover {
    animation-play-state: paused;
}

.animate-scroll-providers-mobile {
    animation: scroll-providers-mobile 45s linear infinite;
}

.animate-scroll-providers-mobile:hover {
    animation-play-state: paused;
}

.animate-scroll-payments {
    animation: scroll-payments 50s linear infinite;
}

.animate-scroll-payments:hover {
    animation-play-state: paused;
}

.animate-scroll-payments-mobile {
    animation: scroll-payments-mobile 40s linear infinite;
}

.animate-scroll-payments-mobile:hover {
    animation-play-state: paused;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Provider photos horizontal scroll
    const providerScroll = document.querySelector('.provider-scroll');
    if (providerScroll) {
        let isDown = false;
        let startX;
        let scrollLeft;

        providerScroll.addEventListener('mousedown', (e) => {
            isDown = true;
            providerScroll.style.cursor = 'grabbing';
            startX = e.pageX - providerScroll.offsetLeft;
            scrollLeft = providerScroll.scrollLeft;
        });

        providerScroll.addEventListener('mouseleave', () => {
            isDown = false;
            providerScroll.style.cursor = 'grab';
        });

        providerScroll.addEventListener('mouseup', () => {
            isDown = false;
            providerScroll.style.cursor = 'grab';
        });

        providerScroll.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - providerScroll.offsetLeft;
            const walk = (x - startX) * 2;
            providerScroll.scrollLeft = scrollLeft - walk;
        });

        // Mouse wheel horizontal scroll
        providerScroll.addEventListener('wheel', (e) => {
            e.preventDefault();
            providerScroll.scrollLeft += e.deltaY;
        });
    }

    // Payment methods horizontal scroll (existing code)
    const paymentScroll = document.querySelector('.payment-scroll');
    if (paymentScroll) {
        let isDown = false;
        let startX;
        let scrollLeft;

        paymentScroll.addEventListener('mousedown', (e) => {
            isDown = true;
            paymentScroll.style.cursor = 'grabbing';
            startX = e.pageX - paymentScroll.offsetLeft;
            scrollLeft = paymentScroll.scrollLeft;
        });

        paymentScroll.addEventListener('mouseleave', () => {
            isDown = false;
            paymentScroll.style.cursor = 'grab';
        });

        paymentScroll.addEventListener('mouseup', () => {
            isDown = false;
            paymentScroll.style.cursor = 'grab';
        });

        paymentScroll.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - paymentScroll.offsetLeft;
            const walk = (x - startX) * 2;
            paymentScroll.scrollLeft = scrollLeft - walk;
        });

        // Mouse wheel horizontal scroll
        paymentScroll.addEventListener('wheel', (e) => {
            e.preventDefault();
            paymentScroll.scrollLeft += e.deltaY;
        });
    }

    // Mobile Provider photos touch scroll
    const providerScrollMobile = document.querySelector('.provider-scroll-mobile');
    if (providerScrollMobile) {
        let isDown = false;
        let startX;
        let scrollLeft;

        providerScrollMobile.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - providerScrollMobile.offsetLeft;
            scrollLeft = providerScrollMobile.scrollLeft;
        });

        providerScrollMobile.addEventListener('touchend', () => {
            isDown = false;
        });

        providerScrollMobile.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.touches[0].pageX - providerScrollMobile.offsetLeft;
            const walk = (x - startX) * 2;
            providerScrollMobile.scrollLeft = scrollLeft - walk;
        });
    }

    // Mobile Payment methods touch scroll
    const paymentScrollMobile = document.querySelector('.payment-scroll-mobile');
    if (paymentScrollMobile) {
        let isDown = false;
        let startX;
        let scrollLeft;

        paymentScrollMobile.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - paymentScrollMobile.offsetLeft;
            scrollLeft = paymentScrollMobile.scrollLeft;
        });

        paymentScrollMobile.addEventListener('touchend', () => {
            isDown = false;
        });

        paymentScrollMobile.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.touches[0].pageX - paymentScrollMobile.offsetLeft;
            const walk = (x - startX) * 2;
            paymentScrollMobile.scrollLeft = scrollLeft - walk;
        });
    }
});
</script>
<?php /**PATH /var/www/vhosts/robinbet777.com/httpdocs/resources/views/layouts/footer.blade.php ENDPATH**/ ?>