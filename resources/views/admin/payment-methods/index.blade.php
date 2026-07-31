@extends('layouts.admin')

@section('title', 'Ödeme Yöntemleri Yönetimi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Başlık -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center justify-center gap-3">
                <i class="fas fa-credit-card text-yellow-400"></i>
                Ödeme Yöntemleri Yönetimi
            </h1>
            <p class="text-gray-400 text-sm">Para yatırma sayfasında görünecek ödeme yöntemlerini buradan aktif/pasif yapabilirsiniz.</p>
        </div>

        <!-- Mesajlar -->
        @if(session('success'))
        <div class="bg-green-600/20 border border-green-400/30 text-green-300 p-3 rounded-lg mb-6 text-sm text-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Mevcut Ödeme Yöntemleri -->
        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 shadow-xl border border-white/10">
            <h2 class="text-lg font-semibold text-white mb-4">Mevcut Ödeme Yöntemleri</h2>
            
            @if($paymentMethods->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-700/50">
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Method Key</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Method Name</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Provider</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Type</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Min/Max</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Status</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Sort</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $method)
                        <tr class="border-b border-gray-700/30 hover:bg-white/5">
                            <td class="py-3 px-4 text-white font-mono text-xs">{{ $method->method_key }}</td>
                            <td class="py-3 px-4 text-white">{{ $method->method_name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @if($method->provider === 'hemen') bg-yellow-500/20 text-yellow-300
                                    @elseif($method->provider === 'extra') bg-green-500/20 text-green-300
                                    @elseif($method->provider === 'oley') bg-purple-500/20 text-purple-300
                                    @else bg-gray-500/20 text-gray-300 @endif">
                                    {{ ucfirst($method->provider) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @if($method->type === 'iframe') bg-blue-500/20 text-blue-300
                                    @elseif($method->type === 'rest') bg-green-500/20 text-green-300
                                    @else bg-orange-500/20 text-orange-300 @endif">
                                    {{ strtoupper($method->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-300 text-xs">
                                {{ number_format($method->min_amount) }}₺ / {{ number_format($method->max_amount) }}₺
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @if($method->is_active) bg-green-500/20 text-green-300 @else bg-red-500/20 text-red-300 @endif">
                                    {{ $method->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-300">{{ $method->sort_order }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button onclick="toggleMethodStatus({{ $method->id }})" 
                                            class="px-3 py-1 rounded text-xs font-medium transition-colors
                                            @if($method->is_active) bg-red-500/20 text-red-300 hover:bg-red-500/30 @else bg-green-500/20 text-green-300 hover:bg-green-500/30 @endif">
                                        <i class="fas {{ $method->is_active ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>
                                        {{ $method->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-400">Henüz ödeme yöntemi bulunmuyor.</p>
            </div>
            @endif
        </div>

        <!-- Bilgi Kartı -->
        <div class="mt-8 bg-blue-900/20 border border-blue-400/30 rounded-lg p-4">
            <h3 class="text-blue-300 font-semibold mb-2 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-400"></i>
                Bilgi
            </h3>
            <div class="text-gray-300 text-sm space-y-1">
                <p>• <strong>Aktif</strong> olan ödeme yöntemleri para yatırma sayfasında görünür</p>
                <p>• <strong>Pasif</strong> olan ödeme yöntemleri para yatırma sayfasında gizlenir</p>
                <p>• Yeni ödeme yöntemi eklemek için veritabanına manuel olarak eklemeniz gerekir</p>
                <p>• Provider renkleri: <span class="text-yellow-300">Sarı</span> (Hemen), <span class="text-green-300">Yeşil</span> (Extra), <span class="text-purple-300">Mor</span> (Oley)</p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMethodStatus(methodId) {
    const methodName = event.target.closest('tr').querySelector('td:nth-child(2)').textContent;
    const currentStatus = event.target.textContent.includes('Pasif Yap') ? 'aktif' : 'pasif';
    const newStatus = currentStatus === 'aktif' ? 'pasif' : 'aktif';
    
    if (confirm(`"${methodName}" ödeme yöntemini ${newStatus} hale getirmek istediğinizden emin misiniz?`)) {
        window.location.href = `/admin/payment-methods/${methodId}/toggle`;
    }
}
</script>
@endsection 