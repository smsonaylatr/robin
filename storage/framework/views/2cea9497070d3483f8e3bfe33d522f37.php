<?php $__env->startSection('title', 'Kayıt Ol - ' . $settings->site_kelimeler); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-8 shadow-2xl">
            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-70"></div>
            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
            
            <div class="relative text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                        <div class="relative p-3 rounded-lg border" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border-color: rgba(235, 255, 0, 0.2);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="m22 21-3-3m0 0a5.5 5.5 0 1 0-7.78-7.78 5.5 5.5 0 0 0 7.78 7.78Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent mb-2" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">Kayıt Ol</h2>
                <p class="text-zinc-400">Sadece adınızı ve soyadınızı girin</p>
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
            
            <form method="POST" action="<?php echo e(route('register')); ?>" class="relative space-y-5" id="registration-form">
                <?php echo csrf_field(); ?>
                
                
                <div>
                    <label for="firstName" class="block text-sm font-medium text-zinc-300 mb-2">Ad *</label>
                    <input type="text" id="firstName" name="firstName" required 
                           value="<?php echo e(old('firstName')); ?>"
                           class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200"
                           style="caret-color: #ebff00;"
                           onfocus="this.style.borderColor='#ebff00'; this.style.boxShadow='0 0 0 1px #ebff00';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="Adınızı girin">
                </div>
                
                
                <div>
                    <label for="lastName" class="block text-sm font-medium text-zinc-300 mb-2">Soyad *</label>
                    <input type="text" id="lastName" name="lastName" required
                           value="<?php echo e(old('lastName')); ?>"
                           class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200"
                           style="caret-color: #ebff00;"
                           onfocus="this.style.borderColor='#ebff00'; this.style.boxShadow='0 0 0 1px #ebff00';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="Soyadınızı girin">
                </div>
                
                
                <div class="p-3 rounded-lg" style="background-color: rgba(235, 255, 0, 0.05); border: 1px solid rgba(235, 255, 0, 0.15);">
                    <div class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0" style="color: #ebff00;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        <p class="text-xs text-zinc-400">Kullanıcı adı ve şifreniz otomatik oluşturulacak ve size gösterilecektir.</p>
                    </div>
                </div>
                
                
                <button type="submit" class="w-full relative overflow-hidden rounded-lg py-3.5 px-6 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg group" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.25), rgba(235, 255, 0, 0.1), transparent); border: 1px solid rgba(235, 255, 0, 0.3);" onmouseover="this.style.borderColor='rgba(235, 255, 0, 0.5)'; this.style.boxShadow='0 10px 25px rgba(235, 255, 0, 0.2)';" onmouseout="this.style.borderColor='rgba(235, 255, 0, 0.3)'; this.style.boxShadow='none';">
                    <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-30"></div>
                    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), rgba(235, 255, 0, 0.05), transparent);"></div>
                    <div class="relative flex items-center justify-center gap-2">
                        <span class="text-white font-semibold text-base">Kayıt Ol</span>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="group-hover:translate-x-1 transition-transform duration-200" style="color: #ebff00;">
                            <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="m12 5 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </button>
            </form>
            
            <div class="relative mt-8 text-center">
                <p class="text-zinc-400">Zaten hesabınız var mı? 
                    <a href="<?php echo e(route('login')); ?>" class="transition-colors font-medium" style="color: #ebff00;">Giriş yapın</a>
                </p>
            </div>
            
            <div class="relative mt-8 pt-6 border-t border-zinc-800/50">
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/auth/register.blade.php ENDPATH**/ ?>