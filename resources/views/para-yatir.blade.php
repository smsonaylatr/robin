    @extends('layouts.app')

    @section('title', 'Para Yatır - ' . ($settings->site_adi ?? 'BetNow'))

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Para Yatır</h1>
            <p class="text-zinc-400">Güvenli ve hızlı ödeme yöntemleri ile hesabınıza para yatırın</p>
        </div>

        <!-- Error Messages -->
        @if(session('error_message'))
            <div class="bg-red-900/20 border border-red-500/30 rounded-lg p-4 mb-6">
                <p class="text-red-400 text-center font-medium">{{ session('error_message') }}</p>
            </div>
        @endif

        <div class="max-w-4xl mx-auto space-y-6">

            @php
                $providers = $paymentMethods->groupBy('provider');
            @endphp

            @foreach($providers as $provider => $methods)
                @php
                    $providerNames = [
                        'hemen' => 'Hemen Pay Ödeme Yöntemleri',
                        'extra' => 'Extra Cüzdan Ödeme Yöntemleri'
                    ];
                    $providerIcons = [
                        'hemen' => 'fas fa-bolt text-yellow-400',
                        'extra' => 'fas fa-wallet text-green-400'
                    ];
                @endphp

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-white mb-4 text-center">
                        <i class="{{ $providerIcons[$provider] ?? 'fas fa-credit-card' }} mr-2"></i>
                        {{ $providerNames[$provider] ?? ucfirst($provider) . ' Ödeme Yöntemleri' }}
                    </h2>
                    <div class="space-y-4">

                        @foreach($methods as $method)
                            <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg overflow-hidden">
                                <div class="p-4 cursor-pointer hover:bg-zinc-800/30 transition-colors" onclick="togglePanel('{{ $method->method_key }}-content')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            @if($method->image)
                                                <img src="{{ asset($method->image) }}" 
                                                    alt="{{ $method->method_name }}" class="w-auto h-12 rounded-lg" onerror="this.style.display='none'">
                                            @else
                                                <div class="w-12 h-12 bg-zinc-700 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-credit-card text-zinc-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="text-white font-semibold">{{ $method->method_name }} ile Para Yatır</h3>
                                                <div class="flex space-x-2 mt-1">
                                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded">Min: {{ number_format($method->min_amount) }}₺</span>
                                                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded">Max: {{ number_format($method->max_amount) }}₺</span>
                                                </div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-zinc-400 transform transition-transform" id="{{ $method->method_key }}-icon">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="hidden border-t border-zinc-800" id="{{ $method->method_key }}-content">
                                    <div class="p-6">
                                        @if($method->provider === 'oley' && $method->method_key === 'KOLAYHAVALE')
                                            <!-- KOLAYHAVALE için özel form (IFRAME) -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-zinc-300 mb-2">Yatırılacak Miktar</label>
                                                <div class="grid grid-cols-3 gap-2 mb-3">
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="100" data-target="{{ $method->method_key }}">100₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="500" data-target="{{ $method->method_key }}">500₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="1000" data-target="{{ $method->method_key }}">1000₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="2500" data-target="{{ $method->method_key }}">2500₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="5000" data-target="{{ $method->method_key }}">5000₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="10000" data-target="{{ $method->method_key }}">10000₺</button>
                                                </div>
                                                <input type="number" id="{{ $method->method_key }}-amount" name="amount" 
                                                    class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2"
                                                    placeholder="Özel miktar giriniz" min="{{ $method->min_amount }}" max="{{ $method->max_amount }}">
                                            </div>

                                            <!-- IFRAME için banka seçimi gerekmez - iframe içinde seçilecek -->
                                            <div class="mb-4 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-blue-300 text-sm">Banka seçimi ödeme sayfasında yapılacaktır</span>
                                                </div>
                                            </div>
                                        @elseif($method->provider === 'oley' && in_array($method->method_key, ['OLEY_CREDIT_CARD', 'OLEY_PAPARA', 'OLEY_MEFETE', 'PARAZULA', 'POPY', 'PAYCO']))
                                            <!-- OleyPayment diğer yöntemleri için miktar seçimi -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-zinc-300 mb-2">Yatırılacak Miktar</label>
                                                <div class="grid grid-cols-3 gap-2 mb-3">
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="{{ $method->min_amount }}" data-target="{{ $method->method_key }}">{{ number_format($method->min_amount) }}₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="100" data-target="{{ $method->method_key }}">100₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="500" data-target="{{ $method->method_key }}">500₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="1000" data-target="{{ $method->method_key }}">1000₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="2500" data-target="{{ $method->method_key }}">2500₺</button>
                                                    <button type="button" class="amount-btn bg-zinc-800 hover:bg-zinc-700 text-white py-2 px-4 rounded transition-colors" data-amount="5000" data-target="{{ $method->method_key }}">5000₺</button>
                                                </div>
                                                <input type="number" id="{{ $method->method_key }}-amount" name="amount" 
                                                    class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2"
                                                    placeholder="Özel miktar giriniz" min="{{ $method->min_amount }}" max="{{ $method->max_amount }}">
                                            </div>
                                        @elseif($method->provider === 'extra' && $method->method_key === 'EXTRA_KRIPTO')
                                            <!-- Extra Cüzdan Kripto için network seçimi -->
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-zinc-300 mb-2">Ağ (Network)</label>
                                                <select id="{{ $method->method_key }}-network" class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2">
                                                    <option value="TRC20">TRC20 (USDT-TRON)</option>
                                                    <option value="ERC20">ERC20 (USDT-ETH)</option>
                                                    <option value="BEP20">BEP20 (USDT-BSC)</option>
                                                </select>
                                                <p class="text-xs text-zinc-400 mt-2">Ödeme sayfasında tutarı belirleyeceksiniz. Bu adımda sadece ağı seçmeniz yeterli.</p>
                                            </div>
                                        @endif

                                        <div class="text-right">
                                            @if($method->provider === 'oley')
                                                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors" 
                                                        onclick="submitOleyPayment('{{ $method->method_key }}')">
                                                    <i class="fa fa-credit-card mr-2"></i>
                                                    PARA YATIR
                                                </button>
                                            @else
                                                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors" 
                                                        onclick="submitPaymentForm('{{ $method->method_key }}')">
                                                    <i class="fa fa-credit-card mr-2"></i>
                                                    PARA YATIR
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div id="oley-iframe-container" style="width: 100%; height: 600px; display: none; margin-top: 20px;">
    <iframe id="oley-iframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>

    <script>
    /* PHP'den gelen bakiye/bonus bilgileri */
    var userBalance = {{ (float)$user->bakiye }};
    var bonusActive = {{ (int)($user->yasakbonus ?? 0) }};

    /* Basit modal örneği: Sizin harici bir modalınız yoksa alert() gösterebiliriz */
    function showWarningModal(msg){
    // Uyarı gösterme - sessizce geç
    console.log('Warning suppressed:', msg);
    }

    /* "Para Yatır" butonu tıklandığında güvenli API'ye POST */
    function submitPaymentForm(paymentMethod){
    // Bonus kontrolü kaldırıldı - direkt devam et

    // Loading göster
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>YÜKLENİYOR...';
    button.disabled = true;

    // FormData oluştur - ödeme yöntemi + opsiyonel alanlar
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('paymentMethod', paymentMethod);

    // Extra Cüzdan Kripto için network ekle
    const networkInput = document.getElementById(paymentMethod + '-network');
    if (networkInput && networkInput.value) {
        formData.append('network', networkInput.value);
    }

    // AJAX isteği gönder - güvenli backend API'ye
    fetch('{{ route("payment.method") }}', {
        method: 'POST',
        body: formData,
        headers: {
        'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Button'u eski haline getir
        button.innerHTML = originalText;
        button.disabled = false;

        if (data.success) {
        if (data.url) {
            // Mobil kontrolü
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            if (isMobile) {
                // Mobilde aynı sekmede aç
                window.location.href = data.url;
            } else {
                // Masaüstünde yeni sekmede aç
                window.open(data.url, '_blank', 'noopener,noreferrer');
            }
        } else {
            // Direct metotları için sadece hata durumunda uyarı
            console.log('Direct payment method - no URL provided');
        }
        } else {
        // Hata durumunda sessizce geç
        console.log('Payment error:', data.message || 'Ödeme işlemi başlatılamadı');
        }
    })
    .catch(error => {
        // Button'u eski haline getir
        button.innerHTML = originalText;
        button.disabled = false;
        
        console.error('Payment error:', error);
        // Hata durumunda sessizce geç
    });
    }

    /* Panel tıklama fonksiyonu */
    function togglePanel(contentId) {
        const content = document.getElementById(contentId);
        const icon = document.getElementById(contentId.replace('-content', '-icon'));
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }

    // Banka listesi artık KOLAYHAVALE için gerekmez - iframe içinde seçilecek

    // Miktar butonlarına tıklama eventi
    document.addEventListener('DOMContentLoaded', function() {
    // Miktar butonları için event listener
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', function() {
        const amount = this.getAttribute('data-amount');
        const target = this.getAttribute('data-target') || 'KOLAYHAVALE';
        
        // Hedef input'u bul ve değeri ayarla
        const inputId = target + '-amount';
        const input = document.getElementById(inputId);
        if (input) {
            input.value = amount;
        }
        
        // Aynı target grubu içindeki butonların stilini sıfırla
        document.querySelectorAll(`.amount-btn[data-target="${target}"]`).forEach(b => b.classList.remove('bg-red-600'));
        this.classList.add('bg-red-600');
        });
    });
    
    // Panel toggle fonksiyonu - ikon döndürme ve özel işlemler
    window.togglePanel = function(contentId) {
        const content = document.getElementById(contentId);
        if (!content) return;
        
        // KOLAYHAVALE artık iframe olduğu için banka listesi yüklemeye gerek yok
        
        // Panel'i aç/kapat
        content.classList.toggle('hidden');
        
        // İlgili ikonu döndür
        const iconId = contentId.replace('-content', '-icon');
        const icon = document.getElementById(iconId);
        if (icon) {
        if (content.classList.contains('hidden')) {
            icon.classList.remove('rotate-180');
        } else {
            icon.classList.add('rotate-180');
        }
        }
        
        // Diğer panelleri kapat (accordion davranışı)
        const allPanels = document.querySelectorAll('[id$="-content"]');
        allPanels.forEach(panel => {
        if (panel.id !== contentId && !panel.classList.contains('hidden')) {
            panel.classList.add('hidden');
            const otherIconId = panel.id.replace('-content', '-icon');
            const otherIcon = document.getElementById(otherIconId);
            if (otherIcon) {
            otherIcon.classList.remove('rotate-180');
            }
        }
        });
    };
    });

    // OleyPayment için genel submit fonksiyonu
    function submitOleyPayment(paymentMethod) {
    // Hangi input'tan miktar alınacağını belirle
    let amountInputId = paymentMethod + '-amount';
    
    const amount = document.getElementById(amountInputId)?.value;
    
    // Kontroller kaldırıldı - direkt devam et

    // Loading göster
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>YÜKLENİYOR...';
    button.disabled = true;

    // FormData oluştur
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('paymentMethod', paymentMethod);
    formData.append('amount', amount);
    
    // KOLAYHAVALE iframe için banka ID'si gerekmez - iframe içinde seçilecek

    // AJAX isteği gönder
    fetch('{{ route("payment.method") }}', {
        method: 'POST',
        body: formData,
        headers: {
        'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Button'u eski haline getir
        button.innerHTML = originalText;
        button.disabled = false;

        if (data.success) {
        if (data.url) {
            // Mobil kontrolü
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            if (isMobile) {
                // Mobilde aynı sekmede aç
                window.location.href = data.url;
            } else {
                // Masaüstünde yeni sekmede aç
                window.open(data.url, '_blank', 'noopener,noreferrer');
            }
        } else {
            // Direct metotları için sadece hata durumunda uyarı
            console.log('Direct payment method - no URL provided');
        }
        } else {
        // Hata durumunda sessizce geç
        console.log('Payment error:', data.message || 'Ödeme işlemi başlatılamadı');
        }
    })
    .catch(error => {
        // Button'u eski haline getir
        button.innerHTML = originalText;
        button.disabled = false;
        
        console.error('Payment error:', error);
        // Hata durumunda sessizce geç
    });
    }
    </script>
    @endsection
