

<?php $__env->startSection('title', '404 - Sayfa Bulunamadı'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-2xl mx-auto">
        <!-- 404 Number with Animation -->
        <div class="mb-8">
            <h1 class="text-9xl md:text-[12rem] font-bold text-red-500/20 mb-4 floating">
                404
            </h1>
        </div>

        <!-- Main Content -->
        <div class="glass-effect rounded-2xl p-8 md:p-12 mb-8">
            <div class="mb-6">
                <div class="w-24 h-24 mx-auto mb-6 bg-red-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Sayfa Bulunamadı
                </h2>
                
                <p class="text-lg text-gray-400 mb-8 leading-relaxed">
                    Aradığınız sayfa mevcut değil veya taşınmış olabilir. 
                    <br class="hidden md:block">
                    Ana sayfaya dönerek yeni oyunlar keşfedebilirsiniz.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('home')); ?>" class="casino-gradient text-black font-bold py-3 px-8 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ana Sayfaya Dön
                </a>
                
                <a href="<?php echo e(route('casino')); ?>" class="bg-zinc-800/60 hover:bg-zinc-700/60 text-white font-bold py-3 px-8 rounded-lg border border-zinc-700 hover:border-red-500/50 transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Casino Oyunları
                </a>
            </div>
        </div>

        <!-- Popular Games Section -->
        <div class="glass-effect rounded-2xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Popüler Oyunlar</h3>
            <div style="display: inline-flex;" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?php echo e(route('casino')); ?>" class="group">
                    <div class="bg-zinc-800/50 rounded-lg p-4 hover:bg-zinc-700/50 transition-all duration-300 transform hover:scale-105">
                        <div class="w-12 h-12 mx-auto mb-3 bg-red-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-white group-hover:text-red-400 transition-colors">Casino Oyunları</p>
                    </div>
                </a>
                
                <a href="<?php echo e(route('live-casino')); ?>" class="group">
                    <div class="bg-zinc-800/50 rounded-lg p-4 hover:bg-zinc-700/50 transition-all duration-300 transform hover:scale-105">
                        <div class="w-12 h-12 mx-auto mb-3 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-white group-hover:text-blue-400 transition-colors">Canlı Casino</p>
                    </div>
                </a>
                
                <a href="<?php echo e(route('sports')); ?>" class="group">
                    <div class="bg-zinc-800/50 rounded-lg p-4 hover:bg-zinc-700/50 transition-all duration-300 transform hover:scale-105">
                        <div class="w-12 h-12 mx-auto mb-3 bg-green-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-white group-hover:text-green-400 transition-colors">Spor Bahisleri</p>
                    </div>
                </a>
                
                <!-- Live betting removed -->
            </div>
        </div>
    </div>
</div>

<!-- Background Animation -->
<div class="fixed inset-0 pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-red-500/5 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute top-3/4 right-1/4 w-40 h-40 bg-yellow-500/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
    <div class="absolute bottom-1/4 left-1/3 w-24 h-24 bg-blue-500/5 rounded-full blur-3xl animate-pulse delay-2000"></div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.floating {
    animation: float 6s ease-in-out infinite;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.casino-gradient {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/404.blade.php ENDPATH**/ ?>