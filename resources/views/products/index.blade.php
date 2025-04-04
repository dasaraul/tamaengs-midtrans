@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-9">
            <h2>Our Products</h2>
        </div>
        <div class="col-md-3">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request()->search }}">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="{{ $product->imageUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
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

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection