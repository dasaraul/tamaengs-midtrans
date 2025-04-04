@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h2 class="mb-4">Checkout</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="shipping_phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('shipping_phone') is-invalid @enderror" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" required>
                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('shipping_email') is-invalid @enderror" id="shipping_email" name="shipping_email" value="{{ old('shipping_email', Auth::user()->email) }}" required>
                                @error('shipping_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                            <label class="form-check-label" for="termsCheck">
                                I agree to the terms and conditions
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Place Order & Pay
                            </button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Return to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @php $total = 0 @endphp
                    @if(session('cart'))
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <tr>
                                            <td class="w-50">
                                                <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="img-thumbnail me-2" width="50" height="50" style="object-fit: cover;" onerror="this.src='https://via.placeholder.com/50x50?text=No+Image'">
                                                {{ $details['name'] }} x {{ $details['quantity'] }}
                                            </td>
                                            <td class="text-end">
                                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total</strong>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    @else
                        <p class="text-center">Your cart is empty</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <p>We accept various payment methods through Midtrans:</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <img src="https://midtrans.com/assets/images/logo-bca.svg" alt="BCA" width="60">
                        <img src="https://midtrans.com/assets/images/logo-bni.svg" alt="BNI" width="60">
                        <img src="https://midtrans.com/assets/images/logo-mandiri.svg" alt="Mandiri" width="60">
                        <img src="https://midtrans.com/assets/images/logo-bri.svg" alt="BRI" width="60">
                        <img src="https://midtrans.com/assets/images/logo-gopay.svg" alt="GoPay" width="60">
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">You'll select your payment method in the next step</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const termsCheck = document.getElementById('termsCheck');
        if (!termsCheck.checked) {
            e.preventDefault();
            alert('You must agree to the terms and conditions to proceed.');
            return false;
        }
    });
</script>
@endsection