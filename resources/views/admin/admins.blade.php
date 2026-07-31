@extends('layouts.admin')

@section('title', 'Adminler')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Adminler</h1>
            <p class="text-gray-400 mt-1">Sistemdeki tüm adminleri yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Yeni Admin
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Admin</p>
                    <p class="stat-card-value">{{ $admins->total() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Admin</p>
                    <p class="stat-card-value">{{ $admins->where('durum', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Bu Ay Kayıt</p>
                    <p class="stat-card-value">{{ $admins->where('kayit_tarih', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Admin Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $admins->firstItem() }}-{{ $admins->lastItem() }} / {{ $admins->total() }} admin
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Admin</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İletişim</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Bakiye</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Kayıt Tarihi</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Son Giriş</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($admins as $admin)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $admin->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <span class="text-blue-500 font-semibold text-sm">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $admin->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $admin->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white">{{ $admin->email }}</div>
                                <div class="text-gray-400">{{ $admin->telefon ?: 'Telefon yok' }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white font-medium">{{ $admin->parabirimi }}{{ number_format($admin->bakiye, 2) }}</div>
                                <div class="text-gray-400">{{ $admin->parabirimi }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $admin->kayit_tarih->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            @if($admin->songiris)
                                {{ \Carbon\Carbon::parse($admin->songiris)->format('d.m.Y H:i') }}
                            @else
                                <span class="text-gray-500">Hiç giriş yapmamış</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($admin->durum == 1)
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
                                <button class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors" title="Detaylar">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button onclick="editAdmin({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->email }}', '{{ $admin->telefon }}', {{ $admin->bakiye }}, {{ $admin->durum }})" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($admin->durum == 1)
                                    <button class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Pasif Yap">
                                        <i data-lucide="user-x" class="w-4 h-4"></i>
                                    </button>
                                @else
                                    <button class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-colors" title="Aktif Yap">
                                        <i data-lucide="user-check" class="w-4 h-4"></i>
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
        @if($admins->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $admins->firstItem() }}-{{ $admins->lastItem() }} / {{ $admins->total() }} admin
            </div>
            <div class="flex items-center gap-2">
                @if($admins->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $admins->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($admins->getUrlRange(1, $admins->lastPage()) as $page => $url)
                    @if($page == $admins->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($admins->hasMorePages())
                    <a href="{{ $admins->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit Admin Modal -->
<div id="editAdminModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-bold text-white">Admin Düzenle</h3>
                <p class="text-gray-400 mt-1">Admin bilgilerini güncelleyin</p>
            </div>
            <button onclick="closeEditAdminModal()" class="p-2 text-gray-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form id="editAdminForm" method="POST" class="space-y-6" action="">
            @csrf
            <input type="hidden" id="editAdminId" name="admin_id">
            
            <!-- Admin Avatar & Basic Info -->
            <div class="flex items-center gap-6 p-6 bg-zinc-800/30 rounded-lg border border-zinc-700">
                <div class="w-16 h-16 rounded-full bg-blue-500/20 flex items-center justify-center">
                    <span id="editAdminAvatar" class="text-blue-500 font-bold text-xl"></span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h4 id="editAdminDisplayName" class="text-xl font-bold text-white"></h4>
                        <span id="editAdminStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                    </div>
                    <div class="text-sm text-gray-400">
                        <span>ID: #<span id="editAdminDisplayId"></span></span>
                        <span class="mx-2">•</span>
                        <span id="editAdminDisplayEmail"></span>
                    </div>
                </div>
            </div>
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                        Ad Soyad
                    </label>
                    <input type="text" id="editAdminName" name="name" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="mail" class="w-4 h-4 inline mr-2"></i>
                        E-posta
                    </label>
                    <input type="email" id="editAdminEmail" name="email" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" required>
                </div>
            </div>
            
            <!-- Contact & Balance -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="phone" class="w-4 h-4 inline mr-2"></i>
                        Telefon
                    </label>
                    <input type="text" id="editAdminPhone" name="telefon" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="wallet" class="w-4 h-4 inline mr-2"></i>
                        Bakiye (TL)
                    </label>
                    <input type="number" id="editAdminBalance" name="bakiye" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" step="0.01" min="0">
                </div>
            </div>
            
            <!-- Password Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                        Yeni Şifre
                    </label>
                    <input type="password" id="editAdminPassword" name="yeni_sifre" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" placeholder="Yeni şifre girin">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="key" class="w-4 h-4 inline mr-2"></i>
                        Şifre Tekrar
                    </label>
                    <input type="password" id="editAdminPasswordConfirm" name="yeni_sifre_tekrar" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" placeholder="Şifreyi tekrar girin">
                </div>
            </div>
            
            <!-- Status & Permissions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i data-lucide="shield" class="w-4 h-4 inline mr-2"></i>
                        Hesap Durumu
                    </label>
                    <select id="editAdminStatus" name="durum" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors">
                        <option value="1">🟢 Aktif - Hesap kullanılabilir</option>
                        <option value="0">🔴 Pasif - Hesap askıya alınmış</option>
                    </select>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="p-4 bg-zinc-800/30 rounded-lg border border-zinc-700">
                <h4 class="text-white font-medium mb-3">Hızlı İşlemler</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <button type="button" onclick="showAdminBalanceModal('add')" class="px-3 py-2 text-sm bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded hover:bg-blue-500/20 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i>
                        Bakiye Ekle
                    </button>
                    <button type="button" onclick="showAdminBalanceModal('subtract')" class="px-3 py-2 text-sm bg-orange-500/10 text-orange-500 border border-orange-500/20 rounded hover:bg-orange-500/20 transition-colors">
                        <i data-lucide="minus" class="w-4 h-4 inline mr-1"></i>
                        Bakiye Çıkar
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6 border-t border-zinc-700">
                <button type="button" onclick="closeEditAdminModal()" class="flex-1 px-6 py-3 bg-zinc-800 text-white rounded-lg hover:bg-zinc-700 transition-colors border border-zinc-700">
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

<!-- Admin Balance Modal -->
<div id="adminBalanceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 id="adminBalanceModalTitle" class="text-xl font-bold text-white">Bakiye İşlemi</h3>
            <button onclick="closeAdminBalanceModal()" class="p-2 text-gray-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="adminBalanceForm" class="space-y-4">
            @csrf
            <input type="hidden" id="adminBalanceUserId" name="user_id">
            <input type="hidden" id="adminBalanceAction" name="action">
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Miktar (TL)</label>
                <input type="number" id="adminBalanceAmount" name="amount" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" step="0.01" min="0.01" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Açıklama</label>
                <textarea id="adminBalanceDescription" name="description" rows="3" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:outline-none transition-colors" placeholder="İşlem açıklaması (isteğe bağlı)"></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeAdminBalanceModal()" class="flex-1 px-4 py-3 bg-zinc-800 text-white rounded-lg hover:bg-zinc-700 transition-colors">
                    İptal
                </button>
                <button type="submit" id="adminBalanceSubmitBtn" class="flex-1 px-4 py-3 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentAdminId = null;

function editAdmin(adminId, name, email, telefon, bakiye, durum) {
    currentAdminId = adminId;
    
    // Set form action
    document.getElementById('editAdminForm').action = `/admin/admins/${adminId}/update`;
    
    // Form fields
    document.getElementById('editAdminId').value = adminId;
    document.getElementById('editAdminName').value = name;
    document.getElementById('editAdminEmail').value = email;
    document.getElementById('editAdminPhone').value = telefon;
    document.getElementById('editAdminBalance').value = bakiye;
    document.getElementById('editAdminStatus').value = durum;

    // Clear password fields
    document.getElementById('editAdminPassword').value = '';
    document.getElementById('editAdminPasswordConfirm').value = '';
    
    // Display fields
    document.getElementById('editAdminDisplayId').textContent = adminId;
    document.getElementById('editAdminDisplayName').textContent = name;
    document.getElementById('editAdminDisplayEmail').textContent = email;
    document.getElementById('editAdminAvatar').textContent = name.charAt(0).toUpperCase();
    
    // Status badge
    const statusBadge = document.getElementById('editAdminStatusBadge');
    if (durum == 1) {
        statusBadge.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>Aktif';
        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20';
    } else {
        statusBadge.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>Pasif';
        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20';
    }
    
    // Show modal
    document.getElementById('editAdminModal').classList.remove('hidden');
    document.getElementById('editAdminModal').classList.add('flex');
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeEditAdminModal() {
    document.getElementById('editAdminModal').classList.add('hidden');
    document.getElementById('editAdminModal').classList.remove('flex');
}

function showAdminBalanceModal(action) {
    if (!currentAdminId) {
        alert('Lütfen önce bir admin seçin!');
        return;
    }
    
    document.getElementById('adminBalanceUserId').value = currentAdminId;
    document.getElementById('adminBalanceAction').value = action;
    document.getElementById('adminBalanceAmount').value = '';
    document.getElementById('adminBalanceDescription').value = '';
    
    const modal = document.getElementById('adminBalanceModal');
    const title = document.getElementById('adminBalanceModalTitle');
    const submitBtn = document.getElementById('adminBalanceSubmitBtn');
    
    if (action === 'add') {
        title.textContent = 'Bakiye Ekle';
        submitBtn.textContent = 'Bakiye Ekle';
        submitBtn.className = 'flex-1 px-4 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-400 transition-colors';
    } else {
        title.textContent = 'Bakiye Çıkar';
        submitBtn.textContent = 'Bakiye Çıkar';
        submitBtn.className = 'flex-1 px-4 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-400 transition-colors';
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeAdminBalanceModal() {
    document.getElementById('adminBalanceModal').classList.add('hidden');
    document.getElementById('adminBalanceModal').classList.remove('flex');
}

// Admin balance form submission
document.getElementById('adminBalanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = formData.get('action');
    
    // CSRF token'ı form içinden al
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    fetch(`/admin/admins/${formData.get('user_id')}/balance`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAdminBalanceModal();
            
            // Başarı mesajı göster
            showSuccessMessage(data.message);
            
            // Bakiye alanını güncelle
            const balanceInput = document.getElementById('editAdminBalance');
            if (balanceInput) {
                balanceInput.value = data.new_balance;
            }
            
            // Form'u temizle
            document.getElementById('adminBalanceAmount').value = '';
            document.getElementById('adminBalanceDescription').value = '';
            
        } else {
            showErrorMessage(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Bir hata oluştu!');
    });
});

// Başarı mesajı göster
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(successDiv);
    
    // Animasyon ile göster
    setTimeout(() => {
        successDiv.classList.remove('translate-x-full');
    }, 100);
    
    // 3 saniye sonra kaldır
    setTimeout(() => {
        successDiv.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(successDiv);
        }, 300);
    }, 3000);
    
    // Lucide icon'ları yenile
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Hata mesajı göster
function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(errorDiv);
    
    // Animasyon ile göster
    setTimeout(() => {
        errorDiv.classList.remove('translate-x-full');
    }, 100);
    
    // 3 saniye sonra kaldır
    setTimeout(() => {
        errorDiv.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(errorDiv);
        }, 300);
    }, 3000);
    
    // Lucide icon'ları yenile
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Modal dışına tıklandığında kapat
document.getElementById('editAdminModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditAdminModal();
    }
});

document.getElementById('adminBalanceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAdminBalanceModal();
    }
});
</script>
@endpush
