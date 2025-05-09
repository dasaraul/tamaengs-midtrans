@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">UNAS Fest 2025</h1>
        <h2 class="lead fs-3 mb-4">"Bio University, Technology, Health"</h2>
        <p class="fs-5 mb-5">Kompetisi akademik dan non-akademik terbesar Universitas Nasional untuk mahasiswa seluruh Indonesia</p>
        <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-4 py-2">Daftar Sekarang</a>
    </div>
</div>

<!-- Competition Section -->
<div class="container">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="theme-heading">Our Competitions</h2>
            <p class="mb-5">Pilih kompetisi yang sesuai dengan minat dan bakat Anda</p>
        </div>
    </div>

    <div class="row">
        <!-- KDBI Competition -->
        <div class="col-md-3 mb-4">
            <div class="card competition-card h-100">
                <div class="card-body text-center">
                    <div class="competition-logo mb-3">
                        <img src="{{ asset('images/kdbi-logo.png') }}" alt="KDBI Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=KDBI';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">Kompetisi Debat Bahasa Indonesia</h3>
                    <p class="card-text">Asah kemampuan debat dan argumentasi dalam Bahasa Indonesia.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', 1) }}" class="btn btn-warning">Daftar Sekarang!</a>
                        <a href="#" class="btn btn-outline-primary">Masuk Website</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMC Competition -->
        <div class="col-md-3 mb-4">
            <div class="card competition-card h-100">
                <div class="card-body text-center">
                    <div class="competition-logo mb-3">
                        <img src="{{ asset('images/smc-logo.png') }}" alt="SMC Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=SMC';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">Short Movie Competition</h3>
                    <p class="card-text">Ekspresikan ide dan kreativitas Anda melalui film pendek.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', 2) }}" class="btn btn-warning">Daftar Sekarang!</a>
                        <a href="#" class="btn btn-outline-primary">Unggah Karya</a>
                        <a href="#" class="btn btn-outline-primary">Masuk Website</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPC Competition -->
        <div class="col-md-3 mb-4">
            <div class="card competition-card h-100">
                <div class="card-body text-center">
                    <div class="competition-logo mb-3">
                        <img src="{{ asset('images/spc-logo.png') }}" alt="SPC Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=SPC';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">Scientific Paper Competition</h3>
                    <p class="card-text">Tuangkan hasil penelitian dan gagasan ilmiah Anda.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', 3) }}" class="btn btn-warning">Daftar Sekarang!</a>
                        <a href="#" class="btn btn-outline-primary">Unggah Karya</a>
                        <a href="#" class="btn btn-outline-primary">Masuk Website</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDC Competition -->
        <div class="col-md-3 mb-4">
            <div class="card competition-card h-100">
                <div class="card-body text-center">
                    <div class="competition-logo mb-3">
                        <img src="{{ asset('images/edc-logo.png') }}" alt="EDC Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=EDC';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">English Debate Competition</h3>
                    <p class="card-text">Tantang kemampuan debat dan argumentasi dalam Bahasa Inggris.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', 4) }}" class="btn btn-warning">Daftar Sekarang!</a>
                        <a href="#" class="btn btn-outline-primary">Masuk Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($registrationTimeline))
<!-- Timeline Section -->
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="theme-heading">Timeline Pendaftaran</h2>
            <p class="mb-5">Jangan lewatkan tanggal penting untuk mendaftar lomba</p>
        </div>
    </div>

    <div class="row">
        @foreach($registrationTimeline as $index => $timeline)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white p-3 rounded me-3">
                            <h4 class="m-0">{{ $timeline['dates'] }}</h4>
                        </div>
                        <div>
                            <h5 class="card-title">{{ $timeline['period'] }}</h5>
                            <h6 class="competition-price">{{ $timeline['price'] }}</h6>
                        </div>
                    </div>
                    <p class="card-text">
                        <small class="text-muted">{{ $timeline['note'] }}</small>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-2"></i> Jl. Sawo Manila No.61, RT.14/RW.7, Pejaten Barat, Ps. Minggu, Kota Jakarta Selatan
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- About Section -->
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="theme-heading">Tentang UNAS Fest</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2 text-center">
            <p class="fs-5">UNAS Fest adalah festival akademik tahunan yang diselenggarakan oleh Universitas Nasional dengan tema "Bio University, Technology, Health". Acara ini bertujuan untuk memfasilitasi mahasiswa dalam mengembangkan bakat dan minat mereka di berbagai bidang, serta membangun jaringan antar mahasiswa dari berbagai universitas di Indonesia.</p>
            <p class="fs-5">Dengan berbagai kompetisi dari debat, film pendek, karya tulis ilmiah, hingga debat bahasa Inggris, UNAS Fest menjadi wadah bagi mahasiswa untuk mengekspresikan kreativitas dan mengasah kemampuan akademik mereka.</p>
        </div>
    </div>
</div>

@if(isset($competitions) && $competitions->isNotEmpty())
<!-- Featured Competitions -->
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="theme-heading">Kompetisi Unggulan</h2>
            <p class="mb-5">Kompetisi yang sedang berlangsung dan bisa Anda ikuti sekarang</p>
        </div>
    </div>

    <div class="row">
        @foreach($competitions as $competition)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <img src="{{ $competition->image ? asset('storage/' . $competition->image) : 'https://via.placeholder.com/300x200.png?text=' . urlencode($competition->name) }}" class="card-img-top" alt="{{ $competition->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $competition->name }}</h5>
                    <p class="card-text">{{ Str::limit($competition->description, 80) }}</p>
                    <p class="competition-price mb-3">Rp {{ number_format($competition->price, 0, ',', '.') }}</p>
                    <p class="mb-2"><small><i class="far fa-calendar-alt me-2"></i> Sampai: {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}</small></p>
                    <div class="d-grid">
                        <a href="{{ route('products.show', $competition->id) }}" class="btn btn-primary">Detail Kompetisi</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary px-4">Lihat Semua Kompetisi</a>
    </div>
</div>
@endif
@endsection

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #0e3e72 0%, #2e86de 100%);
        color: white;
        padding: 80px 0;
        margin-bottom: 40px;
        border-radius: 0 0 20px 20px;
    }
    
    .competition-card {
        border: none;
        background-color: white;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .competition-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .competition-card .card-body {
        padding: 25px;
    }
    
    .competition-logo {
        width: 100px;
        height: 100px;
        margin: 0 auto 15px auto;
    }
    
    .theme-heading {
        font-weight: 700;
        color: #0e3e72;
        margin-bottom: 20px;
    }
    
    .competition-price {
        font-size: 20px;
        font-weight: 700;
        color: #f39c12;
    }
</style>
@endsection