

<?php $__env->startSection('title', 'Şifremi Unuttum'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-8 shadow-2xl">
            <div class="absolute inset-0 bg-[url('<?php echo e(asset('assets/noise.png')); ?>')] opacity-70 pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 via-transparent to-transparent pointer-events-none"></div>

            <div class="relative text-center mb-6">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-red-500 via-red-400 to-red-500 bg-clip-text text-transparent">Şifremi Unuttum</h2>
                <p class="text-zinc-400 mt-1">E-posta adresinizi girin, yeni şifrenizi gönderelim</p>
            </div>

            <?php if(session('status')): ?>
                <div class="mb-4 text-sm text-green-400 text-center"><?php echo e(session('status')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.forgot.post')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-300 mb-2">E-posta Adresi</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200"
                           placeholder="ornek@mail.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-400 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="w-full rounded-lg bg-red-600/80 hover:bg-red-600 text-white font-semibold py-3 transition-colors">Gönder</button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?php echo e(route('login')); ?>" class="text-sm text-zinc-400 hover:text-white">Giriş ekranına dön</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/auth/forgot.blade.php ENDPATH**/ ?>