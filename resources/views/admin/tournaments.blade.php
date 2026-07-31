@extends('layouts.admin')

@section('title', 'Turnuvalar')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Turnuvalar</h1>
            <p class="text-gray-400 mt-1">Aktif turnuvaları yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Turnuva
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Turnuva</p>
                    <p class="stat-card-value">{{ $tournaments->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="trophy" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Turnuva</p>
                    <p class="stat-card-value">{{ $tournaments->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Ödül</p>
                    <p class="stat-card-value">{{ number_format($tournaments->sum('jackpot'), 2) }} TL</p>
                </div>
                <div class="stat-card-icon amber">
                    <i data-lucide="coins" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tournaments Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Turnuva Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $tournaments->firstItem() }}-{{ $tournaments->lastItem() }} / {{ $tournaments->total() }} turnuva
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Turnuva</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Sağlayıcı</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Ödül Havuzu</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Eklenme</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($tournaments as $tournament)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $tournament->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-zinc-800 flex items-center justify-center overflow-hidden">
                                    @if($tournament->cover)
                                        <img src="{{ $tournament->cover }}" alt="{{ $tournament->game_name }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="trophy" class="w-6 h-6 text-gray-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $tournament->game_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $tournament->game_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white">{{ $tournament->provider->name ?? 'Bilinmiyor' }}</div>
                                <div class="text-gray-400">{{ $tournament->provider->code ?? '' }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white font-medium">{{ number_format($tournament->jackpot, 2) }} TL</div>
                                @if($tournament->freespin)
                                    <div class="text-gray-400">{{ $tournament->freespin }} Freespin</div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            @if($tournament->status == 1)
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
                            {{ $tournament->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors" title="Detaylar">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($tournament->status == 1)
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
        @if($tournaments->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $tournaments->firstItem() }}-{{ $tournaments->lastItem() }} / {{ $tournaments->total() }} turnuva
            </div>
            <div class="flex items-center gap-2">
                @if($tournaments->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $tournaments->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($tournaments->getUrlRange(1, $tournaments->lastPage()) as $page => $url)
                    @if($page == $tournaments->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($tournaments->hasMorePages())
                    <a href="{{ $tournaments->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 