<?php

namespace App\Http\Controllers\Kiw;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('kiw.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kiw.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:kiw,admin,juri,peserta',
            'is_active' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'npm' => 'nullable|string|max:50',
            'semester' => 'nullable|integer|min:1|max:12',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'phone' => $request->phone,
            'institution' => $request->institution,
            'faculty' => $request->faculty,
            'npm' => $request->npm,
            'semester' => $request->semester,
        ]);

        return redirect()->route('kiw.users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('kiw.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('kiw.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:kiw,admin,juri,peserta',
            'is_active' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'npm' => 'nullable|string|max:50',
            'semester' => 'nullable|integer|min:1|max:12',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'phone' => $request->phone,
            'institution' => $request->institution,
            'faculty' => $request->faculty,
            'npm' => $request->npm,
            'semester' => $request->semester,
        ];

        // Only update password if it's provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('kiw.users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('kiw.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // Prevent deleting last super admin
        if ($user->role === 'kiw' && User::where('role', 'kiw')->count() <= 1) {
            return redirect()->route('kiw.users.index')->with('error', 'Tidak dapat menghapus Super Admin terakhir');
        }

        $user->delete();
        return redirect()->route('kiw.users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}