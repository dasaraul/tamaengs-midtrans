<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Model ini sudah diubah menjadi model untuk lomba

class HomeController extends Controller
{
    // Metode ini tidak digunakan oleh rute saat ini, tapi bisa digunakan jika Anda ingin menambahkan data dinamis
    // ke halaman home di masa depan
    public function index()
    {
        return view('home');
    }
    
    // Anda bisa menambahkan metode baru misalnya jika ingin membuat halaman beranda dengan data dinamis
    public function home()
    {
        // Ambil semua lomba aktif dan yang periode pendaftarannya masih berlangsung
        $competitions = Product::where('active', true)
                      ->whereDate('registration_end', '>=', now())
                      ->orderBy('created_at', 'desc')
                      ->take(4) // Ambil 4 lomba untuk ditampilkan di home
                      ->get();
        
        // Data untuk timeline pendaftaran
        $registrationTimeline = [
            [
                'period' => 'Pendaftaran Awal',
                'dates' => '23 - 28 Juli',
                'price' => 'Rp300.000/Tim',
                'note' => '* Khusus Mahasiswa Universitas Nasional'
            ],
            [
                'period' => 'Tahap 1',
                'dates' => '29 Juli - 11 Agustus',
                'price' => 'Rp400.000/Tim',
                'note' => '* Khusus Mahasiswa Universitas Nasional'
            ],
            [
                'period' => 'Tahap 2',
                'dates' => '12 Agustus - 07 September',
                'price' => 'Rp450.000/Tim',
                'note' => '* Khusus Mahasiswa Universitas Nasional'
            ]
        ];
                      
        return view('home', compact('competitions', 'registrationTimeline'));
    }
}