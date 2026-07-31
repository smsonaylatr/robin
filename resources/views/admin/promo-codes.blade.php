@extends('layouts.admin')

@section('title', 'Promosyon Kodları')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Başlık -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Promosyon Kodları</h1>
            <p class="text-gray-300">Promosyon kodlarını buradan yönetebilirsiniz.</p>
        </div>

        <!-- Başarı Mesajı -->
        @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Hata Mesajı -->
        @if($errors->any())
        <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Yeni Promosyon Kodu Ekleme -->
        <div class="bg-gray-800 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-plus mr-2"></i>
                Yeni Promosyon Kodu Ekle
            </h2>
            
            <form action="{{ route('admin.promo-codes.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @csrf
                
                <div>
                    <label class="block text-gray-300 mb-2">Promosyon Kodu</label>
                    <input type="text" name="code" required
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none"
                           placeholder="Örn: WELCOME100">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">Miktar (₺)</label>
                    <input type="number" name="amount" step="0.01" min="1" required
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none"
                           placeholder="500">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">Maksimum Kullanım</label>
                    <input type="number" name="max_uses" min="1" required
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none"
                           placeholder="100">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">Çevrim Katı</label>
                    <input type="number" name="turnover_multiplier" min="1" required
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none"
                           placeholder="5">
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">Bitiş Tarihi (Opsiyonel)</label>
                    <input type="datetime-local" name="expires_at"
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Promosyon Kodu Ekle
                    </button>
                </div>
            </form>
        </div>

        <!-- Promosyon Kodları Listesi -->
        <div class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-list mr-2"></i>
                Mevcut Promosyon Kodları
            </h2>
            
            @if($promoCodes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-300">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                        <tr>
                            <th class="px-6 py-3">Kod</th>
                            <th class="px-6 py-3">Miktar</th>
                            <th class="px-6 py-3">Kullanım</th>
                            <th class="px-6 py-3">Çevrim</th>
                            <th class="px-6 py-3">Durum</th>
                            <th class="px-6 py-3">Bitiş Tarihi</th>
                            <th class="px-6 py-3">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promoCodes as $promoCode)
                        <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-mono text-blue-400">{{ $promoCode->code }}</td>
                            <td class="px-6 py-4">₺{{ number_format($promoCode->balance_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="text-green-400">{{ $promoCode->used_count }}</span>
                                /
                                <span class="text-gray-400">{{ $promoCode->max_uses }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $promoCode->turnover_amount }}x</td>
                            <td class="px-6 py-4">
                                @if($promoCode->is_active)
                                    <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-600 text-white text-xs rounded">Pasif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($promoCode->expires_at)
                                    {{ \Carbon\Carbon::parse($promoCode->expires_at)->format('d.m.Y H:i') }}
                                @else
                                    <span class="text-gray-500">Süresiz</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="editPromoCode({{ $promoCode->id }})" 
                                            class="text-blue-400 hover:text-blue-300">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.promo-codes.toggle-status', $promoCode->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-400 hover:text-yellow-300">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.promo-codes.delete', $promoCode->id) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Bu promosyon kodunu silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-ticket text-4xl text-gray-600 mb-4"></i>
                <p class="text-gray-400">Henüz promosyon kodu eklenmemiş.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold text-white mb-4">Promosyon Kodu Düzenle</h3>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 mb-2">Promosyon Kodu</label>
                        <input type="text" name="code" id="edit_code" required
                               class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2">Miktar (₺)</label>
                        <input type="number" name="amount" id="edit_amount" step="0.01" min="1" required
                               class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2">Maksimum Kullanım</label>
                        <input type="number" name="max_uses" id="edit_max_uses" min="1" required
                               class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2">Çevrim Katı</label>
                        <input type="number" name="turnover_multiplier" id="edit_turnover_multiplier" min="1" required
                               class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2">Bitiş Tarihi (Opsiyonel)</label>
                        <input type="datetime-local" name="expires_at" id="edit_expires_at"
                               class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        Güncelle
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        İptal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPromoCode(id) {
    // Burada AJAX ile promosyon kodu verilerini çekip modal'ı doldur
    // Şimdilik basit bir örnek
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection 