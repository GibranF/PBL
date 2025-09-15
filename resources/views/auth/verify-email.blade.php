@extends('layouts.app')

@section('content')
<div class="container">
    <div class="forms">
        <div class="form-content">
            <div class="login-form">
                <div class="title">Email Verification</div>

                <div class="text" style="margin-bottom: 20px;">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                    If you didn't receive the email, we will gladly send you another.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="text" style="color: green; margin-bottom: 20px;">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <div class="input-boxes">
                    {{-- Resend Verification Email --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="button">
                            <input type="submit" value="Resend Verification Email">
                        </div>
                    </form>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: 10px;">
                        @csrf
                        <div class="button">
                            <input type="submit" value="Log Out" style="background-color: #f44336;">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
