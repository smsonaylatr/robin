@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="w-full">
    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Total Users Card -->
        <div class="stat-card emerald">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="users" class="stat-card-icon"></i>
                    Toplam Oyuncular
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Aktif: {{ number_format($activeUsers) }}
            </div>
        </div>

        <!-- Total Balance Card -->
        <div class="stat-card amber">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="wallet" class="stat-card-icon"></i>
                    Toplam Bakiye
                </div>
            </div>
            <div class="stat-card-value">₺{{ number_format($totalBalance, 2) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Ortalama: ₺{{ number_format($totalBalance / max($totalUsers, 1), 2) }}
            </div>
        </div>

        <!-- Total Deposits Card -->
        <div class="stat-card blue">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="trending-up" class="stat-card-icon"></i>
                    Toplam Yatırım
                </div>
            </div>
            <div class="stat-card-value">₺{{ number_format($totalDeposits, 2) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Bugün: ₺{{ number_format($todayDeposits, 2) }}
            </div>
        </div>

        <!-- Total Withdrawals Card -->
        <div class="stat-card rose">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="trending-down" class="stat-card-icon"></i>
                    Toplam Çekim
                </div>
            </div>
            <div class="stat-card-value">₺{{ number_format($totalWithdrawals, 2) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Bugün: ₺{{ number_format($todayWithdrawals, 2) }}
            </div>
        </div>

        <!-- Casino Limit Card -->
        <div class="stat-card {{ $remainingLimit >= 0 ? 'green' : 'red' }}">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="casino" class="stat-card-icon"></i>
                    Casino Limitiniz
                </div>
            </div>
            <div class="stat-card-value">₺{{ number_format($remainingLimit, 2) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Limit: ₺{{ number_format($casinoLimit, 2) }} | Bet: ₺{{ number_format($totalBets, 2) }} | Win: ₺{{ number_format($totalWins, 2) }}
            </div>
        </div>

        <!-- Total Games Card -->
        <div class="stat-card purple">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="gamepad-2" class="stat-card-icon"></i>
                    Toplam Oyun
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($totalGames) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Aktif: {{ number_format($activeGames) }}
            </div>
        </div>

        <!-- Total Providers Card -->
        <div class="stat-card cyan">
            <div class="stat-card-header">
                <div class="stat-card-title">
                    <i data-lucide="building" class="stat-card-icon"></i>
                    Toplam Sağlayıcı
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($totalProviders) }}</div>
            <div class="stat-card-description">
                <div class="stat-card-dot"></div>
                Aktif: {{ number_format($activeProviders) }}
            </div>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="tabs-container">
        <div class="tabs-list">
            <button class="tab-button active" data-tab="activities">
                <i data-lucide="activity"></i>
                Son Aktiviteler
            </button>
            <button class="tab-button" data-tab="users">
                <i data-lucide="users"></i>
                Yeni Oyuncular
            </button>
            <button class="tab-button" data-tab="transactions">
                <i data-lucide="credit-card"></i>
                Son İşlemler
            </button>
            <button class="tab-button" data-tab="casino">
                <i data-lucide="gamepad-2"></i>
                Casino
            </button>
        </div>
    </div>

    <!-- Content Cards -->
    <div class="content-card">
        <div class="card-header">
            <div class="card-title">
                <i data-lucide="activity"></i>
                <span id="tab-title">Son Aktiviteler</span>
            </div>
        </div>
        <div class="space-y-4" id="activities-content">
            @foreach($recentActivities as $activity)
            <div class="flex items-center gap-3 p-3 rounded-lg bg-zinc-900/50 border border-zinc-800">
                <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                    <i data-lucide="{{ $activity['icon'] }}" class="w-5 h-5 text-red-500"></i>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-white">{{ $activity['title'] }}</div>
                    <div class="text-xs text-zinc-400">{{ $activity['description'] }}</div>
                </div>
                <div class="text-xs text-zinc-500">{{ $activity['time'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- System Status -->
    <div class="system-status">
        <div class="status-item">
            <div class="status-header">
                <div class="status-name">
                    <i data-lucide="server"></i>
                    Sunucu Durumu
                </div>
                <div class="status-indicator">
                    <i data-lucide="check-circle"></i>
                    Çevrimiçi
                </div>
            </div>
            <div class="status-bar">
                <div class="status-progress" style="width: 95%"></div>
            </div>
        </div>

        <div class="status-item">
            <div class="status-header">
                <div class="status-name">
                    <i data-lucide="database"></i>
                    Veritabanı
                </div>
                <div class="status-indicator">
                    <i data-lucide="check-circle"></i>
                    Aktif
                </div>
            </div>
            <div class="status-bar">
                <div class="status-progress" style="width: 88%"></div>
            </div>
        </div>

        <div class="status-item">
            <div class="status-header">
                <div class="status-name">
                    <i data-lucide="shield"></i>
                    Güvenlik
                </div>
                <div class="status-indicator">
                    <i data-lucide="check-circle"></i>
                    Güvenli
                </div>
            </div>
            <div class="status-bar">
                <div class="status-progress" style="width: 100%"></div>
            </div>
        </div>

        <div class="status-item">
            <div class="status-header">
                <div class="status-name">
                    <i data-lucide="wifi"></i>
                    Ağ Bağlantısı
                </div>
                <div class="status-indicator">
                    <i data-lucide="check-circle"></i>
                    Stabil
                </div>
            </div>
            <div class="status-bar">
                <div class="status-progress" style="width: 92%"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();

// Tab data
const tabData = {
    activities: @json($recentActivities),
    users: @json($newUsers),
    transactions: @json($recentTransactions),
    casino: @json($casinoTransactions)
};

// Tab functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.getAttribute('data-tab');
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        button.classList.add('active');
        
        // Update content
        updateTabContent(tabName);
    });
});

function updateTabContent(tabName) {
    const content = document.getElementById('activities-content');
    const title = document.getElementById('tab-title');
    const data = tabData[tabName];
    
    // Update title
    const titles = {
        activities: 'Son Aktiviteler',
        users: 'Yeni Oyuncular',
        transactions: 'Son İşlemler',
        casino: 'Casino İşlemleri'
    };
    title.textContent = titles[tabName];
    
    // Update content
    content.innerHTML = '';
    
    data.forEach(item => {
        const colorClass = getColorClass(tabName);
        const iconColor = getIconColor(tabName);
        
        const activityHtml = `
            <div class="flex items-center gap-3 p-3 rounded-lg bg-zinc-900/50 border border-zinc-800">
                <div class="w-10 h-10 rounded-full ${colorClass} flex items-center justify-center">
                    <i data-lucide="${item.icon}" class="w-5 h-5 ${iconColor}"></i>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-white">${item.title}</div>
                    <div class="text-xs text-zinc-400">${item.description}</div>
                </div>
                <div class="text-xs text-zinc-500">${item.time}</div>
            </div>
        `;
        content.innerHTML += activityHtml;
    });
    
    // Reinitialize icons
    lucide.createIcons();
}

function getColorClass(tabName) {
    const colors = {
        activities: 'bg-red-500/20',
        users: 'bg-red-500/20',
        transactions: 'bg-red-500/20',
        casino: 'bg-red-500/20'
    };
    return colors[tabName] || 'bg-red-500/20';
}

function getIconColor(tabName) {
    const colors = {
        activities: 'text-red-500',
        users: 'text-red-500',
        transactions: 'text-red-500',
        casino: 'text-red-500'
    };
    return colors[tabName] || 'text-red-500';
}
</script>

<style>
.tab-button {
    transition: all 0.3s ease;
}

.tab-button:hover {
    background: rgba(244, 63, 94, 0.1);
    color: #ef4444;
}

.tab-button.active {
    background: rgba(244, 63, 94, 0.12);
    color: #ef4444;
}
</style>
@endsection
