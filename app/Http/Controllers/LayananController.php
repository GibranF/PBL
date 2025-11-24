<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    // Tampilkan layanan yang aktif (belum dihapus)
    public function index()
    {
        $layanan = Layanan::paginate(10);
        return view('admin.layanan.index', compact('layanan'));
    }

    // Tampilkan layanan yang sudah di soft delete (arsip)
    public function archive()
    {
        $layananArsip = Layanan::onlyTrashed()->orderBy('id_layanan', 'asc')->paginate(10);
        return view('admin.layanan.archive', compact('layananArsip'));
    }

    // Form create layanan baru
    public function create()
    {
        return view('admin.layanan.create');
    }

    // Simpan layanan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:layanan,nama_layanan',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'required|string',
            'satuan'       => 'nullable|string|max:50',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama_layanan', 'harga', 'deskripsi', 'satuan']);

        // Simpan gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('layanan', 'public');
        }

        Layanan::create($data);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    // Form edit layanan
    public function edit($id_layanan)
    {
        $layanan = Layanan::findOrFail($id_layanan);
        return view('admin.layanan.edit', compact('layanan'));
    }

    // Update layanan
    public function update(Request $request, $id_layanan)
    {
        $layanan = Layanan::findOrFail($id_layanan);

        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:layanan,nama_layanan,' . $id_layanan . ',id_layanan',
            'harga'        => 'required|numeric|min:0',
            'deskripsi'    => 'required|string',
            'satuan'       => 'nullable|string|max:50',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $layanan->nama_layanan = $request->nama_layanan;
        $layanan->harga = $request->harga;
        $layanan->deskripsi = $request->deskripsi;
        $layanan->satuan = $request->satuan;

        // Update gambar jika ada upload baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau ada
            if ($layanan->gambar && Storage::disk('public')->exists($layanan->gambar)) {
                Storage::disk('public')->delete($layanan->gambar);
            }
            $layanan->gambar = $request->file('gambar')->store('layanan', 'public');
        }

        $layanan->save();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui.');
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
}