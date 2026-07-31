@extends('layouts.app')
@section('title', 'Para Yatırma - ' . ($settings->site_adi ?? 'BetNow'))
@section('content')
<div class="max-w-[600px] mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-red-500 via-red-400 to-red-500 bg-clip-text text-transparent">Para Yatırma</h1>
    <form class="space-y-4 bg-zinc-900 p-6 rounded-xl border border-zinc-800" method="POST" action="/deposit">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1" for="method">Yatırma Yöntemi</label>
            <select id="method" name="method" class="w-full rounded-md border border-zinc-700 bg-zinc-800 text-white px-3 py-2">
                <option>Havale/EFT</option>
                <option>Papara</option>
                <option>Kripto</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="amount">Tutar</label>
            <input type="number" id="amount" name="amount" class="w-full rounded-md border border-zinc-700 bg-zinc-800 text-white px-3 py-2" placeholder="Tutar">
        </div>
        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition">Yatır</button>
    </form>
</div>
@endsection 