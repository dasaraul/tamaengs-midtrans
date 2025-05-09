@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Competitions</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x400.png?text=' . urlencode($product->name) }}" class="img-fluid" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-md-7 mb-4">
            <h1>{{ $product->name }}</h1>
            <h2 class="competition-price mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
            
            <div class="mb-4">
                <h5>Deskripsi Kompetisi:</h5>
                <p>{{ $product->description }}</p>
            </div>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-warning btn-lg">Daftar Sekarang</button>
            </form>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Informasi Detail Kompetisi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <h5>Waktu Pelaksanaan</h5>
                            <p><i class="far fa-calendar-alt me-2"></i> 23 Juli - 7 September 2025</p>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5>Tempat</h5>
                            <p><i class="fas fa-map-marker-alt me-2"></i> Universitas Nasional, Jakarta Selatan</p>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5>Pendaftaran</h5>
                            <p><i class="far fa-clock me-2"></i> Pendaftaran dibuka sampai 7 September 2025</p>
                        </div>
                    </div>
                    
                    <h5>Syarat dan Ketentuan</h5>
                    <ul>
                        <li>Peserta adalah mahasiswa aktif S1/D3/D4 dari perguruan tinggi di Indonesia</li>
                        <li>Setiap tim terdiri dari 3-5 orang dari universitas yang sama</li>
                        <li>Memiliki kartu tanda mahasiswa (KTM) yang masih berlaku</li>
                        <li>Melengkapi data diri pada formulir pendaftaran</li>
                        <li>Melakukan pembayaran biaya pendaftaran</li>
                    </ul>

                    <h5 class="mt-4">Hadiah</h5>
                    <ul>
                        <li>Juara 1: Rp 5.000.000 + Sertifikat + Trophy</li>
                        <li>Juara 2: Rp 3.000.000 + Sertifikat + Trophy</li>
                        <li>Juara 3: Rp 2.000.000 + Sertifikat + Trophy</li>
                        <li>Harapan 1: Rp 1.000.000 + Sertifikat</li>
                        <li>Harapan 2: Rp 500.000 + Sertifikat</li>
                    </ul>

                    <h5 class="mt-4">Timeline</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>23 - 28 Juli 2025</td>
                                    <td>Pendaftaran Awal (Rp 300.000/Tim)</td>
                                </tr>
                                <tr>
                                    <td>29 Juli - 11 Agustus 2025</td>
                                    <td>Pendaftaran Tahap 1 (Rp 400.000/Tim)</td>
                                </tr>
                                <tr>
                                    <td>12 Agustus - 7 September 2025</td>
                                    <td>Pendaftaran Tahap 2 (Rp 450.000/Tim)</td>
                                </tr>
                                <tr>
                                    <td>15 September 2025</td>
                                    <td>Technical Meeting</td>
                                </tr>
                                <tr>
                                    <td>20 - 22 September 2025</td>
                                    <td>Pelaksanaan Kompetisi</td>
                                </tr>
                                <tr>
                                    <td>25 September 2025</td>
                                    <td>Pengumuman Pemenang</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-4">Contact Person</h5>
                    <p><i class="fab fa-whatsapp me-2"></i> Ahmad: 0812-3456-7890</p>
                    <p><i class="fab fa-whatsapp me-2"></i> Siti: 0898-7654-3210</p>
                    <p><i class="far fa-envelope me-2"></i> Email: unasfest@unas.ac.id</p>