@extends('layouts.admin')

@section('title', 'Ödeme Yöntemleri')

@section('content')
<div class="content-card p-8 bg-gradient-to-r from-gray-900 via-black to-gray-900 shadow-xl rounded-xl">
    <!-- Page Header -->
    <div class="flex items-center gap-4 border-b border-yellow-500/50 pb-5 mb-8">
        <i data-lucide="credit-card" class="w-12 h-12 text-yellow-400"></i>
        <div>
            <h1 class="text-4xl font-extrabold text-yellow-400 tracking-wide">Ödeme Yöntemleri</h1>
            <p class="text-yellow-300 mt-2 text-lg">Ödeme sistemlerinizi buradan kolayca yönetin.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-5 bg-green-600/90 text-white rounded-lg flex items-center gap-3 shadow-lg">
            <i data-lucide="check-circle" class="w-7 h-7"></i>
            <span class="text-lg font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-5 bg-red-600/90 text-white rounded-lg shadow-lg">
            <ul class="list-disc list-inside space-y-1 text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payment-settings.update') }}" method="POST" class="space-y-10">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            @foreach($settings as $key => $value)
                <div class="stat-card bg-black/70 border-yellow-500 border shadow-lg rounded-lg p-6 hover:shadow-yellow-500 transition-shadow flex flex-col">
                    <label for="{{ $key }}" class="flex items-center gap-3 text-yellow-400 font-semibold text-lg mb-4 cursor-pointer">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                        {{ ucwords(str_replace('_', ' ', $key)) }}
                    </label>
                    <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ old($key, $value) }}"
                        class="w-full rounded-lg border border-yellow-500 bg-black/60 text-yellow-300 px-5 py-4 focus:outline-none focus:ring-4 focus:ring-yellow-400 placeholder-yellow-500 transition"
                        autocomplete="off" placeholder="Değer giriniz" />
                </div>
            @endforeach
        </div>
        <div class="pt-8 border-t border-yellow-500/50 flex justify-end">
            <button type="submit" class="px-12 py-4 bg-yellow-500 hover:bg-yellow-600 text-black font-bold rounded-xl shadow-2xl hover:shadow-yellow-600 transition-all transform hover:-translate-y-1">
                Ayarları Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
