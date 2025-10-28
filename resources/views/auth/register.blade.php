@extends('layouts.loger')

@section('content')
    <div class="container">
        <div class="form-content">
            <div class="image-side">
                <img src="{{ asset('images/backImg.png') }}" alt="Register Image">
            </div>
            <div class="text-side">
                <div class="title">Daftar Akun</div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-boxes">

                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="Masukkan nama anda"
                                value="{{ old('name') }}" title="Masukkan nama lengkap Anda"
                                class="@error('name') error @enderror">
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Masukkan email anda"
                                value="{{ old('email') }}" title="Masukkan email aktif"
                                class="@error('email') error @enderror">
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password minimal 8 karakter" required
                                title="Gunakan password yang kuat" class="@error('password') error @enderror">
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi password anda"
                                required title="Konfirmasi password yang sama"
                                class="@error('password_confirmation') error @enderror">
                            {{-- We check for a specific error on this field --}}
                            @error('password_confirmation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="input-box">
                            <i class="fas fa-home"></i>
                            <input type="text" name="alamat" placeholder="Masukkan alamat lengkap anda"
                                value="{{ old('alamat') }}" title="Masukkan alamat lengkap"
                                class="@error('alamat') error @enderror">
                            @error('alamat')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-box">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="nomor_hp"
                                placeholder="Nomor seluler min 10, max 15 digit"
                                value="{{ old('nomor_hp') }}" required maxlength="15" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                title="Nomor HP hanya boleh angka, min 10, max 15 digit"
                                class="@error('nomor_hp') error @enderror">
                            @error('nomor_hp')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="button input-box">
                            <input type="submit" value="Submit">
                        </div>

                        <div class="text sign-up-text">
                            Sudah punya akun? <a href="{{ route('login') }}">Langsung login saja</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush