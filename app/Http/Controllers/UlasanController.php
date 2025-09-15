<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Ulasan masuk:', $request->all());

        $request->validate([
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            Ulasan::create([
                'id_user' => Auth::id(),
                'pesan' => $request->komentar,
                'rating' => $request->rating,
                'tanggal' => now(),
            ]);

            return redirect()->back()->with('success', 'Ulasan berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan ulasan:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan ulasan.']);
        }
    }

    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);

        if ($ulasan->id_user === Auth::id()) {
            $ulasan->delete();
            return back()->with('success', 'Ulasan Anda dihapus.');
        }

        return back()->withErrors('Tidak diizinkan.');
    }

    public function destroyByAdmin($id)
    {
        $ulasan = Ulasan::findOrFail($id);

        if (Auth::user()->usertype === 'admin') {
            $ulasan->delete();
            return back()->with('success', 'Ulasan dihapus oleh admin.');
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $ulasan = Ulasan::findOrFail($id);

        // Pastikan pengguna yang mengupdate adalah pemilik ulasan
        if ($ulasan->id_user !== Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'Kamu tidak diizinkan mengedit ulasan ini.']);
        }

        $ulasan->update([
            'pesan' => $request->komentar,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil diperbarui.');
    }
}