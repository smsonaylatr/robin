@extends('layouts.app')

@section('title', 'Hesap Hareketleri - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Hesap Hareketleri</h1>
        <p class="text-zinc-400">Tüm işlem geçmişiniz</p>
    </div>

    <!-- Filters -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6 mb-8">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-white mb-2">İşlem Türü</label>
                <select id="type-filter" class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Tümü</option>
                    <option value="bet">Bahis</option>
                    <option value="win">Kazanç</option>
                    <option value="deposit">Para Yatırma</option>
                    <option value="withdrawal">Para Çekme</option>
                    <option value="bonus">Bonus</option>
                </select>
            </div>
            
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-white mb-2">Tarih Aralığı</label>
                <select id="date-filter" class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Tümü</option>
                    <option value="today">Bugün</option>
                    <option value="week">Bu Hafta</option>
                    <option value="month">Bu Ay</option>
                    <option value="year">Bu Yıl</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button id="filter-btn" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Filtrele
                </button>
            </div>
        </div>
    </div>

    <!-- Transactions -->
    <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-700">
                            <th class="text-left py-3 px-4 text-white font-medium">Tarih</th>
                            <th class="text-left py-3 px-4 text-white font-medium">İşlem Türü</th>
                            <th class="text-left py-3 px-4 text-white font-medium">Açıklama</th>
                            <th class="text-right py-3 px-4 text-white font-medium">Miktar</th>
                            <th class="text-right py-3 px-4 text-white font-medium">Bakiye</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3 px-4 text-zinc-300 text-sm">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d.m.Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $transaction->type === 'win' ? 'bg-green-500/20 text-green-400' : ($transaction->type === 'bet' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') }}">
                                    {{ $transaction->type === 'win' ? 'Kazanç' : ($transaction->type === 'bet' ? 'Bahis' : ucfirst($transaction->type)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-zinc-300 text-sm">
                                @if($transaction->gameid && $transaction->game)
                                    {{ $transaction->game->name ?? 'Oyun' }}
                                @else
                                    {{ ucfirst($transaction->type) }} işlemi
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="font-medium {{ $transaction->type === 'win' ? 'text-green-400' : ($transaction->type === 'bet' ? 'text-red-400' : 'text-blue-400') }}">
                                    {{ $transaction->type === 'bet' ? '-' : '+' }}{{ $user->parabirimi }}{{ number_format($transaction->amount, 2) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right text-zinc-300 text-sm">
                                {{ $user->parabirimi }}{{ number_format($transaction->balance_after ?? 0, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">📊</span>
                </div>
                <p class="text-zinc-400 text-lg mb-2">Henüz işlem bulunmuyor</p>
                <p class="text-zinc-500 text-sm">İlk işleminizi yaptığınızda burada görünecek</p>
            </div>
        @endif
    </div>

    <!-- Summary -->
    @if($transactions->count() > 0)
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-400 text-sm">Toplam Kazanç</p>
                    <p class="text-2xl font-bold text-white">
                        {{ $user->parabirimi }}{{ number_format($transactions->where('type', 'win')->sum('amount'), 2) }}
                    </p>
                </div>
                <span class="text-2xl">💰</span>
            </div>
        </div>
        
        <div class="bg-red-900/20 border border-red-500/30 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-400 text-sm">Toplam Bahis</p>
                    <p class="text-2xl font-bold text-white">
                        {{ $user->parabirimi }}{{ number_format($transactions->where('type', 'bet')->sum('amount'), 2) }}
                    </p>
                </div>
                <span class="text-2xl">🎯</span>
            </div>
        </div>
        
        <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-400 text-sm">Net Kazanç</p>
                    <p class="text-2xl font-bold text-white">
                        {{ $user->parabirimi }}{{ number_format($transactions->where('type', 'win')->sum('amount') - $transactions->where('type', 'bet')->sum('amount'), 2) }}
                    </p>
                </div>
                <span class="text-2xl">📈</span>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filter-btn');
    const typeFilter = document.getElementById('type-filter');
    const dateFilter = document.getElementById('date-filter');
    
    filterBtn.addEventListener('click', function() {
        const type = typeFilter.value;
        const date = dateFilter.value;
        
        let url = new URL(window.location);
        if (type) url.searchParams.set('type', type);
        else url.searchParams.delete('type');
        
        if (date) url.searchParams.set('date', date);
        else url.searchParams.delete('date');
        
        window.location.href = url.toString();
    });
});
</script>
@endsection 