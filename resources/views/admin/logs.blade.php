@extends('layouts.admin')

@section('title', 'Sistem Logları')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Sistem Logları</h1>
            <p class="text-gray-400 mt-1">Sistem loglarını görüntüleyin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                Log İndir
            </button>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="text-center py-12">
            <i data-lucide="file-text" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">Sistem Logları</h3>
            <p class="text-gray-400">Bu sayfa yakında eklenecek</p>
        </div>
    </div>
</div>
@endsection 