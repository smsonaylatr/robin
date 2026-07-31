@extends('layouts.admin')

@section('title', 'İşlem Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">İşlem Yönetimi</h1>
            <p class="text-gray-400 mt-1">Kullanıcı işlemlerini görüntüleyin ve yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                Rapor İndir
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam İşlem</p>
                    <p class="stat-card-value">{{ $transactions->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Bahis</p>
                    <p class="stat-card-value">₺{{ number_format($transactions->where('type', 'bet')->sum('amount'), 2) }}</p>
                </div>
                <div class="stat-card-icon amber">
                    <i data-lucide="trending-down" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Kazanç</p>
                    <p class="stat-card-value">₺{{ number_format($transactions->where('type', 'win')->sum('amount'), 2) }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Net Kazanç</p>
                    <p class="stat-card-value">₺{{ number_format($transactions->where('type', 'win')->sum('amount') - $transactions->where('type', 'bet')->sum('amount'), 2) }}</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card">
        <form method="GET" action="{{ route('admin.transactions') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">İşlem Türü</label>
                <select name="type" class="w-full h-10 bg-zinc-800 border border-zinc-700 rounded-lg px-3 text-white focus:border-yellow-500 focus:outline-none">
                    <option value="">Tümü</option>
                    <option value="bet" {{ request('type') == 'bet' ? 'selected' : '' }}>Bahis</option>
                    <option value="win" {{ request('type') == 'win' ? 'selected' : '' }}>Kazanç</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>İade</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">Kullanıcı ID</label>
                <input type="number" name="user_id" placeholder="Kullanıcı ID" value="{{ request('user_id') }}" class="w-full h-10 bg-zinc-800 border border-zinc-700 rounded-lg px-3 text-white focus:border-yellow-500 focus:outline-none">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                    Filtrele
                </button>
                <a href="{{ route('admin.transactions') }}" class="px-4 py-2 bg-zinc-700 text-white rounded-lg hover:bg-zinc-600 transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">İşlem Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $transactions->firstItem() }}-{{ $transactions->lastItem() }} / {{ $transactions->total() }} işlem
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Kullanıcı</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tür</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tutar</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Oyun</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Game ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $transaction->id }}</td>
                        <td class="py-4 px-4">
                            @if($transaction->user)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <span class="text-blue-500 font-semibold text-sm">
                                        {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $transaction->user->name }}</div>
                                    <div class="text-sm text-gray-400">ID: {{ $transaction->user_id }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400">Kullanıcı bulunamadı (ID: {{ $transaction->user_id }})</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($transaction->type == 'bet')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                    <i data-lucide="trending-down" class="w-3 h-3 mr-1"></i>
                                    Bahis
                                </span>
                            @elseif($transaction->type == 'win')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                                    Kazanç
                                </span>
                            @elseif($transaction->type == 'refund')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                    <i data-lucide="refresh-cw" class="w-3 h-3 mr-1"></i>
                                    İade
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/10 text-gray-500 border border-gray-500/20">
                                    {{ $transaction->type }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($transaction->type == 'bet')
                                <span class="text-red-500 font-medium">-₺{{ number_format($transaction->amount, 2) }}</span>
                            @else
                                <span class="text-green-500 font-medium">+₺{{ number_format($transaction->amount, 2) }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($transaction->gamename)
                                <span class="text-white">{{ $transaction->gamename }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $transaction->gameid ?: '-' }}
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d.m.Y H:i:s') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $transactions->firstItem() }}-{{ $transactions->lastItem() }} / {{ $transactions->total() }} işlem
            </div>
            <div class="flex items-center gap-2">
                @if($transactions->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 