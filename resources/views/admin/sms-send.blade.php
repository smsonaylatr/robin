@extends('layouts.admin')

@section('title', 'SMS Gönder')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">SMS Gönder</h1>
        <p class="text-zinc-400">Toplu SMS gönderme sistemi</p>
    </div>

    <!-- SMS Credit Info -->
    <div class="content-card mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-2">SMS Kredisi</h3>
                <p class="text-zinc-400">Kalan SMS hakkınız: <span class="text-yellow-500 font-bold">{{ $settings->smsadet ?? 0 }}</span> adet</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-zinc-400">API Durumu</div>
                <div class="text-green-500 font-semibold">Aktif</div>
            </div>
        </div>
    </div>

    <!-- SMS Form -->
    <div class="content-card">
        <h3 class="text-lg font-semibold text-white mb-4">SMS Gönder</h3>
        
        @if(session('success'))
            <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-4 mb-6">
                <p class="text-green-400 text-center font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-900/20 border border-red-500/30 rounded-lg p-4 mb-6">
                <p class="text-red-400 text-center font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.sms-send.store') }}" class="space-y-6">
            @csrf
            
            <!-- Phone Numbers -->
            <div>
                <label for="phone" class="block text-sm font-medium text-zinc-300 mb-2">
                    Telefon Numaraları
                </label>
                <textarea 
                    id="phone" 
                    name="phone" 
                    rows="4" 
                    class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500"
                    placeholder="Telefon numaralarını virgül ile ayırarak girin&#10;Örnek: 05551234567, 05559876543, +905551234567"
                    required
                >{{ old('phone') }}</textarea>
                <p class="text-xs text-zinc-500 mt-1">
                    Birden fazla numara için virgül kullanın. Başında 0 varsa otomatik olarak 90 eklenir.
                </p>
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block text-sm font-medium text-zinc-300 mb-2">
                    Mesaj İçeriği
                </label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="6" 
                    class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500"
                    placeholder="Göndermek istediğiniz mesajı yazın..."
                    required
                    maxlength="160"
                >{{ old('message') }}</textarea>
                <div class="flex justify-between items-center mt-1">
                    <p class="text-xs text-zinc-500">
                        Maksimum 160 karakter. Türkçe karakterler 2 karakter sayılır.
                    </p>
                    <span id="char-count" class="text-xs text-zinc-400">0/160</span>
                </div>
            </div>

            <!-- Send Button -->
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center gap-2"
                    id="send-btn"
                >
                    <i data-lucide="send" class="w-4 h-4"></i>
                    SMS Gönder
                </button>
            </div>
        </form>
    </div>

    <!-- Recent SMS History -->
    <div class="content-card mt-6">
        <h3 class="text-lg font-semibold text-white mb-4">Son SMS Gönderimleri</h3>
        
        @if(isset($recentSms) && count($recentSms) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-700">
                            <th class="text-left py-2 text-zinc-400">Tarih</th>
                            <th class="text-left py-2 text-zinc-400">Telefon</th>
                            <th class="text-left py-2 text-zinc-400">Mesaj</th>
                            <th class="text-left py-2 text-zinc-400">Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSms as $sms)
                            <tr class="border-b border-zinc-800/50">
                                <td class="py-2 text-zinc-300">{{ $sms->created_at->format('d.m.Y H:i') }}</td>
                                <td class="py-2 text-zinc-300">{{ $sms->phone }}</td>
                                <td class="py-2 text-zinc-300">{{ Str::limit($sms->message, 50) }}</td>
                                <td class="py-2">
                                    @if($sms->status == 'success')
                                        <span class="text-green-500 text-xs">Başarılı</span>
                                    @else
                                        <span class="text-red-500 text-xs">Başarısız</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-zinc-500 text-center py-4">Henüz SMS gönderimi yapılmamış.</p>
        @endif
    </div>
</div>

<script>
// Character counter
const messageTextarea = document.getElementById('message');
const charCount = document.getElementById('char-count');

messageTextarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = `${length}/160`;
    
    if (length > 140) {
        charCount.classList.add('text-yellow-500');
    } else {
        charCount.classList.remove('text-yellow-500');
    }
    
    if (length > 160) {
        charCount.classList.add('text-red-500');
    } else {
        charCount.classList.remove('text-red-500');
    }
});

// Form validation
const form = document.querySelector('form');
const sendBtn = document.getElementById('send-btn');

form.addEventListener('submit', function(e) {
    const phone = document.getElementById('phone').value.trim();
    const message = document.getElementById('message').value.trim();
    
    if (!phone || !message) {
        e.preventDefault();
        alert('Lütfen tüm alanları doldurun.');
        return;
    }
    
    // Phone number validation
    const phones = phone.split(',').map(p => p.trim()).filter(p => p);
    if (phones.length === 0) {
        e.preventDefault();
        alert('Lütfen en az bir telefon numarası girin.');
        return;
    }
    
    // Check SMS credit
    const smsCredit = {{ $settings->smsadet ?? 0 }};
    if (phones.length > smsCredit) {
        e.preventDefault();
        alert(`SMS krediniz yetersiz. ${phones.length} kişiye SMS göndermek için ${phones.length} kredi gerekiyor. Mevcut kredi: ${smsCredit}`);
        return;
    }
    
    // Confirm before sending
    if (!confirm(`${phones.length} kişiye SMS göndermek istediğinizden emin misiniz?`)) {
        e.preventDefault();
        return;
    }
    
    // Disable button and show loading
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Gönderiliyor...';
});
</script>
@endsection 