<?php $__env->startSection('title', 'Bonuslar - BetNow'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen">
    <!-- Bonus Content -->
    <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <?php if($bonuses->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $user = auth('admin')->user();
                    $canClaim = true;
                    $disabledReason = '';
                    $isAlreadyClaimed = false;
                    $claimedDate = null;
                    
                    if ($user) {
                        // Bu bonus için claim kontrolü - yatırım bonusu hariç
                        $bonusClaim = $bonusClaims->get($bonus->id);
                        
                        if ($bonusClaim && $bonus->yatirim != '1') {
                            $isAlreadyClaimed = true;
                            $canClaim = false;
                            $claimedDate = $bonusClaim->claimed_at;
                            
                            if ($bonus->deneme == '1') {
                                $disabledReason = 'Bu deneme bonusunu ' . \Carbon\Carbon::parse($claimedDate)->format('d.m.Y H:i') . ' tarihinde almışsınız. Sadece 1 Kez Alınabilir.';
                            } elseif ($bonus->hosgeldin == '1') {
                                $disabledReason = 'Bu hoşgeldin bonusunu ' . \Carbon\Carbon::parse($claimedDate)->format('d.m.Y H:i') . ' tarihinde almışsınız. Sadece 1 Kez Alınabilir.';
                            } else {
                                $disabledReason = 'Bu bonusu ' . \Carbon\Carbon::parse($claimedDate)->format('d.m.Y H:i') . ' tarihinde almışsınız.';
                            }
                        } else {
                            // Hoşgeldin bonusu için genel kontrol (başka hoşgeldin bonusu alınmış mı?)
                            if ($bonus->hosgeldin == '1') {
                                $existingWelcomeClaim = \App\Models\BonusClaim::where('user_id', $user->id)
                                    ->whereHas('bonus', function($query) {
                                        $query->where('hosgeldin', '1');
                                    })
                                    ->first();
                                if ($existingWelcomeClaim) {
                                    $isAlreadyClaimed = true;
                                    $canClaim = false;
                                    $disabledReason = 'Başka bir hoşgeldin bonusu almışsınız. Hoşgeldin bonusu sadece 1 kez alınabilir.';
                                }
                            }
                            
                            // Hoşgeldin bonusu aldıktan sonra yatırım kontrolü (deneme bonusu hariç)
                            if ($bonus->deneme != '1' && !$isAlreadyClaimed) {
                                $hasWelcomeBonus = \App\Models\BonusClaim::where('user_id', $user->id)
                                    ->whereHas('bonus', function($query) {
                                        $query->where('hosgeldin', '1');
                                    })
                                    ->exists();
                                    
                                if ($hasWelcomeBonus) {
                                    // Hoşgeldin bonusu aldıktan sonra yatırım yapılmış mı kontrol et
                                    $welcomeClaim = \App\Models\BonusClaim::where('user_id', $user->id)
                                        ->whereHas('bonus', function($query) {
                                            $query->where('hosgeldin', '1');
                                        })
                                        ->first();
                                        
                                    $depositAfterWelcome = \App\Models\Parayatir::where('uye', $user->id)
                                        ->where('durum', 1)
                                        ->where('tarih', '>', $welcomeClaim->claimed_at)
                                        ->exists();
                                        
                                    if (!$depositAfterWelcome) {
                                        $canClaim = false;
                                        $disabledReason = 'Hoşgeldin bonusu aldıktan sonra yeni yatırım yapmanız gerekmektedir.';
                                    }
                                }
                            }
                            
                            // Yatırım bonusu kontrolü
                            if ($bonus->yatirim == '1' && !$isAlreadyClaimed && $canClaim) {
                                $recentDeposit = \App\Models\Parayatir::where('uye', $user->id)
                                    ->where('durum', 1)
                                    ->orderBy('tarih', 'desc')
                                    ->first();
                                    
                                if (!$recentDeposit) {
                                    $canClaim = false;
                                    $disabledReason = 'Yatırım bonusu alabilmek için önce yatırım yapmanız gerekmektedir.';
                                } else {
                                    // Son yatırımdan sonra oyun oynama kontrolü
                                    $gameActivityAfterLastDeposit = \App\Models\Transaction::where('user_id', $user->id)
                                        ->where('type', 'bet')
                                        ->where('created_at', '>', $recentDeposit->tarih)
                                        ->exists();
                                    
                                    // Son yatırımdan sonra bonus alma kontrolü (yatırım bonusu hariç)
                                    $bonusAfterLastDeposit = \App\Models\BonusClaim::where('user_id', $user->id)
                                        ->where('claimed_at', '>', $recentDeposit->tarih)
                                        ->whereHas('bonus', function($query) {
                                            $query->where('yatirim', '!=', '1');
                                        })
                                        ->exists();
                                    
                                    // Son yatırımdan sonra yatırım bonusu alınmış mı kontrol et
                                    $yatirimBonusAfterLastDeposit = \App\Models\BonusClaim::where('user_id', $user->id)
                                        ->where('claimed_at', '>', $recentDeposit->tarih)
                                        ->whereHas('bonus', function($query) {
                                            $query->where('yatirim', '1');
                                        })
                                        ->exists();
                                    
                                    if ($gameActivityAfterLastDeposit) {
                                        $canClaim = false;
                                        $disabledReason = 'Son yatırımınızdan sonra oyun oynadığınız için yatırım bonusu alamazsınız.';
                                    } elseif ($bonusAfterLastDeposit) {
                                        $canClaim = false;
                                        $disabledReason = 'Son yatırımınızdan sonra başka bonus aldığınız için yatırım bonusu alamazsınız.';
                                    } elseif ($yatirimBonusAfterLastDeposit) {
                                        $canClaim = false;
                                        $disabledReason = 'Bu yatırım için yatırım bonusunu zaten almışsınız.';
                                    }
                                }
                            }
                            
                            // Kayıp bonusu kontrolü
                            if ($bonus->kayip == '1' && !$isAlreadyClaimed && $canClaim) {
                                if ($user->bakiye >= 5) {
                                    $canClaim = false;
                                    $disabledReason = 'Kayıp bonusu alabilmek için bakiyenizin 5 TL altında olması gerekmektedir.';
                                } else {
                                    // Son yatırım kontrolü
                                    $lastDeposit = \App\Models\Parayatir::where('uye', $user->id)
                                        ->where('durum', 1)
                                        ->orderBy('tarih', 'desc')
                                        ->first();
                                        
                                    if (!$lastDeposit) {
                                        $canClaim = false;
                                        $disabledReason = 'Kayıp bonusu alabilmek için önce yatırım yapmanız gerekmektedir.';
                                    } else {
                                        // Son yatırımdan sonra bonus almış mı? (kayıp bonusu hariç)
                                        $bonusAfterLastDeposit = \App\Models\BonusClaim::where('user_id', $user->id)
                                            ->where('claimed_at', '>=', $lastDeposit->tarih)
                                            ->whereHas('bonus', function($query) {
                                                $query->where('kayip', '!=', '1');
                                            })
                                            ->exists();
                                            
                                        if ($bonusAfterLastDeposit) {
                                            $canClaim = false;
                                            $disabledReason = 'Son yatırımınızdan sonra bonus aldığınız için kayıp bonusu alamazsınız.';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $canClaim = false;
                        $disabledReason = 'Bonus almak için giriş yapmanız gerekmektedir.';
                    }
                ?>
                
                <div class="group bg-zinc-900/50 backdrop-blur-sm rounded-2xl border border-zinc-800/50 hover:border-red-500/50 transition-all duration-500 hover:transform hover:scale-[1.02] hover:shadow-2xl hover:shadow-red-500/10 overflow-hidden">
                    <!-- Bonus Image -->
                    <div class="relative h-48 overflow-hidden bg-zinc-800/30">
                        <?php if($bonus->bonus_image): ?>
                            <img src="<?php echo e($bonus->bonus_image); ?>" alt="<?php echo e($bonus->bonus_name); ?>" 
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-red-500/20 to-zinc-800/50 flex items-center justify-center">
                                <svg class="w-16 h-16 text-red-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <?php if($bonus->aktif == '1'): ?>
                                <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold shadow-lg">
                                    AKTİF
                                </span>
                            <?php else: ?>
                                <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-semibold shadow-lg">
                                    PASİF
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Bonus Type Badges -->
                        <div class="absolute top-4 left-4 flex flex-wrap gap-1">
                            <?php if($bonus->deneme == '1'): ?>
                                <span class="bg-amber-500/90 text-black text-xs px-2 py-1 rounded-full font-semibold">
                                    Deneme
                                </span>
                            <?php endif; ?>
                            <?php if($bonus->hosgeldin == '1'): ?>
                                <span class="bg-green-500/90 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Hoşgeldin
                                </span>
                            <?php endif; ?>
                            <?php if($bonus->yatirim == '1'): ?>
                                <span class="bg-blue-500/90 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Yatırım
                                </span>
                            <?php endif; ?>
                            <?php if($bonus->kayip == '1'): ?>
                                <span class="bg-purple-500/90 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Kayıp
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Bonus Title -->
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-red-400 transition-colors">
                            <?php echo e($bonus->bonus_name); ?>

                        </h3>

                        <!-- Bonus Amount/Percentage -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <?php if($bonus->bonus_amount > 0): ?>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-400">
                                            <?php echo e(number_format($bonus->bonus_amount, 0)); ?> TL
                                        </p>
                                        <p class="text-xs text-zinc-400">Bonus Tutarı</p>
                                    </div>
                                <?php endif; ?>
                                <?php if($bonus->yuzde > 0): ?>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-yellow-400">
                                            %<?php echo e($bonus->yuzde); ?>

                                        </p>
                                        <p class="text-xs text-zinc-400">Bonus Oranı</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Bonus Requirements -->
                        <?php if($bonus->cevrim > 0 || $bonus->maxtutar > 0 || $bonus->altlimit > 0): ?>
                        <div class="bg-zinc-800/30 rounded-lg p-3 mb-4">
                            <h4 class="text-sm font-semibold text-zinc-300 mb-2">Bonus Şartları:</h4>
                            <div class="space-y-1 text-xs text-zinc-400">
                                <?php if($bonus->cevrim > 0): ?>
                                    <div class="flex justify-between">
                                        <span>Çevirme Şartı:</span>
                                        <span class="text-white font-medium"><?php echo e($bonus->cevrim); ?>x</span>
                                    </div>
                                <?php endif; ?>
                                <?php if($bonus->maxtutar > 0): ?>
                                    <div class="flex justify-between">
                                        <span>Maksimum Tutar:</span>
                                        <span class="text-white font-medium"><?php echo e(number_format($bonus->maxtutar, 0)); ?> TL</span>
                                    </div>
                                <?php endif; ?>
                                <?php if($bonus->altlimit > 0): ?>
                                    <div class="flex justify-between">
                                        <span>Minimum Yatırım:</span>
                                        <span class="text-white font-medium"><?php echo e(number_format($bonus->altlimit, 0)); ?> TL</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            <button onclick="showBonusDetails(<?php echo e($bonus->id); ?>)" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white px-3 py-1.5 rounded-lg transition-colors border border-zinc-700 hover:border-zinc-600 flex items-center justify-center gap-2 text-sm" title="Bonus Detayları">
                                <i class="fas fa-info-circle"></i> Detaylar
                            </button>
                            
                            <?php if($bonus->aktif == '1'): ?>
                                <?php if(auth()->guard('admin')->check()): ?>
                                    <?php if($isAlreadyClaimed): ?>
                                        <button disabled class="flex-1 bg-zinc-700 text-zinc-400 px-3 py-1.5 rounded-lg font-semibold cursor-not-allowed opacity-50 text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-check"></i> Alındı
                                        </button>
                                    <?php elseif($canClaim): ?>
                                        <button onclick="claimBonus(<?php echo e($bonus->id); ?>, event)" class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-1.5 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-gift"></i> Bonusu Al
                                        </button>
                                    <?php else: ?>
                                        <button onclick="showDisabledReason('<?php echo e($disabledReason); ?>')" class="flex-1 bg-zinc-700 hover:bg-zinc-600 text-zinc-300 px-3 py-1.5 rounded-lg font-semibold transition-colors text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-lock"></i> Bonusu Al
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button onclick="showDisabledReason('Bonus almak için giriş yapmanız gerekmektedir.')" class="flex-1 bg-zinc-700 hover:bg-zinc-600 text-zinc-300 px-3 py-1.5 rounded-lg font-semibold transition-colors text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-user-lock"></i> Bonusu Al
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button disabled class="flex-1 bg-zinc-600 text-zinc-400 px-3 py-1.5 rounded-lg font-semibold cursor-not-allowed text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-ban"></i> Aktif Değil
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-zinc-800/50 rounded-full mb-6">
                    <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-white mb-4">Henüz Bonus Yok</h3>
                <p class="text-zinc-400 text-lg mb-8 max-w-md mx-auto">
                    Şu anda aktif bonus bulunmuyor. Yeni bonuslar için takipte kalın!
                </p>
                <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition-colors">
                    Ana Sayfaya Dön
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Bonus Info Section -->
    <section class="py-12 px-4 bg-zinc-900/30">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Bonus Hakkında Bilgiler</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-zinc-800/50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Kolay Talep</h3>
                    <p class="text-zinc-400 text-sm">Bonuslarınızı tek tıkla kolayca talep edin</p>
                </div>
                <div class="bg-zinc-800/50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Hızlı İşlem</h3>
                    <p class="text-zinc-400 text-sm">Bonuslarınız anında hesabınıza tanımlanır</p>
                </div>
                <div class="bg-zinc-800/50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Güvenli</h3>
                    <p class="text-zinc-400 text-sm">Tüm bonuslar güvenli şekilde işlenir</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Bonus Detail Modal -->
<div id="bonusModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-zinc-900 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-zinc-800">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-zinc-900 border-b border-zinc-800 p-6 flex items-center justify-between">
            <h2 id="modalTitle" class="text-2xl font-bold text-white">Bonus Detayları</h2>
            <button onclick="closeBonusModal()" class="text-zinc-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6">
            <div id="modalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Bonus data for modal
const bonusData = {
    <?php $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo e($bonus->id); ?>: {
        name: <?php echo json_encode($bonus->bonus_name, 15, 512) ?>,
        image: <?php echo json_encode($bonus->bonus_image, 15, 512) ?>,
        description: <?php echo json_encode($bonus->bonus_description, 15, 512) ?>,
        amount: <?php echo e($bonus->bonus_amount); ?>,
        percentage: <?php echo e($bonus->yuzde); ?>,
        maxAmount: <?php echo e($bonus->maxtutar); ?>,
        minDeposit: <?php echo e($bonus->altlimit); ?>,
        wagering: <?php echo e($bonus->cevrim); ?>,
        isDemo: <?php echo e($bonus->deneme); ?>,
        isWelcome: <?php echo e($bonus->hosgeldin); ?>,
        isDeposit: <?php echo e($bonus->yatirim); ?>,
        isLoss: <?php echo e($bonus->kayip); ?>,
        isActive: <?php echo e($bonus->aktif); ?>

    },
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
};

// Toast notification sistemi
function showToast(message, type = 'success') {
    // Mevcut toast'ları temizle
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-4 right-4 z-[9999] max-w-sm w-full bg-zinc-900 border rounded-lg shadow-lg transform translate-x-full transition-all duration-300 ease-in-out`;
    
    let bgColor, iconColor, icon;
    
    switch(type) {
        case 'success':
            bgColor = 'border-green-500';
            iconColor = 'text-green-400';
            icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
            break;
        case 'warning':
            bgColor = 'border-yellow-500';
            iconColor = 'text-yellow-400';
            icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>';
            break;
        default:
            bgColor = 'border-red-500';
            iconColor = 'text-red-400';
            icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    }
    
    toast.classList.add(bgColor);
    
    toast.innerHTML = `
        <div class="flex items-center p-4">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-white">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animasyon için kısa gecikme
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // 5 saniye sonra otomatik kapat
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Bonus alma fonksiyonu
function claimBonus(bonusId, event) {
    event.stopPropagation(); // Modal açılmasını engelle
    
    // Butonu devre dışı bırak
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> İşleniyor...';
    
    // AJAX isteği
    fetch('/claim-bonus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            bonus_id: bonusId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Modern toast notification göster
            showToast(`🎉 Bonus başarıyla alındı! ${data.bonusAmount} TL hesabınıza eklendi.`, 'success');
            
            // Butonu değiştir
            button.innerHTML = '<i class="fas fa-check mr-2"></i> Alındı';
            button.classList.remove('bg-gradient-to-r', 'from-red-500', 'to-red-600', 'hover:from-red-600', 'hover:to-red-700', 'transform', 'hover:scale-105');
            button.classList.add('bg-zinc-700', 'text-zinc-400', 'cursor-not-allowed', 'opacity-50');
            
            // Sayfayı yenile
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            // Hata toast'ı göster
            showToast(data.message, 'error');
            
            // Butonu eski haline getir
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
        
        // Butonu eski haline getir
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Devre dışı bonus sebebini göster
function showDisabledReason(reason) {
    showToast(reason, 'warning');
}

// Bonus detaylarını göster
function showBonusDetails(bonusId) {
    const bonus = bonusData[bonusId];
    if (!bonus) return;
    
    document.getElementById('modalTitle').textContent = bonus.name;
    
    let bonusTypes = [];
    if (bonus.isDemo) bonusTypes.push('<span class="bg-amber-500/90 text-black text-xs px-2 py-1 rounded-full font-semibold">Deneme</span>');
    if (bonus.isWelcome) bonusTypes.push('<span class="bg-green-500/90 text-white text-xs px-2 py-1 rounded-full font-semibold">Hoşgeldin</span>');
    if (bonus.isDeposit) bonusTypes.push('<span class="bg-blue-500/90 text-white text-xs px-2 py-1 rounded-full font-semibold">Yatırım</span>');
    if (bonus.isLoss) bonusTypes.push('<span class="bg-purple-500/90 text-white text-xs px-2 py-1 rounded-full font-semibold">Kayıp</span>');
    
    const modalContent = `
        <div class="space-y-6">
            <!-- Bonus Image -->
            ${bonus.image ? `
                <div class="relative h-64 rounded-xl overflow-hidden bg-zinc-800/30">
                    <img src="${bonus.image}" alt="${bonus.name}" class="w-full h-full object-contain">
                    <div class="absolute top-4 right-4">
                        <span class="bg-${bonus.isActive ? 'green' : 'red'}-500 text-white text-sm px-3 py-1 rounded-full font-semibold shadow-lg">
                            ${bonus.isActive ? 'AKTİF' : 'PASİF'}
                        </span>
                    </div>
                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                        ${bonusTypes.join('')}
                    </div>
                </div>
            ` : ''}
            
            <!-- Bonus Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bonus Amounts -->
                <div class="bg-zinc-800/30 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Bonus Bilgileri</h3>
                    <div class="space-y-3">
                        ${bonus.amount > 0 ? `
                            <div class="flex justify-between items-center">
                                <span class="text-zinc-400">Bonus Tutarı:</span>
                                <span class="text-green-400 font-bold text-lg">${bonus.amount.toLocaleString()} TL</span>
                            </div>
                        ` : ''}
                        ${bonus.percentage > 0 ? `
                            <div class="flex justify-between items-center">
                                <span class="text-zinc-400">Bonus Oranı:</span>
                                <span class="text-yellow-400 font-bold text-lg">%${bonus.percentage}</span>
                            </div>
                        ` : ''}
                        ${bonus.maxAmount > 0 ? `
                            <div class="flex justify-between items-center">
                                <span class="text-zinc-400">Maksimum Tutar:</span>
                                <span class="text-white font-medium">${bonus.maxAmount.toLocaleString()} TL</span>
                            </div>
                        ` : ''}
                        ${bonus.minDeposit > 0 ? `
                            <div class="flex justify-between items-center">
                                <span class="text-zinc-400">Minimum Yatırım:</span>
                                <span class="text-white font-medium">${bonus.minDeposit.toLocaleString()} TL</span>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <!-- Bonus Requirements -->
                <div class="bg-zinc-800/30 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Bonus Şartları</h3>
                    <div class="space-y-3">
                        ${bonus.wagering > 0 ? `
                            <div class="flex justify-between items-center">
                                <span class="text-zinc-400">Çevirme Şartı:</span>
                                <span class="text-red-400 font-bold text-lg">${bonus.wagering}x</span>
                            </div>
                        ` : ''}
                        <div class="flex justify-between items-center">
                            <span class="text-zinc-400">Durum:</span>
                            <span class="text-${bonus.isActive ? 'green' : 'red'}-400 font-medium">
                                ${bonus.isActive ? 'Aktif' : 'Pasif'}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-zinc-400">Bonus Türü:</span>
                            <div class="flex gap-1">
                                ${bonusTypes.join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bonus Description -->
            ${bonus.description ? `
                <div class="bg-zinc-800/30 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Bonus Kuralları ve Şartları</h3>
                    <div class="text-zinc-300 leading-relaxed whitespace-pre-line">
                        ${bonus.description}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = modalContent;
    document.getElementById('bonusModal').classList.remove('hidden');
    document.getElementById('bonusModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeBonusModal() {
    document.getElementById('bonusModal').classList.add('hidden');
    document.getElementById('bonusModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('bonusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBonusModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBonusModal();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet777.com/httpdocs/resources/views/bonus.blade.php ENDPATH**/ ?>