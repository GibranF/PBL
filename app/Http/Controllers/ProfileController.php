<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
    // ...

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

}