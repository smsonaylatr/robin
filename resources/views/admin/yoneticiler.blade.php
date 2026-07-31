@extends('layouts.admin')

@section('title', 'Yöneticiler')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Yöneticiler</h1>
            <p class="text-gray-400 mt-1">Sistemdeki tüm yöneticileri yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Yeni Yönetici
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Yönetici</p>
                    <p class="stat-card-value">{{ $yoneticiler->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Yönetici</p>
                    <p class="stat-card-value">{{ $yoneticiler->where('durum', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Bu Ay Kayıt</p>
                    <p class="stat-card-value">{{ $yoneticiler->where('olusturulma_tarihi', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Yoneticiler Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Yönetici Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $yoneticiler->firstItem() }}-{{ $yoneticiler->lastItem() }} / {{ $yoneticiler->total() }} yönetici
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Yönetici</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İletişim</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Yetki</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Kayıt Tarihi</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Son Giriş</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($yoneticiler as $yonetici)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $yonetici->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center">
                                    <span class="text-purple-500 font-semibold text-sm">
                                        {{ strtoupper(substr($yonetici->kullanici_adi, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $yonetici->kullanici_adi }}</div>
                                    <div class="text-sm text-gray-400">Yönetici</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white">{{ $yonetici->eposta ?? 'E-posta yok' }}</div>
                                <div class="text-gray-400">{{ $yonetici->telefon ?? 'Telefon yok' }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-500/10 text-purple-500 border border-purple-500/20">
                                <i data-lucide="crown" class="w-3 h-3 mr-1"></i>
                                {{ ucfirst($yonetici->yetki ?? 'admin') }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $yonetici->olusturulma_tarihi->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            @if($yonetici->son_giris)
                                {{ \Carbon\Carbon::parse($yonetici->son_giris)->format('d.m.Y H:i') }}
                            @else
                                <span class="text-gray-500">Hiç giriş yapmamış</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($yonetici->durum == 1)
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
                            <div class="flex items-center gap-2">
                                <button onclick="editYonetici({{ $yonetici->id }}, '{{ $yonetici->kullanici_adi }}', '{{ $yonetici->eposta ?? '' }}', '{{ $yonetici->telefon ?? '' }}', '{{ $yonetici->yetki }}', {{ $yonetici->durum ?? 1 }})" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($yonetici->durum == 1)
                                    <form method="POST" action="{{ route('admin.yoneticiler.toggle-status', $yonetici->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Pasif Yap" onclick="return confirm('Yöneticiyi pasif yapmak istediğinizden emin misiniz?')">
                                            <i data-lucide="user-x" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.yoneticiler.toggle-status', $yonetici->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-colors" title="Aktif Yap" onclick="return confirm('Yöneticiyi aktif yapmak istediğinizden emin misiniz?')">
                                            <i data-lucide="user-check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.yoneticiler.delete', $yonetici->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Sil" onclick="return confirm('Bu yöneticiyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($yoneticiler->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $yoneticiler->firstItem() }}-{{ $yoneticiler->lastItem() }} / {{ $yoneticiler->total() }} yönetici
            </div>
            <div class="flex items-center gap-2">
                @if($yoneticiler->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $yoneticiler->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($yoneticiler->getUrlRange(1, $yoneticiler->lastPage()) as $page => $url)
                    @if($page == $yoneticiler->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($yoneticiler->hasMorePages())
                    <a href="{{ $yoneticiler->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit Yonetici Modal -->
<div id="editYoneticiModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-bold text-white">Yönetici Düzenle</h3>
                <p class="text-gray-400 mt-1">Yönetici bilgilerini güncelleyin</p>
            </div>
            <button onclick="closeEditYoneticiModal()" class="p-2 text-gray-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form id="editYoneticiForm" method="POST" action="" class="space-y-6">
            @csrf
            <input type="hidden" id="editYoneticiId" name="yonetici_id">

            
            <!-- Yonetici Avatar & Basic Info -->
            <div class="flex items-center gap-6 p-6 bg-zinc-800/30 rounded-lg border border-zinc-700">
                <div class="w-16 h-16 rounded-full bg-purple-500/20 flex items-center justify-center">
                    <span id="editYoneticiAvatar" class="text-purple-500 font-bold text-xl"></span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h4 id="editYoneticiDisplayName" class="text-xl font-bold text-white"></h4>
                        <span id="editYoneticiStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                    </div>
                    <div class="text-sm text-gray-400">
                        <span>ID: #<span id="editYoneticiDisplayId"></span></span>
                        <span class="mx-2">•</span>
                        <span id="editYoneticiDisplayEmail"></span>
                    </div>
                </div>
            </div>
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                        Kullanıcı Adı
                    </label>
                    <input type="text" id="editYoneticiUsername" name="kullanici_adi" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="mail" class="w-4 h-4 inline mr-2"></i>
                        E-posta
                    </label>
                    <input type="email" id="editYoneticiEmail" name="eposta" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors">
                </div>
            </div>
            
            <!-- Contact & Permissions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="phone" class="w-4 h-4 inline mr-2"></i>
                        Telefon
                    </label>
                    <input type="text" id="editYoneticiPhone" name="telefon" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="shield" class="w-4 h-4 inline mr-2"></i>
                        Yetki
                    </label>
                    <select id="editYoneticiYetki" name="yetki" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors">
                        <option value="admin">👑 Admin - Tam yetki</option>
                        <option value="moderator">🛡️ Moderator - Sınırlı yetki</option>
                    </select>
                </div>
            </div>
            
            <!-- Status & Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="activity" class="w-4 h-4 inline mr-2"></i>
                        Hesap Durumu
                    </label>
                    <select id="editYoneticiStatus" name="durum" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors">
                        <option value="1">🟢 Aktif - Hesap kullanılabilir</option>
                        <option value="0">🔴 Pasif - Hesap askıya alınmış</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i>
                        Şifre Değiştir
                    </label>
                    <button type="button" onclick="togglePasswordFields()" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white hover:border-yellow-500 transition-colors flex items-center justify-between">
                        <span>Şifre değiştirmek için tıklayın</span>
                        <i data-lucide="chevron-down" class="w-4 h-4" id="passwordToggleIcon"></i>
                    </button>
                </div>
            </div>
            
            <!-- Password Fields (Hidden by default) -->
            <div id="passwordFields" class="hidden space-y-4 p-4 bg-zinc-800/30 rounded-lg border border-zinc-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                            Yeni Şifre
                        </label>
                        <input type="password" id="editYoneticiPassword" name="yeni_sifre" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" placeholder="Yeni şifre girin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                            Şifre Tekrar
                        </label>
                        <input type="password" id="editYoneticiPasswordConfirm" name="yeni_sifre_tekrar" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" placeholder="Şifreyi tekrar girin">
                    </div>
                </div>
                <div class="text-sm text-gray-400">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Şifre değiştirmek istemiyorsanız bu alanları boş bırakın.
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6 border-t border-zinc-700">
                <button type="button" onclick="closeEditYoneticiModal()" class="flex-1 px-6 py-3 bg-zinc-800 text-white rounded-lg hover:bg-zinc-700 transition-colors border border-zinc-700">
                    <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                    İptal
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                    <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editYonetici(yoneticiId, kullaniciAdi, eposta, telefon, yetki, durum) {
    // Form fields
    document.getElementById('editYoneticiId').value = yoneticiId;
    document.getElementById('editYoneticiForm').action = `/admin/yoneticiler/${yoneticiId}/update`;
    document.getElementById('editYoneticiUsername').value = kullaniciAdi;
    document.getElementById('editYoneticiEmail').value = eposta;
    document.getElementById('editYoneticiPhone').value = telefon;
    document.getElementById('editYoneticiYetki').value = yetki;
    document.getElementById('editYoneticiStatus').value = durum;
    
    // Display fields
    document.getElementById('editYoneticiDisplayId').textContent = yoneticiId;
    document.getElementById('editYoneticiDisplayName').textContent = kullaniciAdi;
    document.getElementById('editYoneticiDisplayEmail').textContent = eposta || 'E-posta yok';
    document.getElementById('editYoneticiAvatar').textContent = kullaniciAdi.charAt(0).toUpperCase();
    
    // Status badge
    const statusBadge = document.getElementById('editYoneticiStatusBadge');
    if (durum == 1) {
        statusBadge.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>Aktif';
        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20';
    } else {
        statusBadge.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>Pasif';
        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20';
    }
    
    // Show modal
    document.getElementById('editYoneticiModal').classList.remove('hidden');
    document.getElementById('editYoneticiModal').classList.add('flex');
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeEditYoneticiModal() {
    document.getElementById('editYoneticiModal').classList.add('hidden');
    document.getElementById('editYoneticiModal').classList.remove('flex');
    
    // Şifre alanlarını gizle
    document.getElementById('passwordFields').classList.add('hidden');
    document.getElementById('passwordToggleIcon').innerHTML = '<i data-lucide="chevron-down" class="w-4 h-4"></i>';
    
    // Şifre alanlarını temizle
    document.getElementById('editYoneticiPassword').value = '';
    document.getElementById('editYoneticiPasswordConfirm').value = '';
}

function togglePasswordFields() {
    const passwordFields = document.getElementById('passwordFields');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    
    if (passwordFields.classList.contains('hidden')) {
        passwordFields.classList.remove('hidden');
        toggleIcon.innerHTML = '<i data-lucide="chevron-up" class="w-4 h-4"></i>';
    } else {
        passwordFields.classList.add('hidden');
        toggleIcon.innerHTML = '<i data-lucide="chevron-down" class="w-4 h-4"></i>';
        
        // Şifre alanlarını temizle
        document.getElementById('editYoneticiPassword').value = '';
        document.getElementById('editYoneticiPasswordConfirm').value = '';
    }
    
    // Lucide icon'ları yenile
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Modal dışına tıklandığında kapat
document.getElementById('editYoneticiModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditYoneticiModal();
    }
});
</script>
@endpush 