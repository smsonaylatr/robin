@extends('layouts.app')

@section('title', 'Drakon Casino Oyunu - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">Drakon Casino Oyunu</h1>
            <p class="text-zinc-400">Oyun yükleniyor...</p>
        </div>

        <!-- Loading Animation -->
        <div class="bg-zinc-900/50 backdrop-blur-sm border border-zinc-800 rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-red-500 mx-auto mb-4"></div>
            <p class="text-white font-medium mb-2">Oyun Başlatılıyor</p>
            <p class="text-zinc-400 text-sm">Lütfen bekleyin, oyun yükleniyor...</p>
            
            <!-- Error Display -->
            <div id="errorMessage" class="hidden mt-4 p-4 bg-red-600/20 border border-red-500/30 rounded-lg">
                <p class="text-red-400 text-sm" id="errorText"></p>
                <button onclick="window.history.back()" class="mt-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-colors">
                    Geri Dön
                </button>
            </div>
        </div>
    </div>
</div>

<script>
@if(isset($gameUrl) && $gameUrl)
// Open game in new tab after a short delay
setTimeout(() => {
    window.open('{{ $gameUrl }}', '_blank', 'noopener,noreferrer');
    // Close this tab/window after opening the game
    setTimeout(() => {
        window.close();
    }, 1000);
}, 2000);
@else
// Show error if game URL is not available
document.getElementById('errorMessage').classList.remove('hidden');
document.getElementById('errorText').textContent = '{{ $error ?? "Oyun başlatılamadı. Lütfen tekrar deneyin." }}';
@endif
</script>
@endsection 