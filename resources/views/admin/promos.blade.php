@extends('layouts.admin')

@section('title', 'Promosyonlar')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Promosyonlar</h1>
            <p class="text-gray-400 mt-1">Aktif promosyonları yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Promosyon
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Promosyon</p>
                    <p class="stat-card-value">5</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="gift" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Promosyon</p>
                    <p class="stat-card-value">3</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Bu Ay Katılım</p>
                    <p class="stat-card-value">1,847</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Promos Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Welcome Bonus -->
        <div class="content-card">
            <div class="relative mb-4">
                <div class="w-full h-32 rounded-lg bg-gradient-to-br from-green-500/20 to-blue-500/20 flex items-center justify-center">
                    <i data-lucide="gift" class="w-12 h-12 text-green-500"></i>
                </div>
                <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                        Aktif
                    </span>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="font-semibold text-white text-lg">Hoşgeldin Bonusu</h3>
                <p class="text-sm text-gray-400">Yeni üyeler için özel %200 hoşgeldin bonusu</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Değer:</span>
                    <span class="text-white font-medium">%200</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Max Tutar:</span>
                    <span class="text-white">20,000 TL</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Çevrim:</span>
                    <span class="text-white">15x</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Katılım:</span>
                    <span class="text-white">847 kişi</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button class="px-3 py-2 text-sm bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-colors">
                    <i data-lucide="power-off" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Deposit Bonus -->
        <div class="content-card">
            <div class="relative mb-4">
                <div class="w-full h-32 rounded-lg bg-gradient-to-br from-blue-500/20 to-purple-500/20 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-12 h-12 text-blue-500"></i>
                </div>
                <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                        Aktif
                    </span>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="font-semibold text-white text-lg">Yatırım Bonusu</h3>
                <p class="text-sm text-gray-400">Her yatırımda %25 ekstra bonus</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Değer:</span>
                    <span class="text-white font-medium">%25</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Max Tutar:</span>
                    <span class="text-white">5,000 TL</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Çevrim:</span>
                    <span class="text-white">7x</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Katılım:</span>
                    <span class="text-white">623 kişi</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button class="px-3 py-2 text-sm bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-colors">
                    <i data-lucide="power-off" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Loss Bonus -->
        <div class="content-card">
            <div class="relative mb-4">
                <div class="w-full h-32 rounded-lg bg-gradient-to-br from-red-500/20 to-orange-500/20 flex items-center justify-center">
                    <i data-lucide="heart" class="w-12 h-12 text-red-500"></i>
                </div>
                <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                        Aktif
                    </span>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="font-semibold text-white text-lg">Kayıp Bonusu</h3>
                <p class="text-sm text-gray-400">Kayıplarınızın %25'ini geri alın</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Değer:</span>
                    <span class="text-white font-medium">%25</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Max Tutar:</span>
                    <span class="text-white">10,000 TL</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Çevrim:</span>
                    <span class="text-white">1x</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Katılım:</span>
                    <span class="text-white">377 kişi</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button class="px-3 py-2 text-sm bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-colors">
                    <i data-lucide="power-off" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Free Spins -->
        <div class="content-card">
            <div class="relative mb-4">
                <div class="w-full h-32 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                    <i data-lucide="zap" class="w-12 h-12 text-purple-500"></i>
                </div>
                <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                        Pasif
                    </span>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="font-semibold text-white text-lg">Free Spins</h3>
                <p class="text-sm text-gray-400">100 ücretsiz döndürme</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Değer:</span>
                    <span class="text-white font-medium">100 Spin</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Min Yatırım:</span>
                    <span class="text-white">100 TL</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Çevrim:</span>
                    <span class="text-white">-</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Katılım:</span>
                    <span class="text-white">0 kişi</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button class="px-3 py-2 text-sm bg-green-500/10 text-green-500 rounded-lg hover:bg-green-500/20 transition-colors">
                    <i data-lucide="power" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Cashback -->
        <div class="content-card">
            <div class="relative mb-4">
                <div class="w-full h-32 rounded-lg bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center">
                    <i data-lucide="refresh-cw" class="w-12 h-12 text-yellow-500"></i>
                </div>
                <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                        Pasif
                    </span>
                </div>
            </div>
            <div class="space-y-3">
                <h3 class="font-semibold text-white text-lg">Cashback</h3>
                <p class="text-sm text-gray-400">Haftalık %10 nakit geri</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Değer:</span>
                    <span class="text-white font-medium">%10</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Min Kayıp:</span>
                    <span class="text-white">500 TL</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Çevrim:</span>
                    <span class="text-white">-</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Katılım:</span>
                    <span class="text-white">0 kişi</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button class="px-3 py-2 text-sm bg-green-500/10 text-green-500 rounded-lg hover:bg-green-500/20 transition-colors">
                    <i data-lucide="power" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 