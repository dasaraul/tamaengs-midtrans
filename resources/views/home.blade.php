@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-dark text-white">
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=400&q=80" class="card-img" alt="Hero Image" style="height: 400px; object-fit: cover; filter: brightness(0.6);">
                <div class="card-img-overlay d-flex flex-column justify-content-center text-center">
                    <h1 class="card-title display-4 fw-bold">Midtrans Shop</h1>
                    <p class="card-text fs-5 mb-4">Belanja produk berkualitas dengan pembayaran yang mudah dan aman.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mx-auto" style="width: fit-content;">Belanja Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Produk Terbaru</h2>
        </div>
        
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    @if($product->stock <= 0)
                        <div class="position-absolute top-0 end-0 bg-danger text-white p-2 m-2 rounded">Habis</div>
                    @elseif($product->stock <= 5)
                        <div class="position-absolute top-0 end-0 bg-warning text-white p-2 m-2 rounded">Stok Terbatas</div>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-truncate">{{ $product->description }}</p>
                    <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary">Detail</a>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Features -->
    <div class="row my-5">
        <div class="col-12 text-center mb-4">
            <h2>Mengapa Belanja di Midtrans Shop?</h2>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="text-center mb-3">
                    <i class="fas fa-truck fa-3x text-primary"></i>
                </div>
                <h4>Pengiriman Cepat</h4>
                <p>Barang sampai ke tangan Anda dalam waktu 2-3 hari kerja.</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="text-center mb-3">
                    <i class="fas fa-credit-card fa-3x text-primary"></i>
                </div>
                <h4>Pembayaran Aman</h4>
                <p>Berbagai metode pembayaran yang aman melalui Midtrans Payment Gateway.</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="text-center mb-3">
                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                </div>
                <h4>Produk Berkualitas</h4>
                <p>Semua produk dijamin original dan berkualitas tinggi.</p>
            </div>
        </div>
    </div>
</div>
@endsection