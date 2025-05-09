@extends('layouts.app')

@section('title', 'Registration')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="theme-heading text-center">Pendaftaran Kompetisi</h1>
            <p class="text-center">Kompetisi yang Anda pilih untuk didaftarkan</p>
        </div>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-md-8">
                @foreach(session('cart') as $id => $details)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <img src="{{ isset($details['image']) && $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/150.png?text=' . urlencode($details['name'] ?? 'Kompetisi') }}" class="img-fluid rounded" alt="{{ $details['name'] ?? 'Kompetisi' }}">
                                </div>
                                <div class="col-md-9">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">{{ $details['name'] ?? 'Kompetisi' }}</h4>
                                        <span class="competition-price">Rp {{ number_format($details['price'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <p>{{ Str::limit($details['description'] ?? 'Tidak ada deskripsi', 150) }}</p>
                                    <div class="d-flex justify-content-end">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $total = 0;
                            foreach(session('cart') as $id => $details) {
                                $total += ($details['price'] ?? 0);
                            }
                        @endphp
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Jumlah Kompetisi:</span>
                            <span>{{ count(session('cart')) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Biaya:</span>
                            <span class="competition-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr>
                        
                        <a href="{{ route('checkout.index') }}" class="btn btn-warning w-100">Lanjutkan Pendaftaran</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <p class="text-center mb-0">Anda belum memilih kompetisi. <a href="{{ route('products.index') }}">Pilih kompetisi sekarang!</a></p>
        </div>
    @endif
</div>
@endsection