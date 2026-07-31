@extends('layouts.admin')

@section('title', 'Görsel Ayarları')

@section('content')
<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="w-full">
    <div class="bg-zinc-900/50 border border-zinc-700/50 rounded-lg">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-zinc-700/50">
            <div>
                <h3 class="text-lg font-semibold text-white">Görsel Ayarları</h3>
                <p class="text-gray-400 text-sm mt-1">Oyun görsellerini yönetin ve sıralayın</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <i data-lucide="image" class="w-4 h-4"></i>
                <span>Toplam: {{ $oyunlar->count() + $casinoOyunlari->count() + $canliCasino->count() }} görsel</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="p-4 border-b border-zinc-700/50">
            <div class="flex flex-wrap gap-2">
                <button class="tab-button active px-4 py-2 bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 rounded-lg text-sm transition-all duration-200 flex items-center gap-2" data-tab="oyunlar">
                    <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                    Oyunlar
                    <span class="bg-zinc-700 text-gray-300 px-2 py-0.5 rounded-full text-xs">{{ $oyunlar->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded-lg text-sm transition-all duration-200 flex items-center gap-2" data-tab="casino_oyunlari">
                    <i data-lucide="dice-6" class="w-4 h-4"></i>
                    Casino
                    <span class="bg-zinc-700 text-gray-300 px-2 py-0.5 rounded-full text-xs">{{ $casinoOyunlari->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded-lg text-sm transition-all duration-200 flex items-center gap-2" data-tab="canli_casino">
                    <i data-lucide="video" class="w-4 h-4"></i>
                    Canlı Casino
                    <span class="bg-zinc-700 text-gray-300 px-2 py-0.5 rounded-full text-xs">{{ $canliCasino->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="p-4 border-b border-zinc-700/50">
            <div class="bg-zinc-800/50 border border-zinc-700/50 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="upload" class="w-5 h-5 text-yellow-400"></i>
                    <h4 class="text-white font-medium">Yeni Görsel Ekle</h4>
                </div>
                
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                            <select name="type" id="uploadType" class="w-full bg-black border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-yellow-500">
                                <option value="oyunlar">Oyunlar</option>
                                <option value="casino_oyunlari">Casino Oyunları</option>
                                <option value="canli_casino">Canlı Casino</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Görseller</label>
                            <input type="file" id="multipleImages" accept="image/*" multiple class="w-full bg-black border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-yellow-500">
                            <p class="text-xs text-gray-400 mt-1">Çoklu seçim için Ctrl+tıklayın</p>
                        </div>
                    </div>
                    
                    <!-- Dynamic URL inputs -->
                    <div id="urlInputsContainer" class="space-y-3 mb-4"></div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-zinc-800 hover:bg-green-500/20 border border-zinc-700 hover:border-green-500/30 text-gray-300 hover:text-green-400 text-sm rounded-lg transition-all duration-200 flex items-center gap-2">
                            <i data-lucide="upload" class="w-4 h-4"></i>
                            Yükle
                        </button>
                        <button type="button" id="clearImages" class="px-4 py-2 bg-zinc-800 hover:bg-red-500/20 border border-zinc-700 hover:border-red-500/30 text-gray-300 hover:text-red-400 text-sm rounded-lg transition-all duration-200 flex items-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Temizle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-4">
            <!-- Oyunlar Tab -->
            <div id="tab-oyunlar" class="tab-content active">
                <div id="sortable-oyunlar" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                    @foreach($oyunlar as $oyun)
                    <div class="sortable-item bg-zinc-900/50 border border-zinc-700/50 rounded-lg overflow-hidden hover:border-yellow-500/30 transition-all duration-300 group cursor-move" data-id="{{ $oyun->id }}">
                        <!-- Image -->
                        <div class="relative aspect-square bg-zinc-800/50">
                            <img src="{{ asset($oyun->gorsel) }}" alt="Oyun" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            
                            <!-- Action Buttons -->
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                <button onclick="editItem('oyunlar', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-zinc-800/90 hover:bg-yellow-500/20 border border-zinc-600/50 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 p-1 rounded text-xs transition-all duration-200" title="Düzenle">
                                    <i data-lucide="edit" class="w-3 h-3"></i>
                                </button>
                                <button onclick="deleteItem('oyunlar', {{ $oyun->id }})" class="bg-zinc-800/90 hover:bg-red-500/20 border border-zinc-600/50 hover:border-red-500/30 text-gray-300 hover:text-red-400 p-1 rounded text-xs transition-all duration-200" title="Sil">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                            
                            <!-- Order Badge -->
                            <div class="absolute top-1 left-1 bg-black/70 text-white px-1.5 py-0.5 rounded text-xs font-mono">
                                {{ $oyun->sira }}
                            </div>
                        </div>
                        
                        <!-- URL Info -->
                        <div class="p-2">
                            <div class="text-xs text-gray-400 truncate" title="{{ $oyun->url }}">
                                @if(str_starts_with($oyun->url, 'http'))
                                    <i data-lucide="external-link" class="w-3 h-3 inline mr-1"></i>
                                @else
                                    <i data-lucide="link" class="w-3 h-3 inline mr-1"></i>
                                @endif
                                {{ Str::limit($oyun->url, 20) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($oyunlar->count() == 0)
                <div class="text-center py-12">
                    <i data-lucide="image-off" class="w-12 h-12 text-zinc-600 mx-auto mb-3"></i>
                    <p class="text-gray-400">Henüz oyun görseli eklenmemiş</p>
                </div>
                @endif
            </div>

            <!-- Casino Oyunları Tab -->
            <div id="tab-casino_oyunlari" class="tab-content">
                <div id="sortable-casino_oyunlari" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                    @foreach($casinoOyunlari as $oyun)
                    <div class="sortable-item bg-zinc-900/50 border border-zinc-700/50 rounded-lg overflow-hidden hover:border-yellow-500/30 transition-all duration-300 group cursor-move" data-id="{{ $oyun->id }}">
                        <!-- Image -->
                        <div class="relative aspect-square bg-zinc-800/50">
                            <img src="{{ asset($oyun->gorsel) }}" alt="Casino" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            
                            <!-- Action Buttons -->
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                <button onclick="editItem('casino_oyunlari', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-zinc-800/90 hover:bg-yellow-500/20 border border-zinc-600/50 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 p-1 rounded text-xs transition-all duration-200" title="Düzenle">
                                    <i data-lucide="edit" class="w-3 h-3"></i>
                                </button>
                                <button onclick="deleteItem('casino_oyunlari', {{ $oyun->id }})" class="bg-zinc-800/90 hover:bg-red-500/20 border border-zinc-600/50 hover:border-red-500/30 text-gray-300 hover:text-red-400 p-1 rounded text-xs transition-all duration-200" title="Sil">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                            
                            <!-- Order Badge -->
                            <div class="absolute top-1 left-1 bg-black/70 text-white px-1.5 py-0.5 rounded text-xs font-mono">
                                {{ $oyun->sira }}
                            </div>
                        </div>
                        
                        <!-- URL Info -->
                        <div class="p-2">
                            <div class="text-xs text-gray-400 truncate" title="{{ $oyun->url }}">
                                @if(str_starts_with($oyun->url, 'http'))
                                    <i data-lucide="external-link" class="w-3 h-3 inline mr-1"></i>
                                @else
                                    <i data-lucide="link" class="w-3 h-3 inline mr-1"></i>
                                @endif
                                {{ Str::limit($oyun->url, 20) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($casinoOyunlari->count() == 0)
                <div class="text-center py-12">
                    <i data-lucide="image-off" class="w-12 h-12 text-zinc-600 mx-auto mb-3"></i>
                    <p class="text-gray-400">Henüz casino görseli eklenmemiş</p>
                </div>
                @endif
            </div>

            <!-- Canlı Casino Tab -->
            <div id="tab-canli_casino" class="tab-content">
                <div id="sortable-canli_casino" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                    @foreach($canliCasino as $oyun)
                    <div class="sortable-item bg-zinc-900/50 border border-zinc-700/50 rounded-lg overflow-hidden hover:border-yellow-500/30 transition-all duration-300 group cursor-move" data-id="{{ $oyun->id }}">
                        <!-- Image -->
                        <div class="relative aspect-square bg-zinc-800/50">
                            <img src="{{ asset($oyun->gorsel) }}" alt="Canlı Casino" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            
                            <!-- Action Buttons -->
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                <button onclick="editItem('canli_casino', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-zinc-800/90 hover:bg-yellow-500/20 border border-zinc-600/50 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 p-1 rounded text-xs transition-all duration-200" title="Düzenle">
                                    <i data-lucide="edit" class="w-3 h-3"></i>
                                </button>
                                <button onclick="deleteItem('canli_casino', {{ $oyun->id }})" class="bg-zinc-800/90 hover:bg-red-500/20 border border-zinc-600/50 hover:border-red-500/30 text-gray-300 hover:text-red-400 p-1 rounded text-xs transition-all duration-200" title="Sil">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                            
                            <!-- Order Badge -->
                            <div class="absolute top-1 left-1 bg-black/70 text-white px-1.5 py-0.5 rounded text-xs font-mono">
                                {{ $oyun->sira }}
                            </div>
                        </div>
                        
                        <!-- URL Info -->
                        <div class="p-2">
                            <div class="text-xs text-gray-400 truncate" title="{{ $oyun->url }}">
                                @if(str_starts_with($oyun->url, 'http'))
                                    <i data-lucide="external-link" class="w-3 h-3 inline mr-1"></i>
                                @else
                                    <i data-lucide="link" class="w-3 h-3 inline mr-1"></i>
                                @endif
                                {{ Str::limit($oyun->url, 20) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($canliCasino->count() == 0)
                <div class="text-center py-12">
                    <i data-lucide="image-off" class="w-12 h-12 text-zinc-600 mx-auto mb-3"></i>
                    <p class="text-gray-400">Henüz canlı casino görseli eklenmemiş</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-lg font-semibold text-white">Görseli Düzenle</h5>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="editForm" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="editType" name="type">
            <input type="hidden" id="editId" name="id">
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Mevcut Görsel</label>
                <div class="w-full h-32 bg-zinc-800 rounded-lg overflow-hidden">
                    <img id="currentImage" src="" alt="Mevcut Görsel" class="w-full h-full object-cover">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Yeni Görsel (İsteğe bağlı)</label>
                <input type="file" name="gorsel" accept="image/*" class="w-full bg-black border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-yellow-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">URL</label>
                <input type="text" id="editUrl" name="url" placeholder="/casino veya https://example.com" required class="w-full bg-black border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-yellow-500">
                <p class="text-xs text-gray-400 mt-1">Site içi yol (/casino) veya tam URL girebilirsiniz</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded-lg transition-all duration-200">
                    Güncelle
                </button>
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-zinc-800 hover:bg-red-500/20 border border-zinc-700 hover:border-red-500/30 text-gray-300 hover:text-red-400 rounded-lg transition-all duration-200">
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

    // Aktif tab'ı localStorage'dan al veya varsayılan olarak 'oyunlar' kullan
    let activeTab = localStorage.getItem('activeVisualTab') || 'oyunlar';
    
    // Sayfa yüklendiğinde aktif tab'ı ayarla
    setActiveTab(activeTab);

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            setActiveTab(tabId);
            // Aktif tab'ı localStorage'a kaydet
            localStorage.setItem('activeVisualTab', tabId);
        });
    });
    
    function setActiveTab(tabId) {
        // Remove active class from all tabs
        tabButtons.forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('bg-yellow-500/20', 'border-yellow-500/30', 'text-yellow-400');
            btn.classList.add('bg-zinc-800', 'border-zinc-700', 'text-gray-300');
        });
        tabContents.forEach(content => content.classList.remove('active'));
        
        // Add active class to selected tab
        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
        const activeContent = document.getElementById(`tab-${tabId}`);
        
        if (activeButton && activeContent) {
            activeButton.classList.add('active');
            activeButton.classList.remove('bg-zinc-800', 'border-zinc-700', 'text-gray-300');
            activeButton.classList.add('bg-yellow-500/20', 'border-yellow-500/30', 'text-yellow-400');
            activeContent.classList.add('active');
            
            // Upload form'daki select'i de güncelle
            const uploadTypeSelect = document.getElementById('uploadType');
            if (uploadTypeSelect) {
                uploadTypeSelect.value = tabId;
            }
        }
    }

    // Initialize sortable for each category
    ['oyunlar', 'casino_oyunlari', 'canli_casino'].forEach(type => {
        const container = document.getElementById(`sortable-${type}`);
        if (container) {
            new Sortable(container, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateOrder(type, container);
                }
            });
        }
    });

    // Multiple images handling
    const multipleImagesInput = document.getElementById('multipleImages');
    const urlInputsContainer = document.getElementById('urlInputsContainer');
    const clearImagesBtn = document.getElementById('clearImages');
    
    multipleImagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        urlInputsContainer.innerHTML = '';
        
        if (files.length > 0) {
            files.forEach((file, index) => {
                const urlInputDiv = document.createElement('div');
                urlInputDiv.className = 'bg-zinc-900/50 border border-zinc-700/50 rounded-lg p-3';
                urlInputDiv.innerHTML = `
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="${URL.createObjectURL(file)}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-medium text-sm">${file.name}</p>
                            <p class="text-gray-400 text-xs">${(file.size / 1024).toFixed(1)} KB</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">URL (${index + 1})</label>
                        <input type="text" name="urls[]" placeholder="/casino veya https://example.com" required class="w-full bg-black border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-yellow-500">
                    </div>
                `;
                urlInputsContainer.appendChild(urlInputDiv);
            });
        }
    });
    
    clearImagesBtn.addEventListener('click', function() {
        multipleImagesInput.value = '';
        urlInputsContainer.innerHTML = '';
    });

    // Upload form
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const files = multipleImagesInput.files;
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
        
        // Upload each image separately
        let uploadedCount = 0;
        let totalFiles = files.length;
        
        showMessage(`${totalFiles} resim yükleniyor...`, 'info');
        
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
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(error => {
                uploadedCount++;
                showMessage(`${file.name} yüklenemedi!`, 'error');
                if (uploadedCount === totalFiles) {
                    setTimeout(() => location.reload(), 1500);
                }
            });
        });
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
                setTimeout(() => location.reload(), 1500);
            } else {
                showMessage('Hata: ' + (data.message || 'Bilinmeyen hata'), 'error');
            }
        })
        .catch(error => {
            showMessage('Ağ hatası!', 'error');
        });
    });
});

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
            // Update order numbers in UI
            items.forEach((item, index) => {
                const element = container.querySelector(`[data-id="${item.id}"]`);
                const orderBadge = element.querySelector('.absolute.top-1.left-1');
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
                setTimeout(() => location.reload(), 1500);
            } else {
                showMessage('Hata oluştu!', 'error');
            }
        })
        .catch(error => {
            showMessage('Hata oluştu!', 'error');
        });
    }
}

// Toast notification function
function showMessage(message, type = 'info') {
    const container = document.getElementById('messageContainer');
    const messageDiv = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
    
    messageDiv.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 transform translate-x-full transition-transform duration-300 ease-in-out`;
    messageDiv.innerHTML = `
        <span class="font-bold text-lg">${icon}</span>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(messageDiv);
    
    // Animate in
    setTimeout(() => {
        messageDiv.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.classList.add('translate-x-full');
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.remove();
                }
            }, 300);
        }
    }, 5000);
}
</script>

<style>
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    transform: scale(1.05);
}

.sortable-drag {
    transform: rotate(5deg);
}

.sortable-item {
    transition: all 0.3s ease;
}

.sortable-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}
</style>
@endpush
