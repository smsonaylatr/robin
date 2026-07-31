<?php $__env->startSection('title', 'Giriş Yap - ' . $settings->site_kelimeler); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-8 shadow-2xl">
            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-70 pointer-events-none"></div>
            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
            
            <div class="relative text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                        <div class="relative p-3 rounded-lg border" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border-color: rgba(235, 255, 0, 0.2);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10,17 15,12 10,7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent mb-2" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">Giriş Yap</h2>
                <p class="text-zinc-400">Hesabınıza giriş yapın</p>
            </div>
            
            <?php if($errors->any()): ?>
            <div class="relative mb-6 p-4 rounded-lg" style="background-color: rgba(235, 255, 0, 0.1); border: 1px solid rgba(235, 255, 0, 0.2);">
                <div class="text-sm" style="color: #ebff00;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo e(route('login')); ?>" class="relative space-y-6">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label for="username" class="block text-sm font-medium text-zinc-300 mb-2">E-posta Adresiniz veya Kullanıcı Adınız</label>
                    <input type="text" id="username" name="username" value="<?php echo e(old('username')); ?>" required 
                           class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200" 
                           style="focus:border: #ebff00; focus:ring: 1px solid #ebff00;"
                           placeholder="E-posta adresiniz veya kullanıcı adınız">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-300 mb-2">Şifre</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required 
                           class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200" 
                           style="focus:border: #ebff00; focus:ring: 1px solid #ebff00;"
                           placeholder="Şifreniz">
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-zinc-700 bg-zinc-800/50 focus:ring-offset-0" style="color: #ebff00; focus:ring: #ebff00;">
                        <span class="ml-2 text-sm text-zinc-400">Beni hatırla</span>
                    </label>
                    <a href="<?php echo e(route('password.forgot')); ?>" class="text-sm transition-colors" style="color: #ebff00;" onmouseover="this.style.color='#ebff00'" onmouseout="this.style.color='#ebff00'">Şifremi unuttum</a>
                </div>
                
                <button type="submit" class="relative w-full overflow-hidden rounded-lg py-3 px-4 transition-all duration-300 transform hover:scale-105 hover:shadow-lg group" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.2), rgba(235, 255, 0, 0.1), transparent); border: 1px solid rgba(235, 255, 0, 0.3);" onmouseover="this.style.borderColor='rgba(235, 255, 0, 0.5)'; this.style.boxShadow='0 10px 25px rgba(235, 255, 0, 0.2)';" onmouseout="this.style.borderColor='rgba(235, 255, 0, 0.3)'; this.style.boxShadow='none';">
                    <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), rgba(235, 255, 0, 0.05), transparent);"></div>
                    <div class="relative flex items-center justify-center gap-2">
                        <span class="text-white font-semibold">Giriş Yap</span>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="group-hover:translate-x-1 transition-transform duration-200" style="color: #ebff00;">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="10,17 15,12 10,7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <line x1="15" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <p class="mt-4 text-center text-sm text-zinc-400">
                    Hesabınız yok mu?
                    <button onclick="window.location.href='/register'" style="position: relative; z-index: 9999; background: none; border: none; color: #ebff00; cursor: pointer; font-weight: 500;" class="transition-colors" onmouseover="this.style.color='#ebff00'" onmouseout="this.style.color='#ebff00'">Kayıt olun</button>
                </p>
            </div>
            
            <div class="mt-8 pt-6 border-t border-zinc-800/50">
                <div class="text-center">
                    <p class="text-sm text-zinc-500 mb-4">Hızlı erişim</p>
                    <div class="flex justify-center space-x-4">
                        <?php if($settings->telegram): ?>
                        <a href="<?php echo e($settings->telegram); ?>" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span class="text-sm">Telegram</span>
                        </a>
                        <?php endif; ?>
                        <?php if($settings->whatsapp): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $settings->whatsapp)); ?>" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 hover:bg-green-500/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span class="text-sm">WhatsApp</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet777.com/httpdocs/resources/views/auth/login.blade.php ENDPATH**/ ?>