@extends('layouts.admin')

@section('title', 'Kelola Kompetisi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Kelola Kompetisi</h1>
        <a href="{{ route('kiw.competitions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Tambah Kompetisi
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

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Daftar Kompetisi</h5>
                </div>
                <div class="col-md-6">
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Cari kompetisi..." aria-label="Search" name="search" value="{{ request('search') }}">
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
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Harga</th>
                            <th>Periode Pendaftaran</th>
                            <th>Status</th>
                            <th>Pendaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($competitions as $competition)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($competition->image)
                                        <img src="{{ asset('storage/' . $competition->image) }}" alt="{{ $competition->name }}" class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary me-2 rounded text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $competition->name }}</div>
                                        <small class="text-muted">{{ Str::limit($competition->description, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary">{{ $competition->code }}</span></td>
                            <td>Rp {{ number_format($competition->price, 0, ',', '.') }}</td>
                            <td>
                                <small>
                                    <div><i class="far fa-calendar-alt me-1"></i> {{ $competition->registration_start ? $competition->registration_start->format('d M Y') : 'N/A' }}</div>
                                    <div><i class="far fa-calendar-check me-1"></i> {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}</div>
                                </small>
                            </td>
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
                            <td>
                                {{ $competition->getRegistrationsCount() ?? '0' }} peserta
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('kiw.competitions.show', $competition->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('kiw.competitions.edit', $competition->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('kiw.competitions.criteria', $competition->id) }}" class="btn btn-sm btn-primary" title="Kriteria Penilaian">
                                        <i class="fas fa-list-check"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $competition->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal{{ $competition->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $competition->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $competition->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus kompetisi <strong>{{ $competition->name }}</strong>?</p>
                                                <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan. Kompetisi yang memiliki pendaftaran atau karya tidak dapat dihapus.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('kiw.competitions.destroy', $competition->id) }}" method="POST">
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
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-trophy fa-3x text-secondary mb-3"></i>
                                    <h5>Tidak ada kompetisi yang ditemukan</h5>
                                    <p class="text-muted">Tambahkan kompetisi baru atau coba pencarian yang berbeda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($competitions->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-end">
                {{ $competitions->links() }}
            </div>
        </div>
        @endif
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Panduan Pengelolaan Kompetisi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Membuat Kompetisi</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>Gunakan nama yang jelas dan deskriptif</li>
                                        <li>Tentukan periode pendaftaran yang cukup</li>
                                        <li>Sertakan deskripsi detail tentang kompetisi</li>
                                        <li>Tambahkan gambar menarik untuk kompetisi</li>
                                        <li>Jelaskan persyaratan dan hadiah dengan jelas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Mengelola Kriteria</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>Tambahkan kriteria penilaian yang obyektif</li>
                                        <li>Tetapkan bobot untuk setiap kriteria</li>
                                        <li>Tentukan skor maksimal yang dapat diberikan</li>
                                        <li>Beri deskripsi jelas untuk setiap kriteria</li>
                                        <li>Sesuaikan kriteria dengan jenis kompetisi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Tips Pengelolaan</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        <li>Nonaktifkan kompetisi daripada menghapusnya</li>
                                        <li>Perbarui status secara berkala</li>
                                        <li>Tambahkan kriteria sebelum peserta mendaftar</li>
                                        <li>Tetapkan jumlah minimum juri untuk setiap kompetisi</li>
                                        <li>Pastikan periode pendaftaran sesuai jadwal acara</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection