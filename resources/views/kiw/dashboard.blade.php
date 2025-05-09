@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard Super Admin</h1>
        <div>
            <span class="badge bg-danger p-2">KIW Control Panel</span>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--primary-color);">
                <div class="card-body">
                    <div class="card-title">Total Pengguna</div>
                    <div class="card-value">{{ $totalUsers ?? 0 }}</div>
                    <i class="fas fa-users card-icon" style="color: var(--primary-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--success-color);">
                <div class="card-body">
                    <div class="card-title">Total Kompetisi</div>
                    <div class="card-value">{{ $totalCompetitions ?? 0 }}</div>
                    <i class="fas fa-trophy card-icon" style="color: var(--success-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--warning-color);">
                <div class="card-body">
                    <div class="card-title">Total Pendapatan</div>
                    <div class="card-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    <i class="fas fa-money-bill-wave card-icon" style="color: var(--warning-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--info-color);">
                <div class="card-body">
                    <div class="card-title">Total Penilaian</div>
                    <div class="card-value">{{ $totalEvaluations ?? 0 }}</div>
                    <i class="fas fa-star card-icon" style="color: var(--info-color);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-line me-2"></i>Tren Pendaftaran & Pendapatan</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Tahun 2025
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="chartDropdown">
                            <li><a class="dropdown-item active" href="#">Tahun 2025</a></li>
                            <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                            <li><a class="dropdown-item" href="#">Last 3 Months</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-container" style="position: relative; height: 300px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-users me-2"></i><span>Distribusi Pengguna</span>
                </div>
                <div class="card-body">
                    <div id="pie-chart-container" style="position: relative; height: 300px;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user-plus me-2"></i>Pengguna Terbaru</span>
                    <a href="{{ route('kiw.users.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers ?? [] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'kiw')
                                            <span class="badge bg-danger">Super Admin</span>
                                        @elseif($user->role == 'admin')
                                            <span class="badge bg-primary">Admin</span>
                                        @elseif($user->role == 'juri')
                                            <span class="badge bg-success">Juri</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Peserta</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada pengguna</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('kiw.users.create') }}" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Pengguna Baru
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-trophy me-2"></i>Kompetisi Aktif</span>
                    <a href="{{ route('kiw.competitions.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Peserta</th>
                                    <th>Tanggal Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeCompetitions ?? [] as $competition)
                                <tr>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                            {{ $competition->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ method_exists($competition, 'getRegistrationsCount') ? $competition->getRegistrationsCount() : '0' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $competition->registration_end && !is_string($competition->registration_end) ? $competition->registration_end->format('d M Y') : 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada kompetisi aktif</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('kiw.competitions.create') }}" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Kompetisi Baru
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-money-bill-wave me-2"></i>Pembayaran Terbaru</span>
                    <a href="{{ route('kiw.payments.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tim</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestPayments ?? [] as $payment)
                                <tr>
                                    <td>#{{ $payment->id }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                            {{ $payment->team_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">Rp {{ number_format($payment->total_price ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $payment->updated_at && !is_string($payment->updated_at) ? $payment->updated_at->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada pembayaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('kiw.reports.index') }}" class="btn btn-sm btn-info w-100">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Keuangan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-server me-2"></i>Performa Sistem</span>
                    <button class="btn btn-sm btn-outline-secondary" id="refresh-system-info">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-server fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Server Load</div>
                                            <div class="fw-bold">32%</div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: 32%;" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-memory fa-2x text-success"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Memory Usage</div>
                                            <div class="fw-bold">45%</div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 45%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-database fa-2x text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Database Size</div>
                                            <div class="fw-bold">128 MB</div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-hdd fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Disk Usage</div>
                                            <div class="fw-bold">23%</div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 23%;" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <span class="text-muted">Last updated: {{ now()->format('d M Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <a href="{{ route('kiw.users.index') }}" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                Kelola Pengguna
                            </a>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <a href="{{ route('kiw.competitions.index') }}" class="btn btn-success w-100 py-3">
                                <i class="fas fa-trophy fa-2x mb-2 d-block"></i>
                                Kelola Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <a href="{{ route('kiw.evaluations.index') }}" class="btn btn-warning w-100 py-3 text-white">
                                <i class="fas fa-star fa-2x mb-2 d-block"></i>
                                Kelola Penilaian
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('kiw.settings.index') }}" class="btn btn-info w-100 py-3">
                                <i class="fas fa-cog fa-2x mb-2 d-block"></i>
                                Pengaturan Sistem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration for handling empty data
        const trendLabels = {!! json_encode($trendChartData['labels'] ?? []) !!};
        const registrationsData = {!! json_encode($trendChartData['registrations'] ?? []) !!};
        const revenueData = {!! json_encode($trendChartData['revenue'] ?? []) !!};
        const adminCount = {{ $userDistribution['admin'] ?? 0 }};
        const juriCount = {{ $userDistribution['juri'] ?? 0 }};
        const pesertaCount = {{ $userDistribution['peserta'] ?? 0 }};
        const kiwCount = {{ $userDistribution['kiw'] ?? 0 }};
        
        // Check if chart containers exist
        const trendCtxElement = document.getElementById('trendChart');
        const userCtxElement = document.getElementById('userDistributionChart');

        // Registration & Revenue Trend Chart
        if (trendCtxElement) {
            const trendCtx = trendCtxElement.getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendLabels.length ? trendLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Pendaftaran',
                            data: registrationsData.length ? registrationsData : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            borderColor: 'rgba(46, 134, 222, 1)',
                            backgroundColor: 'rgba(46, 134, 222, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y',
                            fill: true
                        },
                        {
                            label: 'Pendapatan (Rp)',
                            data: revenueData.length ? revenueData : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            borderColor: 'rgba(243, 156, 18, 1)',
                            backgroundColor: 'rgba(243, 156, 18, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y1',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 1) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    } else {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Pendaftaran'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Pendapatan (Rp)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }
        
        // User Distribution Chart
        if (userCtxElement) {
            const userCtx = userCtxElement.getContext('2d');
            const userChart = new Chart(userCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Juri', 'Peserta', 'Super Admin'],
                    datasets: [{
                        data: [adminCount, juriCount, pesertaCount, kiwCount],
                        backgroundColor: [
                            'rgba(46, 134, 222, 0.8)',
                            'rgba(39, 174, 96, 0.8)',
                            'rgba(243, 156, 18, 0.8)',
                            'rgba(231, 76, 60, 0.8)'
                        ],
                        borderColor: [
                            'rgba(46, 134, 222, 1)',
                            'rgba(39, 174, 96, 1)',
                            'rgba(243, 156, 18, 1)',
                            'rgba(231, 76, 60, 1)'
                        ],
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
        
        // System info refresh button
        const refreshBtn = document.getElementById('refresh-system-info');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Animation for refresh button
                refreshBtn.disabled = true;
                const icon = refreshBtn.querySelector('i');
                icon.classList.add('fa-spin');
                
                // Simulating data refresh
                setTimeout(function() {
                    refreshBtn.disabled = false;
                    icon.classList.remove('fa-spin');
                    
                    // Update last updated text
                    const lastUpdated = document.querySelector('.card-footer .text-muted');
                    if (lastUpdated) {
                        const now = new Date();
                        lastUpdated.textContent = 'Last updated: ' + now.toLocaleString('id-ID');
                    }
                    
                    // Show success message
                    alert('Informasi sistem berhasil diperbarui.');
                }, 1500);
            });
        }
    });
</script>
@endsection