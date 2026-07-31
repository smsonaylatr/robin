@extends('layouts.admin')

@section('title', 'Oyunlar')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">🎮 Oyunlar</h1>
            <p class="text-gray-400 mt-1">Sistemdeki tüm oyunları yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Oyun
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Oyun</p>
                    <p class="stat-card-value">{{ $games->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="gamepad-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Oyun</p>
                    <p class="stat-card-value">{{ $games->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Öne Çıkan</p>
                    <p class="stat-card-value">{{ $games->where('is_featured', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon amber">
                    <i data-lucide="star" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Turnuva</p>
                    <p class="stat-card-value">{{ $games->where('turnuva', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon purple">
                    <i data-lucide="trophy" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Games Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Oyun Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $games->firstItem() }}-{{ $games->lastItem() }} / {{ $games->total() }} oyun
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Oyun</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Sağlayıcı</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tür</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">RTP</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Özellikler</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Eklenme</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($games as $game)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $game->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-16 rounded-lg bg-zinc-800 flex items-center justify-center overflow-hidden border border-zinc-700">
                                    @if($game->cover)
                                        <img src="{{ $game->cover }}" alt="{{ $game->game_name }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="image" class="w-8 h-8 text-gray-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $game->game_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $game->game_code }}</div>
                                    @if($game->technology)
                                        <div class="text-xs text-green-400">{{ ucfirst($game->technology) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white font-medium">{{ $game->provider->name ?? 'Bilinmiyor' }}</div>
                                <div class="text-gray-400">{{ $game->provider->code ?? '' }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                {{ ucfirst($game->game_type ?? 'Slot') }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            @if($game->rtp)
                                <span class="text-green-400">%{{ $game->rtp }}</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($game->status == 1)
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
                        <td class="py-4 px-4">
                            <div class="flex flex-wrap gap-1">
                                @if($game->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-500/10 text-amber-500">⭐ Öne Çıkan</span>
                                @endif
                                @if($game->turnuva)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-500/10 text-purple-500">🏆 Turnuva</span>
                                @endif
                                @if($game->jackpot)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-yellow-500/10 text-yellow-500">💰 Jackpot</span>
                                @endif
                                @if($game->freespin)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-500/10 text-green-500">🎁 Freespin</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $game->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors" title="Detaylar">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($game->status == 1)
                                    <button class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Pasif Yap">
                                        <i data-lucide="power-off" class="w-4 h-4"></i>
                                    </button>
                                @else
                                    <button class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-colors" title="Aktif Yap">
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
        @if($games->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $games->firstItem() }}-{{ $games->lastItem() }} / {{ $games->total() }} oyun
            </div>
            <div class="flex items-center gap-2">
                @if($games->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $games->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($games->getUrlRange(1, $games->lastPage()) as $page => $url)
                    @if($page == $games->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($games->hasMorePages())
                    <a href="{{ $games->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
