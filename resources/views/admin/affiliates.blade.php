@extends('layouts.admin')

@section('title', 'Affialiteler')

@section('content')
<div class="w-full">
    <div class="search-card">
        <div class="flex items-center justify-between gap-3">
            <div class="relative w-full max-w-md">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <form method="GET" action="{{ route('admin.affiliates') }}">
                    <input type="text" name="search" value="{{ request('search') }}" class="search-input" placeholder="Affiliate ara (kullanıcı adı, ad, e-posta)">
                </form>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header flex items-center justify-between">
            <div class="card-title flex items-center gap-2">
                <i data-lucide="network"></i>
                <span>Affialiteler</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-800">
                <thead class="bg-zinc-900/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Kullanıcı Adı</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Ad Soyad</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">E-Posta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Kayıt Tarihi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Alt Üye Sayısı</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Toplam Yatırım</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Toplam Çekim</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($affiliates as $aff)
                    <tr class="hover:bg-zinc-900/40">
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $aff->id }}</td>
                        <td class="px-4 py-3 text-sm text-white font-medium">{{ $aff->username }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $aff->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $aff->email }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ optional($aff->kayit_tarih)->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-300">{{ $subCounts[$aff->id] ?? 0 }}</td>
                        <td class="px-4 py-3 text-sm text-green-400">₺{{ number_format($depositTotals[$aff->id] ?? 0, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-rose-400">₺{{ number_format($withdrawTotals[$aff->id] ?? 0, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <a href="#" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 hover:border-yellow-500/60 transition-colors view-subs" data-aff-id="{{ $aff->id }}" data-aff-name="{{ $aff->username }}">
                                <i data-lucide="users" class="w-4 h-4"></i>
                                Alt Üyeleri Gör
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-zinc-400">Kayıt bulunamadı.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $affiliates->withQueryString()->links() }}</div>
    </div>

    <!-- Alt Üyeler Modal -->
    <div id="subs-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="relative bg-zinc-900 border border-zinc-700 rounded-xl w-full max-w-3xl mx-4 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-yellow-500"></i>
                    <h3 class="text-lg font-semibold text-white"><span id="subs-title"></span> - Alt Üyeler</h3>
                </div>
                <button id="subs-close" class="p-2 rounded-lg bg-zinc-800 hover:bg-zinc-700">
                    <i data-lucide="x" class="w-4 h-4 text-zinc-300"></i>
                </button>
            </div>
            <div id="subs-content" class="overflow-x-auto">
                <div class="py-8 text-center text-zinc-400">Yükleniyor...</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    const modal = document.getElementById('subs-modal');
    const title = document.getElementById('subs-title');
    const content = document.getElementById('subs-content');
    const closeBtn = document.getElementById('subs-close');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e){ if(e.target === modal) closeModal(); });

    document.querySelectorAll('.view-subs').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const affId = this.dataset.affId;
            const affName = this.dataset.affName;
            title.textContent = affName;
            content.innerHTML = '<div class="py-8 text-center text-zinc-400">Yükleniyor...</div>';
            openModal();

            fetch(`{{ url('admin/affiliates') }}?aff_id=${affId}&load=subs`)
                .then(r => r.json())
                .then(data => {
                    if (!data || !data.subs) {
                        content.innerHTML = '<div class="py-8 text-center text-zinc-400">Alt üye bulunamadı.</div>';
                        return;
                    }
                    let html = '';
                    html += '<table class="min-w-full divide-y divide-zinc-800">';
                    html += '<thead class="bg-zinc-900/60">'
                         + '<tr>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">ID</th>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Kullanıcı Adı</th>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Ad Soyad</th>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">E-Posta</th>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Kayıt Tarihi</th>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Yatırım (onaylı)</th>'
                         + '<th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Çekim (onaylı)</th>'
                         + '</tr>'
                         + '</thead>';
                    html += '<tbody class="divide-y divide-zinc-800">';
                    data.subs.forEach(u => {
                        html += '<tr class="hover:bg-zinc-900/40">'
                             + `<td class="px-4 py-3 text-sm text-zinc-300">${u.id}</td>`
                             + `<td class="px-4 py-3 text-sm text-white font-medium">${u.username || ''}</td>`
                             + `<td class="px-4 py-3 text-sm text-zinc-300">${u.name || ''}</td>`
                             + `<td class="px-4 py-3 text-sm text-zinc-300">${u.email || ''}</td>`
                             + `<td class="px-4 py-3 text-sm text-zinc-300">${u.kayit_tarih || ''}</td>`
                             + `<td class=\"px-4 py-3 text-sm text-green-400\">₺${(u.deposit_total || 0).toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>`
                             + `<td class=\"px-4 py-3 text-sm text-rose-400\">₺${(u.withdraw_total || 0).toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>`
                             + '</tr>';
                    });
                    html += '</tbody></table>';
                    content.innerHTML = html;
                    lucide.createIcons();
                })
                .catch(() => {
                    content.innerHTML = '<div class="py-8 text-center text-red-400">Alt üyeler yüklenemedi.</div>';
                });
        });
    });
});
</script>
@endsection


