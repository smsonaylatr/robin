@extends('layouts.admin')

@section('title', 'Ödeme Sistemi Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Ödeme Sistemi Ayarları</h1>
            <p class="text-gray-400 mt-1 text-sm">Tüm ödeme yöntemlerinin API anahtarlarını yönetin</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="px-3 py-1.5 bg-green-500/10 border border-green-500/20 text-green-400 rounded-lg text-sm">
                <i data-lucide="shield-check" class="w-4 h-4 inline mr-1"></i>
                Güvenli
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl">
        <div class="flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-3 mt-0.5"></i>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Payment Settings Form -->
    <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Hemen Ödeme Sistemi -->
            <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Hemen Ödeme Sistemi</h2>
                        <p class="text-gray-400 text-sm">Anında ödeme işlemleri</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="bank" class="w-4 h-4 mr-2 text-blue-400"></i>
                            Hemen Havale API Key
                        </label>
                        <input type="text" name="hemen_havale_api_key" value="{{ $settings['hemen_havale_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="smartphone" class="w-4 h-4 mr-2 text-blue-400"></i>
                            Mefete API Key
                        </label>
                        <input type="text" name="mefete_api_key" value="{{ $settings['mefete_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="wallet" class="w-4 h-4 mr-2 text-blue-400"></i>
                            Papara API Key
                        </label>
                        <input type="text" name="papara_api_key" value="{{ $settings['papara_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="lock" class="w-4 h-4 mr-2 text-blue-400"></i>
                            Hemen Parola Para API Key
                        </label>
                        <input type="text" name="hemen_parolapara_api_key" value="{{ $settings['hemen_parolapara_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="credit-card" class="w-4 h-4 mr-2 text-blue-400"></i>
                            Kredi Kartı API Key
                        </label>
                        <input type="text" name="kredi_karti_api_key" value="{{ $settings['kredi_karti_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="bitcoin" class="w-4 h-4 mr-2 text-blue-400"></i>
                            Hemen Kripto API Key
                        </label>
                        <input type="text" name="hemen_kripto_api_key" value="{{ $settings['hemen_kripto_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                </div>
            </div>

            <!-- Extra Cüzdan Sistemi -->
            <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Extra Cüzdan Sistemi</h2>
                        <p class="text-gray-400 text-sm">Çoklu ödeme çözümleri</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="key" class="w-4 h-4 mr-2 text-green-400"></i>
                            Extra API Key
                        </label>
                        <input type="text" name="extra_api_key" value="{{ $settings['extra_api_key'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    </div>
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="shield" class="w-4 h-4 mr-2 text-green-400"></i>
                            Extra Secret
                        </label>
                        <input type="text" name="extra_secret" value="{{ $settings['extra_secret'] ?? '' }}" 
                               class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 transition-all duration-200" placeholder="Gizli anahtarı girin">
                    </div>
                </div>
            </div>
        </div>

        <!-- OleyPayment Sistemi (Full Width) -->
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="building-2" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">OleyPayment Sistemi</h2>
                    <p class="text-gray-400 text-sm">Kurumsal ödeme çözümleri</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="key" class="w-4 h-4 mr-2 text-purple-400"></i>
                        OleyPayment API Key
                    </label>
                    <input type="text" name="oley_api_key" value="{{ $settings['oley_api_key'] ?? '' }}" 
                           class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 transition-all duration-200" placeholder="API anahtarını girin">
                    <p class="text-gray-500 text-xs mt-1">Tüm OleyPayment yöntemleri için kullanılır</p>
                </div>
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="shield" class="w-4 h-4 mr-2 text-purple-400"></i>
                        OleyPayment Secret
                    </label>
                    <input type="text" name="oley_secret" value="{{ $settings['oley_secret'] ?? '' }}" 
                           class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 transition-all duration-200" placeholder="Gizli anahtarı girin">
                    <p class="text-gray-500 text-xs mt-1">Hash şifreleme için kullanılır</p>
                </div>
            </div>
                
            <!-- OleyPayment Bilgi Kartı -->
            <div class="bg-zinc-900/30 border border-zinc-600/30 rounded-xl p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="space-y-3">
                        <h4 class="flex items-center text-purple-400 font-semibold text-sm">
                            <i data-lucide="smartphone" class="w-4 h-4 mr-2"></i>
                            iFrame API
                        </h4>
                        <div class="space-y-1">
                            <div class="flex items-center text-gray-300 text-xs">
                                <i data-lucide="credit-card" class="w-3 h-3 mr-2 text-purple-400"></i>
                                Kredi Kartı
                            </div>
                            <div class="flex items-center text-gray-300 text-xs">
                                <i data-lucide="wallet" class="w-3 h-3 mr-2 text-purple-400"></i>
                                Papara, Mefete
                            </div>
                            <div class="flex items-center text-gray-300 text-xs">
                                <i data-lucide="smartphone" class="w-3 h-3 mr-2 text-purple-400"></i>
                                Parazula, Popy, Payco
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="flex items-center text-purple-400 font-semibold text-sm">
                            <i data-lucide="link" class="w-4 h-4 mr-2"></i>
                            REST API
                        </h4>
                        <div class="space-y-1">
                            <div class="flex items-center text-gray-300 text-xs">
                                <i data-lucide="bank" class="w-3 h-3 mr-2 text-purple-400"></i>
                                Kolayhavale
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="flex items-center text-purple-400 font-semibold text-sm">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            Sistem Bilgileri
                        </h4>
                        <div class="space-y-1 text-xs text-gray-400">
                            <div>Sandbox: api.sandboxoleypayment.com</div>
                            <div>Production: api.oleypayment.com</div>
                            <div>Webhook: {{ url('/webhook/oleypayment') }}</div>
                            <div>Miktar: 50₺ - 100.000₺</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-400 hover:to-purple-500 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                <i data-lucide="save" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                Ayarları Kaydet
            </button>
        </div>
    </form>

    <!-- Payment Methods Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Hemen Ödeme -->
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-4 h-4 text-white"></i>
                </div>
                <h3 class="text-white font-semibold">Hemen Ödeme</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-300">
                <div class="flex items-center">
                    <i data-lucide="bank" class="w-3 h-3 mr-2 text-blue-400"></i>
                    Hemen Havale
                </div>
                <div class="flex items-center">
                    <i data-lucide="smartphone" class="w-3 h-3 mr-2 text-blue-400"></i>
                    Mefete, Papara
                </div>
                <div class="flex items-center">
                    <i data-lucide="credit-card" class="w-3 h-3 mr-2 text-blue-400"></i>
                    Kredi Kartı
                </div>
                <div class="flex items-center">
                    <i data-lucide="bitcoin" class="w-3 h-3 mr-2 text-blue-400"></i>
                    Kripto, Parola Para
                </div>
            </div>
        </div>

        <!-- Extra Cüzdan -->
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-white"></i>
                </div>
                <h3 class="text-white font-semibold">Extra Cüzdan</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-300">
                <div class="flex items-center">
                    <i data-lucide="bank" class="w-3 h-3 mr-2 text-green-400"></i>
                    Havale EFT, Fast
                </div>
                <div class="flex items-center">
                    <i data-lucide="wallet" class="w-3 h-3 mr-2 text-green-400"></i>
                    Papara, IBAN
                </div>
                <div class="flex items-center">
                    <i data-lucide="credit-card" class="w-3 h-3 mr-2 text-green-400"></i>
                    Kredi Kartı
                </div>
                <div class="flex items-center">
                    <i data-lucide="smartphone" class="w-3 h-3 mr-2 text-green-400"></i>
                    Mefete, Payco, Parazula
                </div>
            </div>
        </div>

        <!-- OleyPayment -->
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
                </div>
                <h3 class="text-white font-semibold">OleyPayment</h3>
            </div>
            <div class="space-y-2 text-sm text-gray-300">
                <div class="flex items-center">
                    <i data-lucide="credit-card" class="w-3 h-3 mr-2 text-purple-400"></i>
                    Kredi Kartı
                </div>
                <div class="flex items-center">
                    <i data-lucide="wallet" class="w-3 h-3 mr-2 text-purple-400"></i>
                    Papara, Mefete
                </div>
                <div class="flex items-center">
                    <i data-lucide="smartphone" class="w-3 h-3 mr-2 text-purple-400"></i>
                    Parazula, Popy, Payco
                </div>
                <div class="flex items-center">
                    <i data-lucide="bank" class="w-3 h-3 mr-2 text-purple-400"></i>
                    Kolayhavale
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 