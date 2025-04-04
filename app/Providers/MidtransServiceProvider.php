<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MidtransServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('midtrans', function ($app) {
            // Debug logs untuk membantu debugging
            \Illuminate\Support\Facades\Log::info('Registering Midtrans with config values:', [
                'server_key_exists' => !empty(config('midtrans.server_key')),
                'client_key_exists' => !empty(config('midtrans.client_key')),
                'is_production' => config('midtrans.is_production'),
            ]);

            // Set Midtrans configuration
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$clientKey = config('midtrans.client_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
            
            return new \Midtrans\Config();
        });
    }

    public function boot()
    {
        //
    }
}