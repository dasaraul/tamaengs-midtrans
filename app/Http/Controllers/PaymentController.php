<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MidtransHelper;

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
        $order = Order::findOrFail($id);
        
        // Check if order belongs to logged-in user
        if ($order->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (empty($order->snap_token)) {
            // Jika tidak ada snap token, coba regenerate
            try {
                MidtransHelper::initMidtransConfig();
                
                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_number,
                        'gross_amount' => (int)$order->total_amount,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => $order->shipping_email,
                        'phone' => $order->shipping_phone,
                        'billing_address' => [
                            'address' => $order->shipping_address,
                        ],
                        'shipping_address' => [
                            'address' => $order->shipping_address,
                        ]
                    ],
                ];
                
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $order->snap_token = $snapToken;
                $order->save();
            } catch (\Exception $e) {
                return redirect()->route('orders.index')
                    ->with('error', 'Terjadi kesalahan dengan pembayaran: ' . $e->getMessage());
            }
        }

        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        return view('payment.show', compact('order', 'clientKey', 'isProduction'));
    }

    public function notification(Request $request)
    {
        MidtransHelper::initMidtransConfig();
        
        $serverKey = config('midtrans.server_key');
        
        try {
            $notification = new \Midtrans\Notification();
            
            $order = Order::where('order_number', $notification->order_id)->first();
            
            if (!$order) {
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
            
            return response('OK', 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function finish(Request $request)
    {
        return redirect()->route('orders.index')
            ->with('success', 'Pembayaran berhasil, Pesanan Anda sedang diproses');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('orders.index')
            ->with('info', 'Pembayaran belum selesai, silakan selesaikan pembayaran Anda');
    }

    public function error(Request $request)
    {
        return redirect()->route('orders.index')
            ->with('error', 'Pembayaran gagal, silakan coba lagi nanti');
    }
}