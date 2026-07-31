@extends('layouts.admin')

@section('title', 'Anasayfa Bölüm Yönetimi')

@section('content')
<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="w-full max-w-7xl mx-auto">
    <!-- Enhanced Header -->
    <div class="bg-gradient-to-r from-zinc-900/60 to-zinc-800/60 border border-zinc-700/50 rounded-xl mb-6 shadow-xl">
        <div class="flex items-center justify-between p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500/30 to-yellow-600/30 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="layout" class="w-6 h-6 text-yellow-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Anasayfa Bölüm Yönetimi</h2>
                    <p class="text-sm text-gray-400">Toplam {{ $sections->count() }} bölüm • Sıra numarası ile sıralama</p>
                </div>
            </div>
            
            <!-- Save Button -->
            <button onclick="saveOrder()" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border border-yellow-500/40 hover:border-yellow-500/50 text-yellow-400 hover:text-yellow-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i data-lucide="save" class="w-4 h-4"></i>
                Sırayı Kaydet
            </button>
        </div>
    </div>

    <!-- Enhanced Content -->
    <div class="bg-gradient-to-br from-zinc-900/60 to-zinc-800/60 border border-zinc-700/50 rounded-xl p-6 shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sections as $section)
            <div class="section-item group" data-id="{{ $section->id }}">
                <div class="relative bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-xl border border-zinc-600/30 overflow-hidden hover:border-yellow-500/50 transition-all duration-300 flex flex-col shadow-lg hover:shadow-2xl transform hover:scale-105">
                    
                    <!-- Section Header -->
                    <div class="p-4 bg-gradient-to-r from-zinc-700/30 to-zinc-600/30 border-b border-zinc-600/30">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Section Icon -->
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500/20 to-red-600/20 border border-red-500/30 rounded-lg flex items-center justify-center">
                                    @if($section->icon)
                                        <div class="w-5 h-5 text-red-400">
                                            {!! $section->icon !!}
                                        </div>
                                    @else
                                        <i data-lucide="image" class="w-5 h-5 text-red-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $section->title }}</h3>
                                    <p class="text-xs text-zinc-400 font-mono">{{ $section->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Content -->
                    <div class="p-4 flex-1">
                        <!-- Order Input - More Prominent -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Sıra Numarası</label>
                            <input type="number" 
                                   class="order-input w-full px-4 py-3 bg-zinc-700 border border-zinc-600 rounded-lg text-center text-white text-lg font-bold focus:outline-none focus:border-yellow-500/50 focus:ring-2 focus:ring-yellow-500/20 transition-all cursor-text"
                                   value="{{ $section->order }}"
                                   min="1"
                                   max="{{ $sections->count() }}"
                                   onchange="updateOrderInput(this, {{ $section->id }})"
                                   placeholder="Sıra">
                        </div>
                        
                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-zinc-400">Durum:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer" 
                                       id="status_{{ $section->id }}"
                                       {{ $section->is_active ? 'checked' : '' }}
                                       {{ $section->is_required ? 'disabled' : '' }}
                                       onchange="toggleStatus({{ $section->id }})">
                                <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-green-500 peer-checked:to-green-600 {{ $section->is_required ? 'opacity-50 cursor-not-allowed' : '' }}"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium {{ $section->is_active ? 'text-green-400' : 'text-red-400' }}">
                                {{ $section->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                            
                            @if($section->is_required)
                                <div class="px-3 py-1.5 bg-yellow-500/10 text-yellow-400 rounded-lg border border-yellow-500/20">
                                    <span class="text-xs font-medium">Zorunlu</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($sections->count() == 0)
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-gradient-to-br from-zinc-800/50 to-zinc-700/50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i data-lucide="layout" class="w-10 h-10 text-zinc-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Henüz bölüm eklenmemiş</h3>
            <p class="text-gray-400 text-sm">Bölümler otomatik olarak oluşturulur</p>
        </div>
        @endif
    </div>
</div>

<style>
.section-item {
    transition: all 0.3s ease;
}

.section-item:hover {
    transform: translateY(-2px);
}

.order-input::-webkit-inner-spin-button,
.order-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.order-input[type=number] {
    -moz-appearance: textfield;
}

.order-input:focus {
    transform: scale(1.02);
    box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.1);
}

.order-input:hover {
    border-color: rgba(234, 179, 8, 0.5);
}
</style>

@endsection

<script>
// Global functions - accessible from onclick handlers
function updateOrderInput(input, sectionId) {
    const newOrder = parseInt(input.value);
    const maxOrder = {{ $sections->count() }};
    
    if (newOrder < 1 || newOrder > maxOrder) {
        showMessage(`Sıra numarası 1 ile ${maxOrder} arasında olmalıdır`, 'error');
        input.value = input.defaultValue;
        return;
    }
    
    console.log(`Section ${sectionId} order changed to ${newOrder}`);
}

function saveOrder() {
    const sections = [];
    const orderInputs = document.querySelectorAll('.order-input');
    
    orderInputs.forEach(input => {
        const sectionId = input.closest('.section-item').dataset.id;
        const order = parseInt(input.value);
        sections.push({
            id: parseInt(sectionId),
            order: order
        });
    });
    
    // Sort by order
    sections.sort((a, b) => a.order - b.order);
    
    const sectionIds = sections.map(s => s.id);
    console.log('Saving order:', sectionIds); // Debug log

    // Show loading state
    const saveButton = document.querySelector('button[onclick="saveOrder()"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Kaydediliyor...';
    saveButton.disabled = true;

    fetch('{{ route("admin.home-sections.order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ sections: sectionIds })
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data); // Debug log
        if (data.success) {
            showMessage('Sıralama başarıyla kaydedildi!', 'success');
            // Reload page to show updated order numbers
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage('Bir hata oluştu', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu', 'error');
    })
    .finally(() => {
        // Restore button state
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
}

function toggleStatus(id) {
    const checkbox = document.getElementById(`status_${id}`);
    const originalChecked = checkbox.checked;

    fetch(`{{ url('admin/home-sections') }}/${id}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            // Update the status text
            const statusText = checkbox.closest('.section-item').querySelector('.text-sm.font-medium');
            if (statusText) {
                statusText.textContent = data.new_status ? 'Aktif' : 'Pasif';
                statusText.className = `text-sm font-medium ${data.new_status ? 'text-green-400' : 'text-red-400'}`;
            }
        } else {
            showMessage(data.message, 'error');
            checkbox.checked = !checkbox.checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu', 'error');
        checkbox.checked = !checkbox.checked;
    });
}

function showMessage(message, type = 'info') {
    const messageContainer = document.getElementById('messageContainer');
    const messageElement = document.createElement('div');
    
    messageElement.className = `p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    if (type === 'success') {
        messageElement.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        messageElement.className += ' bg-red-500 text-white';
    } else {
        messageElement.className += ' bg-blue-500 text-white';
    }
    
    messageElement.innerHTML = `
        <div class="flex items-center gap-3">
            <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info'}" class="w-5 h-5"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    messageContainer.appendChild(messageElement);
    
    // Animate in
    setTimeout(() => {
        messageElement.classList.remove('translate-x-full');
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        messageElement.classList.add('translate-x-full');
        setTimeout(() => {
            if (messageElement.parentNode) {
                messageElement.parentNode.removeChild(messageElement);
            }
        }, 300);
    }, 3000);
    
    // Initialize icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...'); // Debug log
    
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script> 