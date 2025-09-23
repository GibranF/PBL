<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function update(Request $request)
{
    $user = auth()->user();

    // Validasi file
    $request->validate([
        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('profile_photo')) {
        // Simpan file ke storage/app/public/profile_photos
        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        // Hapus foto lama (kalau ada)
        if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
            \Storage::disk('public')->delete($user->profile_photo);
        }

        // Update path baru
        $user->profile_photo = $path;
    }

    return back()->with('success', 'Profil berhasil diperbarui!');
}

};
