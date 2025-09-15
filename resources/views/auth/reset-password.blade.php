@extends('layouts.loger')

@section('content')
 <div class="reset-password">
        <div class="reset-container">
            <div class="reset-password-form">
                <div class="title">Reset Password</div>
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <div class="input-boxes">
                        <div class="input-box reset-email">
                            <i class="fas fa-envelope"></i>
                            <input type="email"
                                   name="email"
                                   placeholder="Enter your email"
                                   value="{{ old('email', $request->email) }}"
                                   required autocomplete="email" autofocus>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box reset-password-input">
                            <i class="fas fa-lock"></i>
                            <input type="password"
                                   name="password"
                                   placeholder="Enter new password"
                                   required autocomplete="new-password">
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-box reset-confirm-password">
                            <i class="fas fa-lock"></i>
                            <input type="password"
                                   name="password_confirmation"
                                   placeholder="Confirm new password"
                                   required autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="button">
                            <input type="submit" value="Reset Password" class="reset-button">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection