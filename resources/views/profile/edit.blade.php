@extends('layouts.app')
@section('title', 'Pengaturan Akun')

@section('content')
<div class="page-header">
    <div>
        <h1>Pengaturan Akun</h1>
        <p>Perbarui informasi profil dan keamanan akun Anda</p>
    </div>
</div>

<div class="grid-2">
    {{-- Email Update --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-envelope" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Update Email</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PATCH')
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    {{-- Password Update --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-lock" style="color: var(--warning); margin-right: 0.5rem;"></i>Ganti Password</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PATCH')
                {{-- Keep email for validation if shared route --}}
                <input type="hidden" name="email" value="{{ $user->email }}">
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                    @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-warning" style="color: #fff;">Update Password</button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            background: '#1e293b',
            color: '#f1f5f9',
            confirmButtonColor: '#6366f1'
        });
    });
</script>
@endif
@endsection
