<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        // Pastikan semua item di cart memiliki kunci 'description'
        $cart = session()->get('cart', []);
        
        // Jika ada cart yang tidak memiliki description, tambahkan description kosong
        foreach ($cart as $id => $item) {
            if (!isset($item['description'])) {
                $product = Product::find($id);
                if ($product) {
                    $cart[$id]['description'] = $product->description ?? '';
                } else {
                    $cart[$id]['description'] = '';
                }
            }
        }
        
        // Update session cart
        session()->put('cart', $cart);
        
        return view('cart.index');
    }

    public function add(Request $request)
    {
        // Ambil ID produk dari request (bisa dari product_id atau id)
        $productId = $request->product_id ?? $request->id;
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            // Lomba sudah ada di pendaftaran
            return redirect()->back()->with('info', 'Anda sudah mendaftar untuk kompetisi ini.');
        } else {
            // Tambahkan lomba ke pendaftaran dengan semua data yang diperlukan
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1, 
                "price" => $product->price,
                "image" => $product->image,
                "description" => $product->description ?? '' // Pastikan description selalu ada
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Kompetisi berhasil ditambahkan ke pendaftaran!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Pendaftaran berhasil diperbarui!');
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        }
        return redirect()->back()->with('success', 'Kompetisi berhasil dihapus dari pendaftaran!');
    }
}