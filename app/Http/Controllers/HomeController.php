<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 5 produk terbaru berdasarkan created_at
        $products = Product::orderBy('created_at', 'desc')
                    ->where('stock', '>', 0) // Opsional: hanya tampilkan produk dengan stock > 0
                    ->take(5)
                    ->get();
                    
        return view('home', compact('products'));
    }
}