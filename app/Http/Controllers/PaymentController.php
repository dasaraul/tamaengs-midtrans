<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MidtransHelper;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Inisialisasi konfigurasi Midtrans di constructor
        MidtransHelper::initMidtransConfig();
    }

    public function show($id)
    {
        try {
            // Debug untuk menemukan masalah
            Log::info('Payment start:', ['order_id' => $id]);
            
            $order = Order::findOrFail($id);
            
            // Check if order belongs to logged-in user
            if ($order->user_id != Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            if (empty($order->snap_token)) {
                // Jika tidak ada snap token, coba regenerate
                try {
                    MidtransHelper::initMidtransConfig();
                    
                    // Generate order_id dengan format ORD-ID-TIMESTAMP
                    $orderId = 'ORD-' . $order->id . '-' . time();
                    
                    // Pastikan gross_amount lebih dari 0.01 (disadari dari error)
                    $grossAmount = (int)$order->total_price;
                    if ($grossAmount < 1) {
                        $grossAmount = 10000; // Fallback ke nilai default
                    }
                    
                    // Sangat disederhanakan tanpa mengakses order item atau products
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
                    
                    Log::info('Midtrans params:', ['params' => $params]);
                    
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    Log::info('Snap token generated:', ['token' => $snapToken]);
                    
                    $order->snap_token = $snapToken;
                    $order->transaction_id = $orderId; // Simpan order_id yang digunakan di Midtrans
                    $order->save();
                    
                    Log::info('Token saved to order');
                } catch (\Exception $e) {
                    Log::error('Midtrans Payment Error: ' . $e->getMessage(), [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return redirect()->route('orders.index')
                        ->with('error', 'Terjadi kesalahan dengan pembayaran: ' . $e->getMessage());
                }
            }

            $clientKey = config('midtrans.client_key');
            $isProduction = config('midtrans.is_production');

            return view('payment.show', compact('order', 'clientKey', 'isProduction'));
        } catch (\Exception $e) {
            Log::error('Payment Show Error: ' . $e->getMessage(), [
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
        MidtransHelper::initMidtransConfig();
        
        $serverKey = config('midtrans.server_key');
        
        try {
            $notification = new \Midtrans\Notification();
            
            Log::info('Midtrans notification received', ['order_id' => $notification->order_id]);
            
            // Cari order berdasarkan transaction_id yang disimpan sebelumnya
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
        Log::info('Payment finish', ['request' => $request->all()]);
        return redirect()->route('orders.index')
            ->with('success', 'Pembayaran berhasil, Pesanan Anda sedang diproses');
    }

    public function unfinish(Request $request)
    {
        Log::info('Payment unfinish', ['request' => $request->all()]);
        return redirect()->route('orders.index')
            ->with('info', 'Pembayaran belum selesai, silakan selesaikan pembayaran Anda');
    }

    public function error(Request $request)
    {
        Log::error('Payment error', ['request' => $request->all()]);
        return redirect()->route('orders.index')
            ->with('error', 'Pembayaran gagal, silakan coba lagi nanti');
    }
}