@extends('layouts.admin')

@section('title', 'Görsel Ayarları')

@section('content')
<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div style="width: max-content; max-width: max-content;" class="w-full max-w-7xl mx-auto">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-zinc-900/60 to-zinc-800/60 border border-zinc-700/50 rounded-xl mb-6 shadow-xl">
        <div class="flex items-center justify-between p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="image" class="w-6 h-6 text-yellow-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Görsel Yönetimi</h2>
                    <p class="text-sm text-gray-400">Toplam {{ $oyunlar->count() + $casinoOyunlari->count() + $canliCasino->count() }} görsel • Sürükle-bırak ile sıralama</p>
                </div>
            </div>
            
            <!-- Enhanced Upload Button -->
            <div class="flex items-center gap-3">
                <select id="quickType" class="bg-zinc-800/70 border border-zinc-600/50 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 transition-all duration-200">
                    <option value="oyunlar">🎮 Oyunlar</option>
                    <option value="casino_oyunlari">🎲 Casino</option>
                    <option value="canli_casino">📺 Canlı Casino</option>
                </select>
                <input type="file" id="quickImages" accept="image/*" multiple class="hidden">
                <button onclick="document.getElementById('quickImages').click()" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 hover:border-yellow-500/50 text-yellow-400 hover:text-yellow-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Görsel Ekle
                </button>
            </div>
        </div>

        <!-- Enhanced Tabs -->
        <div style="display: contents;" class="border-t border-zinc-700/50 px-6 py-4">
            <div class="flex gap-2">
                <button class="tab-button active px-4 py-2.5 bg-yellow-500/20 border border-yellow-500/40 text-yellow-400 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-lg" data-tab="oyunlar">
                    <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                    Oyunlar
                    <span class="bg-yellow-500/40 text-yellow-300 px-2 py-0.5 rounded-full text-xs font-bold">{{ $oyunlar->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2.5 bg-zinc-800/70 hover:bg-yellow-500/20 border border-zinc-600/50 hover:border-yellow-500/40 text-gray-400 hover:text-yellow-400 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2" data-tab="casino_oyunlari">
                    <i data-lucide="dice-6" class="w-4 h-4"></i>
                    Casino
                    <span class="bg-zinc-700/70 text-gray-400 px-2 py-0.5 rounded-full text-xs font-bold">{{ $casinoOyunlari->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2.5 bg-zinc-800/70 hover:bg-yellow-500/20 border border-zinc-600/50 hover:border-yellow-500/40 text-gray-400 hover:text-yellow-400 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2" data-tab="canli_casino">
                    <i data-lucide="video" class="w-4 h-4"></i>
                    Canlı Casino
                    <span class="bg-zinc-700/70 text-gray-400 px-2 py-0.5 rounded-full text-xs font-bold">{{ $canliCasino->count() }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Content -->
    <div class="bg-gradient-to-br from-zinc-900/60 to-zinc-800/60 border border-zinc-700/50 rounded-xl p-6 shadow-xl">
        <!-- Oyunlar Tab -->
        <div id="tab-oyunlar" class="tab-content active">
            <div id="sortable-oyunlar" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                @foreach($oyunlar as $oyun)
                <div class="sortable-item group cursor-move" data-id="{{ $oyun->id }}">
                    <div class="relative bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-xl border border-zinc-600/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-2xl transform hover:scale-105">
                        <img src="{{ asset($oyun->gorsel) }}" alt="Oyun" class="max-w-full max-h-48 object-contain group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        
                        <!-- Enhanced Controls -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/30 to-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-between p-3">
                            <div class="flex justify-between items-start">
                                <span class="bg-gradient-to-r from-yellow-500/90 to-yellow-600/90 text-black px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">#{{ $oyun->sira }}</span>
                                <div class="flex gap-2">
                                    <button onclick="editItem('oyunlar', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="flex items-center justify-center bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-black p-2.5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110" title="Düzenle">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteItem('oyunlar', {{ $oyun->id }})" class="flex items-center justify-center bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-2.5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110" title="Sil">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center bg-black/70 rounded-lg p-2 backdrop-blur-sm border border-white/10">
                                <div class="text-white text-xs font-medium truncate" title="{{ $oyun->url }}">
                                    {{ Str::limit($oyun->url, 20) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($oyunlar->count() == 0)
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i data-lucide="image-off" class="w-10 h-10 text-zinc-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Henüz oyun görseli eklenmemiş</h3>
                <p class="text-gray-400 text-sm mb-4">İlk görselinizi ekleyerek başlayın</p>
                <button onclick="document.getElementById('quickImages').click()" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 text-yellow-400 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    İlk görseli ekle
                </button>
            </div>
            @endif
        </div>

        <!-- Casino Oyunları Tab -->
        <div id="tab-casino_oyunlari" class="tab-content">
            <div id="sortable-casino_oyunlari" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                @foreach($casinoOyunlari as $oyun)
                <div class="sortable-item group cursor-move" data-id="{{ $oyun->id }}">
                    <div class="relative bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-xl border border-zinc-600/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-2xl transform hover:scale-105">
                        <img src="{{ asset($oyun->gorsel) }}" alt="Casino" class="max-w-full max-h-48 object-contain group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        
                        <!-- Enhanced Controls -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/30 to-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-between p-3">
                            <div class="flex justify-between items-start">
                                <span class="bg-gradient-to-r from-yellow-500/90 to-yellow-600/90 text-black px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">#{{ $oyun->sira }}</span>
                                <div class="flex gap-2">
                                    <button onclick="editItem('casino_oyunlari', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="flex items-center justify-center bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-black p-2.5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110" title="Düzenle">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteItem('casino_oyunlari', {{ $oyun->id }})" class="flex items-center justify-center bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-2.5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110" title="Sil">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center bg-black/70 rounded-lg p-2 backdrop-blur-sm border border-white/10">
                                <div class="text-white text-xs font-medium truncate" title="{{ $oyun->url }}">
                                    {{ Str::limit($oyun->url, 20) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($casinoOyunlari->count() == 0)
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i data-lucide="image-off" class="w-10 h-10 text-zinc-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Henüz casino görseli eklenmemiş</h3>
                <p class="text-gray-400 text-sm mb-4">İlk görselinizi ekleyerek başlayın</p>
                <button onclick="document.getElementById('quickImages').click()" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 text-yellow-400 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    İlk görseli ekle
                </button>
            </div>
            @endif
        </div>

        <!-- Canlı Casino Tab -->
        <div id="tab-canli_casino" class="tab-content">
            <div id="sortable-canli_casino" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                @foreach($canliCasino as $oyun)
                <div class="sortable-item group cursor-move" data-id="{{ $oyun->id }}">
                    <div class="relative bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-xl border border-zinc-600/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-2xl transform hover:scale-105">
                        <img src="{{ asset($oyun->gorsel) }}" alt="Canlı Casino" class="max-w-full max-h-48 object-contain group-hover:scale-110 transition-transform duration-300" loading="lazy">
                        
                        <!-- Enhanced Controls -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/30 to-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-between p-3">
                            <div class="flex justify-between items-start">
                                <span class="bg-gradient-to-r from-yellow-500/90 to-yellow-600/90 text-black px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">#{{ $oyun->sira }}</span>
                                <div class="flex gap-2">
                                    <button onclick="editItem('canli_casino', {{ $oyun->id }}, '{{ $oyun->gorsel }}', '{{ $oyun->url ?? '' }}')" class="flex items-center justify-center bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-black p-2.5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110" title="Düzenle">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteItem('canli_casino', {{ $oyun->id }})" class="flex items-center justify-center bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-2.5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110" title="Sil">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center bg-black/70 rounded-lg p-2 backdrop-blur-sm border border-white/10">
                                <div class="text-white text-xs font-medium truncate" title="{{ $oyun->url }}">
                                    {{ Str::limit($oyun->url, 20) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($canliCasino->count() == 0)
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i data-lucide="image-off" class="w-10 h-10 text-zinc-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Henüz canlı casino görseli eklenmemiş</h3>
                <p class="text-gray-400 text-sm mb-4">İlk görselinizi ekleyerek başlayın</p>
                <button onclick="document.getElementById('quickImages').click()" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 text-yellow-400 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    İlk görseli ekle
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Enhanced Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-gradient-to-br from-zinc-900/95 to-zinc-800/95 border border-zinc-700/50 rounded-2xl p-6 w-full max-w-2xl mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-lg flex items-center justify-center">
                    <i data-lucide="upload" class="w-4 h-4 text-yellow-400"></i>
                </div>
                Görsel Yükleme
            </h3>
            <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-800/50 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="quickUploadForm" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="uploadType" name="type">
            
            <div class="bg-zinc-800/30 border border-zinc-700/30 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4 text-yellow-400"></i>
                    URL Bilgileri
                </h4>
                <div id="selectedFiles" class="space-y-4 max-h-80 overflow-y-auto"></div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 hover:border-yellow-500/50 text-yellow-400 hover:text-yellow-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Görselleri Yükle
                </button>
                <button type="button" onclick="closeUploadModal()" class="px-6 py-3 bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-600/50 text-gray-400 hover:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-gradient-to-br from-zinc-900/95 to-zinc-800/95 border border-zinc-700/50 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-lg flex items-center justify-center">
                    <i data-lucide="edit" class="w-4 h-4 text-yellow-400"></i>
                </div>
                Görseli Düzenle
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-800/50 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="editForm" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="editType" name="type">
            <input type="hidden" id="editId" name="id">
            
            <div class="bg-zinc-800/30 border border-zinc-700/30 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-yellow-400"></i>
                    Mevcut Görsel
                </h4>
                <div class="w-full h-32 bg-zinc-800/50 rounded-lg overflow-hidden mb-3 border border-zinc-700/30">
                    <img id="currentImage" src="" alt="Mevcut Görsel" class="w-full h-full object-cover">
                </div>
                <input type="file" name="gorsel" accept="image/*" class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 transition-all duration-200">
            </div>
            
            <div class="bg-zinc-800/30 border border-zinc-700/30 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4 text-yellow-400"></i>
                    Yönlendirme URL'si
                </h4>
                <input type="text" id="editUrl" name="url" placeholder="Örnek: /casino, /games, https://example.com" required class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 transition-all duration-200" title="Görsel için yönlendirme URL'si">
                <p class="text-xs text-gray-400 mt-2">Bu URL'ye tıklandığında kullanıcı yönlendirilecek</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 hover:border-yellow-500/50 text-yellow-400 hover:text-yellow-300 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    Güncelle
                </button>
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-zinc-800/70 hover:bg-zinc-700/70 border border-zinc-600/50 text-gray-400 hover:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200">
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
            btn.classList.remove('active', 'bg-yellow-500/20', 'border-yellow-500/40', 'text-yellow-400');
            btn.classList.add('bg-zinc-800/70', 'border-zinc-600/50', 'text-gray-400');
            btn.querySelector('span').classList.remove('bg-yellow-500/40', 'text-yellow-300');
            btn.querySelector('span').classList.add('bg-zinc-700/70', 'text-gray-400');
        });
        tabContents.forEach(content => content.classList.remove('active'));
        
        const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
        const activeContent = document.getElementById(`tab-${tabId}`);
        
        if (activeButton && activeContent) {
            activeButton.classList.add('active');
            activeButton.classList.remove('bg-zinc-800/70', 'border-zinc-600/50', 'text-gray-400');
            activeButton.classList.add('bg-yellow-500/20', 'border-yellow-500/40', 'text-yellow-400');
            activeButton.querySelector('span').classList.remove('bg-zinc-700/70', 'text-gray-400');
            activeButton.querySelector('span').classList.add('bg-yellow-500/40', 'text-yellow-300');
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
        fileDiv.className = 'bg-zinc-800/50 border border-zinc-700/30 rounded-lg p-4';
        fileDiv.innerHTML = `
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-zinc-700/50 rounded-lg overflow-hidden flex-shrink-0 border border-zinc-600/30">
                    <img src="${URL.createObjectURL(file)}" alt="Preview" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <p class="text-white text-sm font-medium">${file.name}</p>
                    <p class="text-gray-400 text-xs">${(file.size / 1024).toFixed(1)} KB</p>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-medium text-gray-300">Yönlendirme URL'si</label>
                <input type="text" name="urls[]" placeholder="Örnek: /casino, /games, https://example.com" required class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 transition-all duration-200" title="Görsel için yönlendirme URL'si">
                <p class="text-xs text-gray-400">Bu URL'ye tıklandığında kullanıcı yönlendirilecek</p>
            </div>
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
                    orderBadge.textContent = '#' + (index + 1);
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
    
    messageDiv.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-xl flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
    messageDiv.innerHTML = `
        <span class="font-bold text-lg">${icon}</span>
        <span class="text-sm font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 p-1 hover:bg-white/10 rounded transition-colors">
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
    transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: rgba(63, 63, 70, 0.3);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: rgba(161, 161, 170, 0.5);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(161, 161, 170, 0.7);
}

/* Enhanced animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sortable-item {
    animation: fadeInUp 0.3s ease-out;
}
</style>
@endpush
