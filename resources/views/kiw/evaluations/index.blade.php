@extends('layouts.admin')

@section('title', 'Penilaian')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Penilaian</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Penilaian</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kiw.evaluations.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="competition_id" class="form-label">Kompetisi</label>
                        <select class="form-select select2" id="competition_id" name="competition_id">
                            <option value="">Semua Kompetisi</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="judge_id" class="form-label">Juri</label>
                        <select class="form-select select2" id="judge_id" name="judge_id">
                            <option value="">Semua Juri</option>
                            @foreach($judges as $judge)
                                <option value="{{ $judge->id }}" {{ request('judge_id') == $judge->id ? 'selected' : '' }}>{{ $judge->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('kiw.evaluations.index') }}" class="btn btn-secondary me-2">Reset</a>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Daftar Penilaian</h5>
                </div>
                <div class="col-md-6">
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Cari penilaian..." aria-label="Search" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kompetisi</th>
                            <th>Tim</th>
                            <th>Juri</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $evaluation)
                        <tr>
                            <td>{{ $evaluation->id }}</td>
                            <td>{{ $evaluation->competition->name ?? 'N/A' }}</td>
                            <td>{{ $evaluation->order->team_name ?? 'N/A' }}</td>
                            <td>{{ $evaluation->judge->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-primary p-2">{{ $evaluation->score }}</span>
                            </td>
                            <td>
                                @if($evaluation->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($evaluation->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">Submitted</span>
                                @endif
                            </td>
                            <td>{{ $evaluation->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('kiw.evaluations.show', $evaluation->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('kiw.evaluations.edit', $evaluation->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $evaluation->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal{{ $evaluation->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $evaluation->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $evaluation->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus penilaian ini?</p>
                                                <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('kiw.evaluations.destroy', $evaluation->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-star fa-3x text-secondary mb-3"></i>
                                    <h5>Tidak ada penilaian yang ditemukan</h5>
                                    <p class="text-muted">Tidak ada data penilaian yang sesuai dengan filter</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($evaluations->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-end">
                {{ $evaluations->appends(request()->except('page'))->links() }}
            </div>
        </div>
        @endif
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Penilaian</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Tentang Penilaian</h6>
                        <p>Penilaian dilakukan oleh juri yang ditugaskan pada kompetisi tertentu. Setiap penilaian menggunakan kriteria yang telah ditetapkan untuk kompetisi tersebut.</p>
                        <p class="mb-0">Status penilaian dapat berupa:</p>
                        <ul class="mb-0">
                            <li><strong>Submitted</strong> - Penilaian telah disubmit oleh juri</li>
                            <li><strong>Approved</strong> - Penilaian telah disetujui oleh admin</li>
                            <li><strong>Rejected</strong> - Penilaian ditolak dan perlu diperiksa kembali</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips Pengelolaan Penilaian</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Pastikan setiap kompetisi memiliki minimal 2-3 juri untuk mendapatkan penilaian yang objektif</li>
                        <li>Periksa dan setujui penilaian yang berstatus "Submitted" secara berkala</li>
                        <li>Pastikan kriteria penilaian telah disiapkan sebelum juri mulai memberikan penilaian</li>
                        <li>Nilai akhir dihitung berdasarkan bobot setiap kriteria penilaian</li>
                        <li>Hapus penilaian hanya jika terjadi kesalahan input atau duplikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when filters change
        const filterInputs = document.querySelectorAll('#competition_id, #judge_id, #status');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });
</script>
@endsection