@extends('layouts.admin')

@section('title', 'Sağlayıcılar')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Sağlayıcılar</h1>
            <p class="text-gray-400 mt-1">Oyun sağlayıcılarını yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Sağlayıcı
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Sağlayıcı</p>
                    <p class="stat-card-value">{{ $providers->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="building" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Sağlayıcı</p>
                    <p class="stat-card-value">{{ $providers->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Oyun</p>
                    <p class="stat-card-value">{{ $providers->sum(function($provider) { return $provider->games->count(); }) }}</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="gamepad-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Providers Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Sağlayıcı Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $providers->firstItem() }}-{{ $providers->lastItem() }} / {{ $providers->total() }} sağlayıcı
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Sağlayıcı</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Kod</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Oyun Sayısı</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">RTP</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Eklenme</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($providers as $provider)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $provider->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-zinc-800 flex items-center justify-center overflow-hidden">
                                    @if($provider->image)
                                        <img src="{{ $provider->image }}" alt="{{ $provider->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="building" class="w-6 h-6 text-gray-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $provider->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $provider->distribution }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                {{ $provider->code }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $provider->games->count() }} oyun
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            %{{ $provider->rtp }}
                        </td>
                        <td class="py-4 px-4">
                            @if($provider->status == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    Pasif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $provider->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors" title="Detaylar">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($provider->status == 1)
                                    <button onclick="toggleProviderStatus({{ $provider->id }})" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Pasif Yap">
                                        <i data-lucide="power-off" class="w-4 h-4"></i>
                                    </button>
                                @else
                                    <button onclick="toggleProviderStatus({{ $provider->id }})" class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-colors" title="Aktif Yap">
                                        <i data-lucide="power" class="w-4 h-4"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($providers->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $providers->firstItem() }}-{{ $providers->lastItem() }} / {{ $providers->total() }} sağlayıcı
            </div>
            <div class="flex items-center gap-2">
                @if($providers->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $providers->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($providers->getUrlRange(1, $providers->lastPage()) as $page => $url)
                    @if($page == $providers->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($providers->hasMorePages())
                    <a href="{{ $providers->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleProviderStatus(providerId) {
    // Butonu disable et
    const button = event.target.closest('button');
    button.disabled = true;
    
    fetch(`/admin/providers/${providerId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Sayfayı yenile
            location.reload();
        } else {
            alert('Hata: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu!');
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endsection 