<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Smartphone XYZ',
                'description' => 'Smartphone terbaru dengan spesifikasi tinggi',
                'price' => 350,
                'image' => 'products/smartphone.jpg',
                'stock' => 15
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Laptop dengan performa tinggi untuk kebutuhan profesional',
                'price' => 150,
                'image' => 'products/laptop.jpg',
                'stock' => 8
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Jam tangan pintar dengan berbagai fitur canggih',
                'price' => 120,
                'image' => 'products/smartwatch.jpg',
                'stock' => 20
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Earbuds wireless dengan kualitas suara premium',
                'price' => 100,
                'image' => 'products/earbuds.jpg',
                'stock' => 25
            ],
            [
                'name' => 'Digital Camera',
                'description' => 'Kamera digital dengan hasil jepretan profesional',
                'price' => 200,
                'image' => 'products/camera.jpg',
                'stock' => 10
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}