@extends('layouts.app')

@section('title', 'Maç Oranları - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-black/40 backdrop-blur-sm border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center space-x-3">
                    <a href="/sports" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-lg font-semibold text-white">Maç Oranları</h1>
                </div>
                <div class="text-sm text-gray-400" id="matchInfo">
                    Yükleniyor...
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="max-w-7xl mx-auto px-4 py-8">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500 mx-auto mb-4"></div>
            <p class="text-gray-400">Oranlar yükleniyor...</p>
        </div>
    </div>

    <!-- Error State -->
    <div id="errorState" class="max-w-7xl mx-auto px-4 py-8 hidden">
        <div class="bg-red-600/20 border border-red-500/30 rounded-lg p-6 text-center">
            <div class="text-red-400 text-lg font-semibold mb-2">Hata</div>
            <p class="text-red-300" id="errorMessage"></p>
            <button onclick="loadMatchOdds()" class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition-colors duration-200">
                Tekrar Dene
            </button>
        </div>
    </div>

    <!-- Content -->
    <div id="contentState" class="max-w-7xl mx-auto px-4 py-4 hidden">
        <div class="grid gap-4" id="bettingOptions">
            <!-- Betting options will be populated here -->
        </div>
    </div>
</div>

<script>
let matchId = '{{ $id }}';
let selectedBets = [];

function loadMatchOdds() {
    // Show loading state
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('errorState').classList.add('hidden');
    document.getElementById('contentState').classList.add('hidden');

    fetch(`/MatchOdds/${matchId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMatchOdds(data.data);
            } else {
                showError(data.error || 'Bilinmeyen bir hata oluştu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Veri yüklenirken bir hata oluştu');
        });
}

function displayMatchOdds(data) {
    // Hide loading, show content
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('contentState').classList.remove('hidden');

    // Update match info
    if (data.events && data.events.length > 0) {
        const event = data.events[0];
        const matchInfo = `${event.homeName || event.name} - ${event.start ? new Date(event.start).toLocaleString('tr-TR') : ''}`;
        document.getElementById('matchInfo').textContent = matchInfo;
    }

    // Display betting options
    const container = document.getElementById('bettingOptions');
    
    if (!data.betOffers || data.betOffers.length === 0) {
        container.innerHTML = `
            <div class="bg-black/20 backdrop-blur-sm rounded-lg p-6 text-center border border-gray-800">
                <p class="text-gray-400">Bu maç için henüz oran bulunmuyor.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = data.betOffers.map(offer => {
        // Skip if no outcomes
        if (!offer.outcomes || offer.outcomes.length === 0) return '';

        // Get bet type name
        const betTypeName = offer.betOfferType?.name || offer.criterion?.label || 'Bahis Türü';
        
        return `
            <div class="bg-black/20 backdrop-blur-sm rounded-lg border border-gray-800 overflow-hidden">
                <div class="bg-black/30 px-4 py-3 border-b border-gray-800">
                    <h3 class="text-sm font-semibold text-white">${betTypeName}</h3>
                </div>
                <div class="p-4">
                    <div class="grid gap-2">
                        ${offer.outcomes.map(outcome => {
                            const odds = (outcome.odds / 1000).toFixed(2); // Convert from millicents
                            const label = outcome.label || outcome.participant || 'Bilinmeyen';
                            
                            return `
                                <div class="flex items-center justify-between p-2 bg-black/30 rounded border border-gray-700 hover:border-gray-600 transition-all duration-200">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-white">${label}</div>
                                    </div>
                                    <button 
                                        onclick="addToCoupon('${betTypeName}', '${label}', ${odds}, '${label}')"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors duration-200 ml-3">
                                        ${odds}
                                    </button>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function addToCoupon(betType, selection, odds, description) {
    // Check if this bet is already selected
    const existingIndex = selectedBets.findIndex(bet => 
        bet.betType === betType && bet.selection === selection
    );
    
    if (existingIndex !== -1) {
        // Remove if already selected
        selectedBets.splice(existingIndex, 1);
        showCouponStatus('Bahis kupondan çıkarıldı', 'info');
    } else {
        // Add new bet
        selectedBets.push({ betType, selection, odds, description });
        showCouponStatus('Bahis kupona eklendi', 'success');
    }
    
    updateCouponDisplay();
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
        return;
    }
    
    // Calculate total odds
    totalOdds = selectedBets.reduce((total, bet) => total * bet.odds, 1);
    
    // Update display
    container.innerHTML = selectedBets.map((bet, index) => {
        // Bahis türü ve seçimi göster
        let betDisplay = bet.betType;
        if (bet.description && bet.selection) {
            betDisplay = `${bet.betType}\n${bet.description} / ${bet.selection}`;
        } else if (bet.selection) {
            betDisplay = `${bet.betType} / ${bet.selection}`;
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
}

function removeBet(index) {
    selectedBets.splice(index, 1);
    updateCouponDisplay();
}

function updatePotentialWin() {
    const betAmount = parseFloat(document.getElementById('betAmount').value) || 0;
    const potentialWin = betAmount * totalOdds;
    document.getElementById('potentialWin').textContent = potentialWin.toFixed(2) + ' TL';
}

function showError(message) {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('contentState').classList.add('hidden');
    document.getElementById('errorState').classList.remove('hidden');
    document.getElementById('errorMessage').textContent = message;
}

function showCouponStatus(message, type) {
    // Create temporary status message
    const statusDiv = document.createElement('div');
    statusDiv.className = `fixed top-4 right-4 p-3 rounded text-sm font-medium z-50 ${
        type === 'success' ? 'bg-green-600 text-white' : 
        type === 'error' ? 'bg-red-600 text-white' : 
        'bg-blue-600 text-white'
    }`;
    statusDiv.textContent = message;
    
    document.body.appendChild(statusDiv);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (statusDiv.parentNode) {
            statusDiv.parentNode.removeChild(statusDiv);
        }
    }, 3000);
}

// Load odds when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadMatchOdds();
});
</script>
@endsection 