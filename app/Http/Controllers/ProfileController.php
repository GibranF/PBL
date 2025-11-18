<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Tambahkan ini jika tidak ada
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil user (termasuk foto profil).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'alamat' => 'required|string',
            'nomor_hp' => [
                'required',
                'regex:/^[0-9+]{10,13}$/',
                Rule::unique('users')->ignore($user->id),
            ],
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid (harus mengandung @).',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'alamat.required' => 'Alamat wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Nomor HP harus 10–13 digit dan hanya boleh berisi angka atau tanda + di awal.',
            'nomor_hp.unique' => 'Nomor HP sudah digunakan oleh pengguna lain.',
        ]);


        // ✅ Isi data baru ke model user
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'nomor_hp' => $validated['nomor_hp'],
        ]);

        // ✅ Reset verifikasi jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // ✅ Upload foto profil baru (hapus lama jika ada)
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        // ✅ Simpan perubahan user
        $user->save();

        // ✅ Kembalikan ke halaman edit profil dengan pesan sukses
        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }


    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Hapus akun pengguna. (METHOD YANG HILANG)
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('DELETE ACCOUNT: Method destroy dimulai.');

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        // ✅ Cek apakah password yang dimasukkan cocok dengan password user
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('DELETE ACCOUNT: Password salah untuk user ID ' . $user->id);

            return back()
                ->withErrors([
                    'password' => 'Password yang Anda masukkan salah.',
                ], 'userDeletion');
        }

        Log::info('DELETE ACCOUNT: Validasi password berhasil untuk user ID: ' . $user->id);

        // ✅ Logout dulu
        Auth::logout();

        // ✅ Soft delete user (butuh trait SoftDeletes di model User)
        $user->delete();
        Log::info('DELETE ACCOUNT: User ID ' . $user->id . ' berhasil di soft delete.');

        // ✅ Hapus sesi dan token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ Redirect ke halaman utama
        return Redirect::to('/')->with('status', 'Akun berhasil dihapus.');
    }
}
