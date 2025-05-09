<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Kiw (Super Admin) has access to everything
        if ($user->isKiw()) {
            return $next($request);
        }
        
        // Admin has access to admin, juri and peserta areas
        if ($user->isAdmin() && (in_array('admin', $roles) || in_array('juri', $roles) || in_array('peserta', $roles))) {
            return $next($request);
        }
        
        // Juri has access to juri areas
        if ($user->isJuri() && in_array('juri', $roles)) {
            return $next($request);
        }
        
        // Peserta has access to peserta areas
        if ($user->isPeserta() && in_array('peserta', $roles)) {
            return $next($request);
        }
        
        // Handle specific redirects based on the current role
        if ($user->hasRole('peserta')) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } elseif ($user->hasRole('juri')) {
            return redirect()->route('judge.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } elseif ($user->hasRole('kiw')) {
            return redirect()->route('kiw.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
        
        // Default redirect
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}