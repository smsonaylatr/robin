@extends('layouts.app')

@section('title', 'Spor Bahisleri - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $iconMap = [
        'Futbol' => 'fa-solid fa-futbol',
        'Basketbol' => 'fa-solid fa-basketball',
        'Voleybol' => 'fa-solid fa-volleyball',
        'Masa Tenisi' => 'fa-solid fa-table-tennis-paddle-ball',
        'Tenis' => 'fa-solid fa-table-tennis-paddle-ball',
        'Buz Hokeyi' => 'fa-solid fa-hockey-puck',
        'Hentbol' => 'fa-solid fa-baseball',
        'Beyzbol' => 'fa-solid fa-baseball',
        'Rugby' => 'fa-solid fa-football',
        'Badminton' => 'fa-solid fa-trophy',
        'Snooker' => 'fa-solid fa-billiards',
        'Amerikan Futbolu' => 'fa-solid fa-football',
        'E-Spor' => 'fa-solid fa-gamepad',
        'MMA' => 'fa-solid fa-user-ninja',
        'Boks' => 'fa-solid fa-hand-fist',
        'Golf' => 'fa-solid fa-golf-ball-tee',
        'At Yarışı' => 'fa-solid fa-horse',
        'Motorsports' => 'fa-solid fa-flag-checkered',
        'Cricket' => 'fa-solid fa-baseball-bat-ball',
        'Darts' => 'fa-solid fa-bullseye',
        'Water Polo' => 'fa-solid fa-water',
        'Bisiklet' => 'fa-solid fa-person-biking',
        'UFC/MMA' => 'fa-solid fa-user-ninja',
        'Virtual Sports' => 'fa-solid fa-vr-cardboard',
        'Australian Rules' => 'fa-solid fa-trophy',
        'Netball' => 'fa-solid fa-basketball',
        'Trotting' => 'fa-solid fa-horse',
        'Gaelic Sports' => 'fa-solid fa-trophy',
        'Masatenisi' => 'fa-solid fa-table-tennis-paddle-ball',
        // ... diğerleri için de uygun olanı ekle
    ];
    $prioritySports = ['Futbol', 'Basketbol', 'Voleybol', 'Masa Tenisi'];
    $sportsArray = $sports->toArray();
    $orderedSports = array_values(array_unique(array_merge($prioritySports, $sportsArray)));
    $otherSports = array_diff($orderedSports, $prioritySports);
    $allSports = array_merge($prioritySports, $otherSports);
    $selectedSport = request('tur') && in_array(request('tur'), $allSports) ? request('tur') : 'Futbol';
@endphp
<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<div class="bg-black/40 backdrop-blur-sm border-b border-gray-800">
    <div class="max-w-5xl mx-auto px-2">
        <div class="w-full overflow-x-auto custom-scrollbar">
            <div class="flex min-w-max gap-2 py-2">
                @foreach($allSports as $sport)
                    <a href="?tur={{ urlencode($sport) }}"
                       class="flex items-center px-3 py-1 rounded-lg text-sm font-semibold transition-all duration-200 whitespace-nowrap shadow-sm border border-transparent {{ $selectedSport == $sport ? 'bg-red-600 text-white' : 'bg-black/30 text-gray-300 hover:bg-black/50 hover:text-white' }}">
                        @if(isset($iconMap[$sport]))
                            <i class="{{ $iconMap[$sport] }} w-4 h-4 mr-1"></i>
                        @else
                            <span class="w-4 h-4 mr-1 inline-block align-middle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><circle cx="12" cy="12" r="4" fill="currentColor"/></svg>
                            </span>
                        @endif
                        <span>{{ $sport }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
    background: #222;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 4px;
}
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #444 #222;
}
.betoffer-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}
.betoffer-card {
    background: transparent;
    border-radius: 0.5rem;
    border: 1px solid #232323;
    padding: 0.75rem 0.5rem 0.5rem 0.5rem;
    margin-bottom: 0.25rem;
    box-shadow: none;
}
.betoffer-title {
    font-size: 0.98em;
    font-weight: 600;
    color: #fff;
    margin-bottom: 0.4rem;
    text-align: left;
    letter-spacing: 0.01em;
}
.betoffer-outcomes {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    justify-content: flex-start;
}
.betoffer-outcome-btn {
    background: transparent;
    color: #fff;
    font-size: 0.85em;
    font-weight: 500;
    border-radius: 0.375rem;
    border: 1px solid #232323;
    padding: 0.18rem 0.6rem 0.18rem 0.6rem;
    margin-bottom: 0.10rem;
    transition: background 0.2s, border 0.2s, color 0.2s;
    min-width: 44px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: none;
}
.betoffer-outcome-btn:hover {
    background: #ef4444;
    color: #fff;
    border-color: #ef4444;
}
</style>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-2 py-1">
        <!-- Matches Grid -->
        <div class="grid gap-2">
            @forelse($matches as $match)
                <div class="bg-black/20 backdrop-blur-sm rounded border border-gray-800 overflow-hidden hover:border-gray-600 transition-all duration-200">
                    <!-- Match Header -->
                    <div class="bg-black/30 px-2 py-1 border-b border-gray-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-400">{{ $match->ulke_isim }}</span>
                                <span class="text-xs font-medium text-white">{{ $match->lig_isim }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($match->baslangic)->format('d.m.Y') }}</div>
                                <div class="text-xs font-bold text-white">{{ \Carbon\Carbon::parse($match->baslangic)->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Match Content -->
                    <div class="p-2">
                        <div class="flex items-center justify-between mb-2">
                            <!-- Home Team -->
                            <div class="flex-1 text-center">
                                <div class="text-xs font-bold text-white">{{ $match->evsahibi_isim }}</div>
                            </div>

                            <!-- VS -->
                            <div class="mx-2 text-center">
                                <div class="text-sm font-bold text-red-500">VS</div>
                            </div>

                            <!-- Away Team -->
                            <div class="flex-1 text-center">
                                <div class="text-xs font-bold text-white">{{ $match->misafir_isim }}</div>
                            </div>
                        </div>

                        <!-- Odds -->
                        <div class="grid grid-cols-3 gap-1">
                            <button onclick="{{ $match->oran1 > 0 ? 'addToCoupon(\'' . $match->evsahibi_isim . ' vs ' . $match->misafir_isim . '\', \'1\', ' . $match->oran1 . ', \'' . $match->evsahibi_isim . ' Kazanır\')' : '' }}" 
                                    class="{{ $match->oran1 > 0 ? 'bg-black/30 hover:bg-black/50 text-white font-bold py-1 px-1 rounded text-xs transition-all duration-200 cursor-pointer' : 'bg-gray-800/50 text-gray-500 font-bold py-1 px-1 rounded text-xs cursor-not-allowed opacity-60' }}"
                                    title="{{ $match->oran1 > 0 ? 'Bu seçeneği seç' : 'Bu seçenek şu anda mevcut değil' }}">
                                <div class="text-xs">Evsahibi</div>
                                <div class="text-sm flex items-center justify-center">
                                    @if($match->oran1 > 0)
                                        {{ $match->oran1 }}
                                    @else
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </button>
                            <button onclick="{{ $match->oran0 > 0 ? 'addToCoupon(\'' . $match->evsahibi_isim . ' vs ' . $match->misafir_isim . '\', \'X\', ' . $match->oran0 . ', \'Beraberlik\')' : '' }}" 
                                    class="{{ $match->oran0 > 0 ? 'bg-black/30 hover:bg-black/50 text-white font-bold py-1 px-1 rounded text-xs transition-all duration-200 cursor-pointer' : 'bg-gray-800/50 text-gray-500 font-bold py-1 px-1 rounded text-xs cursor-not-allowed opacity-60' }}"
                                    title="{{ $match->oran0 > 0 ? 'Bu seçeneği seç' : 'Bu seçenek şu anda mevcut değil' }}">
                                <div class="text-xs">Beraberlik</div>
                                <div class="text-sm flex items-center justify-center">
                                    @if($match->oran0 > 0)
                                        {{ $match->oran0 }}
                                    @else
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </button>
                            <button onclick="{{ $match->oran2 > 0 ? 'addToCoupon(\'' . $match->evsahibi_isim . ' vs ' . $match->misafir_isim . '\', \'2\', ' . $match->oran2 . ', \'' . $match->misafir_isim . ' Kazanır\')' : '' }}" 
                                    class="{{ $match->oran2 > 0 ? 'bg-black/30 hover:bg-black/50 text-white font-bold py-1 px-1 rounded text-xs transition-all duration-200 cursor-pointer' : 'bg-gray-800/50 text-gray-500 font-bold py-1 px-1 rounded text-xs cursor-not-allowed opacity-60' }}"
                                    title="{{ $match->oran2 > 0 ? 'Bu seçeneği seç' : 'Bu seçenek şu anda mevcut değil' }}">
                                <div class="text-xs">Deplasman</div>
                                <div class="text-sm flex items-center justify-center">
                                    @if($match->oran2 > 0)
                                        {{ $match->oran2 }}
                                    @else
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </button>
                        </div>

                        <!-- Additional Betting Options -->
                        <div class="mt-2 pt-1 border-t border-gray-800">
                            <div class="text-center">
                                <button onclick="toggleAdditionalOptions('{{ $match->id }}', '{{ $match->eventid }}')" 
                                        class="text-xs text-gray-400 hover:text-white transition-colors duration-200 cursor-pointer">
                                    Daha Fazla Seçenek
                                </button>
                            </div>
                        </div>
                        
                        <!-- Additional Options Container -->
                        <div id="additionalOptions_{{ $match->id }}" class="hidden mt-3 pt-3 border-t border-gray-800">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-500 mx-auto mb-2"></div>
                                <p class="text-xs text-gray-400">Seçenekler yükleniyor...</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-black/20 backdrop-blur-sm rounded p-4 text-center border border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-400 mb-1">Maç Bulunamadı</h3>
                    <p class="text-gray-500 text-xs">Bu spor dalında şu anda aktif maç bulunmuyor.</p>
                </div>
            @endforelse
        </div>

        <!-- Load More Button -->
        @if($matches->count() > 0)
            <div class="text-center mt-4">
                <button class="bg-red-600/80 hover:bg-red-600 text-white font-medium py-1.5 px-4 rounded text-sm transition-colors duration-200">
                    Daha Fazla Maç Yükle
                </button>
            </div>
        @endif
    </div>
</div>

<script>
let selectedBets = [];
let totalOdds = 1.00;

// LocalStorage key for coupon data
const COUPON_STORAGE_KEY = 'betnow_coupon_data';

// Load coupon data from localStorage on page load
function loadCouponFromStorage() {
    try {
        const savedData = localStorage.getItem(COUPON_STORAGE_KEY);
        if (savedData) {
            const parsedData = JSON.parse(savedData);
            selectedBets = parsedData.selectedBets || [];
            totalOdds = parsedData.totalOdds || 1.00;
            
            // Check if saved data is not too old (24 hours)
            const maxAge = 24 * 60 * 60 * 1000; // 24 hours in milliseconds
            if (parsedData.timestamp && (Date.now() - parsedData.timestamp) > maxAge) {
                // Data is too old, clear it
                clearCouponFromStorage();
                selectedBets = [];
                totalOdds = 1.00;
                showCouponStatus('Eski kupon verileri temizlendi', 'info');
                setTimeout(() => hideCouponStatus(), 3000);
            } else {
                updateCouponDisplay();
                
                // Show status if there are saved bets
                if (selectedBets.length > 0) {
                    showCouponStatus(`${selectedBets.length} bahis kupondan yüklendi`, 'info');
                    setTimeout(() => hideCouponStatus(), 3000);
                }
            }
        }
    } catch (error) {
        console.error('Error loading coupon from storage:', error);
        // If there's an error, clear the corrupted data
        localStorage.removeItem(COUPON_STORAGE_KEY);
    }
}

// Save coupon data to localStorage
function saveCouponToStorage() {
    try {
        const couponData = {
            selectedBets: selectedBets,
            totalOdds: totalOdds,
            timestamp: Date.now()
        };
        localStorage.setItem(COUPON_STORAGE_KEY, JSON.stringify(couponData));
    } catch (error) {
        console.error('Error saving coupon to storage:', error);
    }
}

// Clear coupon data from localStorage
function clearCouponFromStorage() {
    try {
        localStorage.removeItem(COUPON_STORAGE_KEY);
    } catch (error) {
        console.error('Error clearing coupon from storage:', error);
    }
}

function addToCoupon(match, selection, odds, description, betType = null) {
    // Check if odds is 0 or invalid
    if (!odds || odds <= 0) {
        showCouponStatus('Bu seçenek şu anda mevcut değil!', 'error');
        return;
    }
    
    // Check if this exact bet is already selected
    const existingIndex = selectedBets.findIndex(bet => {
        if (betType) {
            return bet.match === match && bet.betType === betType && bet.selection === selection;
        } else {
            return bet.match === match && bet.selection === selection;
        }
    });
    
    if (existingIndex !== -1) {
        // Remove if already selected (toggle behavior)
        selectedBets.splice(existingIndex, 1);
        showCouponStatus('Bahis kupondan çıkarıldı', 'info');
    } else {
        // Check if there's already a bet from the same match
        const sameMatchIndex = selectedBets.findIndex(bet => bet.match === match);
        
        if (sameMatchIndex !== -1) {
            // Remove the existing bet from the same match
            const removedBet = selectedBets[sameMatchIndex];
            selectedBets.splice(sameMatchIndex, 1);
            showCouponStatus(`Önceki seçenek (${removedBet.selection}) kaldırıldı, yeni seçenek (${selection}) eklendi`, 'info');
        }
        
        // Add new bet
        selectedBets.push({ match, selection, odds, description, betType });
        showCouponStatus('Bahis kupona eklendi', 'success');
    }
    
    updateCouponDisplay();
    saveCouponToStorage(); // Save to localStorage after each change
}

function removeBet(index) {
    selectedBets.splice(index, 1);
    updateCouponDisplay();
    saveCouponToStorage(); // Save to localStorage after removal
}

function updateCouponDisplay() {
    const container = document.getElementById('selectedBets');
    const totalOddsElement = document.getElementById('totalOdds');
    const potentialWinElement = document.getElementById('potentialWin');
    const betAmountInput = document.getElementById('betAmount');
    const placeBetBtn = document.getElementById('placeBetBtn');
    
    if (selectedBets.length === 0) {
        container.innerHTML = '<div class="text-xs text-gray-400 text-center py-4">Henüz bahis seçilmedi</div>';
        totalOdds = 1.00;
        totalOddsElement.textContent = '1.00';
        potentialWinElement.textContent = '0.00 TL';
        placeBetBtn.disabled = true;
        saveCouponToStorage(); // Save empty state to localStorage
        return;
    }
    
    // Calculate total odds
    totalOdds = selectedBets.reduce((total, bet) => total * bet.odds, 1);
    
    // Update display
    container.innerHTML = selectedBets.map((bet, index) => {
        // Bahis türü ve seçimi göster
        let betDisplay = bet.match;
        if (bet.description && bet.selection) {
            betDisplay = `${bet.match}\n${bet.description} / ${bet.selection}`;
        } else if (bet.selection) {
            betDisplay = `${bet.match} / ${bet.selection}`;
        }
        
        return `
        <div class="bg-black/30 rounded p-2 border border-gray-700">
            <div class="flex justify-between items-start mb-1">
                <div class="flex-1">
                    <div class="text-xs font-medium text-white whitespace-pre-line">${betDisplay}</div>
                </div>
                <button onclick="removeBet(${index})" class="text-red-400 hover:text-red-300 text-xs ml-2">×</button>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-300">Oran:</span>
                <span class="text-xs font-bold text-white">${bet.odds}</span>
            </div>
        </div>
        `;
    }).join('');
    
    totalOddsElement.textContent = totalOdds.toFixed(2);
    updatePotentialWin();
    placeBetBtn.disabled = false;
    saveCouponToStorage(); // Save to localStorage after display update
}

function updatePotentialWin() {
    const betAmount = parseFloat(document.getElementById('betAmount').value) || 0;
    const potentialWin = betAmount * totalOdds;
    document.getElementById('potentialWin').textContent = potentialWin.toFixed(2) + ' TL';
}

function clearCoupon() {
    selectedBets = [];
    updateCouponDisplay();
    clearCouponFromStorage(); // Clear from localStorage
    showCouponStatus('Kupon temizlendi', 'info');
    setTimeout(() => hideCouponStatus(), 2000);
}

function placeBet() {
    if (selectedBets.length === 0) {
        showCouponStatus('Lütfen en az bir bahis seçin!', 'error');
        return;
    }
    
    const betAmount = parseFloat(document.getElementById('betAmount').value);
    if (!betAmount || betAmount < 10) {
        showCouponStatus('Minimum bahis miktarı 10 TL olmalıdır!', 'error');
        return;
    }
    
    // Loading durumu
    const placeBetBtn = document.getElementById('placeBetBtn');
    const originalText = placeBetBtn.textContent;
    placeBetBtn.disabled = true;
    placeBetBtn.textContent = 'İşleniyor...';
    
    // Backend'e gönder
    fetch('{{ route("place-bet") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            bets: selectedBets,
            amount: betAmount,
            totalOdds: totalOdds,
            potentialWin: betAmount * totalOdds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Başarılı durum - kuponun altında göster
            const statusMessage = `Kupon Oynandı\nTahmini Kazanç: ${(betAmount * totalOdds).toFixed(2)}₺`;
            showCouponStatus(statusMessage, 'success');
            
            // Bakiye güncelle
            if (data.newBalance !== undefined) {
                // Header'daki bakiyeyi güncelle
                const balanceElement = document.querySelector('.casino-gradient');
                if (balanceElement) {
                    balanceElement.textContent = '₺' + data.newBalance.toFixed(2);
                }
            }
            
            // Kuponu temizle
            setTimeout(() => {
                clearCoupon();
                clearCouponFromStorage(); // Clear from localStorage after successful bet
                hideCouponStatus();
            }, 3000);
        } else if (data.expiredMatches && data.expiredMatches.length > 0) {
            // Başlamış maçlar varsa, kupondan sil ve kullanıcıya bildir
            selectedBets = selectedBets.filter(bet => !data.expiredMatches.includes(bet.match));
            updateCouponDisplay();
            showCouponStatus(data.message, 'error');
        } else {
            // Hata durumu
            if (data.message && data.message.includes('Yetersiz bakiye')) {
                showCouponStatus('Bakiyeniz Yetersiz', 'error');
            } else if (data.message && data.message.includes('Giriş')) {
                showCouponStatus('Lütfen Giriş Yapınız', 'error');
            } else {
                showCouponStatus(data.message || 'Bir hata oluştu!', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCouponStatus('Bir hata oluştu!', 'error');
    })
    .finally(() => {
        // Button'u eski haline getir
        placeBetBtn.disabled = false;
        placeBetBtn.textContent = originalText;
    });
}

function showCouponStatus(message, type) {
    // Mevcut status elementini kaldır
    hideCouponStatus();
    
    // Yeni status elementi oluştur
    const statusDiv = document.createElement('div');
    statusDiv.id = 'couponStatus';
    statusDiv.className = `mt-3 p-3 rounded text-sm font-medium text-center ${
        type === 'success' ? 'bg-green-600/20 border border-green-500/30 text-green-400' : 
        type === 'error' ? 'bg-red-600/20 border border-red-500/30 text-red-400' : 
        'bg-blue-600/20 border border-blue-500/30 text-blue-400'
    }`;
    statusDiv.innerHTML = message.replace(/\n/g, '<br>');
    
    // Kupon container'ının altına ekle - ID kullanarak
    const couponContainer = document.getElementById('couponContainer');
    if (couponContainer) {
        couponContainer.appendChild(statusDiv);
    }
}

function hideCouponStatus() {
    const existingStatus = document.getElementById('couponStatus');
    if (existingStatus) {
        existingStatus.remove();
    }
}

function toggleAdditionalOptions(matchId, eventId) {
    const container = document.getElementById(`additionalOptions_${matchId}`);
    const isHidden = container.classList.contains('hidden');
    
    // Önce tüm açık olan seçenekleri kapat
    const allContainers = document.querySelectorAll('[id^="additionalOptions_"]');
    allContainers.forEach(cont => {
        if (cont.id !== `additionalOptions_${matchId}`) {
            cont.classList.add('hidden');
        }
    });
    
    if (isHidden) {
        // Show loading state
        container.classList.remove('hidden');
        loadAdditionalOptions(matchId, eventId);
    } else {
        // Hide options
        container.classList.add('hidden');
    }
}

function loadAdditionalOptions(matchId, eventId) {
    const container = document.getElementById(`additionalOptions_${matchId}`);
    
    // Show loading state
    container.innerHTML = `
        <div class="text-center">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-500 mx-auto mb-2"></div>
            <p class="text-xs text-gray-400">Seçenekler yükleniyor...</p>
        </div>
    `;
    
    // Fetch odds from API
    fetch(`/MatchOdds/${eventId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAdditionalOptions(container, data.data, matchId);
            } else {
                showAdditionalOptionsError(container, 'Oran Bulunamadı');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAdditionalOptionsError(container, 'Oran Bulunamadı');
        });
}

function displayAdditionalOptions(container, data, matchId) {
    if (!data.betOffers || data.betOffers.length === 0) {
        container.innerHTML = `
            <div class="text-center">
                <p class="text-xs text-gray-400">Oran Bulunamadı</p>
            </div>
        `;
        return;
    }
    let matchTeams = 'Bilinmeyen Maç';
    if (data.events && data.events.length > 0) {
        const event = data.events[0];
        if (event.homeName && event.awayName) {
            matchTeams = `${event.homeName} vs ${event.awayName}`;
        } else if (event.name) {
            matchTeams = event.name;
        }
    }
    let optionsHtml = '<div class="betoffer-grid">';
    data.betOffers.forEach(offer => {
        if (!offer.outcomes || offer.outcomes.length === 0) return;
        let betTypeName = offer.criterion?.label || offer.betOfferType?.name || 'Bahis Türü';
        if (offer.outcomes[0]?.line !== undefined) {
            const lineValue = (offer.outcomes[0].line / 1000).toFixed(1);
            betTypeName += ` ${lineValue}`;
        }
        optionsHtml += `<div class=\"betoffer-card\">`;
        optionsHtml += `<div class=\"betoffer-title\">${betTypeName}</div>`;
        optionsHtml += `<div class=\"betoffer-outcomes\">`;
        offer.outcomes.forEach(outcome => {
            const odds = (outcome.odds / 1000).toFixed(2);
            let label = outcome.label || outcome.participant || 'Bilinmeyen';
            label = translateToTurkish(label);
            if (outcome.line !== undefined) {
                const lineValue = (outcome.line / 1000).toFixed(1);
                if (label === 'Üst' || label === 'Alt') {
                    label += ` ${lineValue}`;
                }
            }
            optionsHtml += `
                <button 
                    onclick=\"addToCoupon('${matchTeams}', '${label}', ${odds}, '${betTypeName}', '${betTypeName}')\"
                    class=\"betoffer-outcome-btn\">
                    <span>${label}</span>
                    <span class=\"font-bold\">${odds}</span>
                </button>
            `;
        });
        optionsHtml += `</div></div>`;
    });
    optionsHtml += '</div>';
    container.innerHTML = optionsHtml;
}

function showAdditionalOptionsError(container, message) {
    container.innerHTML = `
        <div class="text-center">
            <p class="text-xs text-gray-400">${message}</p>
        </div>
    `;
}

function translateToTurkish(text) {
    const translations = {
        // Yes/No
        'Yes': 'Evet',
        'No': 'Hayır',
        
        // Over/Under
        'Over': 'Üst',
        'Under': 'Alt',
        
        // Common betting terms
        'Home': 'Ev Sahibi',
        'Away': 'Deplasman',
        'Draw': 'Beraberlik',
        'Win': 'Kazanır',
        'Lose': 'Kaybeder',
        
        // Numbers (if they appear as text)
        '0': '0',
        '1': '1',
        '2': '2',
        '3': '3',
        '4': '4',
        '5': '5',
        '6': '6',
        '7': '7',
        '8': '8',
        '9': '9',
        
        // Common patterns
        'Over ': 'Üst ',
        'Under ': 'Alt ',
        'Over 0.5': 'Üst 0.5',
        'Over 1.5': 'Üst 1.5',
        'Over 2.5': 'Üst 2.5',
        'Over 3.5': 'Üst 3.5',
        'Over 4.5': 'Üst 4.5',
        'Over 5.5': 'Üst 5.5',
        'Under 0.5': 'Alt 0.5',
        'Under 1.5': 'Alt 1.5',
        'Under 2.5': 'Alt 2.5',
        'Under 3.5': 'Alt 3.5',
        'Under 4.5': 'Alt 4.5',
        'Under 5.5': 'Alt 5.5'
    };
    
    // Exact match first
    if (translations[text]) {
        return translations[text];
    }
    
    // Check for patterns (like "Over 2.5")
    for (const [english, turkish] of Object.entries(translations)) {
        if (text.includes(english)) {
            return text.replace(english, turkish);
        }
    }
    
    return text;
}

// Update potential win when bet amount changes
document.addEventListener('DOMContentLoaded', function() {
    const betAmountInput = document.getElementById('betAmount');
    if (betAmountInput) {
        betAmountInput.addEventListener('input', updatePotentialWin);
    }
    
    // Load coupon data from localStorage on page load
    loadCouponFromStorage();
});

// Save coupon data before page unload (navigation, refresh, etc.)
window.addEventListener('beforeunload', function() {
    saveCouponToStorage();
});

// Show login message for non-authenticated users
function showLoginMessage() {
    showCouponStatus('Lütfen Giriş Yapınız', 'error');
}
</script>
@endsection
