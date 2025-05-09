<?php

namespace App\Http\Controllers\Kiw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Get app settings from config or DB
        $appName = config('app.name');
        $appEnvironment = config('app.env');
        $appDebug = config('app.debug');
        $appUrl = config('app.url');
        
        // Get mail settings
        $mailDriver = config('mail.default');
        $mailHost = config('mail.mailers.smtp.host');
        $mailPort = config('mail.mailers.smtp.port');
        $mailUsername = config('mail.mailers.smtp.username');
        $mailEncryption = config('mail.mailers.smtp.encryption');
        $mailFromAddress = config('mail.from.address');
        $mailFromName = config('mail.from.name');
        
        // Get Midtrans settings
        $midtransServerKey = config('midtrans.server_key');
        $midtransClientKey = config('midtrans.client_key');
        $midtransIsProduction = config('midtrans.is_production');
        
        // Get system info
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $databaseType = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $webServer = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown';
        
        // Last maintenance 
        $lastMaintenanceDate = Storage::exists('framework/down') 
            ? date('Y-m-d H:i:s', filemtime(storage_path('framework/down'))) 
            : 'Never';
        
        // Get storage usage
        $totalStorage = disk_total_space(storage_path());
        $freeStorage = disk_free_space(storage_path());
        $usedStorage = $totalStorage - $freeStorage;
        $storageUsagePercentage = ($usedStorage / $totalStorage) * 100;
        
        return view('kiw.settings.index', compact(
            'appName', 'appEnvironment', 'appDebug', 'appUrl',
            'mailDriver', 'mailHost', 'mailPort', 'mailUsername', 'mailEncryption', 'mailFromAddress', 'mailFromName',
            'midtransServerKey', 'midtransClientKey', 'midtransIsProduction',
            'phpVersion', 'laravelVersion', 'databaseType', 'webServer',
            'lastMaintenanceDate', 'totalStorage', 'freeStorage', 'usedStorage', 'storageUsagePercentage'
        ));
    }

    /**
     * Update the application settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string|max:255',
            'midtrans_server_key' => 'nullable|string',
            'midtrans_client_key' => 'nullable|string',
            'midtrans_is_production' => 'boolean',
        ]);
        
        // Update .env file
        $this->updateEnvFile([
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
            'MIDTRANS_SERVER_KEY' => $request->midtrans_server_key,
            'MIDTRANS_CLIENT_KEY' => $request->midtrans_client_key,
            'MIDTRANS_IS_PRODUCTION' => $request->has('midtrans_is_production') ? 'true' : 'false',
        ]);
        
        // Clear cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        
        return redirect()->route('kiw.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui');
    }
    
    /**
     * Handle system maintenance mode.
     */
    public function maintenance(Request $request)
    {
        if ($request->maintenance_mode == 'down') {
            Artisan::call('down', [
                '--message' => $request->maintenance_message ?? 'Sistem sedang dalam pemeliharaan. Silakan coba lagi nanti.',
                '--retry' => $request->maintenance_retry ?? 60,
            ]);
            $message = 'Sistem berhasil dimasukkan ke mode pemeliharaan';
        } else {
            Artisan::call('up');
            $message = 'Sistem berhasil dikembalikan ke mode normal';
        }
        
        return redirect()->route('kiw.settings.index')
            ->with('success', $message);
    }
    
    /**
     * Handle cache clearing.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        return redirect()->route('kiw.settings.index')
            ->with('success', 'Cache berhasil dibersihkan');
    }
    
    /**
     * Update environment file.
     */
    private function updateEnvFile(array $data)
    {
        $envPath = app()->environmentFilePath();
        $envContent = file_get_contents($envPath);
        
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            
            // If value contains spaces, wrap in quotes
            if (strpos($value, ' ') !== false && strpos($value, '"') === false) {
                $value = '"' . $value . '"';
            }
            
            // Replace or add new environment variable
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }
        
        file_put_contents($envPath, $envContent);
    }
}