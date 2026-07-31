@extends('layouts.app')

@section('title', 'Spor Bahisleri - Robinbet')

@section('content')
<div class="min-h-screen bg-transparent">
    <!-- Sports Providers Section -->
    <div class="bg-black/40 backdrop-blur-sm border-b border-gray-800 py-2">
        <div class="max-w-7xl mx-auto px-4">
            <div class="w-full overflow-x-auto custom-scrollbar">
                <div class="flex min-w-max gap-6 py-1 items-center">
                    <!-- BetsAPI - Selected/Active -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="betsapi">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/betsapi.png') }}" alt="BetsAPI" class="relative w-20 h-12 object-contain filter brightness-100 hover:brightness-110 transition-all duration-300" />
                            <span class="led-effect absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                    <!-- BCBetting -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="bcbetting">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/BCBetting.png') }}" alt="BCBetting" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                    <!-- Digitain -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="digitain">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/Digitain.png') }}" alt="Digitain" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                  
                    
                    <!-- OneBet -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="onebet">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/OneBet.png') }}" alt="OneBet" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                    <!-- Pinnacle -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="pinnacle">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/Pinnacle.png') }}" alt="Pinnacle" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                    <!-- Sbobetv1 -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="sbobet">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/Sbobetv1.png') }}" alt="SBOBET" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                    <!-- betb2b -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="betb2b">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/betb2b.webp') }}" alt="BetB2B" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                    
                    <!-- StartGr8 -->
                    <div class="provider-item flex-shrink-0 group cursor-pointer hover:scale-105 transition-transform duration-300 relative" data-provider="startgr8">
                        <div class="relative inline-block overflow-hidden">
                            <img src="{{ asset('img/StartGr8.webp') }}" alt="StartGr8" class="relative w-20 h-12 object-contain filter brightness-0 invert hover:brightness-100 hover:invert-0 transition-all duration-300" />
                            <span class="led-effect hidden absolute top-0 left-[-100%] w-full h-[2px] bg-gradient-to-r from-transparent to-[#ebff00] animate-[btn-anim1_2s_linear_infinite]"></span>
                            <span class="led-effect hidden absolute top-[-100%] right-0 w-[2px] h-full bg-gradient-to-b from-transparent to-[#ebff00] animate-[btn-anim2_2s_linear_infinite] animation-delay-[0.5s]"></span>
                            <span class="led-effect hidden absolute bottom-0 right-[-100%] w-full h-[2px] bg-gradient-to-l from-transparent to-[#ebff00] animate-[btn-anim3_2s_linear_infinite] animation-delay-[1s]"></span>
                            <span class="led-effect hidden absolute bottom-[-100%] left-0 w-[2px] h-full bg-gradient-to-t from-transparent to-[#ebff00] animate-[btn-anim4_2s_linear_infinite] animation-delay-[1.5s]"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sports Iframe Container - 70% Height -->
    <div class="w-full h-[70vh]">
        <iframe 
            src="" 
            class="w-full h-full border-0"
            frameborder="0"
            allowfullscreen
            id="sportsIframe">
        </iframe>
        
        <!-- Loading State -->
        <div id="loadingState" class="absolute inset-0 bg-zinc-900/90 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-red-500 mx-auto mb-4"></div>
                <p class="text-white text-lg">Spor sistemi yükleniyor...</p>
                <p class="text-zinc-400 text-sm mt-2">Lütfen bekleyin</p>
            </div>
        </div>
        
        <!-- Error State -->
        <div id="errorState" class="absolute inset-0 bg-zinc-900/90 flex items-center justify-center hidden">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-white text-lg mb-2">Bağlantı Hatası</p>
                <p class="text-zinc-400 text-sm mb-4">Spor sistemi şu anda kullanılamıyor</p>
                <button onclick="reloadIframe()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Tekrar Dene
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.getElementById('sportsIframe');
    const loadingState = document.getElementById('loadingState');
    const errorState = document.getElementById('errorState');
    const providersContainer = document.querySelector('.custom-scrollbar');
    
    // Initialize sports iframe with API authentication
    initializeSportsIframe();
    
    // Hide loading state when iframe loads
    iframe.addEventListener('load', function() {
        loadingState.style.display = 'none';
    });
    
    // Show error state if iframe fails to load
    iframe.addEventListener('error', function() {
        loadingState.style.display = 'none';
        errorState.classList.remove('hidden');
    });
    
    // Mouse wheel horizontal scroll for providers
    if (providersContainer) {
        providersContainer.addEventListener('wheel', function(e) {
            e.preventDefault();
            providersContainer.scrollLeft += e.deltaY;
        });
    }
    
    // Provider selection with LED effect
    const providerItems = document.querySelectorAll('.provider-item');
    providerItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all providers
            providerItems.forEach(provider => {
                provider.classList.remove('active');
                const ledEffects = provider.querySelectorAll('.led-effect');
                ledEffects.forEach(effect => {
                    effect.classList.add('hidden');
                });
            });
            
            // Add active class to clicked provider
            this.classList.add('active');
            const ledEffects = this.querySelectorAll('.led-effect');
            ledEffects.forEach(effect => {
                effect.classList.remove('hidden');
            });
        });
    });
    
    // iframe mesajlarını dinleme (dynamic origin from database)
    window.addEventListener('message', function(event) {
        if (event.origin !== '{{ $sportsApi->api_url }}') return;
        console.log('Iframe mesajı:', event.data);
    });
});

async function initializeSportsIframe() {
    const iframe = document.getElementById('sportsIframe');
    const loadingState = document.getElementById('loadingState');
    const errorState = document.getElementById('errorState');
    
    try {
        // Show loading state
        loadingState.style.display = 'flex';
        errorState.classList.add('hidden');
        
        // Prepare authentication data from database (align with api_test.php)
        const authData = {
            user_id: {{ $user->id }},
            username: '{{ $user->username }}',
            api_token: '{{ $sportsApi->api_token }}',
            api_secret: '{{ $sportsApi->api_secret_key }}'
        };
        
        // Send authentication request to provider's auth endpoint derived from api_url
        const authEndpoint = '{{ rtrim($sportsApi->api_url, '/') }}' + '/api/auth/login';
        const response = await fetch(authEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(authData)
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const result = await response.json();
        
        if (result && result.success && result.data && result.data.session_token) {
            // Build iframe URL using provider base URL and session token (default to presports)
            const baseUrl = '{{ rtrim($sportsApi->api_url, '/') }}';
            const targetPath = '/tr/presports';
            const sessionToken = encodeURIComponent(result.data.session_token);
            iframe.src = `${baseUrl}${targetPath}?session_token=${sessionToken}`;
        } else {
            throw new Error((result && (result.error || result.message)) || 'Doğrulama başarısız');
        }
        
    } catch (error) {
        console.error('Sports iframe initialization error:', error);
        loadingState.style.display = 'none';
        errorState.classList.remove('hidden');
        
        // Update error message
        const errorMessage = document.querySelector('#errorState p.text-white.text-lg.mb-2');
        if (errorMessage) {
            errorMessage.textContent = 'API Bağlantı Hatası';
        }
        
        const errorDescription = document.querySelector('#errorState p.text-zinc-400.text-sm.mb-4');
        if (errorDescription) {
            errorDescription.textContent = error.message || 'Spor sistemi şu anda kullanılamıyor';
        }
    }
}

function reloadIframe() {
    initializeSportsIframe();
}
</script>

<style>
/* Custom scrollbar for providers section */
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
    background: #1f2937;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #4b5563 #1f2937;
}

/* Custom scrollbar for iframe */
iframe::-webkit-scrollbar {
    width: 8px;
}

iframe::-webkit-scrollbar-track {
    background: #374151;
}

iframe::-webkit-scrollbar-thumb {
    background: #6b5563;
    border-radius: 4px;
}

iframe::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Mouse wheel horizontal scroll for providers */
.providers-container {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Login button style - LED strip going around corners */
@keyframes btn-anim1 {
    0% {
        left: -100%;
    }
    50%,100% {
        left: 100%;
    }
}

@keyframes btn-anim2 {
    0% {
        top: -100%;
    }
    50%,100% {
        top: 100%;
    }
}

@keyframes btn-anim3 {
    0% {
        right: -100%;
    }
    50%,100% {
        right: 100%;
    }
}

@keyframes btn-anim4 {
    0% {
        bottom: -100%;
    }
    50%,100% {
        bottom: 100%;
    }
}
</style>
@endsection
