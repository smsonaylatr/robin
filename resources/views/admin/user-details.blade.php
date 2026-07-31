@extends('layouts.admin')

@section('title', 'Kullanıcı Detayları - ' . $user->username)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-zinc-900 via-black to-zinc-900 p-4">
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                                         <div>
                         <h1 class="text-xl font-bold text-white">Kullanıcı Detayları</h1>
                         <p class="text-gray-400 text-sm">{{ $user->name }} ({{ $user->username }})</p>
                     </div>
                </div>
                <a href="{{ route('admin.users') }}" class="px-3 py-2 bg-zinc-800/50 text-white rounded-lg hover:bg-zinc-700/50 transition-all duration-300 border border-zinc-700/50 text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    Geri Dön
                </a>
            </div>
        </div>

        <!-- User Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4 hover:border-zinc-700/70 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Bakiye</p>
                        <p class="text-lg font-bold text-white">{{ $user->parabirimi }}{{ number_format($user->bakiye, 2) }}</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="wallet" class="w-4 h-4 text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4 hover:border-zinc-700/70 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Toplam İşlem</p>
                        <p class="text-lg font-bold text-white">{{ $transactions->count() }}</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="activity" class="w-4 h-4 text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4 hover:border-zinc-700/70 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Para Yatırma</p>
                        <p class="text-lg font-bold text-white">{{ $deposits->count() }}</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-4 h-4 text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4 hover:border-zinc-700/70 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Para Çekme</p>
                        <p class="text-lg font-bold text-white">{{ $withdrawals->count() }}</p>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="trending-down" class="w-4 h-4 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details & Recent Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Details -->
            <div class="lg:col-span-1 bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded flex items-center justify-center">
                        <i data-lucide="user-check" class="w-3 h-3 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Kullanıcı Bilgileri</h2>
                </div>
                
                <div class="space-y-3">
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Ad Soyad</label>
                        <div class="text-white font-medium text-sm">{{ $user->name ?: 'Belirtilmemiş' }}</div>
                    </div>
                                         <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                         <label class="block text-xs font-medium text-gray-400 mb-1">Kullanıcı Adı</label>
                         <div class="text-white font-medium text-sm">{{ $user->username }}</div>
                     </div>
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Email</label>
                        <div class="text-white font-medium text-sm">{{ $user->email ?: 'Belirtilmemiş' }}</div>
                    </div>
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Telefon</label>
                        <div class="text-white font-medium text-sm">{{ $user->telefon ?: 'Belirtilmemiş' }}</div>
                    </div>
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">TC Kimlik</label>
                        <div class="text-white font-medium text-sm">{{ $user->tc ?: 'Belirtilmemiş' }}</div>
                    </div>
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Doğum Tarihi</label>
                        <div class="text-white font-medium text-sm">{{ $user->dt ?: 'Belirtilmemiş' }}</div>
                    </div>
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Şehir</label>
                        <div class="text-white font-medium text-sm">{{ $user->il ?: 'Belirtilmemiş' }}</div>
                    </div>
                    <div class="bg-zinc-800/20 rounded p-3 border border-zinc-700/30">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Durum</label>
                        <div>
                            @if($user->durum == 1)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    Pasif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="lg:col-span-2 bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-blue-600 rounded flex items-center justify-center">
                        <i data-lucide="activity" class="w-3 h-3 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Son İşlemler</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-700/50">
                                <th class="text-left py-2 px-2 text-xs font-medium text-gray-400">Tarih</th>
                                <th class="text-left py-2 px-2 text-xs font-medium text-gray-400">Tür</th>
                                <th class="text-left py-2 px-2 text-xs font-medium text-gray-400">Tutar</th>
                                <th class="text-left py-2 px-2 text-xs font-medium text-gray-400">Oyun</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-700/30">
                            @forelse($transactions->take(8) as $transaction)
                            <tr class="hover:bg-zinc-800/30 transition-all duration-300">
                                <td class="py-2 px-2 text-xs text-gray-300">
                                    {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d.m.Y H:i') : '-' }}
                                </td>
                                <td class="py-2 px-2">
                                    @if($transaction->type == 'bet')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                            <i data-lucide="trending-down" class="w-3 h-3 mr-1"></i>
                                            Bahis
                                        </span>
                                    @elseif($transaction->type == 'win')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                            <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                                            Kazanç
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-500/10 text-gray-500 border border-gray-500/20">
                                            {{ $transaction->type }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-2">
                                    @if($transaction->type == 'bet')
                                        <span class="text-red-500 font-medium text-xs">-₺{{ number_format($transaction->amount, 2) }}</span>
                                    @else
                                        <span class="text-green-500 font-medium text-xs">+₺{{ number_format($transaction->amount, 2) }}</span>
                                    @endif
                                </td>
                                <td class="py-2 px-2 text-xs text-gray-300">
                                    {{ Str::limit($transaction->gamename, 20) ?: '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 px-2 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="inbox" class="w-6 h-6 text-gray-500"></i>
                                        <span class="text-xs">Henüz işlem bulunmuyor</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Deposits & Withdrawals -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Deposits -->
            <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-3 h-3 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Son Para Yatırma İşlemleri</h2>
                </div>
                
                <div class="space-y-3">
                    @forelse($deposits->take(4) as $deposit)
                    <div class="flex items-center justify-between p-3 bg-zinc-800/20 rounded border border-zinc-700/30 hover:bg-zinc-800/40 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded flex items-center justify-center">
                                <i data-lucide="plus" class="w-4 h-4 text-green-500"></i>
                            </div>
                            <div>
                                <div class="text-white font-medium text-sm">{{ number_format($deposit->miktar, 2) }} TL</div>
                                <div class="text-xs text-gray-400">{{ $deposit->banka }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-300 mb-1">{{ $deposit->tarih ? \Carbon\Carbon::parse($deposit->tarih)->format('d.m.Y H:i') : '-' }}</div>
                            @if($deposit->durum == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                    Bekliyor
                                </span>
                            @elseif($deposit->durum == 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Onaylandı
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    Reddedildi
                                </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-400 py-8">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="inbox" class="w-6 h-6 text-gray-500"></i>
                            <span class="text-xs">Para yatırma işlemi bulunmuyor</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Withdrawals -->
            <div class="bg-black/30 backdrop-blur-sm border border-zinc-800/50 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-600 rounded flex items-center justify-center">
                        <i data-lucide="trending-down" class="w-3 h-3 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Son Para Çekme İşlemleri</h2>
                </div>
                
                <div class="space-y-3">
                    @forelse($withdrawals->take(4) as $withdrawal)
                    <div class="flex items-center justify-between p-3 bg-zinc-800/20 rounded border border-zinc-700/30 hover:bg-zinc-800/40 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-amber-500/20 to-orange-600/20 rounded flex items-center justify-center">
                                <i data-lucide="minus" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            <div>
                                <div class="text-white font-medium text-sm">{{ number_format($withdrawal->miktar, 2) }} TL</div>
                                <div class="text-xs text-gray-400">{{ $withdrawal->banka }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-300 mb-1">{{ $withdrawal->tarih ? \Carbon\Carbon::parse($withdrawal->tarih)->format('d.m.Y H:i') : '-' }}</div>
                            @if($withdrawal->durum == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                    Bekliyor
                                </span>
                            @elseif($withdrawal->durum == 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Onaylandı
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    Reddedildi
                                </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-400 py-8">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="inbox" class="w-6 h-6 text-gray-500"></i>
                            <span class="text-xs">Para çekme işlemi bulunmuyor</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 