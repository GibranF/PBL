@php
    if (auth()->check()) {
        if (auth()->user()->usertype == 'admin') {
            $layout = 'layouts.admin';
        } elseif (auth()->user()->usertype == 'owner') {
            $layout = 'layouts.owner';
        } else {
            $layout = 'layouts.cust';
        }
    } else {
        $layout = 'layouts.cust';
    }
@endphp

@extends($layout)
@section('title', 'Profile')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Judul --}}
                <h2 class="mb-4 fw-bold text-dark text-center">PROFILE</h2>

                {{-- Error (gabungan untuk profil & hapus akun) --}}
                @if ($errors->any() || $errors->userDeletion->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan :</strong>
                        <ul class="mb-0">
                            {{-- Error umum (update profil, password, dll) --}}
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach

                            {{-- Error khusus hapus akun --}}
                            @foreach ($errors->userDeletion->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="row g-4">
                    {{-- Foto Profil --}}
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center p-4 h-100">
                            <div class="position-relative d-inline-block mx-auto mb-3">
                                {{-- Gambar profil --}}

                                <img id="profilePreview"
                                    src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/default-foto.png') }}"
                                    alt="Foto Profil" class="rounded-circle shadow"
                                    style="width: 200px; height: 200px; object-fit: cover;">

                                {{-- Tombol edit foto --}}
                                <button type="button"
                                    class="btn btn-primary rounded-circle position-absolute d-flex align-items-center justify-content-center shadow"
                                    style="bottom: 0; right: 0; width: 40px; height: 40px; padding: 0;"
                                    onclick="document.getElementById('profilePhotoInput').click()">
                                    <i class="bi bi-image" style="font-size: 1.2rem;"></i>
                                </button>

                            </div>

                            {{-- Tombol Hapus Foto --}}
                            @if ($user->profile_photo)
                                <form method="POST" action="{{ route('profile.delete-photo') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Hapus foto profil?')">
                                        <i class="bi bi-trash"></i> Hapus Foto
                                    </button>
                                </form>
                            @endif

                            {{-- Tulisan menarik di bawah foto --}}
                            <p class="mt-3 text-muted fst-italic">
                                “Jadilah versi terbaik dari dirimu sendiri ✨”
                            </p>
                        </div>
                    </div>

                    {{-- Form Profil --}}
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 p-4">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                {{-- Input File (hidden) --}}
                                <input type="file" name="profile_photo" id="profilePhotoInput"
                                    class="d-none @error('profile_photo') is-invalid @enderror" accept="image/*">
                                @error('profile_photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                {{-- Popup sukses --}}
                                @if (session('success'))
                                    <div id="successPopup" class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Sukses!</strong> {{ session('success') }}
                                    </div>
                                @endif

                                {{-- Nama --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                {{-- Alamat --}}
                                <div class="mb-3">
                                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                    <input type="text" id="alamat" name="alamat" class="form-control"
                                        value="{{ old('alamat', $user->alamat) }}" required>
                                </div>

                                {{-- Nomor HP --}}
                                <div class="mb-3">
                                    <label for="nomor_hp" class="form-label fw-semibold">Nomor HP</label>
                                    <input type="text" id="nomor_hp" name="nomor_hp" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15"
                                        class="form-control" value="{{ old('nomor_hp', $user->nomor_hp) }}" required>
                                </div>

                                {{-- Tombol Simpan --}}
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="card shadow-sm border-0 mt-5">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Update Password</h5>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Hapus Akun --}}
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 text-danger">Hapus Akun</h5>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Bootstrap Icons CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profilePhotoInput = document.getElementById('profilePhotoInput');
            const profilePreview = document.getElementById('profilePreview');
            const successPopup = document.getElementById('successPopup');

            // Preview image sebelum upload
            profilePhotoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        profilePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Auto-hide success alert
            if (successPopup) {
                setTimeout(() => {
                    successPopup.classList.remove('show');
                    successPopup.classList.add('fade');
                }, 2500);
            }
        });
    </script>
@endpush