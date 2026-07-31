@extends('layouts.app')

@section('title', 'Bahis Geçmişi - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Bahis Geçmişi</h1>
        <p class="text-zinc-400">Tüm kuponlarınız ve bahis geçmişiniz</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Toplam Kupon</p>
                    <p class="text-2xl font-bold text-white">{{ $kuponlar->total() }}</p>
                </div>
                <span class="text-2xl">🎯</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Toplam Bahis</p>
                    <p class="text-2xl font-bold text-white">{{ $user->parabirimi }}{{ number_format($kuponlar->sum('miktar'), 2) }}</p>
                </div>
                <span class="text-2xl">💰</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Bekleyen Kupon</p>
                    <p class="text-2xl font-bold text-white">{{ $kuponlar->where('durum', 0)->count() }}</p>
                </div>
                <span class="text-2xl">⏳</span>
            </div>
        </div>
        
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-400 text-sm">Kazanan Kupon</p>
                    <p class="text-2xl font-bold text-white">{{ $kuponlar->where('durum', 1)->count() }}</p>
                </div>
                <span class="text-2xl">🏆</span>
            </div>
        </div>
    </div>

    <!-- Kuponlar List -->
    <div class="space-y-6">
        @if($kuponlar->count() > 0)
            @foreach($kuponlar as $kupon)
            <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
                <!-- Kupon Header - Tıklanabilir -->
                <div class="bg-zinc-800/50 px-6 py-4 border-b border-zinc-700 cursor-pointer hover:bg-zinc-700/50 transition-colors" 
                     onclick="toggleKupon({{ $kupon->id }})">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <p class="text-zinc-400 text-xs">Kupon #{{ $kupon->id }}</p>
                                <p class="text-white font-medium">{{ \Carbon\Carbon::parse($kupon->tarih)->format('d.m.Y H:i') }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-zinc-400 text-xs">Bahis</p>
                                <p class="text-red-400 font-medium">{{ $user->parabirimi }}{{ number_format($kupon->miktar, 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-zinc-400 text-xs">Oran</p>
                                <p class="text-white font-medium">{{ number_format($kupon->oran, 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-zinc-400 text-xs">Potansiyel</p>
                                <p class="text-green-400 font-medium">{{ $user->parabirimi }}{{ number_format($kupon->odeme, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                @if($kupon->durum == 0)
                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">
                                        Bekliyor
                                    </span>
                                @elseif($kupon->durum == 1)
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                                        Kazandı
                                    </span>
                                @elseif($kupon->durum == 2)
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-500/20 text-red-400">
                                        Kaybetti
                                    </span>
                                @elseif($kupon->durum == 3)
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-500/20 text-gray-400">
                                        İptal
                                    </span>
                                @endif
                            </div>
                            <div class="text-zinc-400">
                                <svg class="w-5 h-5 transform transition-transform duration-200" id="arrow-{{ $kupon->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kupon Maçları - Gizli -->
                <div class="hidden" id="kupon-content-{{ $kupon->id }}">
                    <div class="p-6">
                        <div class="grid gap-4">
                            @foreach($kupon->kuponMaclar as $mac)
                            <div class="bg-zinc-800/30 rounded-lg p-4 border border-zinc-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="text-white font-medium">{{ $mac->evsahibi }}</span>
                                            <span class="text-zinc-400">vs</span>
                                            <span class="text-white font-medium">{{ $mac->deplasman }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm">
                                            <span class="text-zinc-400">Tür: <span class="text-white">{{ $mac->aciklama ?? 'Maç Sonucu' }}</span></span>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-zinc-400">Seçim:</span>
                                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">
                                                    {{ $mac->tur }}
                                                </span>
                                            </div>
                                            <span class="text-zinc-400">Oran: <span class="text-white font-medium">{{ number_format($mac->oran, 2) }}</span></span>
                                            @if($mac->matchdate)
                                                <span class="text-zinc-400">Tarih: <span class="text-white">{{ \Carbon\Carbon::parse($mac->matchdate)->format('d.m.Y H:i') }}</span></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($mac->sonuc == 0)
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">
                                                Bekliyor
                                            </span>
                                        @elseif($mac->sonuc == 1)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                                                Kazandı
                                            </span>
                                        @elseif($mac->sonuc == 2)
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">
                                                Kaybetti
                                            </span>
                                        @elseif($mac->sonuc == 3)
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-500/20 text-gray-400">
                                                İptal
                                            </span>
                                        @endif
                                        @if($mac->skor)
                                            <div class="text-zinc-400 text-xs mt-1">{{ $mac->skor }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Kupon Detayları -->
                        <div class="mt-4 pt-4 border-t border-zinc-700">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center space-x-6">
                                    <span class="text-zinc-400">Toplam Maç: <span class="text-white">{{ $kupon->toplam }}</span></span>
                                    <span class="text-zinc-400">Kazanan Maç: <span class="text-white">{{ $kupon->kazanan }}</span></span>
                                    @if($kupon->durum == 1)
                                        <span class="text-green-400 font-medium">Kazanç: {{ $user->parabirimi }}{{ number_format($kupon->odeme, 2) }}</span>
                                    @endif
                                </div>
                                <div class="text-zinc-400 text-xs">
                                    IP: {{ $kupon->ip }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-6">
                {{ $kuponlar->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🎯</span>
                </div>
                <p class="text-zinc-400 text-lg mb-2">Henüz kupon bulunmuyor</p>
                <p class="text-zinc-500 text-sm">İlk kuponunuzu oynadığınızda burada görünecek</p>
                <a href="{{ route('sports') }}" class="inline-block mt-4 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Spor Bahisleri
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function toggleKupon(kuponId) {
    const content = document.getElementById(`kupon-content-${kuponId}`);
    const arrow = document.getElementById(`arrow-${kuponId}`);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection 