<?php $__env->startSection('title', 'Aktif Bonuslarım - ' . ($settings->site_adi ?? 'BetNow')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Aktif Bonuslarım</h1>
        <p class="text-zinc-400">Aktif bonus durumlarınız</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Aktif Bonus</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($latestBonusClaim ? '1' : '0'); ?></p>
                </div>
                <span class="text-2xl">🎁</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Bonus Tutarı</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($user->parabirimi); ?><?php echo e($latestBonusClaim ? number_format($latestBonusClaim->bonus_amount, 2) : '0.00'); ?></p>
                </div>
                <span class="text-2xl">💰</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">İlerleme</p>
                    <p class="text-2xl font-bold text-white"><?php echo e(number_format($bonusProgress, 1)); ?>%</p>
                </div>
                <span class="text-2xl">📊</span>
            </div>
        </div>
    </div>

    <!-- Active Bonus -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        <?php if($latestBonusClaim): ?>
            <div class="bg-zinc-800/30 rounded-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-yellow-500/20 rounded-full flex items-center justify-center">
                            <span class="text-xl">🎁</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white"><?php echo e($latestBonusClaim->bonus_name ?? 'Bonus'); ?></h3>
                            <p class="text-zinc-400 text-sm"><?php echo e($latestBonusClaim->bonus->description ?? 'Son alınan bonus'); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-sm rounded-full bg-green-500/20 text-green-400">
                            Aktif
                        </span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-zinc-400 mb-2">
                        <span>Çevrim İlerlemesi</span>
                        <span><?php echo e(number_format($bonusProgress, 1)); ?>%</span>
                    </div>
                    <div class="w-full bg-zinc-700 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" style="width: <?php echo e(min($bonusProgress, 100)); ?>%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-zinc-500 mt-1">
                        <span><?php echo e($user->parabirimi); ?><?php echo e(number_format($totalBetsAfterBonus, 2)); ?> oynandı</span>
                        <span><?php echo e($user->parabirimi); ?><?php echo e(number_format($requiredWager, 2)); ?> gerekli</span>
                    </div>
                </div>

                <!-- Bonus Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-zinc-800/50 rounded-lg p-3">
                        <p class="text-zinc-400 text-sm">Bonus Tutarı</p>
                        <p class="text-white font-medium"><?php echo e($user->parabirimi); ?><?php echo e(number_format($latestBonusClaim->bonus_amount, 2)); ?></p>
                    </div>
                    <div class="bg-zinc-800/50 rounded-lg p-3">
                        <p class="text-zinc-400 text-sm">Talep Tarihi</p>
                        <p class="text-white font-medium"><?php echo e(\Carbon\Carbon::parse($latestBonusClaim->claimed_at)->format('d.m.Y H:i')); ?></p>
                    </div>
                    <div class="bg-zinc-800/50 rounded-lg p-3">
                        <p class="text-zinc-400 text-sm">Çevrim Katı</p>
                        <p class="text-white font-medium"><?php echo e($latestBonusClaim->bonus->cevrim ?? 0); ?>x</p>
                    </div>
                    <div class="bg-zinc-800/50 rounded-lg p-3">
                        <p class="text-zinc-400 text-sm">Toplam Çevrim</p>
                        <p class="text-white font-medium"><?php echo e($user->parabirimi); ?><?php echo e(number_format($requiredWager, 2)); ?></p>
                    </div>
                </div>

                <!-- Requirements -->
                <?php if($latestBonusClaim->bonus): ?>
                <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-4">
                    <h4 class="text-white font-medium mb-2">Bonus Detayları</h4>
                    <ul class="space-y-1 text-sm text-zinc-300">
                        <li>• Çevirme şartı: <?php echo e(number_format($latestBonusClaim->bonus->cevrim, 0)); ?>x (<?php echo e($user->parabirimi); ?><?php echo e(number_format($latestBonusClaim->bonus_amount * $latestBonusClaim->bonus->cevrim, 2)); ?>)</li>
                        <?php if($latestBonusClaim->bonus->maxtutar): ?>
                            <li>• Maksimum tutar: <?php echo e($user->parabirimi); ?><?php echo e(number_format($latestBonusClaim->bonus->maxtutar, 2)); ?></li>
                        <?php endif; ?>
                        <?php if($latestBonusClaim->bonus->yuzde): ?>
                            <li>• Bonus yüzdesi: %<?php echo e($latestBonusClaim->bonus->yuzde); ?></li>
                        <?php endif; ?>
                        <li>• Bonus tarihinden sonra oynanan: <?php echo e($user->parabirimi); ?><?php echo e(number_format($totalBetsAfterBonus, 2)); ?></li>
                        <li>• Kalan çevrim: <?php echo e($user->parabirimi); ?><?php echo e(number_format(max(0, $requiredWager - $totalBetsAfterBonus), 2)); ?></li>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Status -->
                <div class="mt-4 text-center">
                    <?php if($bonusProgress >= 100): ?>
                        <p class="text-green-400 text-sm">
                            ✅ Çevrim şartı tamamlandı!
                        </p>
                    <?php else: ?>
                        <p class="text-yellow-400 text-sm">
                            ⏳ Çevrim şartı devam ediyor (%<?php echo e(number_format($bonusProgress, 1)); ?>)
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Cancel Bonus Button -->
                <div class="mt-4 text-center">
                    <button onclick="showCancelMessage()" class="px-4 py-2 bg-red-600/20 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-600/30 transition-colors text-sm">
                        Bonusu İptal Et
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🎁</span>
                </div>
                <p class="text-zinc-400 text-lg mb-2">Aktif bonusunuz bulunmuyor</p>
                <p class="text-zinc-500 text-sm">Bonus almak için para yatırın veya promosyon kodları kullanın</p>
                <div class="mt-4 space-x-4">
                    <a href="<?php echo e(route('para-yatir')); ?>" class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Para Yatır
                    </a>
                    <a href="<?php echo e(route('bonus')); ?>" class="inline-block px-6 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg transition-colors">
                        Bonusları Gör
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bonus History -->
    <?php if($latestBonusClaim): ?>
    <div class="mt-8 bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Bonus Geçmişi</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-white font-medium">Tarih</th>
                        <th class="text-left py-3 px-4 text-white font-medium">Bonus</th>
                        <th class="text-left py-3 px-4 text-white font-medium">Durum</th>
                        <th class="text-right py-3 px-4 text-white font-medium">Tutar</th>
                        <th class="text-right py-3 px-4 text-white font-medium">İlerleme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $allBonusClaims = \App\Models\BonusClaim::where('user_id', $user->id)
                            ->with('bonus')
                            ->orderBy('claimed_at', 'desc')
                            ->limit(10)
                            ->get();
                    ?>
                    
                    <?php $__currentLoopData = $allBonusClaims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $claim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $claimBetsAfterBonus = \App\Models\Transaction::where('user_id', $user->id)
                            ->where('type', 'bet')
                            ->where('created_at', '>', $claim->claimed_at)
                            ->sum('amount');
                        $claimRequiredWager = $claim->bonus_amount * ($claim->bonus->cevrim ?? 1);
                        $claimProgress = $claimRequiredWager > 0 ? min(($claimBetsAfterBonus / $claimRequiredWager) * 100, 100) : 0;
                        
                        // Sadece ilk bonus (en son alınan) aktif, diğerleri iptal edildi
                        $isLatestBonus = ($index === 0);
                    ?>
                    <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition-colors">
                        <td class="py-3 px-4 text-zinc-300 text-sm">
                            <?php echo e(\Carbon\Carbon::parse($claim->claimed_at)->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="py-3 px-4 text-white text-sm">
                            <?php echo e($claim->bonus_name); ?>

                        </td>
                        <td class="py-3 px-4">
                            <?php if($isLatestBonus): ?>
                                <?php if($claimProgress >= 100): ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                                        Tamamlandı
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">
                                        Devam Ediyor
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if($claimProgress >= 100): ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                                        Tamamlandı
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">
                                        İptal Edildi
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right text-white font-medium">
                            <?php echo e($user->parabirimi); ?><?php echo e(number_format($claim->bonus_amount, 2)); ?>

                        </td>
                        <td class="py-3 px-4 text-right text-zinc-300 text-sm">
                            <?php if($isLatestBonus || $claimProgress >= 100): ?>
                                <?php echo e(number_format($claimProgress, 1)); ?>%
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function showCancelMessage() {
    // Modern modal oluştur
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-zinc-900 border border-zinc-700 rounded-lg p-6 max-w-md mx-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">⚠️</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Bonus İptal Bilgisi</h3>
                <p class="text-zinc-300 mb-6">Yatırım Yaptığınızda Bonusunuz Otomatik Olarak İptal Edilecektir</p>
                <button onclick="closeModal()" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                    Anladım
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    // Modal dışına tıklayınca kapat
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
}

function closeModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) {
        modal.remove();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/aktif-bonuslarim.blade.php ENDPATH**/ ?>