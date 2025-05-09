<?php

namespace App\Http\Controllers\Kiw;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Submission;
use App\Models\EvaluationCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $competitions = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('kiw.competitions.index', compact('competitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kiw.competitions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'code' => 'nullable|string|max:50|unique:products,code',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
            'requirements' => 'nullable|string',
            'prizes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $data = $request->except('image');
        
        // Generate unique code if not provided
        if (empty($data['code'])) {
            $data['code'] = Str::upper(Str::random(8));
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('competitions', 'public');
            $data['image'] = $imagePath;
        }
        
        // Set active status
        $data['active'] = $request->has('active');
        
        $competition = Product::create($data);
        
        return redirect()->route('kiw.competitions.index')
            ->with('success', 'Kompetisi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $competition)
    {
        // Get registrations count
        $registrationsCount = OrderItem::where('product_id', $competition->id)
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'paid');
            })->count();
        
        // Get submissions count
        $submissionsCount = Submission::where('product_id', $competition->id)->count();
        
        // Get evaluation criteria
        $criteria = EvaluationCriteria::where('product_id', $competition->id)->get();
        
        return view('kiw.competitions.show', compact('competition', 'registrationsCount', 'submissionsCount', 'criteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $competition)
    {
        return view('kiw.competitions.edit', compact('competition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $competition)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'code' => 'nullable|string|max:50|unique:products,code,' . $competition->id,
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
            'requirements' => 'nullable|string',
            'prizes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $data = $request->except(['image', '_token', '_method']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($competition->image) {
                Storage::disk('public')->delete($competition->image);
            }
            
            $imagePath = $request->file('image')->store('competitions', 'public');
            $data['image'] = $imagePath;
        }
        
        // Set active status
        $data['active'] = $request->has('active');
        
        $competition->update($data);
        
        return redirect()->route('kiw.competitions.index')
            ->with('success', 'Kompetisi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $competition)
    {
        // Check if competition has entries (registrations, submissions, etc)
        $hasRegistrations = OrderItem::where('product_id', $competition->id)->exists();
        $hasSubmissions = Submission::where('product_id', $competition->id)->exists();
        
        if ($hasRegistrations || $hasSubmissions) {
            return redirect()->route('kiw.competitions.index')
                ->with('error', 'Kompetisi tidak dapat dihapus karena memiliki data pendaftaran atau karya yang terkait');
        }
        
        // Delete image
        if ($competition->image) {
            Storage::disk('public')->delete($competition->image);
        }
        
        // Delete criteria
        EvaluationCriteria::where('product_id', $competition->id)->delete();
        
        $competition->delete();
        
        return redirect()->route('kiw.competitions.index')
            ->with('success', 'Kompetisi berhasil dihapus');
    }
    
    /**
     * Manage competition criteria
     */
    public function criteria(Product $competition)
    {
        $criteria = EvaluationCriteria::where('product_id', $competition->id)->get();
        return view('kiw.competitions.criteria', compact('competition', 'criteria'));
    }
    
    /**
     * Store new criteria
     */
    public function storeCriteria(Request $request, Product $competition)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:1|max:100',
            'max_score' => 'required|integer|min:1|max:100',
        ]);
        
        EvaluationCriteria::create([
            'product_id' => $competition->id,
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            'max_score' => $request->max_score,
        ]);
        
        return redirect()->route('kiw.competitions.criteria', $competition->id)
            ->with('success', 'Kriteria penilaian berhasil ditambahkan');
    }
    
    /**
     * Update criteria
     */
    public function updateCriteria(Request $request, Product $competition, EvaluationCriteria $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|integer|min:1|max:100',
            'max_score' => 'required|integer|min:1|max:100',
        ]);
        
        $criterion->update([
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            'max_score' => $request->max_score,
        ]);
        
        return redirect()->route('kiw.competitions.criteria', $competition->id)
            ->with('success', 'Kriteria penilaian berhasil diperbarui');
    }
    
    /**
     * Delete criteria
     */
    public function destroyCriteria(Product $competition, EvaluationCriteria $criterion)
    {
        $criterion->delete();
        
        return redirect()->route('kiw.competitions.criteria', $competition->id)
            ->with('success', 'Kriteria penilaian berhasil dihapus');
    }
}