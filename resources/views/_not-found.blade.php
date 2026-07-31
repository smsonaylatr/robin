@extends('layouts.app')
@section('title', '404 - Sayfa Bulunamadı')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] py-16">
    <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
    <p class="text-xl text-zinc-400 mb-8">Aradığınız sayfa bulunamadı.</p>
    <a href="/" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-md transition">Ana Sayfa</a>
</div>
@endsection 