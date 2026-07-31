@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-black/10 to-black/90">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">KYC Politikası</h1>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/5 rounded-lg p-8">
                <div class="prose prose-invert max-w-none">
                    <h2 class="text-2xl font-bold text-white mb-4">Kimlik Doğrulama</h2>
                    <p class="text-gray-300 mb-4">
                        Güvenli hizmet için kimlik doğrulama gereklidir.
                    </p>
                    <h3 class="text-xl font-semibold text-white mb-3">Gerekli Belgeler</h3>
                    <p class="text-gray-300 mb-4">
                        Kimlik kartı, pasaport veya ehliyet fotokopisi gerekebilir.
                    </p>
                    <h3 class="text-xl font-semibold text-white mb-3">Doğrulama Süreci</h3>
                    <p class="text-gray-300 mb-4">
                        Belgeler 24-48 saat içinde incelenir ve onaylanır.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 