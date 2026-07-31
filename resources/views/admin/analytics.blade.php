@extends('layouts.admin')

@section('title', 'Analytics - Casino Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">Analytics Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item active">Analytics</li>
        </ol>
    </nav>
</div>

<!-- Özet İstatistikler -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Toplam Gelir</h6>
                        <h3 class="mb-0">₺2.847.392</h3>
                        <small class="opacity-75">Bu ay</small>
                    </div>
                    <div class="stats-card-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-light bg-opacity-20">
                        <i class="fas fa-arrow-up me-1"></i>+12.5%
                    </span>
                    <small class="ms-2 opacity-75">Geçen aya göre</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Aktif Oyuncular</h6>
                        <h3 class="mb-0">8.547</h3>
                        <small class="opacity-75">Son 24 saat</small>
                    </div>
                    <div class="stats-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-light bg-opacity-20">
                        <i class="fas fa-arrow-up me-1"></i>+8.2%
                    </span>
                    <small class="ms-2 opacity-75">Dün'e göre</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Oyun Oturumları</h6>
                        <h3 class="mb-0">24.891</h3>
                        <small class="opacity-75">Bugün</small>
                    </div>
                    <div class="stats-card-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-light bg-opacity-20">
                        <i class="fas fa-arrow-up me-1"></i>+15.7%
                    </span>
                    <small class="ms-2 opacity-75">Ortalamaya göre</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Dönüşüm Oranı</h6>
                        <h3 class="mb-0">%3.42</h3>
                        <small class="opacity-75">Ziyaretçi → Oyuncu</small>
                    </div>
                    <div class="stats-card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-light bg-opacity-20">
                        <i class="fas fa-arrow-down me-1"></i>-2.1%
                    </span>
                    <small class="ms-2 opacity-75">Geçen haftaya göre</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafikler -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>
                    Gelir Trendi
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm active">7 Gün</button>
                    <button type="button" class="btn btn-outline-primary btn-sm">30 Gün</button>
                    <button type="button" class="btn btn-outline-primary btn-sm">90 Gün</button>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Oyun Kategorileri
                </h5>
            </div>
            <div class="card-body">
                <div id="gamesCategoryChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Detaylı Tablolar -->
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    En Popüler Oyunlar
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Oyun</th>
                                <th>Oynama Sayısı</th>
                                <th>Gelir</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-dice text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Sweet Bonanza</div>
                                            <small class="text-muted">Slot</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">12.847</span>
                                    <br><small class="text-muted">oynama</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">₺284.592</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>+18%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-spade text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Blackjack VIP</div>
                                            <small class="text-muted">Kart Oyunu</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">8.234</span>
                                    <br><small class="text-muted">oynama</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">₺195.847</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>+12%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-danger bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-circle text-danger"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Rulet Deluxe</div>
                                            <small class="text-muted">Rulet</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">6.891</span>
                                    <br><small class="text-muted">oynama</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">₺156.234</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-minus me-1"></i>0%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-gem text-info"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Gates of Olympus</div>
                                            <small class="text-muted">Slot</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">5.672</span>
                                    <br><small class="text-muted">oynama</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">₺134.891</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>+25%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-secondary bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-coins text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Crazy Time</div>
                                            <small class="text-muted">Canlı Oyun</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">4.523</span>
                                    <br><small class="text-muted">oynama</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">₺98.765</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-arrow-down me-1"></i>-5%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Gerçek Zamanlı Aktivite
                </h5>
            </div>
            <div class="card-body">
                <div class="activity-timeline" style="max-height: 400px; overflow-y: auto;">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Yeni Üye Kaydı</div>
                            <div class="activity-desc">mehmet_34 adlı kullanıcı sisteme kaydoldu</div>
                            <div class="activity-time">2 dakika önce</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-primary">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Büyük Kazanç</div>
                            <div class="activity-desc">ali_gamer Sweet Bonanza'da ₺15.000 kazandı!</div>
                            <div class="activity-time">5 dakika önce</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Para Yatırma</div>
                            <div class="activity-desc">zeynep_21 hesabına ₺500 yatırdı</div>
                            <div class="activity-time">8 dakika önce</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Oyun Başlatıldı</div>
                            <div class="activity-desc">can_poker Blackjack VIP oyununu başlattı</div>
                            <div class="activity-time">12 dakika önce</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Güvenlik Uyarısı</div>
                            <div class="activity-desc">Şüpheli giriş denemesi tespit edildi</div>
                            <div class="activity-time">15 dakika önce</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Para Çekme</div>
                            <div class="activity-desc">fatma_55 ₺2.500 çekme talebinde bulundu</div>
                            <div class="activity-time">18 dakika önce</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-primary">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Turnuva Kazananı</div>
                            <div class="activity-desc">poker_king haftalık turnuvayı kazandı</div>
                            <div class="activity-time">25 dakika önce</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performans Metrikleri -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Performans Metrikleri
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 text-primary mb-2">2.4s</div>
                            <div class="text-muted small">Ortalama Sayfa Yükleme</div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 text-success mb-2">99.8%</div>
                            <div class="text-muted small">Sistem Uptime</div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 99%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 text-warning mb-2">847</div>
                            <div class="text-muted small">Eşzamanlı Kullanıcı</div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 text-info mb-2">156ms</div>
                            <div class="text-muted small">API Yanıt Süresi</div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gelir Trendi Grafiği
var revenueOptions = {
    series: [{
        name: 'Gelir',
        data: [31000, 40000, 28000, 51000, 42000, 82000, 56000]
    }],
    chart: {
        height: 350,
        type: 'area',
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 3
    },
    colors: ['#6366f1'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.1,
        }
    },
    xaxis: {
        categories: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz']
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return "₺" + val.toLocaleString('tr-TR')
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "₺" + val.toLocaleString('tr-TR')
            }
        }
    }
};

var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
revenueChart.render();

// Oyun Kategorileri Grafiği
var gamesCategoryOptions = {
    series: [44, 25, 18, 13],
    chart: {
        height: 350,
        type: 'donut',
    },
    labels: ['Slot Oyunları', 'Kart Oyunları', 'Rulet', 'Canlı Casino'],
    colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
    legend: {
        position: 'bottom'
    },
    plotOptions: {
        pie: {
            donut: {
                size: '70%'
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + "%"
            }
        }
    }
};

var gamesCategoryChart = new ApexCharts(document.querySelector("#gamesCategoryChart"), gamesCategoryOptions);
gamesCategoryChart.render();

// Gerçek zamanlı veri güncellemesi simülasyonu
setInterval(function() {
    // Burada gerçek zamanlı veriler güncellenebilir
    console.log('Analytics verileri güncellendi');
}, 30000); // 30 saniyede bir güncelle
</script>
@endpush

@push('styles')
<style>
.stats-card-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}

.activity-timeline {
    position: relative;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    position: relative;
}

.activity-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 24px;
    top: 48px;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: linear-gradient(180deg, var(--gray-300) 0%, transparent 100%);
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    margin-right: 1rem;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.activity-content {
    flex: 1;
    padding-top: 2px;
}

.activity-title {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.activity-desc {
    color: var(--gray-600);
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--gray-500);
    font-size: 0.6875rem;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}

.progress {
    border-radius: 8px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 8px;
    transition: width 0.6s ease;
}
</style>
@endpush
@endsection
