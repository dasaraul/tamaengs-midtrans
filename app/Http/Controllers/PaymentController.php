<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['notification']);
        // Don't initialize Midtrans config in constructor
    }

    public function show($id)
    {
        try {
            // Debug for tracing
            Log::info('Payment show method started:', ['order_id' => $id]);
            
            $order = Order::findOrFail($id);
            
            // Check if order belongs to logged-in user
            if ($order->user_id != Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (empty($order->snap_token)) {
                // If no snap token, generate one
                try {
                    // Initialize Midtrans Config directly
                    $serverKey = config('midtrans.server_key');
                    $clientKey = config('midtrans.client_key');
                    $isProduction = config('midtrans.is_production');
                    
                    if (empty($serverKey) || empty($clientKey)) {
                        throw new \Exception('Midtrans keys are not properly configured. Please contact the administrator.');
                    }
                    
                    // Set Midtrans configuration directly
                    \Midtrans\Config::$serverKey = $serverKey;
                    \Midtrans\Config::$clientKey = $clientKey;
                    \Midtrans\Config::$isProduction = $isProduction;
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;
                    
                    // Generate order_id with format ORD-ID-TIMESTAMP
                    $orderId = 'ORD-' . $order->id . '-' . time();
                    
                    // Make sure gross_amount is valid (must be at least 1)
                    $grossAmount = (int)$order->total_price;
                    if ($grossAmount < 1) {
                        $grossAmount = 10000; // Fallback to default value
                    }
                    
                    // Simple params structure for Midtrans
                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => $grossAmount,
                        ],
                        'customer_details' => [
                            'first_name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                        ],
                    ];
                    
                    Log::info('Midtrans params prepared:', ['params' => $params]);
                    
                    // Get snap token from Midtrans
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    Log::info('Snap token successfully generated:', ['token' => $snapToken]);
                    
                    // Save the token and transaction ID
                    $order->snap_token = $snapToken;
                    $order->transaction_id = $orderId;
                    $order->save();
                    
                    Log::info('Order updated with token');
                } catch (\Exception $e) {
                    Log::error('Midtrans Token Generation Error: ' . $e->getMessage(), [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return redirect()->route('orders.index')
                        ->with('error', 'Terjadi kesalahan dengan pembayaran: ' . $e->getMessage());
                }
            }

            // Return the view with necessary data
            $clientKey = config('midtrans.client_key');
            $isProduction = config('midtrans.is_production');

            return view('payment.show', compact('order', 'clientKey', 'isProduction'));
        } catch (\Exception $e) {
            Log::error('Payment Show Method Error: ' . $e->getMessage(), [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('orders.index')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function notification(Request $request)
    {
        // Initialize Midtrans Config directly
        $serverKey = config('midtrans.server_key');
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        try {
            $notification = new \Midtrans\Notification();
            
            Log::info('Midtrans notification received', ['order_id' => $notification->order_id]);
            
            // Find order based on transaction_id
            $order = Order::where('transaction_id', $notification->order_id)->first();
            
            if (!$order) {
                Log::warning('Order not found for notification', ['order_id' => $notification->order_id]);
                return response('Order not found', 404);
            }
            
            // Handle payment status
            if ($notification->transaction_status == 'capture' || $notification->transaction_status == 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'processing';
            } elseif ($notification->transaction_status == 'pending') {
                $order->payment_status = 'pending';
            } elseif ($notification->transaction_status == 'deny' || $notification->transaction_status == 'expire' || $notification->transaction_status == 'cancel') {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
            }
            
            $order->payment_method = $notification->payment_type;
            $order->save();
            
            Log::info('Order status updated', [
                'order_id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]);
            
            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function finish(Request $request)
    {
        // Fix: Don't try to find an Order model with 'finish'
        Log::info('Payment finish callback received', ['request' => $request->all()]);
        
        // Only redirect to orders.index with success message
        return redirect()->route('orders.index')
            ->with('success', 'Pembayaran berhasil, Pesanan Anda sedang diproses');
    }

    public function unfinish(Request $request)
    {
        Log::info('Payment unfinish callback received', ['request' => $request->all()]);
        return redirect()->route('orders.index')
            ->with('info', 'Pembayaran belum selesai, silakan selesaikan pembayaran Anda');
    }

    public function error(Request $request)
    {
        Log::error('Payment error callback received', ['request' => $request->all()]);
        return redirect()->route('orders.index')
            ->with('error', 'Pembayaran gagal, silakan coba lagi nanti');
    }
}