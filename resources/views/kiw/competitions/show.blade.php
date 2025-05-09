@extends('layouts.admin')

@section('title', 'Detail Kompetisi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Kompetisi</h1>
        <div>
            <a href="{{ route('kiw.competitions.criteria', $competition->id) }}" class="btn btn-primary">
                <i class="fas fa-list-check me-2"></i>Kelola Kriteria
            </a>
            <a href="{{ route('kiw.competitions.edit', $competition->id) }}" class="btn btn-warning text-white">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('kiw.competitions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Informasi Umum</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($competition->image)
                            <img src="{{ asset('storage/' . $competition->image) }}" alt="{{ $competition->name }}" class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="bg-secondary p-5 rounded text-white d-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-trophy fa-4x"></i>
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $competition->name }}</h4>
                        <span class="badge bg-secondary">{{ $competition->code }}</span>
                        <div class="mt-2">
                            @if($competition->active)
                                @if($competition->registration_end && $competition->registration_end->isPast())
                                    <span class="badge bg-secondary">Ditutup</span>
                                @elseif($competition->registration_start && $competition->registration_start->isFuture())
                                    <span class="badge bg-info">Akan Dibuka</span>
                                @else
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light">Biaya Pendaftaran</th>
                            <td>Rp {{ number_format($competition->price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Periode Pendaftaran</th>
                            <td>
                                <div><i class="far fa-calendar-alt me-1"></i> {{ $competition->registration_start ? $competition->registration_start->format('d M Y') : 'N/A' }}</div>
                                <div><i class="far fa-calendar-check me-1"></i> {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jumlah Pendaftar</th>
                            <td>{{ $registrationsCount }} peserta</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jumlah Karya</th>
                            <td>{{ $submissionsCount }} karya</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jumlah Kriteria</th>
                            <td>{{ $criteria->count() }} kriteria</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Dibuat</th>
                            <td>{{ $competition->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Diperbarui</th>
                            <td>{{ $competition->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Kriteria Penilaian</h5>
                </div>
                <div class="card-body p-0">
                    @if($criteria->count() > 0)
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Skor Maks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $criterion)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $criterion->name }}</div>
                                    <small class="text-muted">{{ Str::limit($criterion->description, 30) }}</small>
                                </td>
                                <td>{{ $criterion->weight }}%</td>
                                <td>{{ $criterion->max_score }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th>{{ $criteria->sum('weight') }}%</th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    <div class="p-4 text-center">
                        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                        <h5>Belum ada kriteria penilaian</h5>
                        <p class="text-muted mb-3">Tambahkan kriteria penilaian untuk kompetisi ini</p>
                        <a href="{{ route('kiw.competitions.criteria', $competition->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Kriteria
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('kiw.competitions.criteria', $competition->id) }}" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-cog me-2"></i>Kelola Kriteria
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="competitionTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
                                <i class="fas fa-info-circle me-2"></i>Deskripsi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="requirements-tab" data-bs-toggle="tab" data-bs-target="#requirements" type="button" role="tab" aria-controls="requirements" aria-selected="false">
                                <i class="fas fa-clipboard-list me-2"></i>Persyaratan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="prizes-tab" data-bs-toggle="tab" data-bs-target="#prizes" type="button" role="tab" aria-controls="prizes" aria-selected="false">
                                <i class="fas fa-award me-2"></i>Hadiah
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="competitionTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <h5 class="card-title">Deskripsi Kompetisi</h5>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($competition->description)) !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="requirements" role="tabpanel" aria-labelledby="requirements-tab">
                            <h5 class="card-title">Persyaratan Kompetisi</h5>
                            <div class="p-3 bg-light rounded">
                                @if($competition->requirements)
                                    <ul class="mb-0">
                                        @foreach(explode("\n", $competition->requirements) as $requirement)
                                            @if(trim($requirement))
                                                <li>{{ $requirement }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Tidak ada persyaratan khusus untuk kompetisi ini.</p>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="prizes" role="tabpanel" aria-labelledby="prizes-tab">
                            <h5 class="card-title">Hadiah Kompetisi</h5>
                            <div class="p-3 bg-light rounded">
                                @if($competition->prizes)
                                    <ul class="mb-0">
                                        @foreach(explode("\n", $competition->prizes) as $prize)
                                            @if(trim($prize))
                                                <li>{{ $prize }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Tidak ada informasi hadiah untuk kompetisi ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistik dan Informasi Peserta -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card dashboard-card border-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Peserta Terdaftar</h5>
                            <h2 class="card-value">{{ $registrationsCount }}</h2>
                            <p class="card-text text-muted">Jumlah peserta yang sudah mendaftar dan membayar</p>
                            <i class="fas fa-users card-icon text-primary"></i>
                        </div>
                        <div class="card-footer bg-white">
                            <button type="button" class="btn btn-sm btn-outline-primary w-100" disabled>
                                <i class="fas fa-eye me-2"></i>Lihat Peserta
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card dashboard-card border-success h-100">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Karya Terkumpul</h5>
                            <h2 class="card-value">{{ $submissionsCount }}</h2>
                            <p class="card-text text-muted">Jumlah karya yang telah dikumpulkan</p>
                            <i class="fas fa-file-alt card-icon text-success"></i>
                        </div>
                        <div class="card-footer bg-white">
                            <button type="button" class="btn btn-sm btn-outline-success w-100" disabled>
                                <i class="fas fa-eye me-2"></i>Lihat Karya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timeline Kompetisi -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Timeline Kompetisi</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-indicator bg-primary">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <div class="timeline-item-content">
                                <div class="fw-bold">Kompetisi Dibuat</div>
                                <div class="small text-muted">{{ $competition->created_at->format('d M Y H:i') }}</div>
                                <div>Kompetisi {{ $competition->name }} berhasil dibuat.</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-indicator {{ $competition->registration_start && $competition->registration_start->isPast() ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas fa-door-open"></i>
                                </div>
                            </div>
                            <div class="timeline-item-content">
                                <div class="fw-bold">Pendaftaran Dibuka</div>
                                <div class="small text-muted">{{ $competition->registration_start ? $competition->registration_start->format('d M Y') : 'Belum ditentukan' }}</div>
                                <div>Peserta dapat mulai mendaftar kompetisi.</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-indicator {{ $competition->registration_end && $competition->registration_end->isPast() ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas fa-door-closed"></i>
                                </div>
                            </div>
                            <div class="timeline-item-content">
                                <div class="fw-bold">Pendaftaran Ditutup</div>
                                <div class="small text-muted">{{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'Belum ditentukan' }}</div>
                                <div>Batas akhir pendaftaran kompetisi.</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-indicator bg-secondary">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <div class="timeline-item-content">
                                <div class="fw-bold">Penilaian Karya</div>
                                <div class="small text-muted">Setelah pengumpulan karya</div>
                                <div>Juri mulai menilai karya peserta.</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-indicator bg-secondary">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                            <div class="timeline-item-content">
                                <div class="fw-bold">Pengumuman Pemenang</div>
                                <div class="small text-muted">Akan ditentukan</div>
                                <div>Pengumuman pemenang kompetisi.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0.75rem;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item-marker {
        position: absolute;
        left: -1.5rem;
        top: 0;
    }
    
    .timeline-item-marker-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 2rem;
        height: 2rem;
        border-radius: 100%;
        color: #fff;
    }
    
    .timeline-item-content {
        padding-left: 0.75rem;
        padding-bottom: 0.5rem;
    }
    
    /* Dashboard Card Styles */
    .dashboard-card {
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-card .card-icon {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        opacity: 0.2;
        font-size: 3rem;
    }
</style>
@endsection