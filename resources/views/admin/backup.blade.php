@extends('layouts.admin')

@section('title', 'Yedekleme')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Yedekleme</h1>
            <p class="text-gray-400 mt-1">Sistem yedeklerini yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                Yedek İndir
            </button>
            <button class="px-4 py-2 bg-green-500/10 border border-green-500/20 text-green-500 rounded-lg hover:bg-green-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Yedek
            </button>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="text-center py-12">
            <i data-lucide="database" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">Yedekleme Sistemi</h3>
            <p class="text-gray-400">Bu sayfa yakında eklenecek</p>
        </div>
    </div>
</div>
@endsection 