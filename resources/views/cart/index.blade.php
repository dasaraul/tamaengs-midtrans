@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cart</li>
        </ol>
    </nav>

    <h2 class="mb-4">Your Shopping Cart</h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php 
                                        $total += $details['price'] * $details['quantity'];
                                        $product = App\Models\Product::find($id);
                                    @endphp
                                    <tr data-id="{{ $id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product ? $product->imageUrl : 'https://via.placeholder.com/80x60?text=No+Image' }}" alt="{{ $details['name'] }}" class="img-thumbnail me-3" style="width: 80px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $details['name'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group" style="width: 130px;">
                                                <button class="btn btn-sm btn-outline-secondary update-cart-decrease">-</button>
                                                <input type="number" value="{{ $details['quantity'] }}" class="form-control form-control-sm quantity" min="1" />
                                                <button class="btn btn-sm btn-outline-secondary update-cart-increase">+</button>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger remove-from-cart">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        
                        <div class="d-grid">
                            <a href="{{ route('checkout.index') }}" class="btn btn-success">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-5x mb-3 text-muted"></i>
                <h3>Your cart is empty!</h3>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        // Update cart item quantity
        $('.update-cart-increase, .update-cart-decrease').on('click', function(e) {
            e.preventDefault();
            
            var ele = $(this);
            var inputEle = ele.closest('div').find('.quantity');
            var quantity = parseInt(inputEle.val());
            
            if (ele.hasClass('update-cart-increase')) {
                quantity += 1;
            } else {
                quantity = quantity > 1 ? quantity - 1 : quantity;
            }
            
            inputEle.val(quantity);
            
            $.ajax({
                url: '{{ route('cart.update') }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.parents("tr").attr("data-id"),
                    quantity: quantity
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        });
        
        // Remove from cart
        $('.remove-from-cart').on('click', function(e) {
            e.preventDefault();
            
            var ele = $(this);
            
            if (confirm("Are you sure you want to remove this item?")) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.parents("tr").attr("data-id")
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
@endsection