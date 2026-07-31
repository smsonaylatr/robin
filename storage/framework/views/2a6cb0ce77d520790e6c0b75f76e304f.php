<?php $__env->startSection('title', 'Para Çek - ' . ($settings->site_adi ?? 'BetNow')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Para Çek</h1>
        <p class="text-zinc-400">Güvenli para çekme işlemleri</p>
    </div>

    <!-- User Balance -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6 mb-8">
        <div class="text-center">
            <p class="text-zinc-400 text-sm mb-2">Mevcut Bakiye</p>
            <p class="text-3xl font-bold text-white"><?php echo e($user->parabirimi ?? 'TL'); ?> <?php echo e(number_format($bakiye, 2)); ?></p>
            <?php if($cevrim > 0): ?>
                <div class="mt-3 px-4 py-2 bg-red-500/20 border border-red-500/30 rounded-lg">
                    <p class="text-red-400 text-sm">Kalan Çevrim: <?php echo e(number_format($cevrim, 2)); ?> TL</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Success / Error Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-4 mb-6">
            <p class="text-green-400 text-center font-medium"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-900/20 border border-red-500/30 rounded-lg p-4 mb-6">
            <p class="text-red-400 text-center font-medium"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>
    <?php if($errorMessage): ?>
        <div class="bg-red-900/20 border border-red-500/30 rounded-lg p-4 mb-6">
            <p class="text-red-400 text-center font-medium"><?php echo e($errorMessage); ?></p>
        </div>
    <?php endif; ?>

    <?php
    // Form devre dışı bırakma kontrolü
    $disableAttr = $disableForm ? 'disabled' : '';
    $disableClass = $disableForm ? 'opacity-50 cursor-not-allowed' : '';
    ?>

    <div class="max-w-4xl mx-auto space-y-4">

        <!-- HAVALE 
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
            <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('havale-content')">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset('images/payment_18762269_e4ae2e87fba5d9361d0b045460e6847a.png')); ?>" 
                         alt="Havale/EFT" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                    <div>
                        <h3 class="text-white font-semibold">Havale/EFT ile Para Çekme</h3>
                        <p class="text-zinc-400 text-sm">Banka hesabınıza güvenli transfer</p>
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="havale-icon">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="hidden border-t border-zinc-800" id="havale-content">
                <div class="p-6 <?php echo e($disableClass); ?>">
                    <form method="post" action="<?php echo e(route('para-cek.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="withdraw_method" value="HAVALE" <?php echo e($disableAttr); ?>>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Çekmek İstediğiniz Tutar (TL)</label>
                                <input type="number" name="miktar" min="50" step="50" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Banka Adı</label>
                                <select name="banka_adi" required <?php echo e($disableAttr); ?>

                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value="">Banka Seçiniz</option>
                                    <option value="Ziraat Bankası">Ziraat Bankası</option>
                                    <option value="Vakıfbank">Vakıfbank</option>
                                    <option value="Halkbank">Halkbank</option>
                                    <option value="İş Bankası">İş Bankası</option>
                                    <option value="Garanti Bankası">Garanti Bankası</option>
                                    <option value="Akbank">Akbank</option>
                                    <option value="Yapı Kredi">Yapı Kredi</option>
                                    <option value="QNB Finansbank">QNB Finansbank</option>
                                    <option value="TEB Bankası">TEB Bankası</option>
                                    <option value="Denizbank">Denizbank</option>
                                    <option value="ING Bank">ING Bank</option>
                                    <option value="Şekerbank">Şekerbank</option>
                                    <option value="Fibabank">Fibabank</option>
                                    <option value="Albaraka">Albaraka</option>
                                    <option value="Enpara">Enpara</option>
                                    <option value="Kuveyttürk Bankası">Kuveyttürk Bankası</option>
                                    <option value="OdeaBank">OdeaBank</option>
                                    <option value="Türkiye Finans">Türkiye Finans</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">IBAN / Hesap No</label>
                                <input type="text" name="hesap_no" placeholder="TR00 0000 0000... veya Hesap No" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <button type="submit" <?php echo e($disableAttr); ?>

                                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Talep Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- PAPARA 
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
            <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('papara-content')">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset('images/payment_18762269_a6a81e5411b2cb9f4e66554bed8f1ef3.png')); ?>" 
                         alt="Papara" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                    <div>
                        <h3 class="text-white font-semibold">Papara ile Para Çekme</h3>
                        <p class="text-zinc-400 text-sm">Hızlı ve güvenli Papara transferi</p>
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="papara-icon">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="hidden border-t border-zinc-800" id="papara-content">
                <div class="p-6 <?php echo e($disableClass); ?>">
                    <form method="post" action="<?php echo e(route('para-cek.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="withdraw_method" value="PAPARA" <?php echo e($disableAttr); ?>>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Çekmek İstediğiniz Tutar (TL)</label>
                                <input type="number" name="miktar" min="50" step="50" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Papara Numarası</label>
                                <input type="text" name="hesap_no" placeholder="Papara ID" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <button type="submit" <?php echo e($disableAttr); ?>

                                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Talep Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MEFETE 
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
            <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('mefete-content')">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset('images/payment_18762269_612c547f42cee2197a86693f8afbe7a6.png')); ?>" 
                         alt="Mefete" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                    <div>
                        <h3 class="text-white font-semibold">Mefete ile Para Çekme</h3>
                        <p class="text-zinc-400 text-sm">Mefete hesabınıza transfer</p>
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="mefete-icon">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="hidden border-t border-zinc-800" id="mefete-content">
                <div class="p-6 <?php echo e($disableClass); ?>">
                    <form method="post" action="<?php echo e(route('para-cek.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="withdraw_method" value="MEFETE" <?php echo e($disableAttr); ?>>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Çekmek İstediğiniz Tutar (TL)</label>
                                <input type="number" name="miktar" min="50" step="50" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Mefete Hesap / ID</label>
                                <input type="text" name="hesap_no" placeholder="Mefete ID" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <button type="submit" <?php echo e($disableAttr); ?>

                                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Talep Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- PAROLAPARA
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
            <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('parolapara-content')">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset('images/payment_18762269_5b95268ef9e8408029794ff848890edb.png')); ?>" 
                         alt="Parolapara" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                    <div>
                        <h3 class="text-white font-semibold">Parolapara ile Para Çekme</h3>
                        <p class="text-zinc-400 text-sm">Parolapara hesabınıza transfer</p>
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="parolapara-icon">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="hidden border-t border-zinc-800" id="parolapara-content">
                <div class="p-6 <?php echo e($disableClass); ?>">
                    <form method="post" action="<?php echo e(route('para-cek.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="withdraw_method" value="PAROLAPARA" <?php echo e($disableAttr); ?>>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Çekmek İstediğiniz Tutar (TL)</label>
                                <input type="number" name="miktar" min="50" step="50" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Parolapara Hesap / ID</label>
                                <input type="text" name="hesap_no" placeholder="Parolapara ID" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <button type="submit" <?php echo e($disableAttr); ?>

                                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Talep Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

      

        <!-- EXTRA HAVALE EFT -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
            <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('extra-havale-eft-content')">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo e(asset('images/payment_18762269_extra_havale.png')); ?>" 
                         alt="Extra Havale EFT" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                    <div>
                        <h3 class="text-white font-semibold">Extra Cüzdan - Havale EFT ile Para Çekme</h3>
                        <p class="text-zinc-400 text-sm">Extra cüzdan banka transferi</p>
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="extra-havale-eft-icon">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="hidden border-t border-zinc-800" id="extra-havale-eft-content">
                <div class="p-6 <?php echo e($disableClass); ?>">
                    <form method="post" action="<?php echo e(route('para-cek.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="withdraw_method" value="EXTRA_HAVALE_EFT" <?php echo e($disableAttr); ?>>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Çekmek İstediğiniz Tutar (TL)</label>
                                <input type="number" name="miktar" min="50" step="50" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Banka Adı</label>
                                <select name="banka_adi" required <?php echo e($disableAttr); ?>

                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value="">Banka Seçiniz</option>
                                    <option value="Ziraat Bankası">Ziraat Bankası</option>
                                    <option value="Vakıfbank">Vakıfbank</option>
                                    <option value="Halkbank">Halkbank</option>
                                    <option value="İş Bankası">İş Bankası</option>
                                    <option value="Garanti Bankası">Garanti Bankası</option>
                                    <option value="Akbank">Akbank</option>
                                    <option value="Yapı Kredi">Yapı Kredi</option>
                                    <option value="QNB Finansbank">QNB Finansbank</option>
                                    <option value="TEB Bankası">TEB Bankası</option>
                                    <option value="Denizbank">Denizbank</option>
                                    <option value="ING Bank">ING Bank</option>
                                    <option value="Şekerbank">Şekerbank</option>
                                    <option value="Fibabank">Fibabank</option>
                                    <option value="Albaraka">Albaraka</option>
                                    <option value="Enpara">Enpara</option>
                                    <option value="Kuveyttürk Bankası">Kuveyttürk Bankası</option>
                                    <option value="OdeaBank">OdeaBank</option>
                                    <option value="Türkiye Finans">Türkiye Finans</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">IBAN / Hesap No</label>
                                <input type="text" name="hesap_no" placeholder="TR00 0000 0000... veya Hesap No" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <button type="submit" <?php echo e($disableAttr); ?>

                                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Talep Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		
		  <!-- EXTRA PAPARA -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
            <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('extra-papara-content')">
                <div class="flex items-center space-x-3">
                    <img src="" 
                         alt="Extra Papara" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                    <div>
                        <h3 class="text-white font-semibold">Extra Cüzdan - Kripto</h3>
                        <p class="text-zinc-400 text-sm">Extra cüzdan Kripto transferi</p>
                    </div>
                    <div class="ml-auto">
                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="extra-papara-icon">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="hidden border-t border-zinc-800" id="extra-papara-content">
                <div class="p-6 <?php echo e($disableClass); ?>">
                    <form method="post" action="<?php echo e(route('para-cek.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="withdraw_method" value="EXTRA_PAPARA" <?php echo e($disableAttr); ?>>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Çekmek İstediğiniz Tutar (TL)</label>
                                <input type="number" name="miktar" min="50" step="50" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">Kripto Adresi</label>
                                <input type="text" name="hesap_no" placeholder="TRC20 (USDT veya TRX)" required <?php echo e($disableAttr); ?>

                                       class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <button type="submit" <?php echo e($disableAttr); ?>

                                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Talep Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Diğer Extra yöntemler için benzer yapı... (EXTRA_HAVALE_FAST, EXTRA_PAPARA_IBAN, EXTRA_PAYCO, EXTRA_PARAZULA) -->
        <!-- Kısalık için sadece birkaç örnek gösterdim, gerekirse tamamını ekleyebilirim -->

    </div>

    <!-- Information -->
    <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-6 mb-8 mt-8">
        <h3 class="text-lg font-bold text-white mb-4">💡 Bilgilendirme</h3>
        <ul class="space-y-2 text-zinc-300 text-sm">
            <li>• Minimum para çekme tutarı: 50 TL</li>
            <li>• Sadece 50 TL'nin katları çekilebilir (50, 100, 150...)</li>
            <li>• Para çekme işlemleri 7/24 kabul edilir</li>
            <li>• İşlemler 5-30 Dakika içinde tamamlanır</li>
            <li>• Hesap bilgilerinizi doğru girdiğinizden emin olun</li>
            <li>• Çevrim şartınız varsa para çekemezsiniz</li>
            <li>• Bekleyen çekim talebiniz varsa yeni talep oluşturamazsınız</li>
            <li>• Sorun yaşarsanız canlı destek ile iletişime geçin</li>
        </ul>
    </div>

    <!-- Recent Withdrawals -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Son Para Çekme İstekleriniz</h3>
        <?php
            $recentWithdrawals = \App\Models\Paracek::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('uye', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        ?>
        
        <?php if($recentWithdrawals->count() > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $recentWithdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                    <div>
                        <p class="text-white text-sm"><?php echo e($user->parabirimi ?? 'TL'); ?> <?php echo e(number_format($withdrawal->miktar, 2)); ?></p>
                        <p class="text-zinc-400 text-xs"><?php echo e(ucfirst($withdrawal->turu)); ?> - <?php echo e(\Carbon\Carbon::parse($withdrawal->created_at)->format('d.m.Y H:i')); ?></p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($withdrawal->durum == 1 ? 'bg-green-500/20 text-green-400' : ($withdrawal->durum == 0 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400')); ?>">
                        <?php echo e($withdrawal->durum == 1 ? 'Onaylandı' : ($withdrawal->durum == 0 ? 'Beklemede' : 'Reddedildi')); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p class="text-zinc-400 text-center py-4">Para çekme isteği bulunmuyor</p>
        <?php endif; ?>
    </div>

</div>

<script>
function togglePanel(contentId) {
    const content = document.getElementById(contentId);
    const icon = document.getElementById(contentId.replace('-content', '-icon'));
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const miktar = this.querySelector('input[name="miktar"]');
            const hesapNo = this.querySelector('input[name="hesap_no"]');
            const bankaAdi = this.querySelector('select[name="banka_adi"]');
            
            if (miktar && miktar.value) {
                const amount = parseFloat(miktar.value);
                const maxAmount = <?php echo e($bakiye); ?>;
                
                if (amount < 50) {
                    e.preventDefault();
                    alert('Minimum çekim tutarı 50 TL\'dir!');
                    return false;
                }
                
                if (amount % 50 !== 0) {
                    e.preventDefault();
                    alert('Sadece 50 TL\'nin katları çekilebilir! (50,100,150...)');
                    return false;
                }
                
                if (amount > maxAmount) {
                    e.preventDefault();
                    alert('Bakiyeniz yetersiz! Mevcut bakiye: ' + maxAmount + ' TL');
                    return false;
                }
            }
            
            if (hesapNo && !hesapNo.value.trim()) {
                e.preventDefault();
                alert('Hesap bilgisi boş olamaz!');
                return false;
            }
            
            if (bankaAdi && bankaAdi.value === '') {
                e.preventDefault();
                alert('Banka seçimi yapmalısınız!');
                return false;
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/para-cek.blade.php ENDPATH**/ ?>