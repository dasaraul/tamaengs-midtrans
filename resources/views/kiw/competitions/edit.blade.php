@extends('layouts.admin')

@section('title', 'Edit Kompetisi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Kompetisi</h1>
        <a href="{{ route('kiw.competitions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Kompetisi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kiw.competitions.update', $competition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kompetisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $competition->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $competition->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Biaya Pendaftaran (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $competition->price) }}" required min="0">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Kode Kompetisi</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $competition->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="registration_start" class="form-label">Tanggal Mulai Pendaftaran <span class="text-danger">*</span></label>
                                <input type="date" class="form-control datepicker @error('registration_start') is-invalid @enderror" id="registration_start" name="registration_start" value="{{ old('registration_start', $competition->registration_start ? $competition->registration_start->format('Y-m-d') : '') }}" required>
                                @error('registration_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="registration_end" class="form-label">Tanggal Akhir Pendaftaran <span class="text-danger">*</span></label>
                                <input type="date" class="form-control datepicker @error('registration_end') is-invalid @enderror" id="registration_end" name="registration_end" value="{{ old('registration_end', $competition->registration_end ? $competition->registration_end->format('Y-m-d') : '') }}" required>
                                @error('registration_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Persyaratan</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4">{{ old('requirements', $competition->requirements) }}</textarea>
                            <small class="text-muted">Tuliskan persyaratan kompetisi, satu baris per persyaratan</small>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="prizes" class="form-label">Hadiah</label>
                            <textarea class="form-control @error('prizes') is-invalid @enderror" id="prizes" name="prizes" rows="4">{{ old('prizes', $competition->prizes) }}</textarea>
                            <small class="text-muted">Tuliskan hadiah kompetisi, satu baris per hadiah</small>
                            @error('prizes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Kompetisi</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maks: 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('active') is-invalid @enderror" type="checkbox" id="active" name="active" value="1" {{ (old('active', $competition->active) ? 'checked' : '') }}>
                                <label class="form-check-label" for="active">Kompetisi Aktif</label>
                            </div>
                            @error('active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kompetisi aktif akan muncul di halaman pendaftaran dan dapat diikuti oleh peserta jika dalam periode pendaftaran.</small>
                        </div>
                        
                        <div class="mb-3">
                            @if($competition->image)
                            <div class="text-center mt-3">
                                <h6>Gambar Saat Ini</h6>
                                <img src="{{ asset('storage/' . $competition->image) }}" alt="{{ $competition->name }}" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            @endif
                            
                            <div id="imagePreviewContainer" class="text-center d-none mt-3">
                                <h6>Preview Gambar Baru</h6>
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>
                        
                        <div class="card mt-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kompetisi</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Tanggal Dibuat</th>
                                        <td>{{ $competition->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Diupdate</th>
                                        <td>{{ $competition->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Pendaftar</th>
                                        <td>{{ $competition->getRegistrationsCount() ?? '0' }} peserta</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Kriteria</th>
                                        <td>{{ $competition->evaluationCriterias()->count() ?? '0' }} kriteria</td>
                                    </tr>
                                </table>
                                
                                <div class="d-grid gap-2 mt-3">
                                    <a href="{{ route('kiw.competitions.criteria', $competition->id) }}" class="btn btn-primary">
                                        <i class="fas fa-list-check me-2"></i>Kelola Kriteria
                                    </a>
                                    <a href="{{ route('kiw.competitions.show', $competition->id) }}" class="btn btn-info text-white">
                                        <i class="fas fa-eye me-2"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 d-flex justify-content-between">
                    <a href="{{ route('kiw.competitions.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('d-none');
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreviewContainer.classList.add('d-none');
            }
        });
        
        // Ensure end date is after start date
        const startDateInput = document.getElementById('registration_start');
        const endDateInput = document.getElementById('registration_end');
        
        startDateInput.addEventListener('change', function() {
            if (endDateInput.value && startDateInput.value > endDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
            endDateInput.min = startDateInput.value;
        });
        
        // Initialize date constraints
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
    });
</script>
@endsection