@extends('layouts.app')

@section('title', 'Drakon Casino Test - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Drakon Casino Test</h1>
        <p class="text-zinc-400">Oyun başlatma testi</p>
    </div>

    <!-- Test Games -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Direct Launch Test -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-purple-600/20 rounded-lg mx-auto mb-3 flex items-center justify-center">
                    <span class="text-2xl">🎮</span>
                </div>
                <h3 class="text-lg font-bold text-white">Direkt Başlatma</h3>
                <p class="text-zinc-400 text-sm">Eski PHP versiyonu gibi</p>
            </div>
            <a href="{{ route('drakon.game-launch', '23002') }}" 
               target="_blank"
               class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition-colors text-center block">
                Direkt Başlat
            </a>
        </div>
        <!-- Test Game 1 -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-red-600/20 rounded-lg mx-auto mb-3 flex items-center justify-center">
                    <span class="text-2xl">🎰</span>
                </div>
                <h3 class="text-lg font-bold text-white">Test Slot Oyunu</h3>
                <p class="text-zinc-400 text-sm">Game ID: 23002</p>
            </div>
            <button onclick="launchGame('23002')" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Oyunu Başlat
            </button>
        </div>

        <!-- Test Game 2 -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-blue-600/20 rounded-lg mx-auto mb-3 flex items-center justify-center">
                    <span class="text-2xl">🎲</span>
                </div>
                <h3 class="text-lg font-bold text-white">Test Casino Oyunu</h3>
                <p class="text-zinc-400 text-sm">Game ID: 23003</p>
            </div>
            <button onclick="launchGame('23003')" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Oyunu Başlat
            </button>
        </div>

        <!-- Test Game 3 -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-green-600/20 rounded-lg mx-auto mb-3 flex items-center justify-center">
                    <span class="text-2xl">🃏</span>
                </div>
                <h3 class="text-lg font-bold text-white">Test Kart Oyunu</h3>
                <p class="text-zinc-400 text-sm">Game ID: 23004</p>
            </div>
            <button onclick="launchGame('23004')" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Oyunu Başlat
            </button>
        </div>
    </div>

    <!-- API Test Buttons -->
    <div class="mt-8 bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">API Test</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button onclick="testProviders()" 
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Test Providers API
            </button>
            <button onclick="testGames()" 
                    class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Test Games API
            </button>
            <button onclick="testDebug()" 
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Debug Info
            </button>
        </div>
        <div id="apiResult" class="mt-4 p-4 bg-black/30 rounded-lg border border-zinc-700 hidden">
            <pre class="text-xs text-white overflow-auto max-h-64" id="apiResultText"></pre>
        </div>
    </div>

    <!-- Webhook Test -->
    <div class="mt-8 bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-6">
        <h3 class="text-xl font-bold text-white mb-4">Webhook Test</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button onclick="testWebhook('account_details')" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Test Account Details
            </button>
            <button onclick="testWebhook('user_balance')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Test User Balance
            </button>
            <button onclick="testWebhook('transaction_bet')" 
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Test Bet Transaction
            </button>
            <button onclick="testWebhook('transaction_win')" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Test Win Transaction
            </button>
        </div>
        <div id="webhookResult" class="mt-4 p-4 bg-black/30 rounded-lg border border-zinc-700 hidden">
            <pre class="text-xs text-white overflow-auto max-h-64" id="webhookResultText"></pre>
        </div>
    </div>
</div>

<script>
function testProviders() {
    fetch('{{ route("drakon.providers") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('apiResult').classList.remove('hidden');
            document.getElementById('apiResultText').textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            document.getElementById('apiResult').classList.remove('hidden');
            document.getElementById('apiResultText').textContent = 'Error: ' + error.message;
        });
}

function testGames() {
    fetch('{{ route("drakon.games") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('apiResult').classList.remove('hidden');
            document.getElementById('apiResultText').textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            document.getElementById('apiResult').classList.remove('hidden');
            document.getElementById('apiResultText').textContent = 'Error: ' + error.message;
        });
}

function testDebug() {
    fetch('{{ route("drakon.debug") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('apiResult').classList.remove('hidden');
            document.getElementById('apiResultText').textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            document.getElementById('apiResult').classList.remove('hidden');
            document.getElementById('apiResultText').textContent = 'Error: ' + error.message;
        });
}

function testWebhook(method) {
    const data = {
        method: method,
        user_id: 101,
        amount: 10.00,
        transaction_id: 'test_' + Date.now(),
        game_id: '23002',
        session_id: 'test_session_' + Date.now()
    };

    fetch('{{ route("drakon.test-webhook") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('webhookResult').classList.remove('hidden');
        document.getElementById('webhookResultText').textContent = JSON.stringify(data, null, 2);
    })
    .catch(error => {
        document.getElementById('webhookResult').classList.remove('hidden');
        document.getElementById('webhookResultText').textContent = 'Error: ' + error.message;
    });
}

function launchGame(gameId) {
    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Yükleniyor...';
    
    fetch('{{ route("drakon.game-launch-api", ":gameId") }}'.replace(':gameId', gameId), {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.game_url) {
            // Open game in new tab
            window.open(data.game_url, '_blank', 'noopener,noreferrer');
        } else {
            alert('Oyun başlatılamadı: ' + (data.error || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        alert('Hata oluştu: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        button.textContent = originalText;
    });
}
</script>
@endsection 