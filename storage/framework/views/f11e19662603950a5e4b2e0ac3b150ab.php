<?php $__env->startSection('title', 'Casino Geçmişi - ' . ($settings->site_adi ?? 'BetNow')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Casino Geçmişi</h1>
        <p class="text-zinc-400">Casino oyunları işlem geçmişiniz</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Toplam Bahis</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($user->parabirimi); ?><?php echo e(number_format($casinoTransactions->where('type', 'bet')->sum('amount'), 2)); ?></p>
                </div>
                <span class="text-2xl">🎯</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Toplam Kazanç</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($user->parabirimi); ?><?php echo e(number_format($casinoTransactions->where('type', 'win')->sum('amount'), 2)); ?></p>
                </div>
                <span class="text-2xl">💰</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Net Kazanç</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($user->parabirimi); ?><?php echo e(number_format($casinoTransactions->where('type', 'win')->sum('amount') - $casinoTransactions->where('type', 'bet')->sum('amount'), 2)); ?></p>
                </div>
                <span class="text-2xl">📈</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Oynanan Oyun</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($casinoTransactions->whereNotNull('gameid')->unique('gameid')->count()); ?></p>
                </div>
                <span class="text-2xl">🎮</span>
            </div>
        </div>
    </div>

    <!-- Casino Transactions -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        <?php if($casinoTransactions->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-700">
                            <th class="text-left py-3 px-4 text-white font-medium">Tarih</th>
                            <th class="text-left py-3 px-4 text-white font-medium">Oyun</th>
                            <th class="text-left py-3 px-4 text-white font-medium">İşlem Türü</th>
                            <th class="text-right py-3 px-4 text-white font-medium">Miktar</th>
                            <th class="text-right py-3 px-4 text-white font-medium">Bakiye</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $casinoTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3 px-4 text-zinc-300 text-sm">
                                <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="py-3 px-4">
                                <?php if($transaction->game): ?>
                                    <div class="flex items-center space-x-3">
                                        <?php if($transaction->game->cover): ?>
                                            <img src="<?php echo e($transaction->game->cover); ?>" alt="<?php echo e($transaction->game->name); ?>" class="w-8 h-8 rounded object-cover">
                                        <?php endif; ?>
                                        <div>
                                            <span class="text-white text-sm"><?php echo e($transaction->game->name); ?></span>
                                            <?php if($transaction->game->provider): ?>
                                                <p class="text-zinc-400 text-xs"><?php echo e($transaction->game->provider->name); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-zinc-400 text-sm">Bilinmeyen Oyun</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs rounded-full <?php echo e($transaction->type === 'win' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'); ?>">
                                    <?php echo e($transaction->type === 'win' ? 'Kazanç' : 'Bahis'); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="font-medium <?php echo e($transaction->type === 'win' ? 'text-green-400' : 'text-red-400'); ?>">
                                    <?php echo e($transaction->type === 'bet' ? '-' : '+'); ?><?php echo e($user->parabirimi); ?><?php echo e(number_format($transaction->amount, 2)); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-right text-zinc-300 text-sm">
                                <?php echo e($user->parabirimi); ?><?php echo e(number_format($transaction->balance_after ?? 0, 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($casinoTransactions->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🎮</span>
                </div>
                <p class="text-zinc-400 text-lg mb-2">Henüz casino işlemi bulunmuyor</p>
                <p class="text-zinc-500 text-sm">İlk casino oyununuzu oynadığınızda burada görünecek</p>
                <a href="<?php echo e(route('casino')); ?>" class="inline-block mt-4 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Casino Oyunları
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Game Statistics -->
    <?php if($casinoTransactions->count() > 0): ?>
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Most Played Games -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">En Çok Oynanan Oyunlar</h3>
            <div class="space-y-3">
                <?php
                    $mostPlayedGames = $casinoTransactions->whereNotNull('gameid')
                        ->groupBy('gameid')
                        ->map(function($transactions) {
                            return [
                                'game' => $transactions->first()->game,
                                'count' => $transactions->count(),
                                'total_bet' => $transactions->where('type', 'bet')->sum('amount'),
                                'total_win' => $transactions->where('type', 'win')->sum('amount')
                            ];
                        })
                        ->sortByDesc('count')
                        ->take(5);
                ?>
                
                <?php $__currentLoopData = $mostPlayedGames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gameData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <?php if($gameData['game'] && $gameData['game']->cover): ?>
                            <img src="<?php echo e($gameData['game']->cover); ?>" alt="<?php echo e($gameData['game']->name); ?>" class="w-10 h-10 rounded object-cover">
                        <?php endif; ?>
                        <div>
                            <p class="text-white text-sm"><?php echo e($gameData['game'] ? $gameData['game']->name : 'Bilinmeyen Oyun'); ?></p>
                            <p class="text-zinc-400 text-xs"><?php echo e($gameData['count']); ?> oyun</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium <?php echo e(($gameData['total_win'] - $gameData['total_bet']) >= 0 ? 'text-green-400' : 'text-red-400'); ?>">
                            <?php echo e($user->parabirimi); ?><?php echo e(number_format($gameData['total_win'] - $gameData['total_bet'], 2)); ?>

                        </p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Provider Statistics -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Sağlayıcı İstatistikleri</h3>
            <div class="space-y-3">
                <?php
                    $providerStats = $casinoTransactions->whereNotNull('gameid')
                        ->groupBy('game.provider_id')
                        ->map(function($transactions) {
                            $provider = $transactions->first()->game->provider ?? null;
                            return [
                                'provider' => $provider,
                                'total_bet' => $transactions->where('type', 'bet')->sum('amount'),
                                'total_win' => $transactions->where('type', 'win')->sum('amount'),
                                'games_played' => $transactions->unique('gameid')->count()
                            ];
                        })
                        ->sortByDesc('total_bet')
                        ->take(5);
                ?>
                
                <?php $__currentLoopData = $providerStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                    <div>
                        <p class="text-white text-sm"><?php echo e($stats['provider'] ? $stats['provider']->name : 'Bilinmeyen Sağlayıcı'); ?></p>
                        <p class="text-zinc-400 text-xs"><?php echo e($stats['games_played']); ?> oyun</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium <?php echo e(($stats['total_win'] - $stats['total_bet']) >= 0 ? 'text-green-400' : 'text-red-400'); ?>">
                            <?php echo e($user->parabirimi); ?><?php echo e(number_format($stats['total_win'] - $stats['total_bet'], 2)); ?>

                        </p>
                        <p class="text-zinc-400 text-xs">
                            <?php echo e($stats['total_bet'] > 0 ? number_format(($stats['total_win'] / $stats['total_bet']) * 100, 1) : 0); ?>% RTP
                        </p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/casino-gecmisi.blade.php ENDPATH**/ ?>