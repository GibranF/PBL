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

                        <!-- Name -->
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="Masukkan nama anda" value="{{ old('name') }}" required
                                title="Masukkan nama lengkap Anda">
                        </div>

                        <!-- Email -->
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Masukkan email anda" value="{{ old('email') }}"
                                required title="Masukkan email aktif">
                        </div>

                        <!-- Password -->
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password minimal 8 karakter" required
                                title="Gunakan password yang kuat" class="@error('password') error @enderror">
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi password anda" required
                                title="Konfirmasi password yang sama" class="@error('password') error @enderror">
                                @error('password')
                            @enderror
                        </div>


                        <!-- Address -->
                        <div class="input-box">
                            <i class="fas fa-home"></i>
                            <input type="text" name="alamat" placeholder="Masukkan alamat lengkap anda" value="{{ old('alamat') }}"
                                required title="Masukkan alamat lengkap">
                        </div>

                        <!-- Phone Number -->
                        <div class="input-box">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="nomor_hp" placeholder="Masukkan nomor seluler anda"
                                value="{{ old('nomor_hp') }}" required maxlength="15" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                title="Nomor HP hanya boleh angka dan maksimal 15 digit">
                        </div>

                        <!-- Submit -->
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