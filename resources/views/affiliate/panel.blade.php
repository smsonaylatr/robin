@extends('layouts.affiliate')

@section('title', 'Affiliate Paneli')

@section('content')
<div class="min-h-screen py-6 px-4">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Hero / Summary -->
        <section class="relative overflow-hidden rounded-2xl border border-zinc-800/60 bg-gradient-to-br from-zinc-900 via-zinc-900/60 to-transparent p-5 md:p-6">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-40"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-white text-xl md:text-2xl font-bold tracking-tight">Affiliate Panel</h1>
                    <p class="text-zinc-400 text-sm mt-1">Alt üyelerini yönet, performansını takip et ve gelirini artır.</p>
                </div>
                <!-- Referral Link -->
                <div class="w-full md:w-[52%]">
                    <label class="text-zinc-400 text-xs mb-1 block">Referans Linkin</label>
                    <div class="flex items-stretch rounded-lg overflow-hidden border border-zinc-700/60 bg-zinc-900/60">
                        <input type="text" id="referral-link" value="{{ url('/register?ref=' . $user->id) }}" readonly class="flex-1 px-3 py-2 bg-transparent text-white text-xs md:text-sm focus:outline-none">
                        <button onclick="copyReferralLink()" class="px-3 md:px-4 bg-amber-500/20 hover:bg-amber-500/30 border-l border-zinc-700/60 text-amber-400 text-xs md:text-sm whitespace-nowrap">Kopyala</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Date Range Filter -->
        <div class="relative overflow-hidden rounded-xl bg-zinc-900/50 border border-zinc-800/60 p-4">
            <form method="GET" action="{{ route('affiliate.panel') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-zinc-400 text-sm">Başlangıç:</label>
                    <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                           class="px-3 py-2 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-zinc-400 text-sm">Bitiş:</label>
                    <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                           class="px-3 py-2 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white text-sm focus:outline-none focus:border-amber-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg hover:bg-amber-500/30 text-sm">
                    Filtrele
                </button>
                <a href="{{ route('affiliate.panel') }}" class="px-4 py-2 bg-zinc-800/50 text-zinc-400 border border-zinc-700/50 rounded-lg hover:bg-zinc-700/50 text-sm">
                    Sıfırla
                </a>
            </form>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @php
                $kpis = [
                    ['label' => 'Alt Üye', 'value' => number_format($subs->count()), 'icon' => 'users', 'color' => 'from-cyan-500 to-blue-500'],
                    ['label' => 'Toplam Yatırım', 'value' => '₺'.number_format($totalDeposits, 2, ',', '.'), 'icon' => 'arrow-down-circle', 'color' => 'from-green-500 to-emerald-500'],
                    ['label' => 'Komisyon Oranı', 'value' => '%'.number_format($commissionRate, 0), 'icon' => 'percent', 'color' => 'from-violet-500 to-fuchsia-500'],
                    ['label' => 'Çekilebilir Tutar', 'value' => '₺'.number_format($user->bakiye ?? 0, 2, ',', '.'), 'icon' => 'wallet', 'color' => 'from-amber-500 to-yellow-500'],
                    ['label' => 'Bugün Yatırım', 'value' => '₺'.number_format($todayDeposits, 2, ',', '.'), 'icon' => 'calendar', 'color' => 'from-sky-500 to-cyan-500'],
                    ['label' => 'Bu Ay Yatırım', 'value' => '₺'.number_format($monthDeposits, 2, ',', '.'), 'icon' => 'bar-chart-3', 'color' => 'from-indigo-500 to-purple-500'],
                ];
            @endphp
            @foreach($kpis as $k)
            <div class="stat-card p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-white text-base md:text-lg font-bold">{{ $k['value'] }}</div>
                        <div class="text-zinc-400 text-xs">{{ $k['label'] }}</div>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $k['color'] }}/20 border border-zinc-700/50 flex items-center justify-center">
                        <i data-lucide="{{ $k['icon'] }}" class="w-4 h-4 text-zinc-200"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Subs Section -->
        <div class="relative overflow-hidden rounded-xl bg-zinc-900/50 border border-zinc-800/60">
            <div class="p-4 flex items-center justify-between gap-3">
                <h2 class="text-white font-semibold">Alt Üyeler</h2>
                <div class="relative w-full max-w-xs">
                    <input type="text" id="subs-search" class="w-full pl-3 pr-3 py-2 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-200 text-sm" placeholder="Alt üye ara...">
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 sm:hidden gap-3 px-4 pb-4" id="subs-cards">
                @foreach($subs as $s)
                <div class="rounded-lg border border-zinc-800 bg-zinc-900/60 p-3" data-filter-text="{{ strtolower($s->id.' '.$s->username.' '.$s->name.' '.$s->email) }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-white text-sm font-semibold truncate">{{ $s->username }} <span class="text-zinc-400 font-normal">({{ $s->name }})</span></div>
                        <span class="text-zinc-400 text-xs">#{{ $s->id }}</span>
                    </div>
                    <div class="text-zinc-400 text-xs mb-2 truncate">{{ $s->email }}</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-md bg-green-500/10 border border-green-500/20 p-2 text-center">
                            <div class="text-[10px] text-zinc-400">Yatırım</div>
                            <div class="text-green-400 text-xs font-semibold">₺{{ number_format($perUserDeposits[$s->id] ?? 0, 2, ',', '.') }}</div>
                        </div>
                        <div class="rounded-md bg-amber-500/10 border border-amber-500/20 p-2 text-center">
                            <div class="text-[10px] text-zinc-400">Komisyon</div>
                            <div class="text-amber-400 text-xs font-semibold">₺{{ number_format(($perUserDeposits[$s->id] ?? 0) * $commissionRate / 100, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="overflow-x-auto hidden sm:block">
                <table class="min-w-full divide-y divide-zinc-800">
                    <thead class="bg-zinc-900/60">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Kullanıcı</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Kayıt</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Yatırım (onaylı)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Komisyon</th>
                        </tr>
                    </thead>
                    <tbody id="subs-body" class="divide-y divide-zinc-800">
                        @foreach($subs as $s)
                        <tr class="hover:bg-zinc-900/40">
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $s->id }}</td>
                            <td class="px-4 py-3 text-sm text-white">{{ $s->username }} <span class="text-zinc-400">({{ $s->name }})</span></td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $s->email }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ optional($s->kayit_tarih)->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-green-400">₺{{ number_format($perUserDeposits[$s->id] ?? 0, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-amber-400">₺{{ number_format(($perUserDeposits[$s->id] ?? 0) * $commissionRate / 100, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById('subs-search');
    const tbody = document.getElementById('subs-body');
    const cards = document.getElementById('subs-cards');
    if (search) {
        search.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            if (tbody) {
                Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
                    const text = tr.textContent.toLowerCase();
                    tr.style.display = text.includes(term) ? '' : 'none';
                });
            }
            if (cards) {
                Array.from(cards.children).forEach(card => {
                    const text = (card.getAttribute('data-filter-text') || '').toLowerCase();
                    card.style.display = text.includes(term) ? '' : 'none';
                });
            }
        });
    }
});

function copyReferralLink() {
    const linkInput = document.getElementById('referral-link');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        // Show success message
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Kopyalandı!';
        button.classList.add('bg-green-500/20', 'text-green-400', 'border-green-500/30');
        button.classList.remove('bg-amber-500/20', 'text-amber-400', 'border-amber-500/30');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-500/20', 'text-green-400', 'border-green-500/30');
            button.classList.add('bg-amber-500/20', 'text-amber-400', 'border-amber-500/30');
        }, 2000);
    } catch (err) {
        console.error('Kopyalama başarısız:', err);
    }
}
</script>
@endsection


