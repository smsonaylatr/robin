@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-black/10 to-black/90">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">İletişim</h1>
            <p class="text-gray-300">Bizimle iletişime geçin</p>
        </div>
        
        <div class="max-w-2xl mx-auto">
            <div class="bg-white/5 rounded-lg p-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-white font-semibold mb-2">Ad Soyad</label>
                        <input type="text" class="w-full bg-white/10 border border-gray-600 rounded-lg px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">E-posta</label>
                        <input type="email" class="w-full bg-white/10 border border-gray-600 rounded-lg px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Mesaj</label>
                        <textarea rows="5" class="w-full bg-white/10 border border-gray-600 rounded-lg px-4 py-3 text-white"></textarea>
                    </div>
                    <button class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                        Gönder
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 