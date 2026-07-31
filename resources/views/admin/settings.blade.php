@extends('layouts.admin')

@section('title', 'Site Ayarları')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-zinc-900 via-black to-zinc-900 p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4">
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-600/10 to-orange-600/10"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">⚙️ Site Ayarları</h1>
                    <p class="text-gray-400 text-sm">Sistem parametrelerini yönetin</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="settingsForm" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-black font-semibold rounded-lg hover:from-yellow-400 hover:to-orange-400 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-yellow-500/25">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Kaydet
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-500/10 backdrop-blur-sm border border-red-500/30 text-red-400 px-6 py-4 rounded-2xl shadow-lg">
                <div class="flex items-center mb-3">
                    <i data-lucide="alert-circle" class="w-6 h-6 mr-3"></i>
                    <span class="font-bold">Hatalar:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 ml-9">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Settings Form -->
        <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Main Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Site Information Card -->
                <div class="lg:col-span-1">
                    <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4 h-full hover:border-zinc-700/70 transition-colors duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                <i data-lucide="info" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Site Bilgileri</h3>
                                <p class="text-gray-400 text-xs">Temel site ayarları</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Site Adı</label>
                                <input type="text" name="site_adi" value="{{ $settings->site_adi ?? '' }}" required 
                                       class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Site Açıklaması</label>
                                <textarea name="site_aciklama" rows="2" 
                                          class="w-full bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 py-2 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50 resize-none">{{ $settings->site_aciklama ?? '' }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Anahtar Kelimeler</label>
                                <textarea name="site_kelimeler" rows="2" 
                                          class="w-full bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 py-2 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50 resize-none">{{ $settings->site_kelimeler ?? '' }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Footer Açıklaması</label>
                                <textarea name="footer_desc" rows="2" 
                                          class="w-full bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 py-2 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50 resize-none">{{ $settings->footer_desc ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Settings Card -->
                <div class="lg:col-span-1">
                    <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4 h-full hover:border-zinc-700/70 transition-colors duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                <i data-lucide="settings" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Sistem Ayarları</h3>
                                <p class="text-gray-400 text-xs">Teknik konfigürasyonlar</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Casino Limit</label>
                                <input type="text" name="casinolimit" value="{{ $settings->casinolimit ?? '' }}" 
                                       class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50">
                            </div>
                            
                            <div>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-300">Site Durumu</span>
                                        <p class="text-xs text-gray-400 mt-1">Sitenin aktif/pasif durumu</p>
                                    </div>
                                    <div class="relative">
                                        <input type="hidden" name="site_durum" value="0">
                                        <input type="checkbox" name="site_durum" value="1" {{ ($settings->site_durum ?? 1) == 1 ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                            </div>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-300">SMS OTP</span>
                                        <p class="text-xs text-gray-400 mt-1">SMS doğrulama sistemi</p>
                                    </div>
                                    <div class="relative">
                                        <input type="hidden" name="dogrulama" value="0">
                                        <input type="checkbox" name="dogrulama" value="1" {{ ($settings->dogrulama ?? 0) == 1 ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                            </div>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-300">API Modu</span>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <span id="api-status-text">{{ ($settings->fakeapi ?? 0) == 1 ? '🔹 K9 API' : '🔸 Betsapitech API' }}</span>
                                        </p>
                                    </div>
                                    <div class="relative">
                                        <input type="hidden" name="fakeapi" value="0">
                                        <input type="checkbox" name="fakeapi" value="1" {{ ($settings->fakeapi ?? 0) == 1 ? 'checked' : '' }}
                                               class="sr-only peer" 
                                               onchange="updateApiStatus(this)">
                                        <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                            </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Settings Card -->
                <div class="lg:col-span-1">
                    <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4 h-full hover:border-zinc-700/70 transition-colors duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                <i data-lucide="image" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Medya Ayarları</h3>
                                <p class="text-gray-400 text-xs">Logo ve görseller</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Logo Yükle</label>
                                <div class="relative">
                                    <input type="file" name="logo" accept="image/*" 
                                           class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-400 file:cursor-pointer">
                                </div>
                                @if($settings->logo)
                                    <div class="mt-3 flex items-center gap-3 p-3 bg-gradient-to-r from-zinc-900/40 to-zinc-900/20 rounded-lg border border-zinc-700/30 hover:border-zinc-600/50 transition-colors">
                                        <div class="relative">
                                            <img src="{{ asset($settings->logo) }}" alt="Mevcut Logo" class="w-12 h-12 object-cover rounded-lg border-2 border-zinc-600 shadow-lg">
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-zinc-900 flex items-center justify-center">
                                                <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white font-semibold text-base">Mevcut Logo</p>
                                            <p class="text-green-400 text-sm flex items-center gap-1 mt-1">
                                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                                Aktif olarak kullanılıyor
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Logo Boyutu Ayarı -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-300 mb-2">Logo Boyutu</label>
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <input type="range" name="logo_size" min="20" max="200" value="{{ $settings->logo_size ?? 120 }}" 
                                                   class="w-full h-2 bg-zinc-700 rounded-lg appearance-none cursor-pointer slider" 
                                                   id="logo-size-slider"
                                                   onchange="updateLogoPreview(this.value)"
                                                   oninput="updateLogoPreview(this.value)">
                                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                                <span>20px</span>
                                                <span>120px</span>
                                                <span>200px</span>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-8 bg-zinc-800 rounded border border-zinc-600 flex items-center justify-center">
                                                <span id="logo-size-display" class="text-white text-sm font-bold">{{ $settings->logo_size ?? 120 }}px</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Logo Önizleme -->
                                    @if($settings->logo)
                                    <div class="mt-3 p-3 bg-zinc-900/30 rounded-lg border border-zinc-700/30">
                                        <p class="text-xs text-gray-400 mb-2">Önizleme:</p>
                                        <div class="flex items-center justify-center min-h-[60px]">
                                            <img src="{{ asset($settings->logo) }}" alt="Logo Önizleme" 
                                                 id="logo-preview" 
                                                 style="width: {{ $settings->logo_size ?? 120 }}px; height: auto; max-width: 100%;"
                                                 class="object-contain transition-all duration-200">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Favicon Yükle</label>
                                <div class="relative">
                                    <input type="file" name="favicon" accept="image/*" 
                                           class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-400 file:cursor-pointer">
                                </div>
                                @if($settings->favicon)
                                    <div class="mt-3 flex items-center gap-3 p-3 bg-gradient-to-r from-zinc-900/40 to-zinc-900/20 rounded-lg border border-zinc-700/30 hover:border-zinc-600/50 transition-colors">
                                        <div class="relative">
                                            <img src="{{ asset($settings->favicon) }}" alt="Mevcut Favicon" class="w-10 h-10 object-cover rounded-lg border-2 border-zinc-600 shadow-lg">
                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-zinc-900 flex items-center justify-center">
                                                <i data-lucide="check" class="w-2 h-2 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white font-semibold text-base">Mevcut Favicon</p>
                                            <p class="text-green-400 text-sm flex items-center gap-1 mt-1">
                                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                                Aktif olarak kullanılıyor
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Homepage Settings Card -->
                <div class="lg:col-span-1">
                    <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4 h-full hover:border-zinc-700/70 transition-colors duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                <i data-lucide="home" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Anasayfa Ayarları</h3>
                                <p class="text-gray-400 text-xs">Anasayfa içerik kontrolleri</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Spor Bahisleri -->
                            <div>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-300">Spor Bahisleri</span>
                                        <p class="text-xs text-gray-400">Anasayfada yaklaşan maçları göster</p>
                                    </div>
                                    <div class="relative">
                                        <input type="hidden" name="homespor" value="0">
                                        <input type="checkbox" name="homespor" value="1" {{ $settings->homespor == 1 ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                    </div>
                                </label>
                            </div>

                            <!-- Son Kazananlar -->
                            <div>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-300">Son Kazananlar</span>
                                        <p class="text-xs text-gray-400">Anasayfada son kazananları göster</p>
                                    </div>
                                    <div class="relative">
                                        <input type="hidden" name="homewin" value="0">
                                        <input type="checkbox" name="homewin" value="1" {{ $settings->homewin == 1 ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Settings Card -->
                <div class="lg:col-span-2 xl:col-span-3">
                    <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                <i data-lucide="share-2" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Sosyal Medya</h3>
                                <p class="text-gray-400 text-xs">Sosyal medya hesap bağlantıları</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">📱 Telegram</label>
                                <input type="url" name="telegram" value="{{ $settings->telegram ?? '' }}" placeholder="https://t.me/kanaladı" 
                                       class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">📷 Instagram</label>
                                <input type="url" name="instagram" value="{{ $settings->instagram ?? '' }}" placeholder="https://instagram.com/hesap" 
                                       class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">🐦 Twitter</label>
                                <input type="url" name="twitter" value="{{ $settings->twitter ?? '' }}" placeholder="https://twitter.com/hesap" 
                                       class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LiveChat Support Settings Card -->
                <div class="lg:col-span-2 xl:col-span-3">
                    <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-4">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                                <i data-lucide="message-circle" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">💬 LiveChat Canlı Destek</h3>
                                <p class="text-gray-400 text-xs">Sadece LiveChat numaranızı girin</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">LiveChat Numarası</label>
                                <p class="text-gray-400 text-xs mb-3">Sadece LiveChat numaranızı girin (örnek: 19227692). Sistem otomatik olarak gerekli kodu oluşturacaktır.</p>
                                <input type="text" name="canlidestek" placeholder="19227692" 
                                       class="w-full h-10 bg-zinc-900/50 border border-zinc-700/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 hover:border-zinc-600/50 text-lg font-mono"
                                       value="{{ $settings->canlidestek ?? '' }}"
                                       pattern="[0-9]{1,10}"
                                       maxlength="10"
                                       minlength="1"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                       title="Sadece 1-10 haneli rakam girebilirsiniz"
                                       required>
                            </div>
                            
                            @if($settings->canlidestek ?? '')
                                <div class="bg-green-500/10 backdrop-blur-sm border border-green-500/30 text-green-400 px-4 py-3 rounded-lg">
                                    <div class="flex items-center">
                                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                                        <span class="font-semibold">LiveChat Aktif!</span>
                                    </div>
                                    <p class="text-sm mt-1 ml-7">LiveChat numarası: <strong>{{ $settings->canlidestek }}</strong> - Widget tüm sayfalarda görünüyor.</p>
                                </div>
                            @else
                                <div class="bg-yellow-500/10 backdrop-blur-sm border border-yellow-500/30 text-yellow-400 px-4 py-3 rounded-lg">
                                    <div class="flex items-center">
                                        <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                                        <span class="font-semibold">LiveChat Pasif</span>
                                    </div>
                                    <p class="text-sm mt-1 ml-7">LiveChat numaranızı girerek widget'ı aktifleştirin.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Kayıt Form Ayarları -->
        <div class="bg-black/20 backdrop-blur-sm border border-zinc-800/50 rounded-xl p-6 mt-8">
            <div class="flex items-center mb-6">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                    <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Kayıt Form Ayarları</h2>
                    <p class="text-zinc-400 text-sm">Kayıt formundaki alanları yönetin - değişiklikler otomatik kaydedilir</p>
                </div>
            </div>

            @php
                $regFields = \App\Models\RegistrationSettings::getAllFieldsForAdmin();
            @endphp

            <!-- Registration Fields -->
            <div class="space-y-4" id="registration-fields-container">
                @foreach($regFields as $field)
                <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-4 field-item" data-field="{{ $field->field_name }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Drag Handle -->
                            <div class="cursor-move text-zinc-500 hover:text-zinc-300 transition-colors">
                                <i data-lucide="grip-vertical" class="w-5 h-5"></i>
                            </div>
                            
                            <!-- Field Info -->
                            <div>
                                <h3 class="text-white font-medium">{{ $field->field_label }}</h3>
                                <p class="text-zinc-400 text-sm">
                                    Alan: <span class="text-blue-400">{{ $field->field_name }}</span>
                                    • Tip: <span class="text-green-400">{{ $field->field_type }}</span>
                                    • Sıra: <span class="text-yellow-400">{{ $field->field_order }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Required Toggle -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-zinc-300">Zorunlu:</label>
                                @php
                                    $alwaysRequired = in_array($field->field_name, ['firstName', 'lastName', 'username', 'phoneNumber']);
                                @endphp
                                <label class="relative inline-flex items-center {{ $alwaysRequired ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                                    <input type="checkbox" class="sr-only peer required-toggle" 
                                           data-field="{{ $field->field_name }}"
                                           @if($field->is_required) checked @endif
                                           @if($alwaysRequired) disabled @endif>
                                    <div class="w-11 h-6 {{ $alwaysRequired ? 'bg-yellow-500' : 'bg-zinc-600' }} peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                                </label>
                                @if($alwaysRequired)
                                    <span class="text-xs text-yellow-400">🔒</span>
                                @endif
                            </div>

                            <!-- Active Toggle -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-zinc-300">Aktif:</label>
                                @php
                                    $alwaysActive = in_array($field->field_name, ['firstName', 'lastName', 'username', 'phoneNumber']);
                                @endphp
                                <label class="relative inline-flex items-center {{ $alwaysActive ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                                    <input type="checkbox" class="sr-only peer active-toggle" 
                                           data-field="{{ $field->field_name }}"
                                           @if($field->is_active) checked @endif
                                           @if($alwaysActive) disabled @endif>
                                    <div class="w-11 h-6 {{ $alwaysActive ? 'bg-green-500' : 'bg-zinc-600' }} peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                                @if($alwaysActive)
                                    <span class="text-xs text-green-400">🔒</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-400 mt-0.5"></i>
                    <div class="text-sm text-blue-300">
                        <p class="font-medium mb-2">💡 Kullanım Kılavuzu:</p>
                        <ul class="space-y-1 text-blue-200/80">
                            <li>• <strong>Aktif</strong> alanlar kayıt formunda görünür</li>
                            <li>• <strong>Pasif</strong> alanlar kayıt formunda gizlenir</li>
                            <li>• <strong>Zorunlu</strong> alanlar kullanıcı tarafından doldurulması gereken alanlardır</li>
                            <li>• Alanları sürükleyerek sıralamayı değiştirebilirsiniz</li>
                            <li>• 🔒 <strong>Temel Alanlar</strong> (Ad, Soyad, Kullanıcı Adı, Telefon) değiştirilemez</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <div id="reg-messageContainer" class="hidden mt-4"></div>
        </div>

        <!-- Sortable.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sortable functionality for registration fields
            const regFieldsContainer = document.querySelector('#registration-fields-container');
            if (regFieldsContainer) {
                Sortable.create(regFieldsContainer, {
                    animation: 150,
                    handle: '.cursor-move',
                    onEnd: function(evt) {
                        updateRegFieldOrder();
                    }
                });
            }

            // Active toggle handlers for registration fields
            document.querySelectorAll('.active-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    if (this.disabled) return;
                    
                    const fieldName = this.dataset.field;
                    const isActive = this.checked;
                    
                    updateRegFieldStatus(fieldName, 'is_active', isActive);
                });
            });

            // Required toggle handlers for registration fields
            document.querySelectorAll('.required-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    if (this.disabled) return;
                    
                    const fieldName = this.dataset.field;
                    const isRequired = this.checked;
                    
                    updateRegFieldStatus(fieldName, 'is_required', isRequired);
                });
            });

            function updateRegFieldStatus(fieldName, type, value) {
                fetch(`/admin/registration-settings/toggle/${fieldName}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ [type]: value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showRegMessage(data.message, 'success');
                    } else {
                        showRegMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showRegMessage('Bir hata oluştu!', 'error');
                });
            }

            function updateRegFieldOrder() {
                const fieldItems = document.querySelectorAll('#registration-fields-container .field-item');
                const orders = [];
                
                fieldItems.forEach((item, index) => {
                    orders.push({
                        field_name: item.dataset.field,
                        field_order: index + 1
                    });
                });

                fetch('/admin/registration-settings/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ orders: orders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showRegMessage('Alan sıralaması güncellendi!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showRegMessage('Sıralama güncellenirken hata oluştu!', 'error');
                });
            }

            function updateApiStatus(checkbox) {
                const statusText = document.getElementById('api-status-text');
                if (checkbox.checked) {
                    statusText.innerHTML = '🔹 K9 API';
                } else {
                    statusText.innerHTML = '🔸 Betsapitech API';
                }
            }

            function updateLogoPreview(size) {
                console.log('updateLogoPreview çağrıldı, boyut:', size);
                
                const logoPreview = document.getElementById('logo-preview');
                const sizeDisplay = document.getElementById('logo-size-display');
                
                // Boyut göstergesini güncelle
                if (sizeDisplay) {
                    sizeDisplay.textContent = size + 'px';
                    console.log('Boyut göstergesi güncellendi:', size + 'px');
                }
                
                // Logo önizlemeyi güncelle
                if (logoPreview) {
                    logoPreview.style.width = size + 'px';
                    logoPreview.style.height = 'auto';
                    logoPreview.style.maxWidth = '100%';
                    console.log('Logo önizleme güncellendi, yeni genişlik:', size + 'px');
                } else {
                    console.log('Logo preview elementi bulunamadı!');
                }
            }
            
            // Sayfa yüklendiğinde slider'ı başlat
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM yüklendi, slider başlatılıyor...');
                
                const slider = document.getElementById('logo-size-slider');
                if (slider) {
                    console.log('Slider bulundu, değer:', slider.value);
                    
                    // İlk yüklemede önizlemeyi güncelle
                    updateLogoPreview(slider.value);
                    
                    // Slider değişikliklerini dinle
                    slider.addEventListener('input', function() {
                        console.log('Slider değişti, yeni değer:', this.value);
                        updateLogoPreview(this.value);
                    });
                    
                    // oninput event'ini de ekle
                    slider.oninput = function() {
                        console.log('oninput event tetiklendi, değer:', this.value);
                        updateLogoPreview(this.value);
                    };
                } else {
                    console.log('Slider elementi bulunamadı!');
                }
            });

            function showRegMessage(message, type) {
                const container = document.getElementById('reg-messageContainer');
                const bgColor = type === 'success' ? 'bg-green-500/10 border-green-500/30 text-green-400' : 'bg-red-500/10 border-red-500/30 text-red-400';
                const icon = type === 'success' ? 'check-circle' : 'alert-circle';
                
                container.innerHTML = `
                    <div class="${bgColor} backdrop-blur-sm border px-6 py-4 rounded-2xl shadow-lg">
                        <div class="flex items-center">
                            <i data-lucide="${icon}" class="w-6 h-6 mr-3"></i>
                            <span class="font-medium">${message}</span>
                        </div>
                    </div>
                `;
                
                container.classList.remove('hidden');
                
                // Auto hide after 3 seconds
                setTimeout(() => {
                    container.classList.add('hidden');
                }, 3000);
            }
        });
        </script>

        <style>
        /* Slider Stilleri */
        .slider {
            -webkit-appearance: none;
            appearance: none;
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            outline: none;
            border-radius: 8px;
            height: 8px;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ffffff;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .slider::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ffffff;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .slider::-moz-range-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }
        </style>

@endsection 