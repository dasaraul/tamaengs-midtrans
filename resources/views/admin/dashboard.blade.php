@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard Admin</h1>
        <div>
            <span class="badge bg-primary p-2">UNAS Fest 2025</span>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--primary-color);">
                <div class="card-body">
                    <div class="card-title">Total Peserta</div>
                    <div class="card-value">{{ $totalParticipants }}</div>
                    <i class="fas fa-users card-icon" style="color: var(--primary-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--success-color);">
                <div class="card-body">
                    <div class="card-title">Pendaftaran Aktif</div>
                    <div class="card-value">{{ $activeRegistrations }}</div>
                    <i class="fas fa-clipboard-list card-icon" style="color: var(--success-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--warning-color);">
                <div class="card-body">
                    <div class="card-title">Menunggu Pembayaran</div>
                    <div class="card-value">{{ $pendingPayments }}</div>
                    <i class="fas fa-money-bill-wave card-icon" style="color: var(--warning-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--info-color);">
                <div class="card-body">
                    <div class="card-title">Karya Terkumpul</div>
                    <div class="card-value">{{ $totalSubmissions }}</div>
                    <i class="fas fa-file-alt card-icon" style="color: var(--info-color);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Statistik Pendaftaran</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Bulanan
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="chartDropdown">
                            <li><a class="dropdown-item" href="#">Harian</a></li>
                            <li><a class="dropdown-item" href="#">Mingguan</a></li>
                            <li><a class="dropdown-item active" href="#">Bulanan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="registrationChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <span>Distribusi Kompetisi</span>
                </div>
                <div class="card-body">
                    <canvas id="competitionDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Pendaftaran Terbaru</span>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tim</th>
                                    <th>Kompetisi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestRegistrations as $registration)
                                <tr>
                                    <td>#{{ $registration->id }}</td>
                                    <td>{{ $registration->team_name }}</td>
                                    <td>{{ $registration->getCompetitionNames() }}</td>
                                    <td>
                                        @if($registration->payment_status == 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                        @endif
                                    </td>
                                    <td>{{ $registration->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada pendaftaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Karya Terbaru</span>
                    <a href="{{ route('admin.submissions.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tim</th>
                                    <th>Judul</th>
                                    <th>Kompetisi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestSubmissions as $submission)
                                <tr>
                                    <td>#{{ $submission->id }}</td>
                                    <td>{{ $submission->order->team_name }}</td>
                                    <td>{{ Str::limit($submission->title, 20) }}</td>
                                    <td>{{ $submission->competition->name }}</td>
                                    <td>
                                        @if($submission->status == 'approved')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif($submission->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada karya yang dikirimkan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
        // Registration Chart
        const registrationCtx = document.getElementById('registrationChart').getContext('2d');
        const registrationChart = new Chart(registrationCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($registrationChartData['labels']) !!},
                datasets: [{
                    label: 'Pendaftaran',
                    data: {!! json_encode($registrationChartData['data']) !!},
                    backgroundColor: 'rgba(46, 134, 222, 0.2)',
                    borderColor: 'rgba(46, 134, 222, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Competition Distribution Chart
        const competitionCtx = document.getElementById('competitionDistributionChart').getContext('2d');
        const competitionChart = new Chart(competitionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($competitionChartData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($competitionChartData['data']) !!},
                    backgroundColor: [
                        'rgba(46, 134, 222, 0.8)',
                        'rgba(243, 156, 18, 0.8)',
                        'rgba(39, 174, 96, 0.8)',
                        'rgba(231, 76, 60, 0.8)',
                        'rgba(155, 89, 182, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection