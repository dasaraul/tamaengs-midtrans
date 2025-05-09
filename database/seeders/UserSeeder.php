<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat Super Admin (Kiw)
        User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'kiwnich@gmail.com',
            'password' => Hash::make('tamaesnich'),
            'role' => 'kiw',
            'is_active' => true
        ]);
        
        // Buat Admin biasa
        User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'adnich@gmail.com',
            'password' => Hash::make('tamaesnich'),
            'role' => 'admin',
            'is_active' => true
        ]);
        
        // Buat Juri
        User::firstOrCreate([
            'name' => 'Juri',
            'email' => 'jurnich@gmail.com',
            'password' => Hash::make('tamaesnich'),
            'role' => 'juri',
            'is_active' => true
        ]);
    }
}