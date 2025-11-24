<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use App\Services\FonnteService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesananCustomerController extends Controller
{

    /**
     * Menghasilkan ID transaksi unik.
     *
     * @return string
     */
    private function generateIdTransaksi()
    {
        return 'TRX-' . strtoupper(Str::random(8));
    }

    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi.layanan')
            ->where('id_user', Auth::id())
            ->whereNotNull('status_pembayaran')
            ->latest()
            ->get();

        return view('customer.pesanan.index', compact('transaksi'));
    }

    public function show($id_transaksi)
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.layanan'])
            ->where('id_transaksi', $id_transaksi)
            ->where('id_user', Auth::id()) // pastikan hanya transaksi milik user ini
            ->firstOrFail();

        // Hanya bisa dilihat jika status pesanan sudah selesai
        if ($transaksi->status !== 'pesanan selesai') {
            abort(403, 'Struk hanya dapat dilihat setelah pesanan selesai diproses.');
        }

        return view('customer.pesanan.show', compact('transaksi'));
    }

    public function create(Request $request, $id_layanan = null)
{
    $user = Auth::user();
    $layanan = Layanan::all();

    return view('customer.pesanan.create', [
        'user' => $user,
        'layanan' => $layanan,
        'selectedLayanan' => $id_layanan
    ]);
}

    public function store(Request $request, FonnteService $fonnte)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'layanan' => 'required|array',
            'layanan.*.id_layanan' => 'required|integer|exists:layanan,id_layanan',
            'layanan.*.harga' => 'required|numeric|min:0',
            'layanan.*.dimensi' => 'required|numeric|min:0.1|max:500',
            'layanan.*.satuan' => 'required|string|max:10',
            'antar_jemput' => 'nullable|in:yes,no',
            'jarak_km' => 'nullable|numeric|min:0',
            'pembayaran_option' => 'required|in:bayar_online,bayar_offline,bayar_nanti',
        ], [
            'layanan.*.dimensi.max' => 'Jumlah item melebihi batas maksimum.',
        ]);


        DB::beginTransaction();
        try {
            // === Hitung biaya antar jemput ===
            $biaya_antar = ($request->antar_jemput === 'yes' && $request->jarak_km > 3)
                ? ($request->jarak_km - 3) * 5000
                : 0;

            // === Hitung total ===
            $totalLayanan = collect($request->layanan)->sum(fn($item) => $item['harga'] * $item['dimensi']);
            $total = $totalLayanan + $biaya_antar;

            $idTransaksi = $this->generateIdTransaksi();

            $transaksi = Transaksi::create([
                'id_transaksi' => $idTransaksi,
                'id_user' => $user->id,
                'nama_pelanggan' => $user->name,
                'nama_kasir' => null,
                'alamat' => $user->alamat,
                'nomor_hp' => $user->nomor_hp,
                'status' => 'belum diproses',
                'tanggal' => Carbon::now(),
                'biaya_antar' => $biaya_antar,
                'jarak_km' => $request->jarak_km ?? 0,
                'total' => $total,
                'status_pembayaran' => 'belum dibayar',
            ]);

            foreach ($validated['layanan'] as $item) {
                 // Ambil data layanan dari database
                $layanan = Layanan::find($item['id_layanan']);
                DetailTransaksi::create([
                    'id_transaksi' => $idTransaksi,
                    'id_layanan' => $item['id_layanan'],
                    'harga' => $item['harga'],
                    'dimensi' => $item['dimensi'],
                    'satuan' => $item['satuan'],
                    'subtotal' => $item['harga'] * $item['dimensi'],
                ]);
            }

            // === Update pembayaran sesuai pilihan ===
            if ($request->pembayaran_option === 'bayar_offline') {
                $transaksi->update([
                    'status_pembayaran' => 'belum dibayar',
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

            // === Kirim notif ke admin via Fonnte ===
            if (in_array($request->pembayaran_option, ['bayar_offline', 'bayar_nanti'])) {
                $adminNumbers = User::where('usertype', 'admin')->pluck('nomor_hp');

                foreach ($adminNumbers as $phone) {
                    // Normalisasi ke format internasional (62...)
                    $phone = preg_replace('/[^0-9]/', '', $phone);
                    if (strpos($phone, '08') === 0) {
                        $phone = '62' . substr($phone, 1);
                    }

                    $message = "🔔 *Pesanan Baru Masuk* 🔔\n\n" .
                        "👤 Pelanggan: {$transaksi->nama_pelanggan}\n" .
                        "🆔 ID Transaksi: {$transaksi->id_transaksi}\n" .
                        "💰 Total: Rp" . number_format($transaksi->total, 0, ',', '.') . "\n" .
                        "💳 Metode: {$request->pembayaran_option}\n\n" .
                        "👉 Silakan cek di dashboard admin.";

                    $result = $fonnte->sendMessage($phone, $message);

                    // Catat log untuk debug
                    Log::info('Notif Fonnte ke admin', [
                        'admin_phone' => $phone,
                        'response' => $result,
                    ]);
                }
            }

            // === Redirect sesuai pilihan pembayaran ===
            if ($request->pembayaran_option === 'bayar_online') {
                return redirect()->route('customer.pembayaran.create', ['id_transaksi' => $idTransaksi])
                    ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
            } else {
                return redirect()->route('customer.pesanan.index')
                    ->with('success', 'Pesanan berhasil dibuat!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan pesanan: ' . $e->getMessage()]);
        }

    }
    private function hitungBiayaAntarJemput($jarak_km)
    {
        if ($jarak_km <= 3) {
            return 0; // Gratis untuk 3 KM pertama
        } else {
            return ($jarak_km - 3) * 5000; // Rp 5.000 per KM setelah 3 KM pertama
        }
    }

}

