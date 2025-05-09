@extends('layouts.admin')

@section('title', 'Kelola Role')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Kelola Role</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i>Daftar Role Sistem</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($roles as $role)
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-header 
                                @if($role['id'] == 'kiw') bg-danger text-white
                                @elseif($role['id'] == 'admin') bg-primary text-white
                                @elseif($role['id'] == 'juri') bg-success text-white
                                @else bg-warning text-dark
                                @endif">
                                <h5 class="mb-0">{{ $role['name'] }}</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $role['description'] }}</p>
                                
                                <h6 class="mb-3">Izin Akses:</h6>
                                <ul class="mb-3">
                                    @foreach($role['permissions'] as $permission)
                                        <li>{{ $permission }}</li>
                                    @endforeach
                                </ul>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge 
                                        @if($role['id'] == 'kiw') bg-danger
                                        @elseif($role['id'] == 'admin') bg-primary
                                        @elseif($role['id'] == 'juri') bg-success
                                        @else bg-warning text-dark
                                        @endif
                                        rounded-pill py-2 px-3">
                                        {{ $role['users_count'] }} pengguna
                                    </span>
                                    <a href="{{ route('kiw.roles.show', $role['id']) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Role</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h6><i class="fas fa-lightbulb me-2"></i>Tentang Sistem Role</h6>
                <p>Sistem UNAS Fest 2025 menggunakan hirarki peran (role) untuk membatasi akses pengguna. Setiap peran memiliki tingkat akses dan fungsionalitas yang berbeda:</p>
                <ul>
                    <li><strong>Super Admin (KIW)</strong> - Memiliki akses penuh ke seluruh sistem</li>
                    <li><strong>Admin</strong> - Mengelola operasional harian sistem</li>
                    <li><strong>Juri</strong> - Memberikan penilaian pada karya peserta</li>
                    <li><strong>Peserta</strong> - Mendaftar lomba dan mengumpulkan karya</li>
                </ul>
            </div>
            
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Penting!</h6>
                <p>Role merupakan komponen inti dari sistem keamanan aplikasi:</p>
                <ul>
                    <li>Penambahan atau perubahan struktur role membutuhkan perubahan kode program</li>
                    <li>Pastikan selalu ada minimal satu pengguna dengan role <strong>Super Admin (KIW)</strong></li>
                    <li>Pemberian role harus dilakukan dengan hati-hati sesuai dengan tanggung jawab pengguna</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection