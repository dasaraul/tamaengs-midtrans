<?php

namespace App\Http\Controllers\Kiw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = [
            [
                'id' => 'kiw',
                'name' => 'Super Admin (KIW)',
                'description' => 'Memiliki akses penuh ke seluruh sistem, termasuk mengelola admin lain',
                'users_count' => User::where('role', 'kiw')->count(),
                'permissions' => [
                    'Kelola semua pengguna',
                    'Kelola semua kompetisi',
                    'Kelola semua penilaian',
                    'Kelola semua pengaturan sistem',
                    'Akses laporan lengkap'
                ]
            ],
            [
                'id' => 'admin',
                'name' => 'Admin',
                'description' => 'Dapat mengelola lomba, peserta, dan juri',
                'users_count' => User::where('role', 'admin')->count(),
                'permissions' => [
                    'Kelola kompetisi',
                    'Kelola peserta',
                    'Kelola juri',
                    'Kelola pendaftaran',
                    'Kelola pembayaran',
                    'Akses laporan dasar'
                ]
            ],
            [
                'id' => 'juri',
                'name' => 'Juri',
                'description' => 'Dapat menilai karya peserta pada lomba yang ditugaskan',
                'users_count' => User::where('role', 'juri')->count(),
                'permissions' => [
                    'Melihat daftar peserta',
                    'Mengakses karya peserta',
                    'Memberikan penilaian',
                    'Memberikan feedback'
                ]
            ],
            [
                'id' => 'peserta',
                'name' => 'Peserta',
                'description' => 'Dapat mendaftar lomba dan mengumpulkan karya',
                'users_count' => User::where('role', 'peserta')->count(),
                'permissions' => [
                    'Mendaftar lomba',
                    'Melakukan pembayaran',
                    'Mengumpulkan karya',
                    'Melihat hasil penilaian'
                ]
            ]
        ];
        
        return view('kiw.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('kiw.roles.index')
            ->with('info', 'Penambahan role baru hanya dapat dilakukan melalui pengembangan sistem.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('kiw.roles.index')
            ->with('info', 'Penambahan role baru hanya dapat dilakukan melalui pengembangan sistem.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!in_array($id, ['kiw', 'admin', 'juri', 'peserta'])) {
            return redirect()->route('kiw.roles.index')
                ->with('error', 'Role tidak ditemukan.');
        }
        
        $role = [
            'id' => $id,
            'name' => $this->getRoleName($id),
            'description' => $this->getRoleDescription($id),
            'users' => User::where('role', $id)->orderBy('name')->paginate(10),
            'permissions' => $this->getRolePermissions($id)
        ];
        
        return view('kiw.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!in_array($id, ['kiw', 'admin', 'juri', 'peserta'])) {
            return redirect()->route('kiw.roles.index')
                ->with('error', 'Role tidak ditemukan.');
        }
        
        $role = [
            'id' => $id,
            'name' => $this->getRoleName($id),
            'description' => $this->getRoleDescription($id),
            'permissions' => $this->getRolePermissions($id)
        ];
        
        return view('kiw.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('kiw.roles.show', $id)
            ->with('info', 'Pengubahan izin role hanya dapat dilakukan melalui pengembangan sistem. Silakan hubungi pengembang untuk melakukan perubahan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('kiw.roles.index')
            ->with('error', 'Penghapusan role tidak diperbolehkan. Role merupakan bagian integral dari sistem.');
    }
    
    /**
     * Helper methods for role data
     */
    private function getRoleName($roleId)
    {
        $names = [
            'kiw' => 'Super Admin (KIW)',
            'admin' => 'Admin',
            'juri' => 'Juri',
            'peserta' => 'Peserta'
        ];
        
        return $names[$roleId] ?? 'Unknown Role';
    }
    
    private function getRoleDescription($roleId)
    {
        $descriptions = [
            'kiw' => 'Memiliki akses penuh ke seluruh sistem, termasuk mengelola admin lain',
            'admin' => 'Dapat mengelola lomba, peserta, dan juri',
            'juri' => 'Dapat menilai karya peserta pada lomba yang ditugaskan',
            'peserta' => 'Dapat mendaftar lomba dan mengumpulkan karya'
        ];
        
        return $descriptions[$roleId] ?? 'No description available';
    }
    
    private function getRolePermissions($roleId)
    {
        $permissions = [
            'kiw' => [
                'Kelola semua pengguna',
                'Kelola semua kompetisi',
                'Kelola semua penilaian',
                'Kelola semua pengaturan sistem',
                'Akses laporan lengkap'
            ],
            'admin' => [
                'Kelola kompetisi',
                'Kelola peserta',
                'Kelola juri',
                'Kelola pendaftaran',
                'Kelola pembayaran',
                'Akses laporan dasar'
            ],
            'juri' => [
                'Melihat daftar peserta',
                'Mengakses karya peserta',
                'Memberikan penilaian',
                'Memberikan feedback'
            ],
            'peserta' => [
                'Mendaftar lomba',
                'Melakukan pembayaran',
                'Mengumpulkan karya',
                'Melihat hasil penilaian'
            ]
        ];
        
        return $permissions[$roleId] ?? [];
    }
}