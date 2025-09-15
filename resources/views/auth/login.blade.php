@extends('layouts.loger')

@section('content')
<div class="container">
    <div class="form-content">
        <div class="text-side">
            <div class="title">Login</div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-boxes">
                    <!-- Input untuk Username atau Email -->
                    <div class="input-box">
                        <i class="fas fa-user"></i>
                        <input 
                            type="text" 
                            name="login" 
                            placeholder="Masukkan username atau email" 
                            value="{{ old('login') }}" 
                            class="{{ $errors->has('login') ? 'error' : '' }}" 
                            required>
                        @error('login')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input untuk Password -->
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Masukkan password Anda" 
                            required 
                            class="{{ $errors->has('password') ? 'error' : '' }}">
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Pesan Lupa Password -->
                    <div class="text"><a href="{{ route('password.request') }}">Lupa password?</a></div>

                    <!-- Tombol Login -->
                    <div class="button input-box">
                        <input type="submit" value="Login">
                    </div>

                    <!-- Sign-up Link -->
                    <div class="text sign-up-text">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></div>
                </div>
            </form>
        </div>

        <div class="image-side">
            <img src="{{ asset('images/frontImg.png') }}" alt="Login Image">
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush
