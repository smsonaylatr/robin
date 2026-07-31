@extends('layouts.app')

@section('title', 'Hesabım - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Hesabım</h1>
        <p class="text-zinc-400">Hesap bilgileriniz ve işlem geçmişiniz</p>
    </div>

    <!-- User Info Card -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-red-400">{{ substr($user->username, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $user->username }}</h2>
                    <p class="text-zinc-400">Üye ID: {{ $user->id }}</p>
                    <p class="text-zinc-400">Kayıt: {{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-zinc-400 text-sm">Bakiye</p>
                <p class="text-3xl font-bold text-white">{{ $user->parabirimi }}{{ number_format($user->bakiye, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('para-yatir') }}" class="bg-black/30 border border-zinc-700/60 rounded-lg p-4 hover:border-red-500/50 hover:bg-black/40 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="7" width="18" height="10" rx="2" fill="#111" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="7" y1="10" x2="11" y2="10" stroke="#ffffff" stroke-width="1"/>
                        <circle cx="17" cy="12" r="2" fill="#ffffff"/>
                        <path d="M12 4v3M10.5 5.5h3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-medium">Para Yatır</h3>
                    <p class="text-zinc-400 text-sm">Hızlı para yatırma</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('para-cek') }}" class="bg-black/30 border border-zinc-700/60 rounded-lg p-4 hover:border-red-500/50 hover:bg-black/40 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="7" width="18" height="10" rx="2" fill="#111" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="5" y1="10" x2="19" y2="10" stroke="#ffffff" stroke-width="1"/>
                        <path d="M12 4v3M10.5 5.5h3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-medium">Para Çek</h3>
                    <p class="text-zinc-400 text-sm">Güvenli para çekme</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('hesap-hareketleri') }}" class="bg-black/30 border border-zinc-700/60 rounded-lg p-4 hover:border-red-500/50 hover:bg-black/40 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="11" width="2.5" height="6" rx="1" fill="#ffffff"/>
                        <rect x="10.75" y="9" width="2.5" height="8" rx="1" fill="#ffffff"/>
                        <rect x="16.5" y="6" width="2.5" height="11" rx="1" fill="#ffffff"/>
                        <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-medium">Hesap Hareketleri</h3>
                    <p class="text-zinc-400 text-sm">İşlem geçmişi</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('aktif-bonuslarim') }}" class="bg-black/30 border border-zinc-700/60 rounded-lg p-4 hover:border-red-500/50 hover:bg-black/40 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="8" width="16" height="10" rx="2" fill="#111" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="12" y1="8" x2="12" y2="18" stroke="currentColor" stroke-width="1.3"/>
                        <line x1="4" y1="13" x2="20" y2="13" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M8 8 C10 4, 14 4, 16 8" stroke="currentColor" stroke-width="1.4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-medium">Aktif Bonuslarım</h3>
                    <p class="text-zinc-400 text-sm">Bonus durumları</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Son İşlemler</h3>
            <a href="{{ route('hesap-hareketleri') }}" class="text-red-400 hover:text-red-300 transition-colors">Tümünü Gör</a>
        </div>
        
        @if($recentTransactions->count() > 0)
            <div class="space-y-3">
                @foreach($recentTransactions as $transaction)
                <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $transaction->type === 'win' ? 'bg-green-500/20' : ($transaction->type === 'bet' ? 'bg-red-500/20' : 'bg-blue-500/20') }}">
                            <span class="text-sm font-medium {{ $transaction->type === 'win' ? 'text-green-400' : ($transaction->type === 'bet' ? 'text-red-400' : 'text-blue-400') }}">
                                {{ $transaction->type === 'win' ? 'W' : ($transaction->type === 'bet' ? 'B' : 'T') }}
                            </span>
                        </div>
                        <div>
                            <p class="text-white text-sm">{{ ucfirst($transaction->type) }}</p>
                            <p class="text-zinc-400 text-xs">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium {{ $transaction->type === 'win' ? 'text-green-400' : ($transaction->type === 'bet' ? 'text-red-400' : 'text-blue-400') }}">
                            {{ $transaction->type === 'bet' ? '-' : '+' }}{{ $user->parabirimi }}{{ number_format($transaction->amount, 2) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-zinc-400">Henüz işlem bulunmuyor</p>
            </div>
        @endif
    </div>

    <!-- Recent Deposits & Withdrawals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Deposits -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Son Para Yatırma İstekleri</h3>
            @if($deposits->count() > 0)
                <div class="space-y-3">
                    @foreach($deposits as $deposit)
                    <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                        <div>
                            <p class="text-white text-sm">{{ $user->parabirimi }}{{ number_format($deposit->miktar, 2) }}</p>
                            <p class="text-zinc-400 text-xs">{{ ucfirst($deposit->tur) }} - {{ \Carbon\Carbon::parse($deposit->tarih)->format('d.m.Y H:i') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $deposit->durum == 1 ? 'bg-green-500/20 text-green-400' : ($deposit->durum == 0 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                            {{ $deposit->durum == 1 ? 'Onaylandı' : ($deposit->durum == 0 ? 'Beklemede' : 'Reddedildi') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-zinc-400 text-center py-4">Para yatırma isteği bulunmuyor</p>
            @endif
        </div>

        <!-- Withdrawals -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Son Para Çekme İstekleri</h3>
            @if($withdrawals->count() > 0)
                <div class="space-y-3">
                    @foreach($withdrawals as $withdrawal)
                    <div class="flex items-center justify-between p-3 bg-zinc-800/30 rounded-lg">
                        <div>
                            <p class="text-white text-sm">{{ $user->parabirimi }}{{ number_format($withdrawal->miktar, 2) }}</p>
                            <p class="text-zinc-400 text-xs">{{ ucfirst($withdrawal->turu) }} - {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('d.m.Y H:i') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $withdrawal->durum == 1 ? 'bg-green-500/20 text-green-400' : ($withdrawal->durum == 0 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                            {{ $withdrawal->durum == 1 ? 'Onaylandı' : ($withdrawal->durum == 0 ? 'Beklemede' : 'Reddedildi') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-zinc-400 text-center py-4">Para çekme isteği bulunmuyor</p>
            @endif
        </div>
    </div>
</div>
@endsection 