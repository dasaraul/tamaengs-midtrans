<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super-admin {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Super Admin (KIW) user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Email for Super Admin?', 'admin@example.com');
        $password = $this->argument('password') ?? $this->secret('Password for Super Admin?') ?? 'password123';
        
        // Check if the email is already used
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            if ($this->confirm("A user with email {$email} already exists. Do you want to convert this user to Super Admin?")) {
                $existingUser->role = 'kiw';
                $existingUser->is_active = 1;
                $existingUser->save();
                
                $this->info("User {$email} has been updated to Super Admin (KIW) role.");
                return;
            } else {
                $this->error('Operation cancelled.');
                return;
            }
        }
        
        // Create new Super Admin
        $user = new User();
        $user->name = 'Super Admin';
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role = 'kiw';
        $user->is_active = 1;
        $user->save();
        
        $this->info("Super Admin (KIW) created with email: {$email}");
        $this->info("You can now login at: /login");
        $this->info("After login, access the Super Admin dashboard at: /kiw/dashboard");
    }
}