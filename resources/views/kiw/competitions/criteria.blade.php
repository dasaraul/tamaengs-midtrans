@extends('layouts.admin')

@section('title', 'Kelola Kriteria Penilaian')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Kelola Kriteria Penilaian</h1>
        <a href="{{ route('kiw.competitions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
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

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Kriteria Penilaian</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kiw.competitions.criteria.store', $competition->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            <small class="text-muted">Jelaskan kriteria penilaian ini secara detail</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Bobot (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" required min="1" max="100">
                                <small class="text-muted">Total bobot sebaiknya 100%</small>
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="max_score" class="form-label">Skor Maksimal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_score') is-invalid @enderror" id="max_score" name="max_score" value="{{ old('max_score', 10) }}" required min="1" max="100">
                                @error('max_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Simpan Kriteria
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Kriteria Penilaian - {{ $competition->name }}</h5>
                        <span class="badge bg-primary">Total: {{ $criteria->count() }} Kriteria</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Nama Kriteria</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%">Bobot (%)</th>
                                    <th width="15%">Skor Maksimal</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($criteria as $key => $criterion)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $criterion->name }}</span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($criterion->description, 50) ?: 'Tidak ada deskripsi' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $criterion->weight }}%;" aria-valuenow="{{ $criterion->weight }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="badge bg-secondary">{{ $criterion->weight }}%</span>
                                        </div>
                                    </td>
                                    <td>{{ $criterion->max_score }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $criterion->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $criterion->id }}" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal{{ $criterion->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $criterion->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="editModalLabel{{ $criterion->id }}">Edit Kriteria</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('kiw.competitions.criteria.update', ['competition' => $competition->id, 'criterion' => $criterion->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="edit_name{{ $criterion->id }}" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="edit_name{{ $criterion->id }}" name="name" value="{{ $criterion->name }}" required>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="edit_description{{ $criterion->id }}" class="form-label">Deskripsi</label>
                                                                <textarea class="form-control" id="edit_description{{ $criterion->id }}" name="description" rows="3">{{ $criterion->description }}</textarea>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="edit_weight{{ $criterion->id }}" class="form-label">Bobot (%) <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" id="edit_weight{{ $criterion->id }}" name="weight" value="{{ $criterion->weight }}" required min="1" max="100">
                                                                </div>
                                                                
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="edit_max_score{{ $criterion->id }}" class="form-label">Skor Maksimal <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" id="edit_max_score{{ $criterion->id }}" name="max_score" value="{{ $criterion->max_score }}" required min="1" max="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteModal{{ $criterion->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $criterion->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $criterion->id }}">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus kriteria <strong>{{ $criterion->name }}</strong>?</p>
                                                        <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan. Menghapus kriteria akan menghilangkan semua penilaian terkait kriteria ini.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('kiw.competitions.criteria.destroy', ['competition' => $competition->id, 'criterion' => $criterion->id]) }}" method="POST">
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
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                                            <h5>Belum ada kriteria penilaian</h5>
                                            <p class="text-muted">Tambahkan kriteria penilaian untuk kompetisi ini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Kompetisi -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kompetisi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th width="40%">Nama Kompetisi</th>
                                    <td width="60%">{{ $competition->name }}</td>
                                </tr>
                                <tr>
                                    <th>Kode</th>
                                    <td><span class="badge bg-secondary">{{ $competition->code }}</span></td>
                                </tr>
                                <tr>
                                    <th>Biaya Pendaftaran</th>
                                    <td>Rp {{ number_format($competition->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($competition->active)
                                            @if($competition->registration_end && $competition->registration_end->isPast())
                                                <span class="badge bg-secondary">Ditutup</span>
                                            @elseif($competition->registration_start && $competition->registration_start->isFuture())
                                                <span class="badge bg-info">Akan Dibuka</span>
                                            @else
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th width="40%">Total Kriteria</th>
                                    <td width="60%"><span class="badge bg-primary">{{ $criteria->count() }}</span></td>
                                </tr>
                                <tr>
                                    <th>Total Bobot</th>
                                    <td>
                                        @php $totalWeight = $criteria->sum('weight'); @endphp
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar {{ $totalWeight == 100 ? 'bg-success' : ($totalWeight < 100 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ min($totalWeight, 100) }}%;" aria-valuenow="{{ $totalWeight }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="mt-1 d-block">
                                            {{ $totalWeight }}% dari 100% 
                                            @if($totalWeight != 100)
                                                <span class="text-{{ $totalWeight < 100 ? 'warning' : 'danger' }}">
                                                    ({{ $totalWeight < 100 ? 'Kurang ' . (100 - $totalWeight) . '%' : 'Kelebihan ' . ($totalWeight - 100) . '%' }})
                                                </span>
                                            @endif
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Periode Pendaftaran</th>
                                    <td>
                                        <small>
                                            <div><i class="far fa-calendar-alt me-1"></i> {{ $competition->registration_start ? $competition->registration_start->format('d M Y') : 'N/A' }}</div>
                                            <div><i class="far fa-calendar-check me-1"></i> {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}</div>
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Pendaftar</th>
                                    <td>{{ $competition->getRegistrationsCount() ?? '0' }} peserta</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tips -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips Pengaturan Kriteria</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul>
                                <li>Pastikan total bobot semua kriteria adalah 100%</li>
                                <li>Gunakan nama kriteria yang jelas dan deskriptif</li>
                                <li>Sertakan deskripsi detail untuk membantu juri dalam menilai</li>
                                <li>Berikan bobot lebih tinggi pada kriteria yang lebih penting</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li>Standarisasi skor maksimal untuk semua kriteria (mis. 10 atau 100)</li>
                                <li>Tetapkan kriteria yang objektif dan dapat diukur</li>
                                <li>Sesuaikan kriteria dengan jenis dan tujuan kompetisi</li>
                                <li>Pastikan kriteria ditetapkan sebelum kompetisi dimulai</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight total weight if not 100%
        const totalWeight = {{ $criteria->sum('weight') }};
        const totalWeightDisplay = document.querySelector('.progress-bar');
        
        if (totalWeight < 100) {
            totalWeightDisplay.classList.add('bg-warning');
        } else if (totalWeight > 100) {
            totalWeightDisplay.classList.add('bg-danger');
        } else {
            totalWeightDisplay.classList.add('bg-success');
        }
    });
</script>
@endsection