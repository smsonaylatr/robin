<header class="topbar">
    <div class="topbar-left">
        <button class="mobile-menu-toggle" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="search-box">
            <i class="fas fa-search search-box-icon"></i>
            <input type="text" class="search-input" placeholder="Ara...">
            <div class="search-suggestions">
                <div class="suggestion-item">
                    <i class="fas fa-user"></i>
                    <span>Kullanıcı ara</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-coins"></i>
                    <span>İşlem ara</span>
                </div>
                <div class="suggestion-item">
                    <i class="fas fa-gamepad"></i>
                    <span>Oyun ara</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="topbar-right">
        <div class="topbar-item" data-tooltip="Tam Ekran">
            <i class="fas fa-expand"></i>
        </div>
        <div class="topbar-item" data-tooltip="Bildirimler">
            <i class="fas fa-bell"></i>
            <span class="topbar-item-badge">3</span>
        </div>
        <div class="topbar-item" data-tooltip="Mesajlar">
            <i class="fas fa-envelope"></i>
            <span class="topbar-item-badge">5</span>
        </div>
        <div class="user-menu" onclick="toggleUserDropdown()">
            <div class="user-info">
                <div class="user-name">
                    @if(auth()->guard('yonetici')->check())
                        {{ auth()->guard('yonetici')->user()->kullanici_adi }}
                    @else
                        {{ auth()->guard('admin')->user()->username }}
                    @endif
                </div>
                <div class="user-role">
                    @if(auth()->guard('yonetici')->check())
                        Yönetici
                    @else
                        Süper Admin
                    @endif
                </div>
            </div>
            <div class="user-avatar">
                @if(auth()->guard('yonetici')->check())
                    {{ strtoupper(substr(auth()->guard('yonetici')->user()->kullanici_adi, 0, 1)) }}
                @else
                    {{ strtoupper(substr(auth()->guard('admin')->user()->username, 0, 1)) }}
                @endif
            </div>
            <i class="fas fa-chevron-down user-chevron"></i>
            
            <div class="user-dropdown-menu">
                <div class="dropdown-header">
                    <div class="user-avatar-large">
                        @if(auth()->guard('yonetici')->check())
                            {{ strtoupper(substr(auth()->guard('yonetici')->user()->kullanici_adi, 0, 1)) }}
                        @else
                            {{ strtoupper(substr(auth()->guard('admin')->user()->username, 0, 1)) }}
                        @endif
                    </div>
                    <div class="user-details">
                        <div class="user-name-large">
                            @if(auth()->guard('yonetici')->check())
                                {{ auth()->guard('yonetici')->user()->kullanici_adi }}
                            @else
                                {{ auth()->guard('admin')->user()->username }}
                            @endif
                        </div>
                        <div class="user-email">
                            @if(auth()->guard('yonetici')->check())
                                Yönetici Hesabı
                            @else
                                {{ auth()->guard('admin')->user()->email }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    Profil
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    Ayarlar
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-shield-alt"></i>
                    Güvenlik
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
    </div>
</header> 