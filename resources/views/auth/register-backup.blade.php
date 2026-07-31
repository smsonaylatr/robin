@extends('layouts.app')

@section('title', 'Kayıt Ol - BetNow')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-8 shadow-2xl">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 via-transparent to-transparent"></div>
            
            <div class="relative text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-red-500/10 blur-xl rounded-full"></div>
                        <div class="relative p-3 bg-gradient-to-br from-red-500/30 to-red-500/10 rounded-lg border border-red-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="m22 21-3-3m0 0a5.5 5.5 0 1 0-7.78-7.78 5.5 5.5 0 0 0 7.78 7.78Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-red-500 via-red-400 to-red-500 bg-clip-text text-transparent mb-2">Kayıt Ol</h2>
                <p class="text-zinc-400">Yeni hesap oluşturun</p>
                
                <!-- Step indicator -->
                <div class="flex justify-center mt-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div id="step1-indicator" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center text-sm font-bold">1</div>
                            <span id="step1-text" class="ml-2 text-sm text-red-400 font-medium">Kişisel Bilgiler</span>
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
            <div class="relative mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
                <div class="text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" class="relative space-y-6" id="registration-form">
                @csrf
                
                <!-- Step 1: Personal Information -->
                <div id="step1" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-zinc-300 mb-2">Adınız *</label>
                            <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" required 
                                   class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                                   placeholder="Adınızı girin">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-zinc-300 mb-2">Soyadınız *</label>
                            <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" required 
                                   class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                                   placeholder="Soyadınızı girin">
                        </div>
                    </div>
                    
                    <div>
                        <label for="tc" class="block text-sm font-medium text-zinc-300 mb-2">T.C. Kimlik No *</label>
                        <input type="text" id="tc" name="tc" value="{{ old('tc') }}" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="T.C. Kimlik numaranız" maxlength="11" pattern="[0-9]{11}">
                    </div>
                    
                    <div>
                        <label for="birthDate" class="block text-sm font-medium text-zinc-300 mb-2">Doğum Tarihi *</label>
                        <input type="date" id="birthDate" name="birthDate" value="{{ old('birthDate') }}" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="il" class="block text-sm font-medium text-zinc-300 mb-2">İl *</label>
                            <select id="il" name="il" required 
                                    class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200">
                                <option value="">İl seçiniz</option>
                                <option value="Adana">Adana</option>
                                <option value="Adıyaman">Adıyaman</option>
                                <option value="Afyonkarahisar">Afyonkarahisar</option>
                                <option value="Ağrı">Ağrı</option>
                                <option value="Amasya">Amasya</option>
                                <option value="Ankara">Ankara</option>
                                <option value="Antalya">Antalya</option>
                                <option value="Artvin">Artvin</option>
                                <option value="Aydın">Aydın</option>
                                <option value="Balıkesir">Balıkesir</option>
                                <option value="Bilecik">Bilecik</option>
                                <option value="Bingöl">Bingöl</option>
                                <option value="Bitlis">Bitlis</option>
                                <option value="Bolu">Bolu</option>
                                <option value="Burdur">Burdur</option>
                                <option value="Bursa">Bursa</option>
                                <option value="Çanakkale">Çanakkale</option>
                                <option value="Çankırı">Çankırı</option>
                                <option value="Çorum">Çorum</option>
                                <option value="Denizli">Denizli</option>
                                <option value="Diyarbakır">Diyarbakır</option>
                                <option value="Edirne">Edirne</option>
                                <option value="Elazığ">Elazığ</option>
                                <option value="Erzincan">Erzincan</option>
                                <option value="Erzurum">Erzurum</option>
                                <option value="Eskişehir">Eskişehir</option>
                                <option value="Gaziantep">Gaziantep</option>
                                <option value="Giresun">Giresun</option>
                                <option value="Gümüşhane">Gümüşhane</option>
                                <option value="Hakkari">Hakkari</option>
                                <option value="Hatay">Hatay</option>
                                <option value="Isparta">Isparta</option>
                                <option value="Mersin">Mersin</option>
                                <option value="İstanbul">İstanbul</option>
                                <option value="İzmir">İzmir</option>
                                <option value="Kars">Kars</option>
                                <option value="Kastamonu">Kastamonu</option>
                                <option value="Kayseri">Kayseri</option>
                                <option value="Kırklareli">Kırklareli</option>
                                <option value="Kırşehir">Kırşehir</option>
                                <option value="Kocaeli">Kocaeli</option>
                                <option value="Konya">Konya</option>
                                <option value="Kütahya">Kütahya</option>
                                <option value="Malatya">Malatya</option>
                                <option value="Manisa">Manisa</option>
                                <option value="Kahramanmaraş">Kahramanmaraş</option>
                                <option value="Mardin">Mardin</option>
                                <option value="Muğla">Muğla</option>
                                <option value="Muş">Muş</option>
                                <option value="Nevşehir">Nevşehir</option>
                                <option value="Niğde">Niğde</option>
                                <option value="Ordu">Ordu</option>
                                <option value="Rize">Rize</option>
                                <option value="Sakarya">Sakarya</option>
                                <option value="Samsun">Samsun</option>
                                <option value="Siirt">Siirt</option>
                                <option value="Sinop">Sinop</option>
                                <option value="Sivas">Sivas</option>
                                <option value="Tekirdağ">Tekirdağ</option>
                                <option value="Tokat">Tokat</option>
                                <option value="Trabzon">Trabzon</option>
                                <option value="Tunceli">Tunceli</option>
                                <option value="Şanlıurfa">Şanlıurfa</option>
                                <option value="Uşak">Uşak</option>
                                <option value="Van">Van</option>
                                <option value="Yozgat">Yozgat</option>
                                <option value="Zonguldak">Zonguldak</option>
                                <option value="Aksaray">Aksaray</option>
                                <option value="Bayburt">Bayburt</option>
                                <option value="Karaman">Karaman</option>
                                <option value="Kırıkkale">Kırıkkale</option>
                                <option value="Batman">Batman</option>
                                <option value="Şırnak">Şırnak</option>
                                <option value="Bartın">Bartın</option>
                                <option value="Ardahan">Ardahan</option>
                                <option value="Iğdır">Iğdır</option>
                                <option value="Yalova">Yalova</option>
                                <option value="Karabük">Karabük</option>
                                <option value="Kilis">Kilis</option>
                                <option value="Osmaniye">Osmaniye</option>
                                <option value="Düzce">Düzce</option>
                            </select>
                        </div>
                        <div>
                            <label for="ilce" class="block text-sm font-medium text-zinc-300 mb-2">İlçe *</label>
                            <input type="text" id="ilce" name="ilce" value="{{ old('ilce') }}" required 
                                   class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                                   placeholder="İlçenizi girin">
                        </div>
                    </div>
                    
                    <div>
                        <label for="postakodu" class="block text-sm font-medium text-zinc-300 mb-2">Posta Kodu</label>
                        <input type="text" id="postakodu" name="postakodu" value="{{ old('postakodu') }}" 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="34000" maxlength="5" pattern="[0-9]{5}">
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" id="next-step" class="relative overflow-hidden rounded-lg bg-gradient-to-br from-red-500/20 via-red-500/10 to-transparent border border-red-500/30 py-3 px-6 hover:border-red-500/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/20 group">
                            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 via-red-500/5 to-transparent"></div>
                            <div class="relative flex items-center justify-center gap-2">
                                <span class="text-white font-semibold">Sonraki Adım</span>
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="text-red-400 group-hover:translate-x-1 transition-transform duration-200">
                                    <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="m12 5 7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Account Information -->
                <div id="step2" class="space-y-6 hidden">
                    <div>
                        <label for="username" class="block text-sm font-medium text-zinc-300 mb-2">Kullanıcı Adı *</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="En az 6 karakter">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-300 mb-2">E-posta</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="ornek@email.com">
                    </div>
                    
                    <div>
                        <label for="phoneNumber" class="block text-sm font-medium text-zinc-300 mb-2">Telefon *</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="05XXXXXXXXX">
                    </div>
                    
                    <div>
                        <label for="parabirimi" class="block text-sm font-medium text-zinc-300 mb-2">Para Birimi *</label>
                        <select id="parabirimi" name="parabirimi" required 
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200">
                            <option value="">Para birimi seçiniz</option>
                            <option value="₺">₺ Türk Lirası</option>
                            <option value="€">€ Euro</option>
                            <option value="$">$ Dolar</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-300 mb-2">Şifre *</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="En az 6 karakter">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-300 mb-2">Şifre Tekrar</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required 
                               class="w-full px-4 py-3 bg-zinc-800/50 border border-zinc-700/50 rounded-lg text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all duration-200" 
                               placeholder="Şifrenizi tekrar girin">
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="termsAccepted" required 
                               class="mt-1 rounded border-zinc-700 bg-zinc-800/50 text-red-500 focus:ring-red-500 focus:ring-offset-0">
                        <label for="terms" class="ml-2 text-sm text-zinc-400">
                            <a href="{{ route('terms') }}" class="text-red-400 hover:text-red-300">Kullanım şartlarını</a> ve 
                            <a href="{{ route('privacy') }}" class="text-red-400 hover:text-red-300">gizlilik politikasını</a> kabul ediyorum
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
                        <button type="submit" class="relative overflow-hidden rounded-lg bg-gradient-to-br from-red-500/20 via-red-500/10 to-transparent border border-red-500/30 py-3 px-6 hover:border-red-500/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/20 group">
                            <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-30"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 via-red-500/5 to-transparent"></div>
                            <div class="relative flex items-center justify-center gap-2">
                                <span class="text-white font-semibold">Kayıt Ol</span>
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" class="text-red-400 group-hover:translate-x-1 transition-transform duration-200">
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
                    <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 transition-colors font-medium">Giriş yapın</a>
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
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (isValid) {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            // Update indicators
            step1Indicator.classList.remove('bg-red-500', 'text-white');
            step1Indicator.classList.add('bg-green-500', 'text-white');
            step1Text.classList.remove('text-red-400');
            step1Text.classList.add('text-green-400');
            
            step2Indicator.classList.remove('bg-zinc-700', 'text-zinc-400');
            step2Indicator.classList.add('bg-red-500', 'text-white');
            step2Text.classList.remove('text-zinc-500');
            step2Text.classList.add('text-red-400');
        }
    });

    prevBtn.addEventListener('click', function() {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        
        // Update indicators
        step1Indicator.classList.remove('bg-green-500');
        step1Indicator.classList.add('bg-red-500');
        step1Text.classList.remove('text-green-400');
        step1Text.classList.add('text-red-400');
        
        step2Indicator.classList.remove('bg-red-500', 'text-white');
        step2Indicator.classList.add('bg-zinc-700', 'text-zinc-400');
        step2Text.classList.remove('text-red-400');
        step2Text.classList.add('text-zinc-500');
    });
});
</script>
@endsection 