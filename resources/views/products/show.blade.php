@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid" alt="{{ $product->name }}" style="max-height: 400px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
            </div>
        </div>
        
        <div class="col-md-7">
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <h2 class="product-price mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
                <div class="my-3">
                    @if($product->stock > 0)
                        <span class="badge bg-success p-2">Tersedia</span>
                        <span class="ms-2">Stok: {{ $product->stock }}</span>
                    @else
                        <span class="badge bg-danger p-2">Stok Habis</span>
                    @endif
                </div>
            </div>
            
            <hr>
            
            <div class="mb-4">
                <h5>Deskripsi Produk</h5>
                <p>{{ $product->description }}</p>
            </div>
            
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Jumlah:</label>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Tambahkan ke Keranjang
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <div class="d-grid gap-2 d-md-block">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Produk
                </a>
                @if($product->stock > 0)
                    <a href="{{ route('cart.index') }}" class="btn btn-success">
                        <i class="fas fa-shopping-cart"></i> Lihat Keranjang
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection