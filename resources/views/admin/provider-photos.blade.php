@extends('layouts.admin')

@section('title', 'Footer Sağlayıcı Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Footer Sağlayıcı Ayarları</h1>
            <p class="text-gray-400 mt-1">Footer'da görünecek sağlayıcı logo görsellerini yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAddModal()" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Sağlayıcı Ekle
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Sağlayıcı</p>
                    <p class="stat-card-value">{{ $providers->count() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Sağlayıcı</p>
                    <p class="stat-card-value">{{ $providers->where('aktif', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Pasif Sağlayıcı</p>
                    <p class="stat-card-value">{{ $providers->where('aktif', 0)->count() }}</p>
                </div>
                <div class="stat-card-icon red">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Provider Images Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($providers as $provider)
        <div class="content-card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center overflow-hidden">
                        @if($provider->gorsel)
                            <img src="{{ asset($provider->gorsel) }}" alt="{{ $provider->name }}" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="building-2" class="w-6 h-6 text-blue-500"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">{{ $provider->name }}</h3>
                        <p class="text-sm text-gray-400">Sıra: {{ $provider->sira }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $provider->aktif ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20' }}">
                        {{ $provider->aktif ? 'Aktif' : 'Pasif' }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Görsel:</span>
                    <span class="text-white">{{ $provider->gorsel ? 'Yüklü' : 'Yok' }}</span>
                </div>
                @if($provider->link)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Link:</span>
                    <span class="text-white truncate max-w-[120px]">{{ $provider->link }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Oluşturulma:</span>
                    <span class="text-white">{{ $provider->created_at->format('d.m.Y') }}</span>
                </div>
            </div>
            
            <div class="flex gap-2 mt-4">
                <button onclick="openEditModal({{ $provider->id }}, '{{ $provider->name }}', {{ $provider->sira }}, '{{ $provider->link }}')" class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button onclick="toggleStatus({{ $provider->id }})" class="px-3 py-2 text-sm {{ $provider->aktif ? 'bg-red-500/10 text-red-500 hover:bg-red-500/20' : 'bg-green-500/10 text-green-500 hover:bg-green-500/20' }} rounded-lg transition-colors">
                    <i data-lucide="{{ $provider->aktif ? 'power-off' : 'power' }}" class="w-4 h-4"></i>
                </button>
                <button onclick="deleteProvider({{ $provider->id }})" class="px-3 py-2 text-sm bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="content-card text-center py-12">
                <i data-lucide="building-2" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-white mb-2">Henüz sağlayıcı eklenmemiş</h3>
                <p class="text-gray-400 mb-4">Footer'da görünecek sağlayıcı logo görsellerini eklemek için yukarıdaki butonu kullanın.</p>
                <button onclick="openAddModal()" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    İlk Sağlayıcıyı Ekle
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Provider Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 opacity-0 invisible transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-zinc-900 border border-zinc-700 rounded-lg w-full max-w-md transform scale-95 transition-transform duration-300">
            <div class="flex items-center justify-between p-6 border-b border-zinc-700">
                <h3 class="text-lg font-semibold text-white">Yeni Sağlayıcı Ekle</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.provider-photos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sağlayıcı Adı</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Logo Dosyası</label>
                    <input type="file" name="image" accept="image/*" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-500/10 file:text-yellow-500 hover:file:bg-yellow-500/20">
                    <p class="text-xs text-gray-400 mt-1">Desteklenen formatlar: JPEG, PNG, JPG, WebP, SVG (Max: 2MB)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Link (Opsiyonel)</label>
                    <input type="url" name="link" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500" placeholder="https://example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sıralama</label>
                    <input type="number" name="order" min="1" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2 bg-zinc-700 text-white rounded-lg hover:bg-zinc-600 transition-colors">
                        İptal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-400 transition-colors font-medium">
                        Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Provider Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 opacity-0 invisible transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-zinc-900 border border-zinc-700 rounded-lg w-full max-w-md transform scale-95 transition-transform duration-300">
            <div class="flex items-center justify-between p-6 border-b border-zinc-700">
                <h3 class="text-lg font-semibold text-white">Sağlayıcıyı Düzenle</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('POST')
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sağlayıcı Adı</label>
                    <input type="text" name="name" id="editName" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Logo Dosyası (Opsiyonel)</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-500/10 file:text-yellow-500 hover:file:bg-yellow-500/20">
                    <p class="text-xs text-gray-400 mt-1">Yeni logo yüklemezseniz mevcut logo korunacaktır</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Link (Opsiyonel)</label>
                    <input type="url" name="link" id="editLink" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500" placeholder="https://example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sıralama</label>
                    <input type="number" name="order" id="editOrder" min="1" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-zinc-700 text-white rounded-lg hover:bg-zinc-600 transition-colors">
                        İptal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-400 transition-colors font-medium">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function openAddModal() {
    document.getElementById('addModal').classList.remove('opacity-0', 'invisible');
    document.getElementById('addModal').querySelector('.transform').classList.remove('scale-95');
    document.getElementById('addModal').querySelector('.transform').classList.add('scale-100');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('opacity-0', 'invisible');
    document.getElementById('addModal').querySelector('.transform').classList.remove('scale-100');
    document.getElementById('addModal').querySelector('.transform').classList.add('scale-95');
}

function openEditModal(id, name, order, link) {
    document.getElementById('editName').value = name;
    document.getElementById('editOrder').value = order;
    document.getElementById('editLink').value = link || '';
    
    const form = document.getElementById('editForm');
    form.action = `/admin/provider-photos/${id}/update`;
    
    document.getElementById('editModal').classList.remove('opacity-0', 'invisible');
    document.getElementById('editModal').querySelector('.transform').classList.remove('scale-95');
    document.getElementById('editModal').querySelector('.transform').classList.add('scale-100');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('opacity-0', 'invisible');
    document.getElementById('editModal').querySelector('.transform').classList.remove('scale-100');
    document.getElementById('editModal').querySelector('.transform').classList.add('scale-95');
}

// Action functions
function toggleStatus(id) {
    if (confirm('Sağlayıcı durumunu değiştirmek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/provider-photos/${id}/toggle-status`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteProvider(id) {
    if (confirm('Bu sağlayıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/provider-photos/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'addModal') {
        closeAddModal();
    }
    if (e.target.id === 'editModal') {
        closeEditModal();
    }
});
</script>
@endsection 