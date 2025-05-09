@extends('layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pendaftaran Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Pendaftaran #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="theme-heading">Detail Pendaftaran</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pendaftaran #{{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nomor Pendaftaran:</strong></p>
                            <p class="mb-0">#{{ $order->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal Pendaftaran:</strong></p>
                            <p class="mb-0">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status Pendaftaran:</strong></p>
                            <p class="mb-0">
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Memproses</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Terdaftar</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status Pembayaran:</strong></p>
                            <p class="mb-0">
                                @if($order->payment_status == 'unpaid')
                                    <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                @elseif($order->payment_status == 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Pembayaran:</strong></p>
                            <p class="competition-price mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        @if($order->payment_method)
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Metode Pembayaran:</strong></p>
                            <p class="mb-0">{{ strtoupper($order->payment_method) }}</p>
                        </div>
                        @endif
                    </div>

                    @if($order->payment_status == 'unpaid')
                    <div class="mt-3">
                        <a href="{{ route('payment.show', $order->id) }}" class="btn btn-warning">
                            <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Tim</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Nama Tim:</strong></p>
                        <p class="mb-0">{{ $order->team_name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Institusi/Universitas:</strong></p>
                        <p class="mb-0">{{ $order->institution }}</p>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Ketua Tim</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama:</strong></p>
                            <p class="mb-0">{{ $order->leader_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>NPM/NIM:</strong></p>
                            <p class="mb-0">{{ $order->leader_npm }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Semester:</strong></p>
                            <p class="mb-0">{{ $order->leader_semester }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Fakultas:</strong></p>
                            <p class="mb-0">{{ $order->leader_faculty }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email:</strong></p>
                            <p class="mb-0">{{ $order->leader_email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Telepon/WA:</strong></p>
                            <p class="mb-0">{{ $order->leader_phone }}</p>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Anggota Tim</h5>
                    
                    @if($order->members->count() > 0)
                        @foreach($order->members as $index => $member)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6>Anggota {{ $index + 1 }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Nama:</strong></p>
                                    <p class="mb-0">{{ $member->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>NPM/NIM:</strong></p>
                                    <p class="mb-0">{{ $member->npm }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Semester:</strong></p>
                                    <p class="mb-0">{{ $member->semester }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Fakultas:</strong></p>
                                    <p class="mb-0">{{ $member->faculty }}</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada anggota tim yang terdaftar.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Kompetisi yang Didaftarkan</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($order->items as $item)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <small>Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($item->product->description, 100) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <i class="fas fa-info-circle text-info me-2"></i> Silakan selesaikan pembayaran untuk mengonfirmasi pendaftaran
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-info-circle text-info me-2"></i> Simpan bukti pembayaran sebagai referensi
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-info-circle text-info me-2"></i> Technical meeting akan dilaksanakan pada 15 September 2025
                        </li>
                        <li class="list-group-item px-0">
                            <i class="fas fa-info-circle text-info me-2"></i> Untuk informasi lebih lanjut, hubungi panitia
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection