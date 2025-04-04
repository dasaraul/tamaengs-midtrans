<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!session()->has('cart') || count(session()->get('cart')) == 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong!');
        }

        return view('checkout.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'shipping_address' => 'required',
            'shipping_phone' => 'required',
            'shipping_email' => 'required|email',
        ]);

        $cart = session()->get('cart');
        $totalAmount = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Create Order
        $order = new Order();
        $order->user_id = Auth::id();
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->total_amount = $totalAmount;
        $order->status = 'pending';
        $order->payment_status = 'unpaid';
        $order->shipping_address = $request->shipping_address;
        $order->shipping_phone = $request->shipping_phone;
        $order->shipping_email = $request->shipping_email;
        $order->notes = $request->notes;
        $order->save();

        // Create Order Items
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $id;
            $orderItem->quantity = $details['quantity'];
            $orderItem->price = $details['price'];
            $orderItem->subtotal = $details['price'] * $details['quantity'];
            $orderItem->save();

            // Update stock
            $product->stock -= $details['quantity'];
            $product->save();
        }

        // KUNCI PERBAIKAN: Set konfigurasi Midtrans secara manual di sini
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // Generate Midtrans Token
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int)$totalAmount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => $request->shipping_email,
                'phone' => $request->shipping_phone,
                'billing_address' => [
                    'address' => $request->shipping_address,
                ],
                'shipping_address' => [
                    'address' => $request->shipping_address,
                ]
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();

            // Clear cart
            session()->forget('cart');

            return redirect()->route('payment.show', $order->id);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Illuminate\Support\Facades\Log::error('Midtrans Error: ' . $e->getMessage());
            
            // Rollback order (optional)
            // $order->delete(); 
            
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam pemrosesan pembayaran: ' . $e->getMessage());
        }
    }
}