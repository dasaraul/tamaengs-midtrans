<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin {email} {password}';
    protected $description = 'Create an admin user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'is_admin' => 1,
                'password' => Hash::make($password)
            ]);
            $this->info("User {$email} telah diperbarui sebagai admin.");
        } else {
            $name = explode('@', $email)[0];
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => 1
            ]);
            $this->info("Admin dengan email {$email} berhasil dibuat!");
        }

        return 0;
    }
}