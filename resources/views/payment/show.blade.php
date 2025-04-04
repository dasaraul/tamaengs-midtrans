@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Payment</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment for Order #{{ $order->order_number }}</h4>
                </div>
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-credit-card fa-5x text-primary mb-3"></i>
                        <h3>Total Amount: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
                        <p class="text-muted">Please complete your payment to confirm your order</p>
                    </div>
                    
                    <button type="button" id="pay-button" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-money-check-alt me-2"></i> Pay Now
                    </button>
                    
                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to My Orders
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">Your order will be processed once payment is confirmed</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" 
        src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
        data-client-key="{{ $clientKey }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        // Debug info
        console.log('Snap token: {{ $order->snap_token }}');
        console.log('Client key: {{ $clientKey }}');
        console.log('Is production: {{ $isProduction ? 'true' : 'false' }}');
        
        snap.pay('{{ $order->snap_token }}', {
            onSuccess: function(result) {
                console.log('success');
                console.log(result);
                window.location.href = '{{ route('payment.finish') }}?order_id={{ $order->order_number }}&status=success';
            },
            onPending: function(result) {
                console.log('pending');
                console.log(result);
                window.location.href = '{{ route('payment.unfinish') }}?order_id={{ $order->order_number }}&status=pending';
            },
            onError: function(result) {
                console.log('error');
                console.log(result);
                window.location.href = '{{ route('payment.error') }}?order_id={{ $order->order_number }}&status=error';
            },
            onClose: function() {
                console.log('customer closed the popup without finishing the payment');
                alert('You closed the payment window without completing the payment');
            }
        });
    };
</script>
@endsection