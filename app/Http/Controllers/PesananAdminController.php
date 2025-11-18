<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class PesananAdminController extends Controller
{
    // Fungsi untuk generate ID transaksi kustom
    private function generateIdTransaksi()
    {
        return 'TRX-' . strtoupper(Str::random(8));
    }

    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'detailTransaksi']);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', '%' . $request->search . '%')
                    ->orWhere('id_transaksi', 'like', '%' . $request->search . '%');
            });
        }

        $transaksi = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('admin.pesanan.index', compact('transaksi'));
    }


    public function show($id_transaksi)
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.layanan'])
            ->where('id_transaksi', $id_transaksi)
            ->firstOrFail();
        return view('admin.pesanan.show', compact('transaksi'));
    }

    public function create()
    {
        $user = Auth::user();
        $layanan = Layanan::all();

        Log::info('Data layanan:', $layanan->toArray());

        if ($layanan->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada layanan yang tersedia. Silakan tambahkan layanan terlebih dahulu.']);
        }

        return view('admin.pesanan.buatpesanan', compact('user', 'layanan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:500',
            'layanan' => 'required|array',
            'layanan.*.id_layanan' => 'required|integer|exists:layanan,id_layanan',
            'layanan.*.harga' => 'required|numeric|min:0',
            'layanan.*.dimensi' => 'required|numeric|min:0.1|max:500',
            'layanan.*.satuan' => 'required|string|max:10',
            'antar_jemput' => 'nullable|in:yes,no',
            'jarak_km' => 'nullable|numeric|min:0',
            'pembayaran_option' => 'required|in:bayar_online,bayar_offline,bayar_nanti',
        ]);


        DB::beginTransaction();
        try {
            // Hitung biaya antar jemput
            $biaya_antar = 0;
            if ($request->antar_jemput === 'yes') {
                $biaya_antar = $this->hitungBiayaAntarJemput($request->jarak_km);
            }

            // Total biaya semua layanan
            $totalLayanan = collect($request->layanan)->sum(fn($item) => $item['harga'] * $item['dimensi']);
            $total = $totalLayanan + $biaya_antar;

            // Generate ID transaksi kustom
            $idTransaksi = $this->generateIdTransaksi();

            // Menyimpan transaksi
            $transaksi = Transaksi::create([
                'id_transaksi' => $idTransaksi,
                'id_user' => $user->id,
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'nama_kasir' => $user->id,
                'alamat' => $validated['alamat'],
                'nomor_hp' => $validated['nomor_hp'],
                'status' => 'belum diproses',
                'tanggal' => Carbon::now(),
                'biaya_antar' => $biaya_antar,
                'jarak_km' => $request->jarak_km ?? 0,
                'total' => $total,
                'status_pembayaran' => 'belum dibayar',
            ]);

            // Menyimpan detail transaksi
            foreach ($validated['layanan'] as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $idTransaksi,
                    'id_layanan' => $item['id_layanan'],
                    'harga' => $item['harga'],
                    'dimensi' => $item['dimensi'],
                    'satuan' => $item['satuan'],
                    'subtotal' => $item['harga'] * $item['dimensi'],
                ]);
            }

            // Simpan status_pembayaran dan metode_pembayaran sesuai pilihan
            if ($request->pembayaran_option === 'bayar_offline') {
                $transaksi->update([
                    'status_pembayaran' => 'sudah dibayar',
                    'tanggal_pembayaran' => Carbon::now(),
                    'metode_pembayaran' => 'cash',
                ]);
            } elseif ($request->pembayaran_option === 'bayar_nanti') {
                $transaksi->update([
                    'status_pembayaran' => 'belum dibayar',
                    'metode_pembayaran' => null,
                ]);
            }

            DB::commit();

            // Redirect berdasarkan pilihan pembayaran

            if ($request->pembayaran_option === 'bayar_online') {
                return redirect()->route('admin.pembayaran.create', ['id_transaksi' => $idTransaksi])
                    ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
            } elseif ($request->pembayaran_option === 'bayar_offline') {
                return redirect()->route('admin.pesanan.index')
                    ->with('success', 'Pesanan berhasil dibuat dengan metode pembayaran Cash.');
            } else {
                return redirect()->route('admin.pesanan.index')
                    ->with('success', 'Pesanan berhasil dibuat. Anda dapat membayar nanti.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan pesanan: ' . $e->getMessage()]);
        }
    }

    private function hitungBiayaAntarJemput($jarak_km)
    {
        if ($jarak_km <= 3) {
            return 0;
        } else {
            return ($jarak_km - 3) * 5000;
        }
    }

    public function editStatus($id_transaksi)
    {
        $transaksi = Transaksi::findOrFail($id_transaksi);
        return view('admin.pesanan.edit-status', compact('transaksi'));
    }

    public function updateStatus(Request $request, $id_transaksi, FonnteService $fonnte)
    {
        $request->validate([
            'status' => 'required|in:belum diproses,pesanan diproses,pesanan selesai',
        ]);

        $transaksi = Transaksi::with('user')->findOrFail($id_transaksi);

        // Update status
        $transaksi->status = $request->status;

        // Hanya isi nama kasir jika masih kosong
        if (empty($transaksi->nama_kasir)) {
            $transaksi->nama_kasir = Auth::user()->name ?? null;
        }

        $transaksi->save();

        // Jika status pesanan selesai, kirim pesan ke pelanggan via Fonnte
        if ($transaksi->status === 'pesanan selesai') {
            $phone = $transaksi->nomor_hp;

            if ($phone) {
                $message = "Halo {$transaksi->nama_pelanggan}, pesanan Anda dengan ID {$transaksi->id_transaksi} telah SELESAI. Terima kasih telah menggunakan layanan kami 🙏";

                // Kirim pesan via Fonnte
                $result = $fonnte->sendMessage($phone, $message);

                // Log hasil response Fonnte
                Log::info('Fonnte updateStatus result', [
                    'phone' => $phone,
                    'response' => $result,
                ]);
            } else {
                Log::warning('Nomor HP tidak ditemukan untuk transaksi', [
                    'id_transaksi' => $transaksi->id_transaksi,
                ]);
            }
        }

        return redirect()->route('admin.pesanan.index')
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }




}
