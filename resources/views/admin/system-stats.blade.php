@extends('layouts.admin')

@section('title', 'Sistem İstatistikleri')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Sistem İstatistikleri</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item active">Sistem İstatistikleri</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam Üye</p>
                            <h4 class="mb-0">{{ number_format($totalUsers) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-user font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Aktif Üye</p>
                            <h4 class="mb-0">{{ number_format($activeUsers) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-check-circle font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam Bakiye</p>
                            <h4 class="mb-0">₺{{ number_format($totalBalance, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-wallet font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam İşlem</p>
                            <h4 class="mb-0">{{ number_format($totalTransactions) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-transfer font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam Yatırım</p>
                            <h4 class="mb-0">₺{{ number_format($totalDeposits, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-up-arrow font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam Çekim</p>
                            <h4 class="mb-0">₺{{ number_format($totalWithdrawals, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-danger align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-down-arrow font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam Oyun</p>
                            <h4 class="mb-0">{{ number_format($totalGames) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-game font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Toplam Sağlayıcı</p>
                            <h4 class="mb-0">{{ number_format($totalProviders) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-server font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Bu Ay Kayıt</p>
                            <h4 class="mb-0">{{ number_format($monthlyUsers) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-user-plus font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Bu Ay Yatırım</p>
                            <h4 class="mb-0">₺{{ number_format($monthlyDeposits, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-up-arrow font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Bu Ay Çekim</p>
                            <h4 class="mb-0">₺{{ number_format($monthlyWithdrawals, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-danger align-self-center overflow-hidden">
                                <span class="avatar-title">
                                    <i class="bx bx-down-arrow font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Aylık İstatistikler</h4>
                </div>
                <div class="card-body">
                    <div id="monthlyChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Kullanıcı Dağılımı</h4>
                </div>
                <div class="card-body">
                    <div id="userDistributionChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Stats Tables -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">En Yüksek Bakiyeli Kullanıcılar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>Bakiye</th>
                                    <th>Kayıt Tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Admin::orderBy('bakiye', 'desc')->limit(10)->get() as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $user->parabirimi }}{{ number_format($user->bakiye, 2) }}</span>
                                    </td>
                                    <td>{{ $user->kayit_tarih->format('d.m.Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">En Aktif Kullanıcılar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>İşlem Sayısı</th>
                                    <th>Toplam Tutar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Transaction::selectRaw('user_id, COUNT(*) as transaction_count, SUM(amount) as total_amount')->groupBy('user_id')->orderBy('transaction_count', 'desc')->limit(10)->get() as $stat)
                                @php $user = \App\Models\Admin::find($stat->user_id) @endphp
                                @if($user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat->transaction_count }}</span>
                                    </td>
                                    <td>₺{{ number_format($stat->total_amount, 2) }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Monthly Statistics Chart
var monthlyOptions = {
    series: [{
        name: 'Yeni Üyeler',
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125, 150, 180, 200]
    }, {
        name: 'Yatırımlar',
        data: [10000, 15000, 12000, 20000, 18000, 25000, 30000, 35000, 40000, 45000, 50000, 55000]
    }],
    chart: {
        height: 300,
        type: 'area',
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        categories: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık']
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    },
};

var monthlyChart = new ApexCharts(document.querySelector("#monthlyChart"), monthlyOptions);
monthlyChart.render();

// User Distribution Chart
var userDistributionOptions = {
    series: [{{ $activeUsers }}, {{ $totalUsers - $activeUsers }}],
    chart: {
        type: 'donut',
        height: 300
    },
    labels: ['Aktif Kullanıcılar', 'Pasif Kullanıcılar'],
    colors: ['#28a745', '#dc3545'],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

var userDistributionChart = new ApexCharts(document.querySelector("#userDistributionChart"), userDistributionOptions);
userDistributionChart.render();
</script>
@endpush 