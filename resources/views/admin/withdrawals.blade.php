@extends('layouts.admin')

@section('title', 'Para Çekme Talepleri')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Para Çekme Talepleri</h1>
            <p class="text-gray-400 mt-1">Kullanıcı para çekme taleplerini yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                Toplu İşlem
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Talep</p>
                    <p class="stat-card-value">{{ $withdrawals->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="upload" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Bekleyen</p>
                    <p class="stat-card-value">{{ $withdrawals->where('durum', 0)->count() }}</p>
                </div>
                <div class="stat-card-icon amber">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Onaylanan</p>
                    <p class="stat-card-value">{{ $withdrawals->where('durum', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Tutar</p>
                    <p class="stat-card-value">{{ number_format($withdrawals->where('durum', 1)->sum('miktar'), 2) }} TL</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="coins" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">Durum</label>
                <select class="w-full h-10 bg-zinc-800 border border-zinc-700 rounded-lg px-3 text-white focus:border-yellow-500 focus:outline-none">
                    <option value="">Tüm Durumlar</option>
                    <option value="0">Bekleyen</option>
                    <option value="1">Onaylanan</option>
                    <option value="2">Reddedilen</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">Kullanıcı ID</label>
                <input type="text" placeholder="Kullanıcı ID girin..." class="w-full h-10 bg-zinc-800 border border-zinc-700 rounded-lg px-3 text-white focus:border-yellow-500 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                    <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                    Filtrele
                </button>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Para Çekme Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $withdrawals->firstItem() }}-{{ $withdrawals->lastItem() }} / {{ $withdrawals->total() }} talep
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Kullanıcı</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Miktar</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Banka Bilgileri</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tarih</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($withdrawals as $withdrawal)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $withdrawal->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                                    <span class="text-red-500 font-semibold text-sm">
                                        {{ strtoupper(substr($withdrawal->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $withdrawal->user->name ?? 'Bilinmiyor' }}</div>
                                    <div class="text-sm text-gray-400">ID: {{ $withdrawal->user_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white font-medium">{{ number_format($withdrawal->miktar, 2) }} TL</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white">{{ $withdrawal->banka }}</div>
                                @if($withdrawal->sube)
                                    <div class="text-gray-400">Şube: {{ $withdrawal->sube }}</div>
                                @endif
                                @if($withdrawal->hesap)
                                    <div class="text-gray-400">Hesap: {{ $withdrawal->hesap }}</div>
                                @endif
                                @if($withdrawal->iban)
                                    <div class="text-gray-400">IBAN: {{ $withdrawal->iban }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $withdrawal->tarih ? $withdrawal->tarih->format('d.m.Y H:i') : '-' }}
                        </td>
                        <td class="py-4 px-4">
                            @if($withdrawal->durum == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                    Bekliyor
                                </span>
                            @elseif($withdrawal->durum == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Onaylandı
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    Reddedildi
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors" title="Detaylar">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                @if($withdrawal->durum == 0)
                                    <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-colors" title="Onayla">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Reddet">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($withdrawals->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $withdrawals->firstItem() }}-{{ $withdrawals->lastItem() }} / {{ $withdrawals->total() }} talep
            </div>
            <div class="flex items-center gap-2">
                @if($withdrawals->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $withdrawals->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($withdrawals->getUrlRange(1, $withdrawals->lastPage()) as $page => $url)
                    @if($page == $withdrawals->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($withdrawals->hasMorePages())
                    <a href="{{ $withdrawals->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 