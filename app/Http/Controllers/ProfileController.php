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

        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->fill([
            'name'      => $request->name,
            'email'     => $request->email,
            'alamat'    => $request->alamat,
            'nomor_hp'  => $request->nomor_hp,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

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
        
        // 1. Validasi Password - UJI COBA: Diubah dari 'current_password' menjadi 'min:8'
        // Jika ini berhasil, masalahnya ada pada password atau hash database Anda.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'string', 'min:8'], 
        ]);
        
        Log::info('DELETE ACCOUNT: Validasi password berhasil untuk user ID: ' . $request->user()->id);

        $user = $request->user();

        // 2. Logout Pengguna Saat Ini
        Auth::logout();
        Log::info('DELETE ACCOUNT: User berhasil di-logout dari sesi.');

        // 3. Hapus Pengguna (Soft Delete)
        // Ini memanggil event 'deleting' atau 'forceDeleting' di model
        // dan mengisi kolom 'deleted_at'.
        $user->delete();
        Log::info('DELETE ACCOUNT: User ID ' . $user->id . ' berhasil di soft delete.');

        // 4. Invalidate Session dan Regenerate Token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 5. Redirect ke Halaman Utama/Login
        return Redirect::to('/')->with('status', 'Akun berhasil dihapus.');
    }
}
