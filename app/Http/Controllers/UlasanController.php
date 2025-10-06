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

            // ⬇️ Redirect kembali ke bagian #komentar
            return redirect()->to(url()->previous() . '#komentar')
                             ->with('success', 'Ulasan berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan ulasan:', ['error' => $e->getMessage()]);
            return redirect()->to(url()->previous() . '#komentar')
                             ->withErrors(['error' => 'Gagal menyimpan ulasan.']);
        }
    }

    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);

        if ($ulasan->id_user === Auth::id()) {
            $ulasan->delete();
            // ⬇️ Tambahkan juga agar tetap di posisi rating
            return redirect()->to(url()->previous() . '#komentar')
                             ->with('success', 'Ulasan Anda dihapus.');
        }

        return redirect()->to(url()->previous() . '#komentar')
                         ->withErrors('Tidak diizinkan.');
    }

    public function destroyByAdmin($id)
    {
        $ulasan = Ulasan::findOrFail($id);

        if (Auth::user()->usertype === 'admin') {
            $ulasan->delete();
            return redirect()->to(url()->previous() . '#komentar')
                             ->with('success', 'Ulasan dihapus oleh admin.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $ulasan = Ulasan::findOrFail($id);

        if ($ulasan->id_user !== Auth::id()) {
            return redirect()->to(url()->previous() . '#komentar')
                             ->withErrors(['error' => 'Kamu tidak diizinkan mengedit ulasan ini.']);
        }

        $ulasan->update([
            'pesan' => $request->komentar,
            'rating' => $request->rating,
        ]);

        return redirect()->to(url()->previous() . '#komentar')
                         ->with('success', 'Ulasan berhasil diperbarui.');
    }
}
