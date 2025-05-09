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
                        <img src="/images/kdbi-logo.png" alt="KDBI Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=KDBI';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">Kompetisi Debat Bahasa Indonesia</h3>
                    <p class="card-text">Asah kemampuan debat dan argumentasi dalam Bahasa Indonesia.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-warning">Daftar Sekarang!</a>
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
                        <img src="/images/smc-logo.png" alt="SMC Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=SMC';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">Short Movie Competition</h3>
                    <p class="card-text">Ekspresikan ide dan kreativitas Anda melalui film pendek.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-warning">Daftar Sekarang!</a>
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
                        <img src="/images/spc-logo.png" alt="SPC Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=SPC';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">Scientific Paper Competition</h3>
                    <p class="card-text">Tuangkan hasil penelitian dan gagasan ilmiah Anda.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-warning">Daftar Sekarang!</a>
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
                        <img src="/images/edc-logo.png" alt="EDC Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/100x100.png?text=EDC';this.onerror='';">
                    </div>
                    <h3 class="card-title mb-3">English Debate Competition</h3>
                    <p class="card-text">Tantang kemampuan debat dan argumentasi dalam Bahasa Inggris.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-warning">Daftar Sekarang!</a>
                        <a href="#" class="btn btn-outline-primary">Masuk Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Section -->
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="theme-heading">Timeline Pendaftaran</h2>
            <p class="mb-5">Jangan lewatkan tanggal penting untuk mendaftar lomba</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white p-3 rounded me-3">
                            <h4 class="m-0">23-28<br>Juli</h4>
                        </div>
                        <div>
                            <h5 class="card-title">Pendaftaran Awal</h5>
                            <h6 class="competition-price">Rp300.000/Tim</h6>
                        </div>
                    </div>
                    <p class="card-text">
                        <small class="text-muted">* Khusus Mahasiswa Universitas Nasional</small>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-2"></i> Jl. Sawo Manila No.61, RT.14/RW.7, Pejaten Barat, Ps. Minggu, Kota Jakarta Selatan
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white p-3 rounded me-3">
                            <h4 class="m-0">29 Juli-<br>11 Agustus</h4>
                        </div>
                        <div>
                            <h5 class="card-title">Tahap 1</h5>
                            <h6 class="competition-price">Rp400.000/Tim</h6>
                        </div>
                    </div>
                    <p class="card-text">
                        <small class="text-muted">* Khusus Mahasiswa Universitas Nasional</small>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-2"></i> Jl. Sawo Manila No.61, RT.14/RW.7, Pejaten Barat, Ps. Minggu, Kota Jakarta Selatan
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white p-3 rounded me-3">
                            <h4 class="m-0">12 Agustus-<br>07 September</h4>
                        </div>
                        <div>
                            <h5 class="card-title">Tahap 2</h5>
                            <h6 class="competition-price">Rp450.000/Tim</h6>
                        </div>
                    </div>
                    <p class="card-text">
                        <small class="text-muted">* Khusus Mahasiswa Universitas Nasional</small>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-2"></i> Jl. Sawo Manila No.61, RT.14/RW.7, Pejaten Barat, Ps. Minggu, Kota Jakarta Selatan
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection