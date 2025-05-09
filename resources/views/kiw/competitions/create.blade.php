@extends('layouts.admin')

@section('title', 'Tambah Kompetisi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tambah Kompetisi Baru</h1>
        <a href="{{ route('kiw.competitions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Form Tambah Kompetisi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kiw.competitions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kompetisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Biaya Pendaftaran (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required min="0">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Kode Kompetisi</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Opsional, akan dibuat otomatis jika kosong">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="registration_start" class="form-label">Tanggal Mulai Pendaftaran <span class="text-danger">*</span></label>
                                <input type="date" class="form-control datepicker @error('registration_start') is-invalid @enderror" id="registration_start" name="registration_start" value="{{ old('registration_start') }}" required>
                                @error('registration_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="registration_end" class="form-label">Tanggal Akhir Pendaftaran <span class="text-danger">*</span></label>
                                <input type="date" class="form-control datepicker @error('registration_end') is-invalid @enderror" id="registration_end" name="registration_end" value="{{ old('registration_end') }}" required>
                                @error('registration_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Persyaratan</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4">{{ old('requirements') }}</textarea>
                            <small class="text-muted">Tuliskan persyaratan kompetisi, satu baris per persyaratan</small>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="prizes" class="form-label">Hadiah</label>
                            <textarea class="form-control @error('prizes') is-invalid @enderror" id="prizes" name="prizes" rows="4">{{ old('prizes') }}</textarea>
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
                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maks: 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('active') is-invalid @enderror" type="checkbox" id="active" name="active" value="1" {{ old('active') !== '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">Kompetisi Aktif</label>
                            </div>
                            @error('active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kompetisi aktif akan muncul di halaman pendaftaran dan dapat diikuti oleh peserta jika dalam periode pendaftaran.</small>
                        </div>
                        
                        <div class="mb-3">
                            <div id="imagePreviewContainer" class="text-center d-none mt-3">
                                <h6>Preview Gambar</h6>
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 d-flex justify-content-between">
                    <a href="{{ route('kiw.competitions.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Kompetisi</button>
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