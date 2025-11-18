<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\p{N} \'_-]+$/u'], 
            'email' => ['required', 'string', 'lowercase', 'email:dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'alamat' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\s.,-]+$/u'],
            'nomor_hp' => ['required', 'string', 'min:10','max:15', 'unique:users'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 100 karakter',
            'name.regex' => 'Nama tidak boleh mengandung simbol.', 
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'email.max' => 'Email terlalu panjang',
            'password.min' => 'Password kurang dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 200 karakter.',
            'alamat.regex' => 'Alamat tidak boleh mengandung simbol selain titik, koma, dan strip.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.min' => 'Nomor HP minimal 10 digit.',
            'nomor_hp.max' => 'Nomor HP maksimal 15 digit.',
        ]);


        $usertype = $request->usertype ?? 'customer';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'usertype' => $usertype,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('halaman.landing-page');
    }
}
