@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Order #{{ $order->order_number }}</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <p class="mb-1">Order Date: {{ $order->created_at->format('d M Y, H:i') }}</p>
            
            @if($order->payment_status == 'unpaid' || $order->payment_status == 'pending')
                <a href="{{ route('payment.show', $order->id) }}" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i> Pay Now
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            @if($order->notes)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Status:</strong>
                        @if($order->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status == 'processing')
                            <span class="badge bg-info">Processing</span>
                        @elseif($order->status == 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($order->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Status:</strong>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->payment_status == 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-secondary">Unpaid</span>
                        @endif
                    </div>
                    
                    @if($order->payment_method)
                        <div class="mb-3">
                            <strong>Payment Method:</strong>
                            <span>{{ ucfirst($order->payment_method) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">{{ $order->shipping_phone }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $order->shipping_email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to My Orders
        </a>
    </div>
</div>
@endsection