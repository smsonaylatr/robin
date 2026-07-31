@extends('layouts.admin')

@section('title', 'Duyuru Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Duyuru Yönetimi</h1>
            <p class="text-gray-400 mt-1">Site duyurularını yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAddModal()" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Duyuru
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Duyuru</p>
                    <p class="stat-card-value">{{ $duyurular->count() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Duyuru</p>
                    <p class="stat-card-value">{{ $duyurular->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Bu Ay</p>
                    <p class="stat-card-value">{{ $duyurular->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Duyuru Management -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Duyuru Listesi</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Görsel</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Konu</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Açıklama</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">URL</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tarih</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @forelse($duyurular as $duyuru)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $duyuru->id }}</td>
                        <td class="py-4 px-4">
                            <div class="w-16 h-16 rounded-lg bg-zinc-800 flex items-center justify-center overflow-hidden border border-zinc-700">
                                @if($duyuru->resim)
                                    <img src="{{ $duyuru->resim }}" alt="Duyuru {{ $duyuru->id }}" class="w-full h-full object-contain">
                                @else
                                    <i data-lucide="image" class="w-6 h-6 text-gray-500"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-medium text-white">{{ $duyuru->konu }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm text-gray-300 max-w-xs truncate">
                                {{ Str::limit($duyuru->aciklama, 100) }}
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            @if($duyuru->url)
                                <a href="{{ $duyuru->url }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm">
                                    {{ Str::limit($duyuru->url, 30) }}
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <button onclick="toggleDuyuruStatus({{ $duyuru->id }})" 
                                    class="px-2 py-1 text-xs {{ $duyuru->status == 1 ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20' }} border rounded hover:opacity-80 transition-colors">
                                {{ $duyuru->status == 1 ? 'Aktif' : 'Pasif' }}
                            </button>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $duyuru->created_at ? \Carbon\Carbon::parse($duyuru->created_at)->format('d.m.Y H:i') : '-' }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button onclick="editDuyuru({{ $duyuru->id }})" 
                                        data-konu="{{ $duyuru->konu }}"
                                        data-aciklama="{{ $duyuru->aciklama }}"
                                        data-url="{{ $duyuru->url }}"
                                        data-status="{{ $duyuru->status }}"
                                        data-resim="{{ $duyuru->resim }}"
                                        class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="deleteDuyuru({{ $duyuru->id }})" 
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Sil">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 px-4 text-center text-gray-400">
                            <i data-lucide="megaphone" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                            Duyuru bulunamadı
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Duyuru Modal -->
<div id="duyuruModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white" id="modalTitle">Yeni Duyuru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="duyuruForm" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="duyuruId">
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Konu</label>
                <input type="text" id="duyuruKonu" required placeholder="Duyuru konusu..."
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Açıklama</label>
                <textarea id="duyuruAciklama" rows="3" placeholder="Duyuru açıklaması..."
                          class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">URL (Opsiyonel)</label>
                <input type="text" id="duyuruUrl" placeholder="https://example.com veya /games/234"
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Görsel (Opsiyonel)</label>
                <input type="file" id="duyuruResim" accept="image/*" 
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Opsiyonel. Boyut: 400x200 - 800x600px, Max: 2MB</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Durum</label>
                <select id="duyuruStatus" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                </select>
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeModal()" 
                        class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 hidden">
    <div id="messageContent" class="px-4 py-3 rounded-lg text-white font-medium"></div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Yeni Duyuru';
    document.getElementById('duyuruForm').reset();
    document.getElementById('duyuruId').value = '';
    document.getElementById('duyuruModal').classList.remove('hidden');
}

function editDuyuru(id) {
    const button = event.target.closest('button');
    const konu = button.getAttribute('data-konu');
    const aciklama = button.getAttribute('data-aciklama');
    const url = button.getAttribute('data-url');
    const status = button.getAttribute('data-status');
    const resim = button.getAttribute('data-resim');
    
    document.getElementById('modalTitle').textContent = 'Duyuru Düzenle';
    document.getElementById('duyuruId').value = id;
    document.getElementById('duyuruKonu').value = konu;
    document.getElementById('duyuruAciklama').value = aciklama;
    document.getElementById('duyuruUrl').value = url;
    document.getElementById('duyuruStatus').value = status;
    document.getElementById('duyuruModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('duyuruModal').classList.add('hidden');
}

function deleteDuyuru(id) {
    if (!confirm('Bu duyuruyu silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    fetch(`/admin/duyurular/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage('Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu!', 'error');
    });
}

function toggleDuyuruStatus(id) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    fetch(`/admin/duyurular/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage('Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu!', 'error');
    });
}

document.getElementById('duyuruForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    const id = document.getElementById('duyuruId').value;
    const formData = new FormData();
    formData.append('konu', document.getElementById('duyuruKonu').value);
    formData.append('aciklama', document.getElementById('duyuruAciklama').value);
    formData.append('url', document.getElementById('duyuruUrl').value);
    formData.append('status', document.getElementById('duyuruStatus').value);
    
    const resimFile = document.getElementById('duyuruResim').files[0];
    if (resimFile) {
        formData.append('resim', resimFile);
    }
    
    const url = id ? `/admin/duyurular/${id}/update` : '/admin/duyurular/store';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage('Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu!', 'error');
    });
});

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const content = document.getElementById('messageContent');
    
    content.textContent = message;
    content.className = `px-4 py-3 rounded-lg text-white font-medium ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    
    container.classList.remove('hidden');
    
    setTimeout(() => {
        container.classList.add('hidden');
    }, 3000);
}
</script>
@endsection 