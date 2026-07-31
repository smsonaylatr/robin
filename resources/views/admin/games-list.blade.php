@extends('layouts.admin')

@section('title', 'Oyunlar')

@section('content')
<div class="w-full">
    <div class="bg-zinc-900/50 border border-zinc-700/50 rounded-lg">
        <div class="flex items-center justify-between p-4 border-b border-zinc-700/50">
            <h3 class="text-lg font-semibold text-white">Oyunlar Listesi</h3>
            <button type="button" class="px-3 py-2 bg-zinc-800 hover:bg-green-500/20 border border-zinc-700 hover:border-green-500/30 text-gray-300 hover:text-green-400 text-sm rounded-lg transition-all duration-200" onclick="refreshGames()">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>Yenile
            </button>
        </div>
                
        <!-- Filters -->
        <div class="p-4">
            <form method="GET" action="{{ route('admin.games-list') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Oyun adı, kodu ara..." 
                           class="w-full px-3 py-2 bg-black border border-zinc-700 rounded-lg text-white placeholder-gray-400 text-sm focus:border-yellow-500 focus:outline-none">
                </div>
                <div>
                    <select name="status" class="w-full px-3 py-2 bg-black border border-zinc-700 rounded-lg text-white text-sm focus:border-yellow-500 focus:outline-none">
                        <option value="">Tüm Durumlar</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <div>
                    <select name="provider" class="w-full px-3 py-2 bg-black border border-zinc-700 rounded-lg text-white text-sm focus:border-yellow-500 focus:outline-none">
                        <option value="">Tüm Sağlayıcılar</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" {{ request('provider') == $provider->id ? 'selected' : '' }}>{{ $provider->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="sort" class="w-full px-3 py-2 bg-black border border-zinc-700 rounded-lg text-white text-sm focus:border-yellow-500 focus:outline-none">
                        <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>En Yeni</option>
                        <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>En Eski</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-zinc-800 hover:bg-yellow-500/20 border border-zinc-700 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 text-sm rounded-lg transition-all duration-200">Filtrele</button>
                    @if(request()->hasAny(['search', 'status', 'provider', 'sort']))
                        <a href="{{ route('admin.games-list') }}" class="px-4 py-2 bg-zinc-800 hover:bg-red-500/20 border border-zinc-700 hover:border-red-500/30 text-gray-300 hover:text-red-400 text-sm rounded-lg transition-all duration-200">Temizle</a>
                    @endif
                </div>
            </form>
                    
            <!-- Games Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2">
                @forelse($games as $game)
                <div id="game-card-{{ $game->id }}" data-ikincilmi="{{ (int)($game->ikincilmi ?? 0) }}" data-url="{{ $game->url ?? '' }}" data-vendorcode="{{ $game->vendorcode ?? '' }}" class="bg-zinc-900/50 border border-zinc-700/50 rounded-lg overflow-hidden hover:border-yellow-500/30 transition-all duration-300 group">
                    <!-- Game Image -->
                    <div class="relative aspect-square bg-zinc-800/50 overflow-hidden">
                        @if($game->cover)
                            <img src="{{ $game->cover }}" 
                                 alt="{{ $game->game_name ?? 'Oyun' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy"
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjMjcyNzJBIi8+CjxwYXRoIGQ9Ik0zMCAyNEg0MFYzNEgzMFYyNFoiIGZpbGw9IiM1MjUyNTciLz4KPHA+dGggZD0iTTMwIDM0SDQwVjQ0SDMwVjM0WiIgZmlsbD0iIzUyNTI1NyIvPgo8cGF0aCBkPSJNNDAgMjRINTBWMzRINDBWMjRaIiBmaWxsPSIjNTI1MjU3Ii8+CjxwYXRoIGQ9Ik01MCAzNEg2MFY0NEg1MFYzNFoiIGZpbGw9IiM1MjUyNTciLz4KPHA+dGggZD0iTTQwIDQ0SDUwVjU0SDQwVjQ0WiIgZmlsbD0iIzUyNTI1NyIvPgo8L3N2Zz4='">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-zinc-800/50">
                                <i data-lucide="image" class="w-4 h-4 text-zinc-600"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-0.5 left-0.5">
                            @if($game->status == 1)
                                <span class="px-1 py-0.5 bg-green-600/90 text-white rounded text-xs font-medium backdrop-blur-sm">✓</span>
                            @else
                                <span class="px-1 py-0.5 bg-red-600/90 text-white rounded text-xs font-medium backdrop-blur-sm">✕</span>
                            @endif
                        </div>
                        
                        <!-- Game ID -->
                        <div class="absolute top-0.5 right-0.5">
                            <span class="px-1 py-0.5 bg-black/70 text-white rounded text-xs font-mono backdrop-blur-sm">{{ $game->id }}</span>
                        </div>
                    </div>
                    
                    <!-- Game Info -->
                    <div class="p-2">
                        <div class="mb-1">
                            <h3 class="text-white font-medium text-xs line-clamp-1 mb-0.5" title="{{ $game->game_name ?? 'İsimsiz Oyun' }}">{{ $game->game_name ?? 'İsimsiz Oyun' }}</h3>
                            <p class="text-gray-400 text-xs font-mono truncate">{{ Str::limit($game->game_code ?? '-', 8) }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mb-1 text-xs">
                            <span class="text-gray-300 truncate text-xs">{{ Str::limit($game->provider->name ?? 'Bilinmeyen', 6) }}</span>
                            <span class="text-gray-500 text-xs">{{ $game->created_at ? $game->created_at->format('d.m') : '-' }}</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-1">
                            <button onclick="editGame({{ $game->id }}, '{{ addslashes($game->game_name) }}', '{{ addslashes($game->game_code) }}', {{ $game->status }})" 
                                    class="flex-1 px-1 py-1 bg-zinc-700/50 hover:bg-yellow-500/20 border border-zinc-600/50 hover:border-yellow-500/30 text-gray-300 hover:text-yellow-400 rounded text-xs transition-all duration-200 flex items-center justify-center">
                                <i data-lucide="edit" class="w-3 h-3"></i>
                            </button>
                            <button onclick="toggleGameStatus({{ $game->id }})" 
                                    class="px-1 py-1 bg-zinc-700/50 hover:bg-{{ $game->status == 1 ? 'red' : 'green' }}-500/20 border border-zinc-600/50 hover:border-{{ $game->status == 1 ? 'red' : 'green' }}-500/30 text-gray-300 hover:text-{{ $game->status == 1 ? 'red' : 'green' }}-400 rounded text-xs transition-all duration-200">
                                <i data-lucide="{{ $game->status == 1 ? 'pause' : 'play' }}" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <i data-lucide="gamepad-2" class="w-12 h-12 text-zinc-600"></i>
                        <p class="text-gray-400">Oyun bulunamadı</p>
                    </div>
                </div>
                @endforelse
            </div>
                    
            <!-- Pagination -->
            @if($games->hasPages())
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-zinc-700">
                    <div class="text-gray-400 text-sm">
                        Toplam {{ $games->total() }} oyundan {{ $games->firstItem() }}-{{ $games->lastItem() }} arası gösteriliyor
                    </div>
                    <div>
                        {{ $games->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Game Modal -->
<div id="editGameModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-zinc-900 border border-zinc-700 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-lg font-semibold text-white">Oyun Düzenle</h5>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editGameForm" class="space-y-4">
            <input type="hidden" id="editGameId">
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Oyun Adı</label>
                <input type="text" id="editGameName" required
                       class="w-full px-3 py-2 bg-zinc-800/50 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:border-blue-500 focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Oyun Kodu</label>
                <input type="text" id="editGameCode" required
                       class="w-full px-3 py-2 bg-zinc-800/50 border border-zinc-600 rounded-lg text-white placeholder-gray-400 font-mono text-sm focus:border-blue-500 focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Durum</label>
                <select id="editGameStatus" class="w-full px-3 py-2 bg-zinc-800/50 border border-zinc-600 rounded-lg text-white focus:border-blue-500 focus:outline-none">
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                </select>
            </div>

            @php $settings = \App\Models\Ayarlar::getSettings(); @endphp
            @if($settings && (int)($settings->fakeapi ?? 0) === 1)
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">İkincil mi</label>
                    <select id="editGameIkincilmi" class="w-full px-3 py-2 bg-zinc-800/50 border border-zinc-600 rounded-lg text-white focus:border-blue-500 focus:outline-none">
                        <option value="0">Hayır</option>
                        <option value="1">Evet</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">URL</label>
                    <input type="text" id="editGameUrl" placeholder="/game234234jskdfj" class="w-full px-3 py-2 bg-zinc-800/50 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:border-blue-500 focus:outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Vendorcode</label>
                    <input type="text" id="editGameVendorcode" placeholder="slot-pragmatic" class="w-full px-3 py-2 bg-zinc-800/50 border border-zinc-600 rounded-lg text-white placeholder-gray-400 focus:border-blue-500 focus:outline-none">
                </div>
            </div>
            @endif
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" 
                        class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
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

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Edit Game Modal Functions
function editGame(id, name, code, status) {
    document.getElementById('editGameId').value = id;
    document.getElementById('editGameName').value = name;
    document.getElementById('editGameCode').value = code;
    document.getElementById('editGameStatus').value = status;
    // optional fields if exist
    const ikincil = document.getElementById('editGameIkincilmi');
    const urlInp = document.getElementById('editGameUrl');
    if (ikincil) {
        // değerini kartlardan alamıyoruz; düzenle butonunu genişletelim
        ikincil.value = (document.querySelector(`#game-card-${id}`)?.dataset.ikincilmi ?? '0');
    }
    if (urlInp) {
        urlInp.value = (document.querySelector(`#game-card-${id}`)?.dataset.url ?? '');
    }
    const vendorInp = document.getElementById('editGameVendorcode');
    if (vendorInp) {
        vendorInp.value = (document.querySelector(`#game-card-${id}`)?.dataset.vendorcode ?? '');
    }
    document.getElementById('editGameModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editGameModal').classList.add('hidden');
}

// Toggle Game Status
function toggleGameStatus(id) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        alert('CSRF token bulunamadı!');
        return;
    }
    
    fetch(`/admin/games/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Bir hata oluştu!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu!');
    });
}

// Refresh Games
function refreshGames() {
    location.reload();
}

// Edit Game Form Submit
document.getElementById('editGameForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
        alert('CSRF token bulunamadı!');
        return;
    }
    
    const id = document.getElementById('editGameId').value;
    const formData = new FormData();
    formData.append('game_name', document.getElementById('editGameName').value);
    formData.append('game_code', document.getElementById('editGameCode').value);
    formData.append('status', document.getElementById('editGameStatus').value);
    const ikincil = document.getElementById('editGameIkincilmi');
    if (ikincil) formData.append('ikincilmi', ikincil.value);
    const urlInp = document.getElementById('editGameUrl');
    if (urlInp) formData.append('url', urlInp.value);
    const vendorInp = document.getElementById('editGameVendorcode');
    if (vendorInp) formData.append('vendorcode', vendorInp.value);
    
    fetch(`/admin/games/${id}/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            location.reload();
        } else {
            alert('Bir hata oluştu!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu!');
    });
});
</script>
@endsection
