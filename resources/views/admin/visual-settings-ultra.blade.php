@extends('layouts.admin')

@section('title', 'Görsel Ayarları')

@section('content')
<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="w-full max-w-7xl mx-auto">
    <!-- Ultra Compact Header -->
    <div class="bg-zinc-900/40 border border-zinc-700/40 rounded-lg mb-4">
        <div class="flex items-center justify-between p-2.5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="image" class="w-4 h-4 text-yellow-400"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Görsel Yönetimi</h3>
                    <p class="text-xs text-gray-400">{{ $oyunlar->count() + $casinoOyunlari->count() + $canliCasino->count() }} görsel</p>
                </div>
            </div>
            
            <!-- Inline Upload -->
            <div class="flex items-center gap-2">
                <select id="quickType" class="bg-zinc-800/50 border border-zinc-700/50 rounded px-2 py-1 text-white text-xs focus:outline-none focus:border-yellow-500/50">
                    <option value="oyunlar">Oyunlar</option>
                    <option value="casino_oyunlari">Casino</option>
                    <option value="canli_casino">Canlı Casino</option>
                </select>
                <input type="file" id="quickImages" accept="image/*" multiple class="hidden">
                <button onclick="document.getElementById('quickImages').click()" class="px-3 py-1 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/30 hover:border-yellow-500/40 text-yellow-400 hover:text-yellow-300 rounded text-xs transition-all duration-200 flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    Ekle
                </button>
            </div>
        </div>

        <!-- Ultra Compact Tabs -->
        <div class="border-t border-zinc-700/40 px-2.5 py-2">
            <div class="flex gap-1">
                <button class="tab-button active px-2.5 py-1 bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 rounded text-xs transition-all duration-200 flex items-center gap-1" data-tab="oyunlar">
                    <i data-lucide="gamepad-2" class="w-3 h-3"></i>
                    Oyunlar
                    <span class="bg-yellow-500/30 text-yellow-300 px-1 py-0.5 rounded text-xs font-mono">{{ $oyunlar->count() }}</span>
                </button>
                <button class="tab-button px-2.5 py-1 bg-zinc-800/50 hover:bg-yellow-500/20 border border-zinc-700/50 hover:border-yellow-500/30 text-gray-400 hover:text-yellow-400 rounded text-xs transition-all duration-200 flex items-center gap-1" data-tab="casino_oyunlari">
                    <i data-lucide="dice-6" class="w-3 h-3"></i>
                    Casino
                    <span class="bg-zinc-700/50 text-gray-400 px-1 py-0.5 rounded text-xs font-mono">{{ $casinoOyunlari->count() }}</span>
                </button>
                <button class="tab-button px-2.5 py-1 bg-zinc-800/50 hover:bg-yellow-500/20 border border-zinc-700/50 hover:border-yellow-500/30 text-gray-400 hover:text-yellow-400 rounded text-xs transition-all duration-200 flex items-center gap-1" data-tab="canli_casino">
                    <i data-lucide="video" class="w-3 h-3"></i>
                    Canlı Casino
                    <span class="bg-zinc-700/50 text-gray-400 px-1 py-0.5 rounded text-xs font-mono">{{ $canliCasino->count() }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ultra Compact Content -->
    <div class="bg-zinc-900/40 border border-zinc-700/40 rounded-lg p-3">
        <!-- Oyunlar Tab -->
        <div id="tab-oyunlar" class="tab-content active">
            <div id="sortable-oyunlar" class="grid grid-cols-8 md:grid-cols-12 lg:grid-cols-16 xl:grid-cols-20 gap-1">
                @foreach($oyunlar as $oyun)
                <div class="sortable-item group cursor-move" data-id="{{ $oyun->id }}">
                    <div class="relative aspect-square bg-zinc-800/30 rounded border border-zinc-700/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300">
                        <img src="{{ asset($oyun->gorsel) }}" alt="Oyun" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        
                        <!-- Ultra Mini Controls -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-1">
                            <div class="flex justify-between items-start">
                                <span class="bg-black/80 text-white px-1 py-0.5 rounded text-xs font-mono leading-none">{{ $oyun->sira }}</span>
                                <div class="flex gap-0.5">
                                    <button onclick="editItem('oyunlar', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-yellow-500/90 hover:bg-yellow-500 text-black p-0.5 rounded transition-all duration-200" title="Düzenle">
                                        <i data-lucide="edit" class="w-2 h-2"></i>
                                    </button>
                                    <button onclick="deleteItem('oyunlar', {{ $oyun->id }})" class="bg-red-500/90 hover:bg-red-500 text-white p-0.5 rounded transition-all duration-200" title="Sil">
                                        <i data-lucide="trash-2" class="w-2 h-2"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-white text-xs truncate" title="{{ $oyun->url }}">
                                    {{ Str::limit($oyun->url, 8) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($oyunlar->count() == 0)
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="image-off" class="w-8 h-8 text-zinc-600"></i>
                </div>
                <p class="text-gray-400 text-sm">Henüz oyun görseli eklenmemiş</p>
                <button onclick="document.getElementById('quickImages').click()" class="mt-2 px-3 py-1 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/30 text-yellow-400 rounded text-xs transition-all duration-200">
                    İlk görseli ekle
                </button>
            </div>
            @endif
        </div>

        <!-- Casino Oyunları Tab -->
        <div id="tab-casino_oyunlari" class="tab-content">
            <div id="sortable-casino_oyunlari" class="grid grid-cols-8 md:grid-cols-12 lg:grid-cols-16 xl:grid-cols-20 gap-1">
                @foreach($casinoOyunlari as $oyun)
                <div class="sortable-item group cursor-move" data-id="{{ $oyun->id }}">
                    <div class="relative aspect-square bg-zinc-800/30 rounded border border-zinc-700/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300">
                        <img src="{{ asset($oyun->gorsel) }}" alt="Casino" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        
                        <!-- Ultra Mini Controls -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-1">
                            <div class="flex justify-between items-start">
                                <span class="bg-black/80 text-white px-1 py-0.5 rounded text-xs font-mono leading-none">{{ $oyun->sira }}</span>
                                <div class="flex gap-0.5">
                                    <button onclick="editItem('casino_oyunlari', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-yellow-500/90 hover:bg-yellow-500 text-black p-0.5 rounded transition-all duration-200" title="Düzenle">
                                        <i data-lucide="edit" class="w-2 h-2"></i>
                                    </button>
                                    <button onclick="deleteItem('casino_oyunlari', {{ $oyun->id }})" class="bg-red-500/90 hover:bg-red-500 text-white p-0.5 rounded transition-all duration-200" title="Sil">
                                        <i data-lucide="trash-2" class="w-2 h-2"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-white text-xs truncate" title="{{ $oyun->url }}">
                                    {{ Str::limit($oyun->url, 8) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($casinoOyunlari->count() == 0)
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="image-off" class="w-8 h-8 text-zinc-600"></i>
                </div>
                <p class="text-gray-400 text-sm">Henüz casino görseli eklenmemiş</p>
                <button onclick="document.getElementById('quickImages').click()" class="mt-2 px-3 py-1 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/30 text-yellow-400 rounded text-xs transition-all duration-200">
                    İlk görseli ekle
                </button>
            </div>
            @endif
        </div>

        <!-- Canlı Casino Tab -->
        <div id="tab-canli_casino" class="tab-content">
            <div id="sortable-canli_casino" class="grid grid-cols-8 md:grid-cols-12 lg:grid-cols-16 xl:grid-cols-20 gap-1">
                @foreach($canliCasino as $oyun)
                <div class="sortable-item group cursor-move" data-id="{{ $oyun->id }}">
                    <div class="relative aspect-square bg-zinc-800/30 rounded border border-zinc-700/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300">
                        <img src="{{ asset($oyun->gorsel) }}" alt="Canlı Casino" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        
                        <!-- Ultra Mini Controls -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-1">
                            <div class="flex justify-between items-start">
                                <span class="bg-black/80 text-white px-1 py-0.5 rounded text-xs font-mono leading-none">{{ $oyun->sira }}</span>
                                <div class="flex gap-0.5">
                                    <button onclick="editItem('canli_casino', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-yellow-500/90 hover:bg-yellow-500 text-black p-0.5 rounded transition-all duration-200" title="Düzenle">
                                        <i data-lucide="edit" class="w-2 h-2"></i>
                                    </button>
                                    <button onclick="deleteItem('canli_casino', {{ $oyun->id }})" class="bg-red-500/90 hover:bg-red-500 text-white p-0.5 rounded transition-all duration-200" title="Sil">
                                        <i data-lucide="trash-2" class="w-2 h-2"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-white text-xs truncate" title="{{ $oyun->url }}">
                                    {{ Str::limit($oyun->url, 8) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($canliCasino->count() == 0)
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="image-off" class="w-8 h-8 text-zinc-600"></i>
                </div>
                <p class="text-gray-400 text-sm">Henüz canlı casino görseli eklenmemiş</p>
                <button onclick="document.getElementById('quickImages').click()" class="mt-2 px-3 py-1 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/30 text-yellow-400 rounded text-xs transition-all duration-200">
                    İlk görseli ekle
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900/95 border border-zinc-700/50 rounded-lg p-4 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-semibold text-white flex items-center gap-2">
                <i data-lucide="upload" class="w-4 h-4 text-yellow-400"></i>
                Hızlı Yükleme
            </h5>
            <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <form id="quickUploadForm" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" id="uploadType" name="type">
            
            <div id="selectedFiles" class="space-y-2 max-h-40 overflow-y-auto"></div>
            
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/30 hover:border-yellow-500/40 text-yellow-400 hover:text-yellow-300 rounded text-xs transition-all duration-200 flex items-center justify-center gap-1">
                    <i data-lucide="upload" class="w-3 h-3"></i>
                    Yükle
                </button>
                <button type="button" onclick="closeUploadModal()" class="px-3 py-2 bg-zinc-800/50 hover:bg-zinc-700/50 border border-zinc-700/50 text-gray-400 hover:text-gray-300 rounded text-xs transition-all duration-200">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900/95 border border-zinc-700/50 rounded-lg p-4 w-full max-w-sm mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-sm font-semibold text-white flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4 text-yellow-400"></i>
                Görseli Düzenle
            </h5>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <form id="editForm" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" id="editType" name="type">
            <input type="hidden" id="editId" name="id">
            
            <div>
                <div class="w-full h-20 bg-zinc-800/50 rounded overflow-hidden mb-2">
                    <img id="currentImage" src="" alt="Mevcut Görsel" class="w-full h-full object-cover">
                </div>
                <input type="file" name="gorsel" accept="image/*" class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500/50">
            </div>
            
            <div>
                <input type="text" id="editUrl" name="url" placeholder="URL girin (/casino veya https://...)" required class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500/50">
            </div>
            
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/30 hover:border-yellow-500/40 text-yellow-400 hover:text-yellow-300 rounded text-xs transition-all duration-200">
                    Güncelle
                </button>
                <button type="button" onclick="closeEditModal()" class="px-3 py-2 bg-zinc-800/50 hover:bg-zinc-700/50 border border-zinc-700/50 text-gray-400 hover:text-gray-300 rounded text-xs transition-all duration-200">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    let activeTab = localStorage.getItem('activeVisualTab') || 'oyunlar';
    setActiveTab(activeTab);

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            setActiveTab(tabId);
            localStorage.setItem('activeVisualTab', tabId);
        });
    });
    
    function setActiveTab(tabId) {
        tabButtons.forEach(btn => {
            btn.classList.remove('active', 'bg-yellow-500/20', 'border-yellow-500/30', 'text-yellow-400');
            btn.classList.add('bg-zinc-800/50', 'border-zinc-700/50', 'text-gray-400');
            btn.querySelector('span').classList.remove('bg-yellow-500/30', 'text-yellow-300');
            btn.querySelector('span').classList.add('bg-zinc-700/50', 'text-gray-400');
        });
        tabContents.forEach(content => content.classList.remove('active'));
        
        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
        const activeContent = document.getElementById(`tab-${tabId}`);
        
        if (activeButton && activeContent) {
            activeButton.classList.add('active');
            activeButton.classList.remove('bg-zinc-800/50', 'border-zinc-700/50', 'text-gray-400');
            activeButton.classList.add('bg-yellow-500/20', 'border-yellow-500/30', 'text-yellow-400');
            activeButton.querySelector('span').classList.remove('bg-zinc-700/50', 'text-gray-400');
            activeButton.querySelector('span').classList.add('bg-yellow-500/30', 'text-yellow-300');
            activeContent.classList.add('active');
            
            document.getElementById('quickType').value = tabId;
        }
    }

    // Initialize sortable
    ['oyunlar', 'casino_oyunlari', 'canli_casino'].forEach(type => {
        const container = document.getElementById(`sortable-${type}`);
        if (container) {
            new Sortable(container, {
                animation: 150,
                ghostClass: 'opacity-50',
                chosenClass: 'scale-105',
                dragClass: 'rotate-3',
                onEnd: function(evt) {
                    updateOrder(type, container);
                }
            });
        }
    });

    // Quick upload handling
    const quickImages = document.getElementById('quickImages');
    const quickType = document.getElementById('quickType');
    
    quickImages.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            document.getElementById('uploadType').value = quickType.value;
            showUploadModal(files);
        }
    });

    // Edit form
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const type = document.getElementById('editType').value;
        const id = document.getElementById('editId').value;
        
        fetch(`{{ url('admin/visual-settings') }}/${type}/${id}/update`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Başarıyla güncellendi!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showMessage('Hata: ' + (data.message || 'Bilinmeyen hata'), 'error');
            }
        })
        .catch(error => {
            showMessage('Ağ hatası!', 'error');
        });
    });

    // Quick upload form
    document.getElementById('quickUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const files = quickImages.files;
        const urls = Array.from(document.querySelectorAll('input[name="urls[]"]')).map(input => input.value);
        const type = document.getElementById('uploadType').value;
        
        if (files.length === 0) {
            showMessage('Lütfen en az bir resim seçin!', 'error');
            return;
        }
        
        if (urls.length !== files.length || urls.some(url => !url.trim())) {
            showMessage('Tüm URL alanlarını doldurmanız gerekiyor!', 'error');
            return;
        }
        
        let uploadedCount = 0;
        let totalFiles = files.length;
        
        showMessage(`${totalFiles} resim yükleniyor...`, 'info');
        closeUploadModal();
        
        Array.from(files).forEach((file, index) => {
            const formData = new FormData();
            formData.append('type', type);
            formData.append('gorsel', file);
            formData.append('url', urls[index]);
            
            fetch('{{ route("admin.visual-settings.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                uploadedCount++;
                
                if (data.success) {
                    showMessage(`${file.name} yüklendi! (${uploadedCount}/${totalFiles})`, 'success');
                } else {
                    showMessage(`${file.name} yüklenemedi!`, 'error');
                }
                
                if (uploadedCount === totalFiles) {
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                uploadedCount++;
                showMessage(`${file.name} yüklenemedi!`, 'error');
                if (uploadedCount === totalFiles) {
                    setTimeout(() => location.reload(), 1000);
                }
            });
        });
    });
});

function showUploadModal(files) {
    const selectedFiles = document.getElementById('selectedFiles');
    selectedFiles.innerHTML = '';
    
    files.forEach((file, index) => {
        const fileDiv = document.createElement('div');
        fileDiv.className = 'bg-zinc-800/30 border border-zinc-700/30 rounded p-2';
        fileDiv.innerHTML = `
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-zinc-700/50 rounded overflow-hidden flex-shrink-0">
                    <img src="${URL.createObjectURL(file)}" alt="Preview" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <p class="text-white text-xs font-medium">${file.name}</p>
                    <p class="text-gray-400 text-xs">${(file.size / 1024).toFixed(1)} KB</p>
                </div>
            </div>
            <input type="text" name="urls[]" placeholder="URL girin (/casino veya https://...)" required class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded px-2 py-1 text-white text-xs focus:outline-none focus:border-yellow-500/50">
        `;
        selectedFiles.appendChild(fileDiv);
    });
    
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
    document.getElementById('quickImages').value = '';
}

function updateOrder(type, container) {
    const items = Array.from(container.children).map((item, index) => ({
        id: item.getAttribute('data-id'),
        order: index + 1
    }));

    fetch('{{ route("admin.visual-settings.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            items: items
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            items.forEach((item, index) => {
                const element = container.querySelector(`[data-id="${item.id}"]`);
                const orderBadge = element.querySelector('span');
                if (orderBadge) {
                    orderBadge.textContent = index + 1;
                }
            });
        }
    });
}

function editItem(type, id, gorsel, url) {
    document.getElementById('editType').value = type;
    document.getElementById('editId').value = id;
    document.getElementById('editUrl').value = url;
    document.getElementById('currentImage').src = '{{ asset("") }}' + gorsel;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

function deleteItem(type, id) {
    if (confirm('Bu görseli silmek istediğinizden emin misiniz?')) {
        fetch(`{{ url('admin/visual-settings') }}/${type}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Görsel başarıyla silindi!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showMessage('Hata oluştu!', 'error');
            }
        })
        .catch(error => {
            showMessage('Hata oluştu!', 'error');
        });
    }
}

function showMessage(message, type = 'info') {
    const container = document.getElementById('messageContainer');
    const messageDiv = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
    
    messageDiv.className = `${bgColor} text-white px-3 py-2 rounded-lg shadow-lg flex items-center space-x-2 transform translate-x-full transition-transform duration-300 ease-in-out`;
    messageDiv.innerHTML = `
        <span class="font-bold">${icon}</span>
        <span class="text-sm">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.classList.add('translate-x-full');
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.remove();
                }
            }, 300);
        }
    }, 4000);
}
</script>

<style>
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.sortable-item {
    transition: all 0.3s ease;
}

.sortable-item:hover {
    transform: translateY(-1px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: rgba(63, 63, 70, 0.3);
    border-radius: 2px;
}

::-webkit-scrollbar-thumb {
    background: rgba(161, 161, 170, 0.5);
    border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(161, 161, 170, 0.7);
}
</style>
@endpush
