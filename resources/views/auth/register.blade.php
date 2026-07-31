@extends('layouts.app')

@section('title', 'Kayıt Ol - ' . $settings->site_kelimeler)

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-8 shadow-2xl">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
            <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
            
            <div class="relative text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                        <div class="relative p-3 rounded-lg border" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border-color: rgba(235, 255, 0, 0.2);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ebff00;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="m22 21-3-3m0 0a5.5 5.5 0 1 0-7.78-7.78 5.5 5.5 0 0 0 7.78 7.78Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent mb-2" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">Kayıt Ol</h2>
                <p class="text-zinc-400">Yeni hesap oluşturun</p>
                

                
                <!-- Step indicator -->
                <div class="flex justify-center mt-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div id="step1-indicator" class="w-8 h-8 rounded-full text-white flex items-center justify-center text-sm font-bold" style="background-color: #ebff00; color: #000;">1</div>
                            <span id="step1-text" class="ml-2 text-sm font-medium" style="color: #ebff00;">Kişisel Bilgiler</span>
                        </div>
                        <div class="w-12 h-0.5 bg-zinc-700"></div>
                        <div class="flex items-center">
                            <div id="step2-indicator" class="w-8 h-8 rounded-full bg-zinc-700 text-zinc-400 flex items-center justify-center text-sm font-bold">2</div>
                            <span id="step2-text" class="ml-2 text-sm text-zinc-500">Hesap Bilgileri</span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($errors->any())
            <div class="relative mb-6 p-4 rounded-lg" style="background-color: rgba(235, 255, 0, 0.1); border: 1px solid rgba(235, 255, 0, 0.2);">
                <div class="text-sm" style="color: #ebff00;">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" class="relative space-y-6" id="registration-form" novalidate>
                @csrf
                
                <!-- Step 1: Personal Information -->
                <div id="step1" class="space-y-6">
                    @if($step1Fields->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($step1Fields as $field)
                                @if($field->field_name === 'firstName' || $field->field_name === 'lastName')
                                    <div>
                                        @include('auth.partials.dynamic-field', ['field' => $field])
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        @foreach($step1Fields as $field)
                            @if(!in_array($field->field_name, ['firstName', 'lastName']))
                                @if($field->field_name === 'il' || $field->field_name === 'ilce')
                                    @if($field->field_name === 'il')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @include('auth.partials.dynamic-field', ['field' => $field])
                                            @php
                                                $ilceField = $step1Fields->where('field_name', 'ilce')->first();
                                            @endphp
                                            @if($ilceField)
                                                @include('auth.partials.dynamic-field', ['field' => $ilceField])
                                            @endif
                                        </div>
                                    @endif
                                @elseif($field->field_name !== 'ilce')
                                    @include('auth.partials.dynamic-field', ['field' => $field])
                                @endif
                            @endif
                        @endforeach
                    @endif
                    
                    <div class="flex justify-end">
                        <button type="button" id="next-step" class="relative overflow-hidden rounded-lg py-3 px-6 transition-all duration-300 transform hover:scale-105 hover:shadow-lg group" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.2), rgba(235, 255, 0, 0.1), transparent); border: 1px solid rgba(235, 255, 0, 0.3);" onmouseover="this.style.borderColor='rgba(235, 255, 0, 0.5)'; this.style.boxShadow='0 10px 25px rgba(235, 255, 0, 0.2)';" onmouseout="this.style.borderColor='rgba(235, 255, 0, 0.3)'; this.style.boxShadow='none';">
                            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), rgba(235, 255, 0, 0.05), transparent);"></div>
                            <div class="relative flex items-center justify-center gap-2">
                                <span class="text-white font-semibold">Sonraki Adım</span>
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="group-hover:translate-x-1 transition-transform duration-200" style="color: #ebff00;">
                                    <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="m12 5 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Account Information -->
                <div id="step2" class="space-y-6 hidden">
                    @if($step2Fields->count() > 0)
                        @foreach($step2Fields as $field)
                            @include('auth.partials.dynamic-field', ['field' => $field])
                        @endforeach
                    @endif
                    
                    <!-- Password fields (always required) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-300 mb-2">Şifre *</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200" 
                               style="focus:border: #ebff00; focus:ring: 1px solid #ebff00;"
                               placeholder="En az 6 karakter">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-300 mb-2">Şifre Tekrar *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none transition-all duration-200" 
                               style="focus:border: #ebff00; focus:ring: 1px solid #ebff00;"
                               placeholder="Şifrenizi tekrar girin">
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="termsAccepted" required 
                               class="mt-1 rounded border-zinc-700 bg-zinc-800/50 focus:ring-offset-0" style="color: #ebff00; focus:ring: #ebff00;">
                        <label for="terms" class="ml-2 text-sm text-zinc-400">
                            <a href="{{ route('terms') }}" class="transition-colors" style="color: #ebff00;" onmouseover="this.style.color='#ebff00'" onmouseout="this.style.color='#ebff00'">Kullanım şartlarını</a> ve 
                            <a href="{{ route('privacy') }}" class="transition-colors" style="color: #ebff00;" onmouseover="this.style.color='#ebff00'" onmouseout="this.style.color='#ebff00'">gizlilik politikasını</a> kabul ediyorum
                        </label>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" id="prev-step" class="relative overflow-hidden rounded-lg bg-gradient-to-br from-zinc-800/50 via-zinc-800/30 to-transparent border border-zinc-700/50 py-3 px-6 hover:border-zinc-600/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-zinc-800/50 group">
                            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                            <div class="relative flex items-center justify-center gap-2">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="text-zinc-400 group-hover:-translate-x-1 transition-transform duration-200">
                                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="m12 19-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="text-white font-semibold">Önceki Adım</span>
                            </div>
                        </button>
                        <button type="submit" class="relative overflow-hidden rounded-lg py-3 px-6 transition-all duration-300 transform hover:scale-105 hover:shadow-lg group" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.2), rgba(235, 255, 0, 0.1), transparent); border: 1px solid rgba(235, 255, 0, 0.3);" onmouseover="this.style.borderColor='rgba(235, 255, 0, 0.5)'; this.style.boxShadow='0 10px 25px rgba(235, 255, 0, 0.2)';" onmouseout="this.style.borderColor='rgba(235, 255, 0, 0.3)'; this.style.boxShadow='none';">
                            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.1), rgba(235, 255, 0, 0.05), transparent);"></div>
                            <div class="relative flex items-center justify-center gap-2">
                                <span class="text-white font-semibold">Kayıt Ol</span>
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="group-hover:translate-x-1 transition-transform duration-200" style="color: #ebff00;">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                    <path d="m22 21-3-3m0 0a5.5 5.5 0 1 0-7.78-7.78 5.5 5.5 0 0 0 7.78 7.78Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-zinc-400">Zaten hesabınız var mı? 
                    <a href="{{ route('login') }}" class="transition-colors font-medium" style="color: #ebff00;" onmouseover="this.style.color='#ebff00'" onmouseout="this.style.color='#ebff00'">Giriş yapın</a>
                </p>
            </div>
            
            <div class="mt-8 pt-6 border-t border-zinc-800/50">
                <div class="text-center">
                    <p class="text-sm text-zinc-500 mb-4">Hızlı erişim</p>
                    <div class="flex justify-center space-x-4">
                        @if($settings->telegram)
                        <a href="{{ $settings->telegram }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span class="text-sm">Telegram</span>
                        </a>
                        @endif
                        @if($settings->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 hover:bg-green-500/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span class="text-sm">WhatsApp</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.getElementById('next-step');
    const prevBtn = document.getElementById('prev-step');
    const step1Indicator = document.getElementById('step1-indicator');
    const step2Indicator = document.getElementById('step2-indicator');
    const step1Text = document.getElementById('step1-text');
    const step2Text = document.getElementById('step2-text');

    nextBtn.addEventListener('click', function() {
        // Validate step 1 fields
        const requiredFields = step1.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#ebff00';
                isValid = false;
            } else {
                field.style.borderColor = '';
            }
        });

        if (isValid) {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            // Update indicators
            step1Indicator.style.backgroundColor = '#10b981';
            step1Indicator.style.color = '#000';
            step1Text.style.color = '#10b981';
            
            step2Indicator.classList.remove('bg-zinc-700', 'text-zinc-400');
            step2Indicator.style.backgroundColor = '#ebff00';
            step2Indicator.style.color = '#000';
            step2Text.classList.remove('text-zinc-500');
            step2Text.style.color = '#ebff00';
        }
    });

    prevBtn.addEventListener('click', function() {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        
        // Update indicators
        step1Indicator.style.backgroundColor = '#ebff00';
        step1Indicator.style.color = '#000';
        step1Text.style.color = '#ebff00';
        
        step2Indicator.classList.remove('bg-zinc-700', 'text-zinc-400');
        step2Indicator.style.backgroundColor = '#374151';
        step2Indicator.style.color = '#9ca3af';
        step2Text.style.color = '#6b7280';
    });
});
</script>
@endsection