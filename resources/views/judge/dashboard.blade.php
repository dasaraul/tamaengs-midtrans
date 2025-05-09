@extends('layouts.admin')

@section('title', 'Dashboard Juri')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard Juri</h1>
        <div>
            <span class="badge bg-success p-2">UNAS Fest 2025</span>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--primary-color);">
                <div class="card-body">
                    <div class="card-title">Kompetisi yang Dinilai</div>
                    <div class="card-value">{{ $assignedCompetitions->count() }}</div>
                    <i class="fas fa-trophy card-icon" style="color: var(--primary-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--success-color);">
                <div class="card-body">
                    <div class="card-title">Karya Belum Dinilai</div>
                    <div class="card-value">{{ $pendingEvaluations }}</div>
                    <i class="fas fa-file-alt card-icon" style="color: var(--success-color);"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card dashboard-card h-100" style="border-left-color: var(--info-color);">
                <div class="card-body">
                    <div class="card-title">Penilaian Selesai</div>
                    <div class="card-value">{{ $completedEvaluations }}</div>
                    <i class="fas fa-check-circle card-icon" style="color: var(--info-color);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <span>Kompetisi yang Ditugaskan</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($assignedCompetitions as $competition)
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $competition->name }}</h5>
                                    <p class="card-text">{{ Str::limit($competition->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Karya Terkumpul:</span>
                                        <span class="badge bg-primary">{{ $competition->getSubmissionsCount() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Sudah Dinilai:</span>
                                        <span class="badge bg-success">{{ $judgeEvaluationCounts[$competition->id]['completed'] ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Belum Dinilai:</span>
                                        <span class="badge bg-warning text-dark">{{ $judgeEvaluationCounts[$competition->id]['pending'] ?? 0 }}</span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('judge.submissions.index', ['competition_id' => $competition->id]) }}" class="btn btn-primary">Lihat Karya</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Anda belum ditugaskan untuk menilai kompetisi apapun. Silakan hubungi admin untuk informasi lebih lanjut.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Penilaian Terbaru</span>
                    <a href="{{ route('judge.evaluations.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tim</th>
                                    <th>Kompetisi</th>
                                    <th>Judul Karya</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentEvaluations as $evaluation)
                                <tr>
                                    <td>{{ $evaluation->order->team_name }}</td>
                                    <td>{{ $evaluation->competition->name }}</td>
                                    <td>{{ optional($evaluation->order->submissions->where('product_id', $evaluation->product_id)->first())->title ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary">{{ $evaluation->score }}</span></td>
                                    <td>{{ $evaluation->updated_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada penilaian yang dilakukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <span>Karya yang Perlu Dinilai</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tim</th>
                                    <th>Judul Karya</th>
                                    <th>Kompetisi</th>
                                    <th>Tanggal Pengumpulan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingSubmissions as $submission)
                                <tr>
                                    <td>{{ $submission->order->team_name }}</td>
                                    <td>{{ $submission->title }}</td>
                                    <td>{{ $submission->competition->name }}</td>
                                    <td>{{ $submission->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('judge.evaluations.create', ['submission_id' => $submission->id]) }}" class="btn btn-sm btn-primary">Nilai</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada karya yang perlu dinilai saat ini</td>
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