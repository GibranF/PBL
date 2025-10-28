<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi input login.
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // Bisa email atau username
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Logika autentikasi login.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Deteksi apakah input berupa email atau username
        $loginField = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $loginField => $this->input('login'),
            'password' => $this->input('password'),
        ];

        // Coba login
        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // 🔴 Pesan error kustom
            throw ValidationException::withMessages([
                'login' => 'Username/email atau password salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Batasi percobaan login berulang (rate limiting).
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Key rate limit unik berdasarkan login + IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
    }
}
