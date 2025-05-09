<?php

namespace App\Http\Controllers\Kiw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Submission;
use App\Models\Evaluation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the KIW (super admin) dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $userDistribution = [
            'admin' => User::where('role', 'admin')->count(),
            'juri' => User::where('role', 'juri')->count(),
            'peserta' => User::where('role', 'peserta')->count(),
            'kiw' => User::where('role', 'kiw')->count(),
        ];
        
        // Latest users
        $latestUsers = User::latest()->take(5)->get();
        
        // Competition statistics
        $totalCompetitions = Product::count();
        $activeCompetitions = Product::where('active', 1)
            ->where('registration_end', '>=', now())
            ->take(5)
            ->get();
        
        // Payment statistics
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $latestPayments = Order::where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();
        
        // Evaluation statistics
        $totalEvaluations = Evaluation::count();
        
        // Trend data for chart (registrations and revenue by month)
        $trendData = $this->getTrendData();
        
        return view('kiw.dashboard', [
            'totalUsers' => $totalUsers,
            'userDistribution' => $userDistribution,
            'latestUsers' => $latestUsers,
            'totalCompetitions' => $totalCompetitions,
            'activeCompetitions' => $activeCompetitions,
            'totalRevenue' => $totalRevenue,
            'latestPayments' => $latestPayments,
            'totalEvaluations' => $totalEvaluations,
            'trendChartData' => $trendData,
        ]);
    }
    
    /**
     * Get trend data for charts.
     *
     * @return array
     */
    private function getTrendData()
    {
        $now = Carbon::now();
        $months = [];
        $registrationsData = [];
        $revenueData = [];
        
        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            // Registrations count
            $registrationsCount = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $registrationsData[] = $registrationsCount;
            
            // Revenue
            $revenue = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_price');
            $revenueData[] = $revenue;
        }
        
        return [
            'labels' => $months,
            'registrations' => $registrationsData,
            'revenue' => $revenueData,
        ];
    }
}