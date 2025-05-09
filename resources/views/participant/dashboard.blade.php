@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #0e3e72 0%, #2e86de 100%);
        padding: 40px 0;
        margin-bottom: 30px;
        border-radius: 10px;
        color: white;
    }
    
    .dashboard-card {
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        margin-bottom: 20px;
        overflow: hidden;
        border: none;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .dashboard-card .card-header {
        background-color: #0e3e72;
        color: white;
        font-weight: 600;
        padding: 15px 20px;
    }
    
    .dashboard-card .card-body {
        padding: 20px;
    }
    
    .status-badge {
        font-size: 0.9rem;
        padding: 8px 12px;
        border-radius: 50px;
    }
    
    .registration-item {
        border-radius: 10px;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }
    
    .registration-item:hover {
        transform: translateY(-3px);
    }
    
    .nav-tabs .nav-item .nav-link {
        color: #0e3e72;
        font-weight: 500;
    }
    
    .nav-tabs .nav-item .nav-link.active {
        color: #0e3e72;
        font-weight: 600;
        border-bottom: 3px solid #0e3e72;
    }
    
    .submission-card {
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .submission-card:hover {
        border-color: #0e3e72;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    
    .competition-icon {
        font-size: 2rem;
        color: #0e3e72;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="dashboard-header text-center">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="mb-3">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="lead mb-0">Dashboard Peserta UNAS Fest 2025</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="dashboard-card h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div style="width: 80px; height: 80px; background-color: #e9ecef; border-radius: 50%; font-size: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; color: #0e3e72;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark px-3 py-2">Peserta</span>
                    </div>
                    
                    @if(auth()->user()->institution)
                    <p class="mb-1"><i class="fas fa-university me-2"></i> {{ auth()->user()->institution }}</p>
                    @endif
                    
                    @if(auth()->user()->faculty)
                    <p class="mb-1"><i class="fas fa-graduation-cap me-2"></i> {{ auth()->user()->faculty }}</p>
                    @endif
                    
                    @if(auth()->user()->npm)
                    <p class="mb-1"><i class="fas fa-id-card me-2"></i> {{ auth()->user()->npm }}</p>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Edit Profil</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9 mb-4">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Ringkasan</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="text-center">
                                <div class="competition-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <h3 class="h5 mb-2">{{ $registrationCount }}</h3>
                                <p class="text-muted mb-0">Kompetisi Terdaftar</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="text-center">
                                <div class="competition-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h3 class="h5 mb-2">{{ $submissionCount }}</h3>
                                <p class="text-muted mb-0">Karya Dikumpulkan</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="competition-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h3 class="h5 mb-2">{{ $evaluationCount }}</h3>
                                <p class="text-muted mb-0">Penilaian Diterima</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card mt-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="registrations-tab" data-bs-toggle="tab" data-bs-target="#registrations" type="button" role="tab" aria-controls="registrations" aria-selected="true">Pendaftaran</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="submissions-tab" data-bs-toggle="tab" data-bs-target="#submissions" type="button" role="tab" aria-controls="submissions" aria-selected="false">Karya</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="evaluations-tab" data-bs-toggle="tab" data-bs-target="#evaluations" type="button" role="tab" aria-controls="evaluations" aria-selected="false">Penilaian</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dashboardTabsContent">
                        <div class="tab-pane fade show active" id="registrations" role="tabpanel" aria-labelledby="registrations-tab">
                            @if($registrations->isEmpty())
                                <div class="alert alert-info">
                                    <p class="mb-0">Anda belum terdaftar di kompetisi apapun. <a href="{{ route('products.index') }}">Daftar sekarang</a>!</p>
                                </div>
                            @else
                                @foreach($registrations as $registration)
                                <div class="registration-item p-3 border">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="mb-1">{{ $registration->getCompetitionNames() }}</h5>
                                            <p class="mb-0 text-muted">Tim: {{ $registration->team_name }}</p>
                                            <p class="mb-0"><small>Tanggal: {{ $registration->created_at->format('d M Y') }}</small></p>
                                        </div>
                                        <div class="col-md-3">
                                            @if($registration->payment_status == 'paid')
                                                <span class="badge bg-success status-badge">Terdaftar</span>
                                            @else
                                                <span class="badge bg-warning text-dark status-badge">Belum Dibayar</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <a href="{{ route('orders.show', $registration->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                            
                                            @if($registration->payment_status != 'paid')
                                                <a href="{{ route('payment.show', $registration->id) }}" class="btn btn-sm btn-warning">Bayar</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="submissions" role="tabpanel" aria-labelledby="submissions-tab">
                            @if($submissions->isEmpty())
                                <div class="alert alert-info">
                                    <p class="mb-0">Anda belum mengumpulkan karya apapun.</p>
                                </div>
                            @else
                                <div class="row">
                                    @foreach($submissions as $submission)
                                    <div class="col-md-6">
                                        <div class="submission-card p-3">
                                            <h5 class="mb-2">{{ $submission->title }}</h5>
                                            <p class="mb-2 text-muted">{{ $submission->competition->name }}</p>
                                            <p class="mb-3"><small>Dikumpulkan: {{ $submission->created_at->format('d M Y') }}</small></p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                @if($submission->status == 'approved')
                                                    <span class="badge bg-success status-badge">Diterima</span>
                                                @elseif($submission->status == 'rejected')
                                                    <span class="badge bg-danger status-badge">Ditolak</span>
                                                @else
                                                    <span class="badge bg-secondary status-badge">Menunggu</span>
                                                @endif
                                                
                                                <a href="{{ route('participant.submissions.show', $submission->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($pendingSubmissions->isNotEmpty())
                                <div class="mt-4">
                                    <h5>Kompetisi yang Perlu Mengumpulkan Karya</h5>
                                    <div class="row">
                                        @foreach($pendingSubmissions as $registration)
                                            @foreach($registration->items as $item)
                                                @if(!$item->product->submissions()->where('order_id', $registration->id)->exists())
                                                <div class="col-md-6">
                                                    <div class="submission-card p-3">
                                                        <h5 class="mb-2">{{ $item->product->name }}</h5>
                                                        <p class="mb-2 text-muted">Tim: {{ $registration->team_name }}</p>
                                                        <div class="d-grid">
                                                            <a href="{{ route('participant.submissions.create', ['competition_id' => $item->product_id, 'order_id' => $registration->id]) }}" class="btn btn-warning">Kumpulkan Karya</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="evaluations" role="tabpanel" aria-labelledby="evaluations-tab">
                            @if($evaluations->isEmpty())
                                <div class="alert alert-info">
                                    <p class="mb-0">Belum ada penilaian untuk karya Anda.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kompetisi</th>
                                                <th>Karya</th>
                                                <th>Nilai</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($evaluations as $evaluation)
                                            <tr>
                                                <td>{{ $evaluation->competition->name }}</td>
                                                <td>{{ $evaluation->order->submissions()->where('product_id', $evaluation->product_id)->first()->title ?? 'N/A' }}</td>
                                                <td><span class="badge bg-primary px-3 py-2">{{ $evaluation->score }}</span></td>
                                                <td>{{ $evaluation->updated_at->format('d M Y') }}</td>
                                                <td><a href="{{ route('participant.evaluations.show', $evaluation->id) }}" class="btn btn-sm btn-primary">Detail</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Kompetisi yang Sedang Berjalan</span>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($activeCompetitions as $competition)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100">
                                <img src="{{ $competition->image ? asset('storage/' . $competition->image) : 'https://via.placeholder.com/300x200.png?text=' . urlencode($competition->name) }}" class="card-img-top" alt="{{ $competition->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $competition->name }}</h5>
                                    <p class="card-text">{{ Str::limit($competition->description, 80) }}</p>
                                    <p class="competition-price mb-3">Rp {{ number_format($competition->price, 0, ',', '.') }}</p>
                                    <p class="mb-2"><small><i class="far fa-calendar-alt me-2"></i> Sampai: {{ $competition->registration_end->format('d M Y') }}</small></p>
                                    <div class="d-grid">
                                        <a href="{{ route('products.show', $competition->id) }}" class="btn btn-primary">Detail Kompetisi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection