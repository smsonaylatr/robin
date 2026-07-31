@extends('layouts.admin')

@section('title', 'Oyuncular')

@section('content')
<div class="space-y-4">
    <div>
            <h1 class="text-xl font-bold text-white">Oyuncular</h1>
            <p class="text-gray-400 text-sm">Sistemdeki tüm oyuncuları yönetin</p>
        </div>
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-4">
        
        <div style="min-width: max-content;" class="flex items-center gap-2">
            <a href="{{ route('admin.users.export') }}" class="inline-flex items-center px-3 py-2 text-sm bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 rounded-lg hover:bg-yellow-500/20 transition-colors">
                <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                Excel İndir
            </a>
            <button style="Display:flex" class="px-3 py-2 text-sm bg-green-500/10 border border-green-500/20 text-green-500 rounded-lg hover:bg-green-500/20 transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1"></i>
                Yeni Oyuncu
            </button>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-4">
        <form method="GET" action="{{ route('admin.users') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="relative md:col-span-2">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="text" name="search" class="w-full h-9 bg-zinc-900/50 border border-zinc-600/50 rounded-lg pl-10 pr-3 text-white text-sm placeholder-gray-500 focus:border-blue-500 focus:outline-none" placeholder="Kullanıcı adı, email veya ad" value="{{ request('search') }}">
                </div>
                <div>
                    <select name="status" class="w-full h-9 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">Tüm Durumlar</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <div>
                    <select name="sort" class="w-full h-9 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white text-sm focus:border-blue-500 focus:outline-none">
                        <option value="id_desc">En Yeni</option>
                        <option value="id_asc">En Eski</option>
                        <option value="name_asc">İsim A-Z</option>
                        <option value="name_desc">İsim Z-A</option>
                        <option value="balance_desc">Bakiye Yüksek</option>
                        <option value="balance_asc">Bakiye Düşük</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button style="display: inline-flex;" type="submit" class="flex-1 px-2 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-md hover:bg-blue-400 transition-colors">
                        <i data-lucide="filter" class="w-3 h-3 mr-1"></i>
                        Filtrele
                    </button>
                    <a href="{{ route('admin.users') }}" class="px-2 py-1.5 bg-zinc-700 text-white rounded-md hover:bg-zinc-600 transition-colors">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Toplam Oyuncu</p>
                    <p class="text-lg font-bold text-white mt-1">{{ $users->total() }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4 text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Aktif Oyuncu</p>
                    <p class="text-lg font-bold text-green-400 mt-1">{{ $users->where('durum', 1)->count() }}</p>
                </div>
                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="w-4 h-4 text-green-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Bu Ay Kayıt</p>
                    <p class="text-lg font-bold text-purple-400 mt-1">{{ $users->where('kayit_tarih', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar" class="w-4 h-4 text-purple-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Toplam Bakiye</p>
                    <p class="text-lg font-bold text-yellow-400 mt-1">{{ number_format($users->sum('bakiye'), 2) }}</p>
                </div>
                <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-zinc-800/30 border border-zinc-700/50 rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Oyuncu Listesi</h2>
            <div class="text-xs text-gray-400">
                {{ $users->firstItem() }}-{{ $users->lastItem() }} / {{ $users->total() }} oyuncu
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Oyuncu</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İletişim</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Bakiye</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Kayıt Tarihi</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Son Giriş</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Durum</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-300">#{{ $user->id }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                    <span class="text-yellow-500 font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white">{{ $user->email }}</div>
                                <div class="text-gray-400">{{ $user->telefon ?: 'Telefon yok' }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-sm">
                                <div class="text-white font-medium">{{ $user->parabirimi }}{{ number_format($user->bakiye, 2) }}</div>
                                <div class="text-gray-400">{{ $user->parabirimi }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            {{ $user->kayit_tarih->format('d.m.Y H:i') }}
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-300">
                            @if($user->songiris)
                                {{ \Carbon\Carbon::parse($user->songiris)->format('d.m.Y H:i') }}
                            @else
                                <span class="text-gray-500">Hiç giriş yapmamış</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($user->durum == 1)
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
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.user.details', $user->id) }}" class="p-2 text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/10 rounded-lg transition-colors" title="Detaylar">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->telefon }}', {{ $user->bakiye }}, {{ $user->durum }}, {{ $user->aff ?? 0 }})" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Düzenle">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($user->durum == 1)
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Pasif Yap" onclick="return confirm('Kullanıcıyı pasif yapmak istediğinizden emin misiniz?')">
                                            <i data-lucide="user-x" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-colors" title="Aktif Yap" onclick="return confirm('Kullanıcıyı aktif yapmak istediğinizden emin misiniz?')">
                                            <i data-lucide="user-check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-zinc-700">
            <div class="text-sm text-gray-400">
                {{ $users->firstItem() }}-{{ $users->lastItem() }} / {{ $users->total() }} oyuncu
            </div>
            <div class="flex items-center gap-2">
                @if($users->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Önceki</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Önceki</a>
                @endif
                
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="px-3 py-2 text-black bg-yellow-500 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-white bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">Sonraki</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-zinc-800 rounded-lg cursor-not-allowed">Sonraki</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-gradient-to-br from-zinc-900 to-zinc-800 border border-zinc-700/70 rounded-xl p-6 w-full max-w-3xl mx-auto max-h-[90vh] overflow-y-auto shadow-2xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-cog" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Oyuncu Düzenle</h3>
                    <p class="text-gray-400 text-sm">Kullanıcı bilgilerini güncelleyin</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="p-2 text-gray-400 hover:text-white hover:bg-zinc-800/50 rounded-lg transition-all duration-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="editUserForm" method="POST" class="space-y-4" action="">
            @csrf
            <input type="hidden" id="editUserId" name="user_id">
            
            <!-- User Avatar & Basic Info -->
            <div class="relative overflow-hidden bg-gradient-to-r from-zinc-800/80 to-zinc-700/50 backdrop-blur-sm rounded-lg p-4 border border-zinc-600/30">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5"></div>
                <div class="relative flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg border-2 border-white/10">
                            <span class="text-white font-bold text-xl" id="editUserAvatar">U</span>
                        </div>
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-zinc-900 flex items-center justify-center">
                            <i data-lucide="user" class="w-3 h-3 text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-bold text-lg mb-1" id="editUserDisplayName">Kullanıcı Adı</h4>
                        <p class="text-blue-300/80 font-medium mb-2 text-sm" id="editUserDisplayEmail">email@example.com</p>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-500/15 text-green-400 border border-green-500/30" id="editUserStatusBadge">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></div>
                                Aktif
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-zinc-700/50 text-gray-300 border border-zinc-600/50">
                                <i data-lucide="hash" class="w-3 h-3 mr-1"></i>
                                <span id="editUserDisplayId">-</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Fields -->
            <div class="space-y-4">
                <div class="bg-zinc-800/30 rounded-lg p-4 border border-zinc-700/50">
                    <h4 class="text-white font-semibold mb-3 flex items-center text-sm">
                        <i data-lucide="user-circle" class="w-4 h-4 mr-2 text-blue-400"></i>
                        Kişisel Bilgiler
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="flex items-center text-xs font-medium text-gray-300">
                                <i data-lucide="user" class="w-3 h-3 mr-1 text-blue-400"></i>
                                Ad Soyad
                            </label>
                            <input type="text" id="editUserName" name="name" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm" required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="flex items-center text-xs font-medium text-gray-300">
                                <i data-lucide="mail" class="w-3 h-3 mr-1 text-blue-400"></i>
                                Email
                            </label>
                            <input type="email" id="editUserEmail" name="email" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm" required>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="flex items-center text-xs font-medium text-gray-300">
                                <i data-lucide="phone" class="w-3 h-3 mr-1 text-blue-400"></i>
                                Telefon
                            </label>
                            <input type="text" id="editUserPhone" name="telefon" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm" placeholder="05XX XXX XX XX">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="flex items-center text-xs font-medium text-gray-300">
                                <i data-lucide="wallet" class="w-3 h-3 mr-1 text-green-400"></i>
                                Bakiye (TL)
                            </label>
                            <input type="number" id="editUserBalance" name="bakiye" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Fields -->
            <div class="bg-zinc-800/30 rounded-lg p-4 border border-zinc-700/50">
                <h4 class="text-white font-semibold mb-3 flex items-center text-sm">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-purple-400"></i>
                    Güvenlik Ayarları
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="flex items-center text-xs font-medium text-gray-300">
                            <i data-lucide="key" class="w-3 h-3 mr-1 text-purple-400"></i>
                            Yeni Şifre
                        </label>
                        <input type="password" id="editUserPassword" name="yeni_sifre" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm" placeholder="Yeni şifre girin">
                    </div>
                    <div class="space-y-1">
                        <label class="flex items-center text-xs font-medium text-gray-300">
                            <i data-lucide="key" class="w-3 h-3 mr-1 text-purple-400"></i>
                            Şifre Tekrar
                        </label>
                        <input type="password" id="editUserPasswordConfirm" name="yeni_sifre_tekrar" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm" placeholder="Şifreyi tekrar girin">
                    </div>
                </div>
                <div class="mt-3 p-2 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <p class="text-yellow-400 text-xs flex items-center">
                        <i data-lucide="info" class="w-3 h-3 mr-1"></i>
                        Şifre değiştirmek için her iki alanı da doldurun
                    </p>
                </div>
            </div>
            
            <!-- Status & Permissions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-zinc-800/30 rounded-lg p-4 border border-zinc-700/50">
                    <h4 class="text-white font-semibold mb-3 flex items-center text-sm">
                        <i data-lucide="shield" class="w-4 h-4 mr-2 text-orange-400"></i>
                        Hesap Durumu
                    </h4>
                    <select id="editUserStatus" name="durum" class="w-full h-10 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500/20 transition-all duration-200 hover:border-zinc-500/70 text-sm">
                        <option value="1">🟢 Aktif - Hesap kullanılabilir</option>
                        <option value="0">🔴 Pasif - Hesap askıya alınmış</option>
                    </select>
                </div>
                
                <div class="bg-zinc-800/30 rounded-lg p-4 border border-zinc-700/50">
                    <h4 class="text-white font-semibold mb-3 flex items-center text-sm">
                        <i data-lucide="settings" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        İzinler
                    </h4>
                    <div class="space-y-2">
                        <label class="flex items-center p-2 bg-zinc-900/30 rounded-lg border border-zinc-600/30">
                            <input type="checkbox" class="w-3 h-3 text-indigo-500 bg-zinc-800 border-zinc-600 rounded focus:ring-indigo-500 focus:ring-1" checked disabled>
                            <span class="ml-2 text-xs text-gray-300 flex items-center">
                                <i data-lucide="trophy" class="w-3 h-3 mr-1 text-indigo-400"></i>
                                Spor Bahisleri
                            </span>
                        </label>
                        <label class="flex items-center p-2 bg-zinc-900/30 rounded-lg border border-zinc-600/30">
                            <input type="checkbox" class="w-3 h-3 text-indigo-500 bg-zinc-800 border-zinc-600 rounded focus:ring-indigo-500 focus:ring-1" checked disabled>
                            <span class="ml-2 text-xs text-gray-300 flex items-center">
                                <i data-lucide="gamepad-2" class="w-3 h-3 mr-1 text-indigo-400"></i>
                                Casino Oyunları
                            </span>
                        </label>
                        <label class="flex items-center p-2 bg-zinc-900/30 rounded-lg border border-zinc-600/30">
                            <input type="checkbox" class="w-3 h-3 text-indigo-500 bg-zinc-800 border-zinc-600 rounded focus:ring-indigo-500 focus:ring-1" checked disabled>
                            <span class="ml-2 text-xs text-gray-300 flex items-center">
                                <i data-lucide="banknote" class="w-3 h-3 mr-1 text-indigo-400"></i>
                                Para Çekme
                            </span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-2 bg-zinc-900/30 rounded-lg border border-zinc-600/30">
                                <input type="checkbox" id="editUserAff" name="aff" value="1" class="w-3 h-3 text-amber-500 bg-zinc-800 border-zinc-600 rounded focus:ring-amber-500 focus:ring-1">
                                <span class="ml-2 text-xs text-gray-300 flex items-center">
                                    <i data-lucide="network" class="w-3 h-3 mr-1 text-amber-400"></i>
                                    Affiliate Yetkisi
                                </span>
                            </label>
                            <div id="affRateContainer" class="hidden grid grid-cols-3 gap-2 items-center">
                                <label for="editUserAffRate" class="text-xs text-gray-300 col-span-1">Affiliate Oranı (%)</label>
                                <input type="number" id="editUserAffRate" name="afforani" min="0" max="100" step="0.01" class="col-span-2 h-9 bg-zinc-900/50 border border-zinc-600/50 rounded-lg px-3 text-white placeholder-gray-500 focus:border-amber-500 focus:outline-none text-sm" placeholder="ör: 10">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-zinc-800/30 rounded-lg p-4 border border-zinc-700/50">
                <h4 class="text-white font-semibold mb-3 flex items-center text-sm">
                    <i data-lucide="zap" class="w-4 h-4 mr-2 text-yellow-400"></i>
                    Hızlı İşlemler
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <button type="button" onclick="showBalanceModal('add')" class="group flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-500/10 to-blue-600/10 text-blue-400 border border-blue-500/30 rounded-lg hover:from-blue-500/20 hover:to-blue-600/20 hover:border-blue-400/50 transition-all duration-200 hover:scale-105 text-sm">
                        <i data-lucide="plus-circle" class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform"></i>
                        Bakiye Ekle
                    </button>
                    <button type="button" onclick="showBalanceModal('subtract')" class="group flex items-center justify-center px-3 py-2 bg-gradient-to-r from-orange-500/10 to-red-500/10 text-orange-400 border border-orange-500/30 rounded-lg hover:from-orange-500/20 hover:to-red-500/20 hover:border-orange-400/50 transition-all duration-200 hover:scale-105 text-sm">
                        <i data-lucide="minus-circle" class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform"></i>
                        Bakiye Çıkar
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3 pt-6 border-t border-zinc-700/50">
                <button type="button" onclick="closeEditModal()" class="flex-1 group flex items-center justify-center px-4 py-3 bg-zinc-800/80 text-gray-300 border border-zinc-600/50 rounded-lg hover:bg-zinc-700/80 hover:text-white hover:border-zinc-500/70 transition-all duration-200 hover:scale-[1.02] text-sm">
                    <i data-lucide="x" class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform"></i>
                    İptal
                </button>
                <button type="submit" class="flex-1 group flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-400 hover:to-purple-500 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-[1.02] text-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform"></i>
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Balance Modal -->
<div id="balanceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 id="balanceModalTitle" class="text-xl font-bold text-white">Bakiye İşlemi</h3>
            <button onclick="closeBalanceModal()" class="p-2 text-gray-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="balanceForm" class="space-y-4">
            @csrf
            <input type="hidden" id="balanceUserId" name="user_id">
            <input type="hidden" id="balanceAction" name="action">
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Miktar (TL)</label>
                <input type="number" id="balanceAmount" name="amount" class="w-full h-12 bg-zinc-800 border border-zinc-700 rounded-lg px-4 text-white focus:border-yellow-500 focus:outline-none transition-colors" step="0.01" min="0.01" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Açıklama</label>
                <textarea id="balanceDescription" name="description" rows="3" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:outline-none transition-colors" placeholder="İşlem açıklaması (isteğe bağlı)"></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeBalanceModal()" class="flex-1 px-4 py-3 bg-zinc-800 text-white rounded-lg hover:bg-zinc-700 transition-colors">
                    İptal
                </button>
                <button type="submit" id="balanceSubmitBtn" class="flex-1 px-4 py-3 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentUserId = null;

function editUser(userId, name, email, telefon, bakiye, durum, aff) {
    currentUserId = userId;
    
    // Set form action
    document.getElementById('editUserForm').action = `/admin/users/${userId}/update`;
    
    // Form fields
    document.getElementById('editUserId').value = userId;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserPhone').value = telefon;
    document.getElementById('editUserBalance').value = bakiye;
    document.getElementById('editUserStatus').value = durum;
    // Affiliate checkbox
    const affChk = document.getElementById('editUserAff');
    if (affChk) affChk.checked = (parseInt(aff) === 1);
    // Affiliate oranı alanını göster/gizle
    const affRateWrap = document.getElementById('affRateContainer');
    if (affRateWrap) {
        if (parseInt(aff) === 1) {
            affRateWrap.classList.remove('hidden');
        } else {
            affRateWrap.classList.add('hidden');
            document.getElementById('editUserAffRate').value = '';
        }
    }

    // Clear password fields
    document.getElementById('editUserPassword').value = '';
    document.getElementById('editUserPasswordConfirm').value = '';
    
    // Display fields
    document.getElementById('editUserDisplayId').textContent = userId;
    document.getElementById('editUserDisplayName').textContent = name;
    document.getElementById('editUserDisplayEmail').textContent = email;
    document.getElementById('editUserAvatar').textContent = name.charAt(0).toUpperCase();
    
    // Status badge
    const statusBadge = document.getElementById('editUserStatusBadge');
    if (durum == 1) {
        statusBadge.innerHTML = '<div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>Aktif';
        statusBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-500/15 text-green-400 border border-green-500/30 shadow-sm';
    } else {
        statusBadge.innerHTML = '<div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>Pasif';
        statusBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-500/15 text-red-400 border border-red-500/30 shadow-sm';
    }
    
    // Show modal
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserModal').classList.add('flex');
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
    document.getElementById('editUserModal').classList.remove('flex');
}

function showBalanceModal(action) {
    if (!currentUserId) {
        alert('Lütfen önce bir kullanıcı seçin!');
        return;
    }
    
    document.getElementById('balanceUserId').value = currentUserId;
    document.getElementById('balanceAction').value = action;
    document.getElementById('balanceAmount').value = '';
    document.getElementById('balanceDescription').value = '';
    
    const modal = document.getElementById('balanceModal');
    const title = document.getElementById('balanceModalTitle');
    const submitBtn = document.getElementById('balanceSubmitBtn');
    
    if (action === 'add') {
        title.textContent = 'Bakiye Ekle';
        submitBtn.textContent = 'Bakiye Ekle';
        submitBtn.className = 'flex-1 px-4 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-400 transition-colors';
    } else {
        title.textContent = 'Bakiye Çıkar';
        submitBtn.textContent = 'Bakiye Çıkar';
        submitBtn.className = 'flex-1 px-4 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-400 transition-colors';
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeBalanceModal() {
    document.getElementById('balanceModal').classList.add('hidden');
    document.getElementById('balanceModal').classList.remove('flex');
}

// Balance form submission
document.getElementById('balanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = formData.get('action');
    
    // CSRF token'ı form içinden al
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    fetch(`/admin/users/${formData.get('user_id')}/balance`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBalanceModal();
            
            // Başarı mesajı göster
            showSuccessMessage(data.message);
            
            // Bakiye alanını güncelle
            const balanceInput = document.getElementById('editUserBalance');
            if (balanceInput) {
                balanceInput.value = data.new_balance;
            }
            
            // Form'u temizle
            document.getElementById('balanceAmount').value = '';
            document.getElementById('balanceDescription').value = '';
            
        } else {
            showErrorMessage(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Bir hata oluştu!');
    });
});

// Başarı mesajı göster
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(successDiv);
    
    // Animasyon ile göster
    setTimeout(() => {
        successDiv.classList.remove('translate-x-full');
    }, 100);
    
    // 3 saniye sonra kaldır
    setTimeout(() => {
        successDiv.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(successDiv);
        }, 300);
    }, 3000);
    
    // Lucide icon'ları yenile
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Hata mesajı göster
function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(errorDiv);
    
    // Animasyon ile göster
    setTimeout(() => {
        errorDiv.classList.remove('translate-x-full');
    }, 100);
    
    // 3 saniye sonra kaldır
    setTimeout(() => {
        errorDiv.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(errorDiv);
        }, 300);
    }, 3000);
    
    // Lucide icon'ları yenile
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function toggleUserStatus(userId, status) {
    if (confirm('Kullanıcı durumunu değiştirmek istediğinizden emin misiniz?')) {
        // AJAX ile durum değiştirme
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Modal dışına tıklandığında kapat
document.getElementById('editUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Affiliate checkbox toggle - oran alanını aç/kapat
document.addEventListener('DOMContentLoaded', function() {
    const affChk = document.getElementById('editUserAff');
    const affRateWrap = document.getElementById('affRateContainer');
    if (affChk && affRateWrap) {
        affChk.addEventListener('change', function() {
            if (this.checked) {
                affRateWrap.classList.remove('hidden');
            } else {
                affRateWrap.classList.add('hidden');
                const rate = document.getElementById('editUserAffRate');
                if (rate) rate.value = '';
            }
        });
    }
});
</script>
@endpush
