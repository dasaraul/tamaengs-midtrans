@extends('layouts.app')

@section('title', 'Competitions')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="theme-heading text-center">Daftar Kompetisi</h1>
            <p class="text-center mb-5">Pilih dan daftar kompetisi sesuai minat dan bakatmu</p>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card competition-card h-100">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200.png?text=' . urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h3 class="card-title mb-3">{{ $product->name }}</h3>
                    <p class="competition-price mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Detail Kompetisi</a>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-warning w-100">Daftar Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection