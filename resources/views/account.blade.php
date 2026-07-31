@extends('layouts.app')
@section('title', 'Hesabım - ' . ($settings->site_adi ?? 'BetNow'))
@section('content')
<div class="max-w-[800px] mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-red-500 via-red-400 to-red-500 bg-clip-text text-transparent">Hesabım</h1>
    <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="h-24 w-24 rounded-full bg-zinc-800 mb-4 mx-auto animate-pulse"></div>
                <div class="h-6 w-32 bg-zinc-800 animate-pulse rounded mb-2 mx-auto"></div>
                <div class="h-4 w-48 bg-zinc-800 animate-pulse rounded mb-2 mx-auto"></div>
            </div>
            <div>
                <div class="h-6 w-32 bg-zinc-800 animate-pulse rounded mb-2"></div>
                <div class="h-4 w-48 bg-zinc-800 animate-pulse rounded mb-2"></div>
                <div class="h-4 w-40 bg-zinc-800 animate-pulse rounded"></div>
            </div>
        </div>
    </div>
</div>
@endsection 