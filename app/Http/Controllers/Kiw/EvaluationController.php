<?php

namespace App\Http\Controllers\Kiw;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationScore;
use App\Models\Product;
use App\Models\User;
use App\Models\Submission;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Evaluation::with(['judge', 'competition', 'order']);
        
        // Filter by competition
        if ($request->has('competition_id')) {
            $query->where('product_id', $request->competition_id);
        }
        
        // Filter by judge
        if ($request->has('judge_id')) {
            $query->where('user_id', $request->judge_id);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $evaluations = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Data for filters
        $competitions = Product::orderBy('name')->get();
        $judges = User::where('role', 'juri')->orderBy('name')->get();
        
        return view('kiw.evaluations.index', compact('evaluations', 'competitions', 'judges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validation
        if (!$request->has('submission_id')) {
            return redirect()->route('kiw.evaluations.index')
                ->with('error', 'Submission ID is required');
        }
        
        $submission = Submission::findOrFail($request->submission_id);
        $competition = Product::findOrFail($submission->product_id);
        $criteria = $competition->criteria;
        $judges = User::where('role', 'juri')->orderBy('name')->get();
        
        // Check if criteria exist
        if ($criteria->isEmpty()) {
            return redirect()->route('kiw.submissions.show', $submission->id)
                ->with('error', 'Tidak dapat membuat penilaian karena belum ada kriteria penilaian untuk kompetisi ini');
        }
        
        return view('kiw.evaluations.create', compact('submission', 'competition', 'criteria', 'judges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'user_id' => 'required|exists:users,id',
            'feedback' => 'nullable|string',
            'score.*' => 'required|numeric|min:0|max:100',
            'notes.*' => 'nullable|string',
        ]);
        
        // Get submission and competition data
        $submission = Submission::findOrFail($request->submission_id);
        $competition = Product::findOrFail($submission->product_id);
        $order = Order::findOrFail($submission->order_id);
        
        // Check if judge has already evaluated this submission
        $existingEvaluation = Evaluation::where('order_id', $order->id)
            ->where('user_id', $request->user_id)
            ->where('product_id', $competition->id)
            ->first();
            
        if ($existingEvaluation) {
            return redirect()->back()
                ->with('error', 'Juri ini telah memberikan penilaian untuk submission ini')
                ->withInput();
        }
        
        // Calculate total score
        $totalScore = 0;
        $totalWeight = 0;
        
        foreach ($request->score as $criteriaId => $score) {
            $criteria = $competition->criteria()->findOrFail($criteriaId);
            $totalScore += $score * $criteria->weight;
            $totalWeight += $criteria->weight;
        }
        
        $finalScore = $totalWeight > 0 ? round($totalScore / $totalWeight) : 0;
        
        // Create evaluation
        DB::beginTransaction();
        
        try {
            $evaluation = Evaluation::create([
                'order_id' => $order->id,
                'user_id' => $request->user_id,
                'product_id' => $competition->id,
                'score' => $finalScore,
                'feedback' => $request->feedback,
                'status' => 'submitted',
            ]);
            
            // Store individual criteria scores
            foreach ($request->score as $criteriaId => $score) {
                EvaluationScore::create([
                    'evaluation_id' => $evaluation->id,
                    'evaluation_criteria_id' => $criteriaId,
                    'score' => $score,
                    'notes' => $request->notes[$criteriaId] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('kiw.evaluations.show', $evaluation->id)
                ->with('success', 'Penilaian berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['judge', 'competition', 'order', 'scores.criteria']);
        
        // Get submission related to this evaluation
        $submission = Submission::where('order_id', $evaluation->order_id)
            ->where('product_id', $evaluation->product_id)
            ->first();
        
        return view('kiw.evaluations.show', compact('evaluation', 'submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        $evaluation->load(['judge', 'competition', 'order', 'scores.criteria']);
        
        // Get submission related to this evaluation
        $submission = Submission::where('order_id', $evaluation->order_id)
            ->where('product_id', $evaluation->product_id)
            ->first();
            
        if (!$submission) {
            return redirect()->route('kiw.evaluations.index')
                ->with('error', 'Karya terkait penilaian ini tidak ditemukan');
        }
        
        // Get all judges
        $judges = User::where('role', 'juri')->orderBy('name')->get();
        
        return view('kiw.evaluations.edit', compact('evaluation', 'submission', 'judges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'feedback' => 'nullable|string',
            'score.*' => 'required|numeric|min:0|max:100',
            'notes.*' => 'nullable|string',
        ]);
        
        // Check if changing judge and if the new judge already has evaluation for this submission
        if ($request->user_id != $evaluation->user_id) {
            $existingEvaluation = Evaluation::where('order_id', $evaluation->order_id)
                ->where('user_id', $request->user_id)
                ->where('product_id', $evaluation->product_id)
                ->where('id', '!=', $evaluation->id)
                ->first();
                
            if ($existingEvaluation) {
                return redirect()->back()
                    ->with('error', 'Juri ini telah memberikan penilaian untuk submission ini')
                    ->withInput();
            }
        }
        
        // Calculate total score
        $totalScore = 0;
        $totalWeight = 0;
        $criteria = $evaluation->competition->criteria;
        
        foreach ($request->score as $criteriaId => $score) {
            $criterion = $criteria->find($criteriaId);
            if ($criterion) {
                $totalScore += $score * $criterion->weight;
                $totalWeight += $criterion->weight;
            }
        }
        
        $finalScore = $totalWeight > 0 ? round($totalScore / $totalWeight) : 0;
        
        // Update evaluation
        DB::beginTransaction();
        
        try {
            $evaluation->update([
                'user_id' => $request->user_id,
                'score' => $finalScore,
                'feedback' => $request->feedback,
                'status' => $request->status ?? 'submitted',
            ]);
            
            // Update individual criteria scores
            foreach ($request->score as $criteriaId => $score) {
                $evaluationScore = EvaluationScore::where('evaluation_id', $evaluation->id)
                    ->where('evaluation_criteria_id', $criteriaId)
                    ->first();
                    
                if ($evaluationScore) {
                    $evaluationScore->update([
                        'score' => $score,
                        'notes' => $request->notes[$criteriaId] ?? null,
                    ]);
                } else {
                    EvaluationScore::create([
                        'evaluation_id' => $evaluation->id,
                        'evaluation_criteria_id' => $criteriaId,
                        'score' => $score,
                        'notes' => $request->notes[$criteriaId] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('kiw.evaluations.show', $evaluation->id)
                ->with('success', 'Penilaian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        // Delete all related scores first
        $evaluation->scores()->delete();
        
        // Delete the evaluation
        $evaluation->delete();
        
        return redirect()->route('kiw.evaluations.index')
            ->with('success', 'Penilaian berhasil dihapus');
    }
}