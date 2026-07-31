@extends('layouts.admin')

@section('title', 'Banner Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Banner Yönetimi</h1>
            <p class="text-gray-400 mt-1">Site bannerlarını yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAddModal()" class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Banner Ekle
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Banner</p>
                    <p class="stat-card-value">{{ $sliders->count() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="image" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Banner</p>
                    <p class="stat-card-value">{{ $sliders->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Alt Banner</p>
                    <p class="stat-card-value">{{ !empty($settings->bottom_banner_image) ? '1' : '0' }}</p>
                </div>
                <div class="stat-card-icon violet">
                    <i data-lucide="image" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Management -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Banner Listesi</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sliders as $slider)
            <div class="bg-zinc-800/50 rounded-lg border border-zinc-700 overflow-hidden hover:border-yellow-500/50 transition-colors">
                <div class="relative">
                    <img src="{{ $slider->gorsel }}" alt="Banner {{ $slider->id }}" class="w-full h-48 object-cover">
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/50 text-white">
                            Sıra: {{ $slider->sira }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-white font-medium">Banner #{{ $slider->id }}</h3>
                        <div class="flex items-center gap-2">
                            <button onclick="editBanner({{ $slider->id }}, '{{ $slider->url }}', {{ $slider->sira }}, '{{ $slider->gorsel }}')" 
                                    class="p-1 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded transition-colors" title="Düzenle">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteBanner({{ $slider->id }})" 
                                    class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded transition-colors" title="Sil">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    @if($slider->url)
                        <div class="text-sm text-gray-400 mb-2">
                            <i data-lucide="link" class="w-3 h-3 inline mr-1"></i>
                            {{ $slider->url }}
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">ID: {{ $slider->id }}</span>
                        <div class="flex items-center gap-2">
                            <button onclick="toggleBannerStatus({{ $slider->id }})" 
                                    class="px-2 py-1 text-xs {{ $slider->status == 1 ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20' }} border rounded hover:opacity-80 transition-colors">
                                {{ $slider->status == 1 ? 'Aktif' : 'Pasif' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Alt Banner Alanı -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Alt Banner (Slider Altı)</h2>
        </div>

        <form action="{{ route('admin.banners.bottom.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Mevcut Görsel</label>
                    <div class="w-full h-40 bg-zinc-800 rounded-lg border border-zinc-700 flex items-center justify-center overflow-hidden">
                        @if(!empty($settings->bottom_banner_image))
                            <img src="{{ asset($settings->bottom_banner_image) }}" alt="Alt Banner" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="image" class="w-10 h-10 text-gray-500"></i>
                        @endif
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Yeni Görsel</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Tıklanınca Gidecek URL (opsiyonel)</label>
                        <input type="text" name="url" value="{{ $settings->bottom_banner_url ?? '' }}" placeholder="https://..."
                               class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Durum</label>
                        <select name="active" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1" {{ ($settings->bottom_banner_active ?? 0) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ ($settings->bottom_banner_active ?? 0) == 0 ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">Kaydet</button>
            </div>
        </form>
    </div>

    <!-- Alt Banner Altı Görseller -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Alt Banner Altı Görseller</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <h3 class="text-white font-medium mb-3">Yeni Görsel Ekle</h3>
                <form id="bottomBelowAddForm" class="space-y-4" enctype="multipart/form-data">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Görsel</label>
                        <input type="file" name="gorsel" id="bbAddImage" accept="image/*" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">URL (opsiyonel)</label>
                        <input type="text" name="url" id="bbAddUrl" placeholder="https://..." class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Sıra</label>
                        <input type="number" name="sira" id="bbAddSira" min="1" value="{{ ($bottomImages->max('sira') ?? 0) + 1 }}" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white" />
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">Ekle</button>
                    </div>
                </form>
                <div class="mt-6 p-3 rounded-lg border border-zinc-700 bg-zinc-800/40">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-300 font-medium">Yanyana göster (Mobilde 2x2)</p>
                            <p class="text-xs text-gray-500">Mobilde 2 sütun; masaüstünde tek satır.</p>
                        </div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="bbMobileGridToggle" class="peer sr-only" {{ ($settings->bottom_below_mobile_grid ?? 0) == 1 ? 'checked' : '' }}>
                            <span class="w-11 h-6 bg-zinc-700 rounded-full relative transition-colors peer-checked:bg-green-500">
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-all peer-checked:translate-x-5"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-white font-medium mb-3">Mevcut Görseller</h3>
                @if(isset($bottomImages) && $bottomImages->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($bottomImages as $bb)
                    <div class="bg-zinc-800/50 rounded-lg border border-zinc-700 overflow-hidden">
                        <div class="relative">
                            <img src="{{ asset($bb->gorsel) }}" alt="Alt Görsel #{{ $bb->id }}" class="w-full h-32 object-cover">
                            <div class="absolute top-2 left-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/50 text-white">Sıra: {{ $bb->sira }}</div>
                            <div class="absolute top-2 right-2 flex gap-2">
                                <button onclick="toggleBottomBelow({{ $bb->id }})" class="px-2 py-1 text-xs {{ $bb->aktif ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20' }} border rounded">{{ $bb->aktif ? 'Aktif' : 'Pasif' }}</button>
                                <button onclick="deleteBottomBelow({{ $bb->id }})" class="px-2 py-1 text-xs bg-red-500/10 text-red-500 border border-red-500/20 rounded">Sil</button>
                            </div>
                        </div>
                        <div class="p-3 space-y-2">
                            <form class="space-y-2" onsubmit="return updateBottomBelow(event, {{ $bb->id }})">
                                <div>
                                    <label class="block text-xs text-gray-400 mb-1">URL</label>
                                    <input type="text" name="url" value="{{ $bb->url }}" class="w-full px-3 py-2 bg-zinc-900 border border-zinc-700 rounded-lg text-white" />
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-1">Sıra</label>
                                        <input type="number" name="sira" min="1" value="{{ $bb->sira }}" class="w-full px-3 py-2 bg-zinc-900 border border-zinc-700 rounded-lg text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-1">Görsel (değiştir)</label>
                                        <input type="file" name="gorsel" accept="image/*" class="w-full px-2 py-1 bg-zinc-900 border border-zinc-700 rounded-lg text-white" />
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded">Güncelle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                    <div class="text-gray-400 text-sm">Henüz görsel eklenmemiş.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Banner Modal -->
<div id="addBannerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Yeni Banner Ekle</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="addBannerForm" class="space-y-4" enctype="multipart/form-data">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Banner Görseli</label>
                <input type="file" id="addBannerImage" accept="image/*" required
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF veya WebP formatında olmalıdır. Boyut: 800x400 - 1920x1080px, Max: 5MB</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">URL</label>
                <input type="text" id="addBannerUrl" required placeholder="/games/234 veya https://example.com"
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Sıra</label>
                <input type="number" id="addBannerSira" required min="1" placeholder="1, 2, 3..." value="{{ $sliders->count() + 1 }}"
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeAddModal()" 
                        class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-lg transition-colors">
                    Banner Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editBannerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Banner Düzenle</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="editBannerForm" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="editBannerId">
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Mevcut Görsel</label>
                <div id="currentImage" class="w-full h-32 bg-zinc-800 rounded-lg flex items-center justify-center overflow-hidden border border-zinc-700">
                    <img id="currentImageSrc" src="" alt="Mevcut görsel" class="w-full h-full object-cover hidden">
                    <i data-lucide="image" class="w-8 h-8 text-gray-500" id="currentImageIcon"></i>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Yeni Görsel (Opsiyonel)</label>
                <input type="file" id="editBannerImage" accept="image/*" 
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Boş bırakırsanız mevcut görsel korunur. Boyut: 800x400 - 1920x1080px, Max: 5MB</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">URL</label>
                <input type="text" id="editBannerUrl" required placeholder="/games/234 veya https://example.com"
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Sıra</label>
                <input type="number" id="editBannerSira" required min="1" placeholder="1, 2, 3..."
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeEditModal()" 
                        class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Güncelle
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
    document.getElementById('addBannerModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addBannerModal').classList.add('hidden');
    document.getElementById('addBannerForm').reset();
}

// Add Banner Form Submit
document.getElementById('addBannerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('url', document.getElementById('addBannerUrl').value);
    formData.append('sira', document.getElementById('addBannerSira').value);
    
    const imageFile = document.getElementById('addBannerImage').files[0];
    if (imageFile) {
        formData.append('gorsel', imageFile);
    }
    
    fetch('/admin/banners/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message || 'Banner başarıyla eklendi!', 'success');
            closeAddModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu!', 'error');
    });
});

function editBanner(id, url, sira, gorsel) {
    document.getElementById('editBannerId').value = id;
    document.getElementById('editBannerUrl').value = url;
    document.getElementById('editBannerSira').value = sira;
    
    // Mevcut görseli göster
    const currentImageSrc = document.getElementById('currentImageSrc');
    const currentImageIcon = document.getElementById('currentImageIcon');
    
    if (gorsel) {
        currentImageSrc.src = gorsel;
        currentImageSrc.classList.remove('hidden');
        currentImageIcon.classList.add('hidden');
    } else {
        currentImageSrc.classList.add('hidden');
        currentImageIcon.classList.remove('hidden');
    }
    
    document.getElementById('editBannerModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editBannerModal').classList.add('hidden');
}

function deleteBanner(id) {
    if (!confirm('Bu bannerı silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    fetch(`/admin/banners/${id}`, {
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

function toggleBannerStatus(id) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    fetch(`/admin/banners/${id}/toggle-status`, {
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

document.getElementById('editBannerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    const id = document.getElementById('editBannerId').value;
    const formData = new FormData();
    formData.append('url', document.getElementById('editBannerUrl').value);
    formData.append('sira', document.getElementById('editBannerSira').value);
    
    const imageFile = document.getElementById('editBannerImage').files[0];
    if (imageFile) {
        formData.append('gorsel', imageFile);
    }
    
    fetch(`/admin/banners/${id}/update`, {
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
            closeEditModal();
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

// Bottom-below: add
document.getElementById('bottomBelowAddForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData(this);
    fetch('/admin/banners/bottom-below/store', { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: formData })
        .then(r => r.json()).then(d => {
            if (d.success) { showMessage('Görsel eklendi', 'success'); setTimeout(()=>location.reload(), 800); }
            else { showMessage(d.message || 'Hata', 'error'); }
        }).catch(()=> showMessage('Hata', 'error'));
});

// Bottom-below: mobile grid toggle
document.getElementById('bbMobileGridToggle')?.addEventListener('change', function() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('mobile_grid', this.checked ? '1' : '0');
    fetch('/admin/banners/bottom-below/layout', { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: formData })
        .then(r => r.json()).then(d => {
            if (d.success) { showMessage('Görünüm ayarı güncellendi', 'success'); }
            else { showMessage(d.message || 'Hata', 'error'); }
        }).catch(()=> showMessage('Hata', 'error'));
});

// Bottom-below: update
function updateBottomBelow(ev, id) {
    ev.preventDefault();
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = ev.target;
    const formData = new FormData(form);
    fetch(`/admin/banners/bottom-below/${id}/update`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: formData })
        .then(r => r.json()).then(d => {
            if (d.success) { showMessage('Güncellendi', 'success'); setTimeout(()=>location.reload(), 800); }
            else { showMessage(d.message || 'Hata', 'error'); }
        }).catch(()=> showMessage('Hata', 'error'));
    return false;
}

// Bottom-below: delete
function deleteBottomBelow(id) {
    if (!confirm('Bu görseli silmek istiyor musunuz?')) return;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(`/admin/banners/bottom-below/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } })
        .then(r => r.json()).then(d => {
            if (d.success) { showMessage('Silindi', 'success'); setTimeout(()=>location.reload(), 800); }
            else { showMessage(d.message || 'Hata', 'error'); }
        }).catch(()=> showMessage('Hata', 'error'));
}

// Bottom-below: toggle
function toggleBottomBelow(id) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(`/admin/banners/bottom-below/${id}/toggle-status`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token } })
        .then(r => r.json()).then(d => {
            if (d.success) { showMessage('Durum güncellendi', 'success'); setTimeout(()=>location.reload(), 500); }
            else { showMessage(d.message || 'Hata', 'error'); }
        }).catch(()=> showMessage('Hata', 'error'));
}

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