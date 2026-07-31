@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-black/10 to-black/90">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Slot Oyunları</h1>
            <p class="text-gray-300">En popüler slot oyunlarını keşfedin</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($games as $game)
            <div class="bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-all duration-300">
                <img src="{{ $game->image }}" alt="{{ $game->name }}" class="w-full h-32 object-cover rounded mb-2">
                <h3 class="text-white font-semibold text-sm">{{ $game->name }}</h3>
                <p class="text-gray-400 text-xs">{{ $game->provider->name ?? 'Provider' }}</p>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $games->links() }}
        </div>
    </div>
</div>
@endsection 