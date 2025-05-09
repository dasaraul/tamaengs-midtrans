<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define Gates for roles
        
        // Kiw (Super Admin) can do everything
        Gate::define('kiw', function ($user) {
            return $user->isKiw();
        });
        
        // Admin can manage competitions, participants, and judges
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
        
        // Judges can evaluate submissions
        Gate::define('juri', function ($user) {
            return $user->isJuri() || $user->isAdmin();
        });
        
        // Participants can join competitions and submit their work
        Gate::define('peserta', function ($user) {
            return $user->isPeserta() || $user->isAdmin();
        });
        
        // Specific permissions
        
        // Manage users
        Gate::define('manage-users', function ($user) {
            return $user->isKiw();
        });
        
        // Manage admins
        Gate::define('manage-admins', function ($user) {
            return $user->isKiw();
        });
        
        // Manage judges
        Gate::define('manage-judges', function ($user) {
            return $user->isAdmin();
        });
        
        // Manage competitions
        Gate::define('manage-competitions', function ($user) {
            return $user->isAdmin();
        });
        
        // Evaluate submissions
        Gate::define('evaluate-submissions', function ($user, $competition = null) {
            if (!$user->isJuri()) {
                return false;
            }
            
            if ($competition) {
                return $user->evaluations()->where('product_id', $competition->id)->exists();
            }
            
            return true;
        });
        
        // Submit work
        Gate::define('submit-work', function ($user, $order) {
            return $user->id === $order->user_id && $order->payment_status === 'paid';
        });
    }
}