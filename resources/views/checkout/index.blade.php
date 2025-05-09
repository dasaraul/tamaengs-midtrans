@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="theme-heading text-center">Formulir Pendaftaran Kompetisi</h1>
            <p class="text-center">Silakan lengkapi data peserta untuk melanjutkan pendaftaran</p>
        </div>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Peserta</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('checkout.process') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="team_name" class="form-label">Nama Tim <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('team_name') is-invalid @enderror" id="team_name" name="team_name" value="{{ old('team_name') }}" required>
                                @error('team_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="institution" class="form-label">Universitas/Institusi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('institution') is-invalid @enderror" id="institution" name="institution" value="{{ old('institution') }}" required>
                                @error('institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <h5 class="mt-4 mb-3">Ketua Tim</h5>
                            
                            <div class="mb-3">
                                <label for="leader_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('leader_name') is-invalid @enderror" id="leader_name" name="leader_name" value="{{ old('leader_name') }}" required>
                                @error('leader_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="leader_npm" class="form-label">NPM/NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('leader_npm') is-invalid @enderror" id="leader_npm" name="leader_npm" value="{{ old('leader_npm') }}" required>
                                @error('leader_npm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="leader_semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                    <select class="form-select @error('leader_semester') is-invalid @enderror" id="leader_semester" name="leader_semester" required>
                                        <option value="" selected disabled>Pilih Semester</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ old('leader_semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('leader_semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="leader_faculty" class="form-label">Fakultas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('leader_faculty') is-invalid @enderror" id="leader_faculty" name="leader_faculty" value="{{ old('leader_faculty') }}" required>
                                    @error('leader_faculty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="leader_phone" class="form-label">Nomor Telepon/WA <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('leader_phone') is-invalid @enderror" id="leader_phone" name="leader_phone" value="{{ old('leader_phone') }}" required>
                                @error('leader_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="leader_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('leader_email') is-invalid @enderror" id="leader_email" name="leader_email" value="{{ old('leader_email') }}" required>
                                @error('leader_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <h5 class="mt-4 mb-3">Anggota Tim</h5>
                            
                            <div id="members-container">
                                <div class="member-form mb-4 pb-3 border-bottom">
                                    <h6>Anggota 1</h6>
                                    
                                    <div class="mb-3">
                                        <label for="member_name_1" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="member_name_1" name="member_name[]" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="member_npm_1" class="form-label">NPM/NIM <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="member_npm_1" name="member_npm[]" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="member_semester_1" class="form-label">Semester <span class="text-danger">*</span></label>
                                            <select class="form-select" id="member_semester_1" name="member_semester[]" required>
                                                <option value="" selected disabled>Pilih Semester</option>
                                                @for($i = 1; $i <= 8; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="member_faculty_1" class="form-label">Fakultas <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="member_faculty_1" name="member_faculty[]" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <button type="button" id="add-member" class="btn btn-outline-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah Anggota
                                </button>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="agree_terms" name="agree_terms" required>
                                <label class="form-check-label" for="agree_terms">
                                    Saya menyetujui syarat dan ketentuan lomba yang berlaku
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">Lanjutkan ke Pembayaran</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $total = 0;
                        @endphp
                        
                        <h6 class="mb-3">Kompetisi yang Dipilih:</h6>
                        
                        <ul class="list-group mb-3">
                            @foreach(session('cart') as $id => $details)
                                @php
                                    $total += $details['price'];
                                @endphp
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">{{ $details['name'] }}</h6>
                                    </div>
                                    <span>Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="d-flex justify-content-between mt-3 mb-0">
                            <span class="fw-bold">Total Biaya:</span>
                            <span class="competition-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Informasi</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <i class="fas fa-info-circle text-info me-2"></i> Pastikan data yang diisi sudah benar
                            </li>
                            <li class="list-group-item px-0">
                                <i class="fas fa-info-circle text-info me-2"></i> Satu tim terdiri dari minimal 3 dan maksimal 5 anggota
                            </li>
                            <li class="list-group-item px-0">
                                <i class="fas fa-info-circle text-info me-2"></i> Pembayaran dilakukan melalui gateway Midtrans
                            </li>
                            <li class="list-group-item px-0">
                                <i class="fas fa-info-circle text-info me-2"></i> Untuk informasi lebih lanjut, hubungi panitia
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <p class="text-center mb-0">Anda belum memilih kompetisi. <a href="{{ route('products.index') }}">Pilih kompetisi sekarang!</a></p>
        </div>
    @endif
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addMemberBtn = document.getElementById('add-member');
        const membersContainer = document.getElementById('members-container');
        let memberCount = 1;
        
        addMemberBtn.addEventListener('click', function() {
            if (memberCount < 4) { // Max 4 additional members (total 5 including team leader)
                memberCount++;
                
                const memberForm = document.createElement('div');
                memberForm.className = 'member-form mb-4 pb-3 border-bottom';
                memberForm.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Anggota ${memberCount}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-member">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <label for="member_name_${memberCount}" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="member_name_${memberCount}" name="member_name[]" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="member_npm_${memberCount}" class="form-label">NPM/NIM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="member_npm_${memberCount}" name="member_npm[]" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="member_semester_${memberCount}" class="form-label">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="member_semester_${memberCount}" name="member_semester[]" required>
                                <option value="" selected disabled>Pilih Semester</option>
                                ${Array.from({length: 8}, (_, i) => i + 1)
                                    .map(num => `<option value="${num}">${num}</option>`)
                                    .join('')}
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="member_faculty_${memberCount}" class="form-label">Fakultas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="member_faculty_${memberCount}" name="member_faculty[]" required>
                        </div>
                    </div>
                `;
                
                membersContainer.appendChild(memberForm);
                
                // Check if max members reached
                if (memberCount >= 4) {
                    addMemberBtn.disabled = true;
                }
                
                // Add event listener to remove button
                const removeBtn = memberForm.querySelector('.remove-member');
                removeBtn.addEventListener('click', function() {
                    membersContainer.removeChild(memberForm);
                    memberCount--;
                    addMemberBtn.disabled = false;
                    
                    // Renumber remaining member forms
                    const memberForms = membersContainer.querySelectorAll('.member-form');
                    memberForms.forEach((form, index) => {
                        form.querySelector('h6').textContent = `Anggota ${index + 1}`;
                    });
                });
            }
        });
    });
</script>
@endsection
@endsection