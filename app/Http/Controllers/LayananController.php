<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    // Tampilkan layanan yang aktif (belum dihapus)
        public function index(Request $request)
    {
        // Ambil data layanan dengan pagination 10 per halaman
        $layanan = Layanan::paginate(10);

        // Kirim data ke view
        return view('admin.layanan.index', compact('layanan'));
    }

    // Tampilkan layanan yang sudah di soft delete (arsip)
    public function archive()
    {
        $layananArsip = Layanan::onlyTrashed()->orderBy('id_layanan', 'asc')->paginate(10);
        return view('admin.layanan.archive', compact('layananArsip'));
    }

    // Soft delete layanan
    public function destroy($id_layanan)
    {
        $layanan = Layanan::findOrFail($id_layanan);
        $layanan->delete(); // soft delete

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diarsipkan.');
    }

    // Restore layanan yang di soft delete
    public function restore($id_layanan)
    {
        $layanan = Layanan::onlyTrashed()->findOrFail($id_layanan);
        $layanan->restore();

        return redirect()->route('admin.layanan.archive')
            ->with('success', 'Layanan berhasil dipulihkan.');
    }

    // Contoh method create dan store
    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:layanan,nama_layanan',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
        ]);

        Layanan::create($request->only(['nama_layanan', 'harga', 'deskripsi']));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }
}
