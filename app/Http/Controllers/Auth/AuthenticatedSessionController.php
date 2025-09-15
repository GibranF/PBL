<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
/**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(LoginRequest $request)
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->usertype === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->usertype === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    return redirect()->intended(route('halaman.landing-page', absolute: false));
}


    /**
     * Destroy an authenticated session.
     */
public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('halaman.landing-page');
}
}