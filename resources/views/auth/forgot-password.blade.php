@extends('layouts.loger')

@section('content')
<div class="container">
    <div class="forms">
        <div class="form-content">
            <div class="login-form"> 
                <div class="title">Forgot Password</div>

                <div class="text">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="text" style="color: green; margin-top: 10px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="input-boxes">
                        <div class="input-box"> 
                            <i class="fas fa-envelope"></i> 
                            <input type="email"
                                   name="email"
                                   placeholder="Enter your email"
                                   value="{{ old('email') }}" 
                                   required
                                   autocomplete="email"
                                   autofocus>
                            @error('email')
                                <div class="error-message" style="color: red; font-size: 0.85em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="button">
                            <input type="submit" value="Email Password Reset Link">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
