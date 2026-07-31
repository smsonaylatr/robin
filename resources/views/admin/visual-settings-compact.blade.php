@extends('layouts.admin')

@section('title', 'Görsel Ayarları')

@section('content')
<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="w-full">
    <div class="bg-zinc-900/50 border border-zinc-700/50 rounded-lg">
        <!-- Compact Header -->
        <div class="flex items-center justify-between p-3 border-b border-zinc-700/50">
            <div>
                <h3 class="text-base font-semibold text-white">Görsel Ayarları</h3>
                <p class="text-gray-400 text-xs mt-0.5">Oyun görsellerini yönetin</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <i data-lucide="image" class="w-3 h-3"></i>
                <span>{{ $oyunlar->count() + $casinoOyunlari->count() + $canliCasino->count() }}</span>
            </div>
        </div>

        <!-- Compact Tabs -->
        <div class="p-3 border-b border-zinc-700/50">
            <div class="flex gap-1">
                <button class="tab-button active px-3 py-1.5 bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 rounded text-xs transition-all duration-200 flex items-center gap-1.5" data-tab="oyunlar">
                    <i data-lucide="gamepad-2" class="w-3 h-3"></i>
                    Oyunlar
                    <span class="bg-zinc-700 text-gray-300 px-1.5 py-0.5 rounded-full text-xs">{{ $oyunlar->count() }}</span>
                </button>
                <button class="tab-button px-3 py-1.5 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded text-xs transition-all duration-200 flex items-center gap-1.5" data-tab="casino_oyunlari">
                    <i data-lucide="dice-6" class="w-3 h-3"></i>
                    Casino
                    <span class="bg-zinc-700 text-gray-300 px-1.5 py-0.5 rounded-full text-xs">{{ $casinoOyunlari->count() }}</span>
                </button>
                <button class="tab-button px-3 py-1.5 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded text-xs transition-all duration-200 flex items-center gap-1.5" data-tab="canli_casino">
                    <i data-lucide="video" class="w-3 h-3"></i>
                    Canlı Casino
                    <span class="bg-zinc-700 text-gray-300 px-1.5 py-0.5 rounded-full text-xs">{{ $canliCasino->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Compact Upload Form -->
        <div class="p-3 border-b border-zinc-700/50">
            <div class="bg-zinc-800/30 border border-zinc-700/30 rounded p-3">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="upload" class="w-4 h-4 text-yellow-400"></i>
                    <h4 class="text-white font-medium text-sm">Yeni Görsel</h4>
                </div>
                
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Kategori</label>
                            <select name="type" id="uploadType" class="w-full bg-black border border-zinc-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500">
                                <option value="oyunlar">Oyunlar</option>
                                <option value="casino_oyunlari">Casino Oyunları</option>
                                <option value="canli_casino">Canlı Casino</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Görseller</label>
                            <input type="file" id="multipleImages" accept="image/*" multiple class="w-full bg-black border border-zinc-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500">
                        </div>
                    </div>
                    
                    <!-- Dynamic URL inputs -->
                    <div id="urlInputsContainer" class="space-y-2 mb-3"></div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="px-3 py-1.5 bg-zinc-800 hover:bg-green-500/20 border border-zinc-700 hover:border-green-500/30 text-gray-300 hover:text-green-400 text-xs rounded transition-all duration-200 flex items-center gap-1">
                            <i data-lucide="upload" class="w-3 h-3"></i>
                            Yükle
                        </button>
                        <button type="button" id="clearImages" class="px-3 py-1.5 bg-zinc-800 hover:bg-red-500/20 border border-zinc-700 hover:border-red-500/30 text-gray-300 hover:text-red-400 text-xs rounded transition-all duration-200 flex items-center gap-1">
                            <i data-lucide="x" class="w-3 h-3"></i>
                            Temizle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-3">
            <!-- Oyunlar Tab -->
            <div id="tab-oyunlar" class="tab-content active">
                <div id="sortable-oyunlar" class="grid grid-cols-6 md:grid-cols-10 lg:grid-cols-14 xl:grid-cols-18 gap-1.5">
                    @foreach($oyunlar as $oyun)
                    <div class="sortable-item bg-zinc-900/30 border border-zinc-700/30 rounded overflow-hidden hover:border-yellow-500/50 transition-all duration-300 group cursor-move" data-id="{{ $oyun->id }}">
                        <!-- Mini Image -->
                        <div class="relative aspect-square bg-zinc-800/30">
                            <img src="{{ asset($oyun->gorsel) }}" alt="Oyun" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                            
                            <!-- Mini Action Buttons -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1">
                                <button onclick="editItem('oyunlar', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-yellow-500/80 hover:bg-yellow-500 text-black p-1 rounded text-xs transition-all duration-200" title="Düzenle">
                                    <i data-lucide="edit" class="w-2.5 h-2.5"></i>
                                </button>
                                <button onclick="deleteItem('oyunlar', {{ $oyun->id }})" class="bg-red-500/80 hover:bg-red-500 text-white p-1 rounded text-xs transition-all duration-200" title="Sil">
                                    <i data-lucide="trash-2" class="w-2.5 h-2.5"></i>
                                </button>
                            </div>
                            
                            <!-- Mini Order Badge -->
                            <div class="absolute top-0.5 left-0.5 bg-black/80 text-white px-1 py-0.5 rounded text-xs font-mono leading-none">
                                {{ $oyun->sira }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($oyunlar->count() == 0)
                <div class="text-center py-8">
                    <i data-lucide="image-off" class="w-8 h-8 text-zinc-600 mx-auto mb-2"></i>
                    <p class="text-gray-400 text-sm">Henüz oyun görseli eklenmemiş</p>
                </div>
                @endif
            </div>

            <!-- Casino Oyunları Tab -->
            <div id="tab-casino_oyunlari" class="tab-content">
                <div id="sortable-casino_oyunlari" class="grid grid-cols-6 md:grid-cols-10 lg:grid-cols-14 xl:grid-cols-18 gap-1.5">
                    @foreach($casinoOyunlari as $oyun)
                    <div class="sortable-item bg-zinc-900/30 border border-zinc-700/30 rounded overflow-hidden hover:border-yellow-500/50 transition-all duration-300 group cursor-move" data-id="{{ $oyun->id }}">
                        <!-- Mini Image -->
                        <div class="relative aspect-square bg-zinc-800/30">
                            <img src="{{ asset($oyun->gorsel) }}" alt="Casino" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                            
                            <!-- Mini Action Buttons -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1">
                                <button onclick="editItem('casino_oyunlari', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-yellow-500/80 hover:bg-yellow-500 text-black p-1 rounded text-xs transition-all duration-200" title="Düzenle">
                                    <i data-lucide="edit" class="w-2.5 h-2.5"></i>
                                </button>
                                <button onclick="deleteItem('casino_oyunlari', {{ $oyun->id }})" class="bg-red-500/80 hover:bg-red-500 text-white p-1 rounded text-xs transition-all duration-200" title="Sil">
                                    <i data-lucide="trash-2" class="w-2.5 h-2.5"></i>
                                </button>
                            </div>
                            
                            <!-- Mini Order Badge -->
                            <div class="absolute top-0.5 left-0.5 bg-black/80 text-white px-1 py-0.5 rounded text-xs font-mono leading-none">
                                {{ $oyun->sira }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($casinoOyunlari->count() == 0)
                <div class="text-center py-8">
                    <i data-lucide="image-off" class="w-8 h-8 text-zinc-600 mx-auto mb-2"></i>
                    <p class="text-gray-400 text-sm">Henüz casino görseli eklenmemiş</p>
                </div>
                @endif
            </div>

            <!-- Canlı Casino Tab -->
            <div id="tab-canli_casino" class="tab-content">
                <div id="sortable-canli_casino" class="grid grid-cols-6 md:grid-cols-10 lg:grid-cols-14 xl:grid-cols-18 gap-1.5">
                    @foreach($canliCasino as $oyun)
                    <div class="sortable-item bg-zinc-900/30 border border-zinc-700/30 rounded overflow-hidden hover:border-yellow-500/50 transition-all duration-300 group cursor-move" data-id="{{ $oyun->id }}">
                        <!-- Mini Image -->
                        <div class="relative aspect-square bg-zinc-800/30">
                            <img src="{{ asset($oyun->gorsel) }}" alt="Canlı Casino" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                            
                            <!-- Mini Action Buttons -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1">
                                <button onclick="editItem('canli_casino', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="bg-yellow-500/80 hover:bg-yellow-500 text-black p-1 rounded text-xs transition-all duration-200" title="Düzenle">
                                    <i data-lucide="edit" class="w-2.5 h-2.5"></i>
                                </button>
                                <button onclick="deleteItem('canli_casino', {{ $oyun->id }})" class="bg-red-500/80 hover:bg-red-500 text-white p-1 rounded text-xs transition-all duration-200" title="Sil">
                                    <i data-lucide="trash-2" class="w-2.5 h-2.5"></i>
                                </button>
                            </div>
                            
                            <!-- Mini Order Badge -->
                            <div class="absolute top-0.5 left-0.5 bg-black/80 text-white px-1 py-0.5 rounded text-xs font-mono leading-none">
                                {{ $oyun->sira }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($canliCasino->count() == 0)
                <div class="text-center py-8">
                    <i data-lucide="image-off" class="w-8 h-8 text-zinc-600 mx-auto mb-2"></i>
                    <p class="text-gray-400 text-sm">Henüz canlı casino görseli eklenmemiş</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Compact Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg p-4 w-full max-w-sm mx-4">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-base font-semibold text-white">Görseli Düzenle</h5>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <form id="editForm" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" id="editType" name="type">
            <input type="hidden" id="editId" name="id">
            
            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Mevcut Görsel</label>
                <div class="w-full h-24 bg-zinc-800 rounded overflow-hidden">
                    <img id="currentImage" src="" alt="Mevcut Görsel" class="w-full h-full object-cover">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Yeni Görsel (İsteğe bağlı)</label>
                <input type="file" name="gorsel" accept="image/*" class="w-full bg-black border border-zinc-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">URL</label>
                <input type="text" id="editUrl" name="url" placeholder="/casino veya https://example.com" required class="w-full bg-black border border-zinc-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500">
            </div>
            
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-3 py-1.5 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded text-xs transition-all duration-200">
                    Güncelle
                </button>
                <button type="button" onclick="closeEditModal()" class="flex-1 px-3 py-1.5 bg-zinc-800 hover:bg-red-500/20 border border-zinc-700 hover:border-red-500/30 text-gray-300 hover:text-red-400 rounded text-xs transition-all duration-200">
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
            btn.classList.add('bg-zinc-800', 'border-zinc-700', 'text-gray-300');
        });
        tabContents.forEach(content => content.classList.remove('active'));
        
        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
        const activeContent = document.getElementById(`tab-${tabId}`);
        
        if (activeButton && activeContent) {
            activeButton.classList.add('active');
            activeButton.classList.remove('bg-zinc-800', 'border-zinc-700', 'text-gray-300');
            activeButton.classList.add('bg-yellow-500/20', 'border-yellow-500/30', 'text-yellow-400');
            activeContent.classList.add('active');
            
            const uploadTypeSelect = document.getElementById('uploadType');
            if (uploadTypeSelect) {
                uploadTypeSelect.value = tabId;
            }
        }
    }

    // Initialize sortable
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
                urlInputDiv.className = 'bg-zinc-900/30 border border-zinc-700/30 rounded p-2';
                urlInputDiv.innerHTML = `
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 bg-zinc-800 rounded overflow-hidden flex-shrink-0">
                            <img src="${URL.createObjectURL(file)}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-medium text-xs">${file.name}</p>
                            <p class="text-gray-400 text-xs">${(file.size / 1024).toFixed(1)} KB</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">URL (${index + 1})</label>
                        <input type="text" name="urls[]" placeholder="/casino veya https://example.com" required class="w-full bg-black border border-zinc-700 rounded px-2 py-1.5 text-white text-xs focus:outline-none focus:border-yellow-500">
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
            items.forEach((item, index) => {
                const element = container.querySelector(`[data-id="${item.id}"]`);
                const orderBadge = element.querySelector('.absolute.top-0\\.5.left-0\\.5');
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
