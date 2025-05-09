<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Submission;
use App\Models\Evaluation;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Display the participant dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user registrations/orders
        $registrations = Order::where('user_id', $user->id)->get();
        
        // Get registrations with paid status for submissions
        $pendingSubmissions = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->get();
        
        // Get user submissions
        $submissions = Submission::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        
        // Get user evaluations
        $evaluations = Evaluation::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        // Get active competitions
        $activeCompetitions = Product::where('active', 1)
            ->where('registration_end', '>=', now())
            ->take(4)
            ->get();
        
        // Count summary data
        $registrationCount = $registrations->count();
        $submissionCount = $submissions->count();
        $evaluationCount = $evaluations->count();
        
        return view('participant.dashboard', compact(
            'registrations',
            'pendingSubmissions',
            'submissions',
            'evaluations',
            'activeCompetitions',
            'registrationCount',
            'submissionCount',
            'evaluationCount'
        ));
    }
}