@extends('layouts.admin')

@section('title', 'Bonuslar')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Bonuslar</h1>
            <p class="text-gray-400 mt-1">Sistemdeki tüm bonusları yönetin</p>
        </div>
        <div class="flex items-center gap-3">
            <button style="display: ruby; min-width: max-content;" onclick="showNewBonusModal()" class="px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Yeni Bonus
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Toplam Bonus</p>
                    <p class="stat-card-value">{{ $bonuses->count() }}</p>
                </div>
                <div class="stat-card-icon emerald">
                    <i data-lucide="gift" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Aktif Bonus</p>
                    <p class="stat-card-value">{{ $bonuses->where('aktif', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon green">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Deneme Bonusu</p>
                    <p class="stat-card-value">{{ $bonuses->where('deneme', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon amber">
                    <i data-lucide="star" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Yatırım Bonusu</p>
                    <p class="stat-card-value">{{ $bonuses->where('yatirim', 1)->count() }}</p>
                </div>
                <div class="stat-card-icon blue">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bonuses Table -->
    <div class="content-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white">Bonus Listesi</h2>
            <div class="text-sm text-gray-400">
                {{ $bonuses->count() }} bonus
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Bonus</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Miktar</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Tür</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Çevrim Katı X</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Eklenme</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($bonuses as $bonus)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $bonus->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-zinc-800 flex items-center justify-center overflow-hidden">
                                    @if($bonus->bonus_image)
                                        <img src="{{ $bonus->bonus_image }}" alt="{{ $bonus->bonus_name }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="gift" class="w-6 h-6 text-gray-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $bonus->bonus_name }}</div>
                                    <div class="text-sm text-gray-400">{{ Str::limit($bonus->bonus_description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                @if($bonus->bonus_amount > 0)
                                    <div class="text-white font-medium">{{ number_format($bonus->bonus_amount, 2) }} TL</div>
                                @endif
                                @if($bonus->yuzde > 0)
                                    <div class="text-gray-400">%{{ $bonus->yuzde }}</div>
                                @endif
                                @if($bonus->maxtutar > 0)
                                    <div class="text-gray-400">Max: {{ number_format($bonus->maxtutar, 2) }} TL</div>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex flex-wrap gap-1">
                                @if($bonus->deneme)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-500/10 text-amber-500">Deneme</span>
                                @endif
                                @if($bonus->hosgeldin)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-500/10 text-green-500">Hoşgeldin</span>
                                @endif
                                @if($bonus->yatirim)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-500/10 text-blue-500">Yatırım</span>
                                @endif
                                @if($bonus->kayip)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-red-500/10 text-red-500">Kayıp</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            @if($bonus->cevrim > 0)
                                {{ $bonus->cevrim }}x
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($bonus->aktif == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-500 border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    Pasif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $bonus->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button onclick="editBonus({{ $bonus->id }})" 
                                        data-name="{{ $bonus->bonus_name }}"
                                        data-description="{{ $bonus->bonus_description }}"
                                        data-amount="{{ $bonus->bonus_amount }}"
                                        data-yuzde="{{ $bonus->yuzde }}"
                                        data-maxtutar="{{ $bonus->maxtutar }}"
                                        data-cevrim="{{ $bonus->cevrim }}"
                                        data-aktif="{{ $bonus->aktif }}"
                                        data-deneme="{{ $bonus->deneme }}"
                                        data-hosgeldin="{{ $bonus->hosgeldin }}"
                                        data-yatirim="{{ $bonus->yatirim }}"
                                        data-kayip="{{ $bonus->kayip }}"
                                        data-image="{{ $bonus->bonus_image }}"
                                        class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="toggleBonusStatus({{ $bonus->id }})" 
                                        class="p-2 text-gray-400 hover:text-{{ $bonus->aktif == 1 ? 'red' : 'green' }}-500 hover:bg-{{ $bonus->aktif == 1 ? 'red' : 'green' }}-500/10 rounded-lg transition-colors" 
                                        title="{{ $bonus->aktif == 1 ? 'Pasif Yap' : 'Aktif Yap' }}">
                                    <i data-lucide="{{ $bonus->aktif == 1 ? 'power-off' : 'power' }}" class="w-4 h-4"></i>
                                </button>
                                <button onclick="deleteBonus({{ $bonus->id }})" 
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Sil">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Bonus Modal -->
<div id="newBonusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Yeni Bonus Ekle</h3>
            <button onclick="closeNewModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="newBonusForm" class="space-y-4" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Adı</label>
                    <input type="text" id="newBonusName" required placeholder="Bonus adı..."
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Durum</label>
                    <select id="newBonusAktif" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Açıklama</label>
                <textarea id="newBonusDescription" rows="3" placeholder="Bonus açıklaması..."
                          class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Türleri</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="newBonusDeneme" class="mr-2" onchange="toggleNewBonusFields()">
                        <span class="text-sm text-gray-300">Deneme</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="newBonusHosgeldin" class="mr-2" onchange="toggleNewBonusFields()">
                        <span class="text-sm text-gray-300">Hoşgeldin</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="newBonusYatirim" class="mr-2" onchange="toggleNewBonusFields()">
                        <span class="text-sm text-gray-300">Yatırım</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="newBonusKayip" class="mr-2" onchange="toggleNewBonusFields()">
                        <span class="text-sm text-gray-300">Kayıp</span>
                    </label>
                </div>
            </div>
            
            <!-- Conditional Fields -->
            <div id="newDenemeFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Miktarı (TL)</label>
                    <input type="number" id="newBonusAmount" min="0" step="0.01" placeholder="0.00"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Çevrim Katı X</label>
                    <input type="number" id="newBonusCevrim" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div id="newHosgeldinFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Yüzde (%)</label>
                    <input type="number" id="newBonusYuzde" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Çevrim</label>
                    <input type="number" id="newBonusCevrimHosgeldin" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div id="newYatirimKayipFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Yüzde (%)</label>
                    <input type="number" id="newBonusYuzdeYatirim" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Çevrim Katı X</label>
                    <input type="number" id="newBonusCevrimYatirim" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Max Tutar (TL)</label>
                <input type="number" id="newBonusMaxtutar" min="0" step="0.01" placeholder="0.00"
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Görseli (Opsiyonel)</label>
                <input type="file" id="newBonusImage" accept="image/*" 
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeNewModal()" 
                        class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Bonus Modal -->
<div id="editBonusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Bonus Düzenle</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="editBonusForm" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="editBonusId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Adı</label>
                    <input type="text" id="editBonusName" required placeholder="Bonus adı..."
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Durum</label>
                    <select id="editBonusAktif" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Açıklama</label>
                <textarea id="editBonusDescription" rows="3" placeholder="Bonus açıklaması..."
                          class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Türleri</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="editBonusDeneme" class="mr-2" onchange="toggleBonusFields()">
                        <span class="text-sm text-gray-300">Deneme</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="editBonusHosgeldin" class="mr-2" onchange="toggleBonusFields()">
                        <span class="text-sm text-gray-300">Hoşgeldin</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="editBonusYatirim" class="mr-2" onchange="toggleBonusFields()">
                        <span class="text-sm text-gray-300">Yatırım</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="editBonusKayip" class="mr-2" onchange="toggleBonusFields()">
                        <span class="text-sm text-gray-300">Kayıp</span>
                    </label>
                </div>
            </div>
            
            <!-- Conditional Fields -->
            <div id="denemeFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Miktarı (TL)</label>
                    <input type="number" id="editBonusAmount" min="0" step="0.01" placeholder="0.00"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Çevrim Katı X</label>
                    <input type="number" id="editBonusCevrim" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div id="hosgeldinFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Yüzde (%)</label>
                    <input type="number" id="editBonusYuzde" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Çevrim</label>
                    <input type="number" id="editBonusCevrimHosgeldin" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div id="yatirimKayipFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Yüzde (%)</label>
                    <input type="number" id="editBonusYuzdeYatirim" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Çevrim Katı X</label>
                    <input type="number" id="editBonusCevrimYatirim" min="0" placeholder="0"
                           class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Max Tutar (TL)</label>
                <input type="number" id="editBonusMaxtutar" min="0" step="0.01" placeholder="0.00"
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Bonus Görseli (Opsiyonel)</label>
                <input type="file" id="editBonusImage" accept="image/*" 
                       class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Boş bırakırsanız mevcut görsel korunur</p>
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeEditModal()" 
                        class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 hidden">
    <div id="messageContent" class="px-4 py-3 rounded-lg text-white font-medium"></div>
</div>

<script>
function showNewBonusModal() {
    // Reset form
    document.getElementById('newBonusForm').reset();
    
    // Hide all conditional fields
    document.getElementById('newDenemeFields').classList.add('hidden');
    document.getElementById('newHosgeldinFields').classList.add('hidden');
    document.getElementById('newYatirimKayipFields').classList.add('hidden');
    
    document.getElementById('newBonusModal').classList.remove('hidden');
}

function closeNewModal() {
    document.getElementById('newBonusModal').classList.add('hidden');
}

function toggleNewBonusFields() {
    const deneme = document.getElementById('newBonusDeneme').checked;
    const hosgeldin = document.getElementById('newBonusHosgeldin').checked;
    const yatirim = document.getElementById('newBonusYatirim').checked;
    const kayip = document.getElementById('newBonusKayip').checked;
    
    // Hide all conditional fields first
    document.getElementById('newDenemeFields').classList.add('hidden');
    document.getElementById('newHosgeldinFields').classList.add('hidden');
    document.getElementById('newYatirimKayipFields').classList.add('hidden');
    
    // Show appropriate fields based on selection
    if (deneme) {
        document.getElementById('newDenemeFields').classList.remove('hidden');
    }
    
    if (hosgeldin) {
        document.getElementById('newHosgeldinFields').classList.remove('hidden');
    }
    
    if (yatirim || kayip) {
        document.getElementById('newYatirimKayipFields').classList.remove('hidden');
    }
}

function editBonus(id) {
    const button = event.target.closest('button');
    const name = button.getAttribute('data-name');
    const description = button.getAttribute('data-description');
    const amount = button.getAttribute('data-amount');
    const yuzde = button.getAttribute('data-yuzde');
    const maxtutar = button.getAttribute('data-maxtutar');
    const cevrim = button.getAttribute('data-cevrim');
    const aktif = button.getAttribute('data-aktif');
    const deneme = button.getAttribute('data-deneme');
    const hosgeldin = button.getAttribute('data-hosgeldin');
    const yatirim = button.getAttribute('data-yatirim');
    const kayip = button.getAttribute('data-kayip');
    
    document.getElementById('editBonusId').value = id;
    document.getElementById('editBonusName').value = name;
    document.getElementById('editBonusDescription').value = description;
    document.getElementById('editBonusAmount').value = amount;
    document.getElementById('editBonusYuzde').value = yuzde;
    document.getElementById('editBonusYuzdeYatirim').value = yuzde;
    document.getElementById('editBonusMaxtutar').value = maxtutar;
    document.getElementById('editBonusCevrim').value = cevrim;
    document.getElementById('editBonusCevrimHosgeldin').value = cevrim;
    document.getElementById('editBonusCevrimYatirim').value = cevrim;
    document.getElementById('editBonusAktif').value = aktif;
    document.getElementById('editBonusDeneme').checked = deneme == 1;
    document.getElementById('editBonusHosgeldin').checked = hosgeldin == 1;
    document.getElementById('editBonusYatirim').checked = yatirim == 1;
    document.getElementById('editBonusKayip').checked = kayip == 1;
    
    // Show/hide fields based on bonus types
    toggleBonusFields();
    
    document.getElementById('editBonusModal').classList.remove('hidden');
}

function toggleBonusFields() {
    const deneme = document.getElementById('editBonusDeneme').checked;
    const hosgeldin = document.getElementById('editBonusHosgeldin').checked;
    const yatirim = document.getElementById('editBonusYatirim').checked;
    const kayip = document.getElementById('editBonusKayip').checked;
    
    // Hide all conditional fields first
    document.getElementById('denemeFields').classList.add('hidden');
    document.getElementById('hosgeldinFields').classList.add('hidden');
    document.getElementById('yatirimKayipFields').classList.add('hidden');
    
    // Show appropriate fields based on selection
    if (deneme) {
        document.getElementById('denemeFields').classList.remove('hidden');
    }
    
    if (hosgeldin) {
        document.getElementById('hosgeldinFields').classList.remove('hidden');
    }
    
    if (yatirim || kayip) {
        document.getElementById('yatirimKayipFields').classList.remove('hidden');
    }
}

function closeEditModal() {
    document.getElementById('editBonusModal').classList.add('hidden');
}

function deleteBonus(id) {
    if (!confirm('Bu bonusu silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    fetch(`/admin/bonuses/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu: ' + error.message, 'error');
    });
}

function toggleBonusStatus(id) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    fetch(`/admin/bonuses/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu: ' + error.message, 'error');
    });
}

document.getElementById('newBonusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('bonus_name', document.getElementById('newBonusName').value);
    formData.append('bonus_description', document.getElementById('newBonusDescription').value);
    formData.append('maxtutar', document.getElementById('newBonusMaxtutar').value);
    formData.append('aktif', document.getElementById('newBonusAktif').value);
    
    // Get values based on bonus types
    const deneme = document.getElementById('newBonusDeneme').checked;
    const hosgeldin = document.getElementById('newBonusHosgeldin').checked;
    const yatirim = document.getElementById('newBonusYatirim').checked;
    const kayip = document.getElementById('newBonusKayip').checked;
    
    if (deneme) {
        formData.append('bonus_amount', document.getElementById('newBonusAmount').value);
        formData.append('cevrim', document.getElementById('newBonusCevrim').value);
        formData.append('deneme', '1');
    }
    
    if (hosgeldin) {
        formData.append('yuzde', document.getElementById('newBonusYuzde').value);
        formData.append('cevrim', document.getElementById('newBonusCevrimHosgeldin').value);
        formData.append('hosgeldin', '1');
    }
    
    if (yatirim || kayip) {
        formData.append('yuzde', document.getElementById('newBonusYuzdeYatirim').value);
        formData.append('cevrim', document.getElementById('newBonusCevrimYatirim').value);
        if (yatirim) formData.append('yatirim', '1');
        if (kayip) formData.append('kayip', '1');
    }
    
    const imageFile = document.getElementById('newBonusImage').files[0];
    if (imageFile) {
        formData.append('bonus_image', imageFile);
    }
    
    fetch('/admin/bonuses/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeNewModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu: ' + error.message, 'error');
    });
});

document.getElementById('editBonusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        showMessage('CSRF token bulunamadı!', 'error');
        return;
    }
    
    const id = document.getElementById('editBonusId').value;
    const formData = new FormData();
    formData.append('bonus_name', document.getElementById('editBonusName').value);
    formData.append('bonus_description', document.getElementById('editBonusDescription').value);
    formData.append('maxtutar', document.getElementById('editBonusMaxtutar').value);
    formData.append('aktif', document.getElementById('editBonusAktif').value);
    
    // Get values based on bonus types
    const deneme = document.getElementById('editBonusDeneme').checked;
    const hosgeldin = document.getElementById('editBonusHosgeldin').checked;
    const yatirim = document.getElementById('editBonusYatirim').checked;
    const kayip = document.getElementById('editBonusKayip').checked;
    
    if (deneme) {
        formData.append('bonus_amount', document.getElementById('editBonusAmount').value);
        formData.append('cevrim', document.getElementById('editBonusCevrim').value);
        formData.append('deneme', '1');
    }
    
    if (hosgeldin) {
        formData.append('yuzde', document.getElementById('editBonusYuzde').value);
        formData.append('cevrim', document.getElementById('editBonusCevrimHosgeldin').value);
        formData.append('hosgeldin', '1');
    }
    
    if (yatirim || kayip) {
        formData.append('yuzde', document.getElementById('editBonusYuzdeYatirim').value);
        formData.append('cevrim', document.getElementById('editBonusCevrimYatirim').value);
        if (yatirim) formData.append('yatirim', '1');
        if (kayip) formData.append('kayip', '1');
    }
    
    const imageFile = document.getElementById('editBonusImage').files[0];
    if (imageFile) {
        formData.append('bonus_image', imageFile);
    }
    
    fetch(`/admin/bonuses/${id}/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeEditModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.message || 'Bir hata oluştu!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Bir hata oluştu: ' + error.message, 'error');
    });
});

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const content = document.getElementById('messageContent');
    
    content.textContent = message;
    content.className = `px-4 py-3 rounded-lg text-white font-medium ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    
    container.classList.remove('hidden');
    
    setTimeout(() => {
        container.classList.add('hidden');
    }, 3000);
}
</script>
@endsection 