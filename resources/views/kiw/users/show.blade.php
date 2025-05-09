@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Pengguna</h1>
        <div>
            <a href="{{ route('kiw.users.edit', $user->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('kiw.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div style="width: 100px; height: 100px; background-color: #e9ecef; border-radius: 50%; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; color: #0e3e72;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    
                    @if($user->role == 'kiw')
                        <div class="badge bg-danger px-3 py-2 mb-3">Super Admin</div>
                    @elseif($user->role == 'admin')
                        <div class="badge bg-primary px-3 py-2 mb-3">Admin</div>
                    @elseif($user->role == 'juri')
                        <div class="badge bg-success px-3 py-2 mb-3">Juri</div>
                    @else
                        <div class="badge bg-warning text-dark px-3 py-2 mb-3">Peserta</div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        @if($user->is_active)
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>Akun Aktif
                            </div>
                        @else
                            <div class="alert alert-secondary mb-0">
                                <i class="fas fa-times-circle me-2"></i>Akun Non-Aktif
                            </div>
                        @endif
                    </div>
                </div>
                
                <ul class="list-group list-group-flush">
                    @if($user->phone)
                    <li class="list-group-item">
                        <i class="fas fa-phone me-2 text-primary"></i>{{ $user->phone }}
                    </li>
                    @endif
                    <li class="list-group-item">
                        <i class="fas fa-calendar me-2 text-primary"></i>Terdaftar: {{ $user->created_at->format('d M Y') }}
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-clock me-2 text-primary"></i>Terakhir diperbarui: {{ $user->updated_at->format('d M Y H:i') }}
                    </li>
                </ul>
                
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('kiw.users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Pengguna
                        </a>
                        @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt me-2"></i>Hapus Pengguna
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            @if($user->role == 'peserta')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Informasi Akademik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Institusi/Universitas</h6>
                            <p>{{ $user->institution ?? 'Tidak diisi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Fakultas</h6>
                            <p>{{ $user->faculty ?? 'Tidak diisi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>NPM/NIM</h6>
                            <p>{{ $user->npm ?? 'Tidak diisi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Semester</h6>
                            <p>{{ $user->semester ?? 'Tidak diisi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Aktivitas</h5>
                </div>
                <div class="card-body">
                    @if($user->role == 'peserta')
                        <h6>Kompetisi yang Diikuti</h6>
                        @php
                            $registrations = \App\Models\Order::where('user_id', $user->id)
                                ->where('payment_status', 'paid')
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp
                        
                        @if($registrations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tim</th>
                                            <th>Kompetisi</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registrations as $registration)
                                            <tr>
                                                <td>{{ $registration->id }}</td>
                                                <td>{{ $registration->team_name }}</td>
                                                <td>{{ $registration->getCompetitionNames() }}</td>
                                                <td>{{ $registration->created_at->format('d M Y') }}</td>
                                                <td>
                                                    @if($registration->status == 'completed')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @elseif($registration->status == 'processing')
                                                        <span class="badge bg-info">Proses</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Pengguna belum mengikuti kompetisi apapun.
                            </div>
                        @endif
                    @elseif($user->role == 'juri')
                        <h6>Kompetisi yang Dinilai</h6>
                        @php
                            $evaluations = \App\Models\Evaluation::where('user_id', $user->id)
                                ->with('competition')
                                ->select('product_id')
                                ->distinct()
                                ->get();
                        @endphp
                        
                        @if($evaluations->count() > 0)
                            <div class="list-group mb-4">
                                @foreach($evaluations as $evaluation)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $evaluation->competition->name ?? 'Kompetisi tidak ditemukan' }}</h6>
                                            <small>{{ $evaluation->competition->registration_end->format('d M Y') ?? 'N/A' }}</small>
                                        </div>
                                        <p class="mb-1">{{ Str::limit($evaluation->competition->description ?? '', 100) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <h6>Statistik Penilaian</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h3 class="mb-0">{{ \App\Models\Evaluation::where('user_id', $user->id)->count() }}</h3>
                                            <small class="text-muted">Total Penilaian</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h3 class="mb-0">{{ $evaluations->count() }}</h3>
                                            <small class="text-muted">Kompetisi Dinilai</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-3 text-center">
                                            <h3 class="mb-0">{{ \App\Models\Evaluation::where('user_id', $user->id)->avg('score') ? number_format(\App\Models\Evaluation::where('user_id', $user->id)->avg('score'), 1) : 'N/A' }}</h3>
                                            <small class="text-muted">Rata-rata Nilai</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Pengguna belum melakukan penilaian apapun.
                            </div>
                        @endif
                    @elseif($user->role == 'admin' || $user->role == 'kiw')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Pengguna dengan level {{ $user->role == 'kiw' ? 'Super Admin' : 'Admin' }} memiliki akses ke fitur manajemen sistem.
                        </div>
                        
                        @php
                            $lastLoginActivity = null; // Idealnya diambil dari tabel activity_log
                            $userActions = []; // Idealnya diambil dari tabel activity_log
                        @endphp
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-3">Login Terakhir</h6>
                                        <p class="mb-1">{{ $lastLoginActivity ? $lastLoginActivity->created_at->format('d M Y H:i') : 'Tidak ada data' }}</p>
                                        <small class="text-muted">{{ $lastLoginActivity ? $lastLoginActivity->created_at->diffForHumans() : '' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="mb-3">Total Aktivitas</h6>
                                        <p class="mb-0">{{ count($userActions) }} tindakan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($user->id !== auth()->id())
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Tindakan Keamanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kiw.users.update', $user->id) }}" method="POST" id="security-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="toggle_active" {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="toggle_active">
                                    {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                                </label>
                                <input type="hidden" name="is_active" value="{{ $user->is_active ? '1' : '0' }}" id="is_active_input">
                            </div>
                            <small class="text-muted">{{ $user->is_active ? 'Menonaktifkan akun akan mencegah pengguna untuk login' : 'Mengaktifkan akun akan memungkinkan pengguna untuk login' }}</small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning" id="security-action-btn">
                                {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->name }}</strong>?</p>
                    <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('kiw.users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleActive = document.getElementById('toggle_active');
        const isActiveInput = document.getElementById('is_active_input');
        const securityActionBtn = document.getElementById('security-action-btn');
        
        if (toggleActive && isActiveInput && securityActionBtn) {
            toggleActive.addEventListener('change', function() {
                const isChecked = this.checked;
                isActiveInput.value = isChecked ? '1' : '0';
                securityActionBtn.textContent = isChecked ? 'Aktifkan Akun' : 'Nonaktifkan Akun';
            });
        }
    });
</script>
@endsection