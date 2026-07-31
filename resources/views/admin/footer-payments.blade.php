@extends('layouts.admin')

@section('title', 'Footer Görsel Sayfası')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Footer Görsel Sayfası</h1>
            <p class="text-gray-400 mt-1">Footer'da görünecek ödeme yöntemi görsellerini yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAddModal()" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Görsel Ekle
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Görsel</p>
                    <p class="stat-card-value">{{ $payments->count() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="image" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Görsel</p>
                    <p class="stat-card-value">{{ $payments->where('status', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Pasif Görsel</p>
                    <p class="stat-card-value">{{ $payments->where('status', 0)->count() }}</p>
                </div>
                <div class="stat-card-icon red">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Images Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($payments as $payment)
        <div class="content-card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center overflow-hidden">
                        @if($payment->gorsel)
                            <img src="{{ asset($payment->gorsel) }}" alt="{{ $payment->name }}" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="image" class="w-6 h-6 text-blue-500"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">{{ $payment->name }}</h3>
                        <p class="text-sm text-gray-400">Sıra: {{ $payment->sira }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20' }}">
                        {{ $payment->status ? 'Aktif' : 'Pasif' }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Görsel:</span>
                    <span class="text-white">{{ $payment->gorsel ? 'Yüklü' : 'Yok' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Oluşturulma:</span>
                    <span class="text-white">{{ $payment->created_at->format('d.m.Y') }}</span>
                </div>
            </div>
            
            <div class="flex gap-2 mt-4">
                <button onclick="openEditModal({{ $payment->id }}, '{{ $payment->name }}', {{ $payment->sira }})" class="flex-1 px-3 py-2 text-sm bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                    Düzenle
                </button>
                <button onclick="toggleStatus({{ $payment->id }})" class="px-3 py-2 text-sm {{ $payment->status ? 'bg-red-500/10 text-red-500 hover:bg-red-500/20' : 'bg-green-500/10 text-green-500 hover:bg-green-500/20' }} rounded-lg transition-colors">
                    <i data-lucide="{{ $payment->status ? 'power-off' : 'power' }}" class="w-4 h-4"></i>
                </button>
                <button onclick="deletePayment({{ $payment->id }})" class="px-3 py-2 text-sm bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="content-card text-center py-12">
                <i data-lucide="image-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-white mb-2">Henüz görsel eklenmemiş</h3>
                <p class="text-gray-400 mb-4">Footer'da görünecek ödeme yöntemi görsellerini eklemek için yukarıdaki butonu kullanın.</p>
                <button onclick="openAddModal()" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    İlk Görseli Ekle
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Payment Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 opacity-0 invisible transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-zinc-900 border border-zinc-700 rounded-lg w-full max-w-md transform scale-95 transition-transform duration-300">
            <div class="flex items-center justify-between p-6 border-b border-zinc-700">
                <h3 class="text-lg font-semibold text-white">Yeni Ödeme Görseli Ekle</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.footer-payments.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Görsel Adı</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Görsel Dosyası</label>
                    <input type="file" name="image" accept="image/*" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-500/10 file:text-yellow-500 hover:file:bg-yellow-500/20">
                    <p class="text-xs text-gray-400 mt-1">Desteklenen formatlar: JPEG, PNG, JPG, WebP (Max: 2MB)</p>
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

<!-- Edit Payment Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 opacity-0 invisible transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-zinc-900 border border-zinc-700 rounded-lg w-full max-w-md transform scale-95 transition-transform duration-300">
            <div class="flex items-center justify-between p-6 border-b border-zinc-700">
                <h3 class="text-lg font-semibold text-white">Ödeme Görselini Düzenle</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('POST')
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Görsel Adı</label>
                    <input type="text" name="name" id="editName" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Görsel Dosyası (Opsiyonel)</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-500/10 file:text-yellow-500 hover:file:bg-yellow-500/20">
                    <p class="text-xs text-gray-400 mt-1">Yeni görsel yüklemezseniz mevcut görsel korunacaktır</p>
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

@endsection

@push('scripts')
<script>
// Lucide ikonlarını yeniden yükle
lucide.createIcons();
function openAddModal() {
    const modal = document.getElementById('addModal');
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100', 'visible');
    modal.querySelector('.transform').classList.remove('scale-95');
    modal.querySelector('.transform').classList.add('scale-100');
}

function closeAddModal() {
    const modal = document.getElementById('addModal');
    modal.classList.add('opacity-0', 'invisible');
    modal.classList.remove('opacity-100', 'visible');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
}

function openEditModal(id, name, order) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    const nameInput = document.getElementById('editName');
    const orderInput = document.getElementById('editOrder');
    
    form.action = `/admin/footer-payments/${id}/update`;
    nameInput.value = name;
    orderInput.value = order;
    
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100', 'visible');
    modal.querySelector('.transform').classList.remove('scale-95');
    modal.querySelector('.transform').classList.add('scale-100');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.add('opacity-0', 'invisible');
    modal.classList.remove('opacity-100', 'visible');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
}

function toggleStatus(id) {
    if (confirm('Bu görselin durumunu değiştirmek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/footer-payments/${id}/toggle-status`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function deletePayment(id) {
    if (confirm('Bu görseli silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/footer-payments/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Modal dışına tıklandığında kapat
document.addEventListener('click', function(e) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (e.target === addModal) {
        closeAddModal();
    }
    
    if (e.target === editModal) {
        closeEditModal();
    }
});

// ESC tuşu ile modal kapatma
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
});
</script>
@endpush 