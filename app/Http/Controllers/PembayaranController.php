<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\DetailTransaksi;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Config as MidtransConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = config('midtrans.is_3ds');
    }


    public function create($id_transaksi)
    {
        $transaksi = Transaksi::findOrFail($id_transaksi);

        // Kalau sudah dibayar
        if ($transaksi->status_pembayaran === 'sudah dibayar') {
            return redirect()->back()->with('error', 'Transaksi ini sudah dibayar.');
        }

        $snapToken = null;
        // Kalau ada snap_token tersimpan, coba cek status
        if ($transaksi->snap_token) {
            try {
                $status = Transaction::status($transaksi->id_transaksi);

                if ($status->transaction_status === 'pending') {
                    $snapToken = $transaksi->snap_token;
                }
            } catch (\Exception $e) {
            }
        }

        // Kalau snapToken belum ada (atau expired), buat baru
        if (!$snapToken) {
            $params = [
                'transaction_details' => [
                    'order_id' => $transaksi->id_transaksi,
                    'gross_amount' => $transaksi->total,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->nama_pelanggan,
                    'email' => Auth::user()->email,
                    'phone' => $transaksi->nomor_hp,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $transaksi->update(['snap_token' => $snapToken]);
        }

        // Arahkan sesuai role
        if (Auth::user()->usertype === 'admin') {
            return view('admin.pembayaran.create', compact('transaksi', 'snapToken'));
        } else {
            return view('customer.pembayaran.create', compact('transaksi', 'snapToken'));
        }
    }



    public function store(Request $request, $id_transaksi, FonnteService $fonnte)
    {
        try {
            $request->validate([
                'snap_token' => 'nullable|string',
            ]);

            $transaksi = Transaksi::findOrFail($id_transaksi);

            // Cek apakah transaksi sudah dibayar
            if ($transaksi->status_pembayaran === 'sudah dibayar') {
                return response()->json(['error' => 'Transaksi ini sudah dibayar'], 400);
            }

            // Update data transaksi dasar
            $updateData = [
                'status_pembayaran' => 'sudah dibayar',
                'tanggal_pembayaran' => Carbon::now(),
                'metode_pembayaran' => 'Transfer',
                'snap_token' => $request->snap_token ?? null,
            ];

            // Jika yang login adalah admin, isi nama_kasir (jika masih kosong)
            if (Auth::check() && Auth::user()->usertype === 'admin') {
                if (empty($transaksi->nama_kasir)) {
                    $updateData['nama_kasir'] = Auth::user()->name;
                }
            }

            $transaksi->update($updateData);
            $transaksi->refresh();

            // --- Kirim WA ke admin hanya kalau yang bayar adalah customer ---
            if (Auth::user()->usertype === 'customer') {
                $pesan = "📢 *Pesanan Baru Dibayar!*\n\n"
                    . "🧑 Nama: {$transaksi->nama_pelanggan}\n"
                    . "📱 No HP: {$transaksi->nomor_hp}\n"
                    . "📍 Alamat: {$transaksi->alamat}\n"
                    . "💰 Total: Rp " . number_format($transaksi->total, 0, ',', '.') . "\n"
                    . "✅ Status: Sudah Dibayar";

                // Kirim ke semua admin yang punya nomor HP
                $adminNumbers = User::where('usertype', 'admin')
                    ->whereNotNull('nomor_hp')
                    ->pluck('nomor_hp');

                foreach ($adminNumbers as $adminPhone) {
                    $fonnte->sendMessage($adminPhone, $pesan);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat.',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in PembayaranController@store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function batalPesanan($id_transaksi)
    {
        $transaksi = Transaksi::find($id_transaksi);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        try {
            $transaksi->detailTransaksi()->delete();
            $transaksi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function bayarCash($id_transaksi)
    {
        try {
            $transaksi = Transaksi::findOrFail($id_transaksi);

            $dataUpdate = [
                'status_pembayaran' => 'sudah dibayar',
                'tanggal_pembayaran' => Carbon::now(),
                'metode_pembayaran' => 'cash',
            ];

            // Isi nama kasir hanya jika masih kosong
            if (empty($transaksi->nama_kasir)) {
                $dataUpdate['nama_kasir'] = auth()->user()->name ?? null;
            }

            $transaksi->update($dataUpdate);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran cash berhasil diproses.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran cash: ' . $e->getMessage()
            ], 500);
        }
    }


    public function setMetodeDanBayar(Request $request, $id_transaksi)
    {
        try {
            $transaksi = Transaksi::findOrFail($id_transaksi);

            $dataUpdate = [
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'sudah dibayar',
                'tanggal_pembayaran' => Carbon::now(),
            ];

            // Isi nama kasir hanya jika masih kosong
            if (empty($transaksi->nama_kasir)) {
                $dataUpdate['nama_kasir'] = auth()->user()->name ?? null;
            }

            $transaksi->update($dataUpdate);

            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil disimpan dan transaksi ditandai sudah dibayar.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Generate ID Transaksi Unik
     */
    private function generateIdTransaksi()
    {
        $prefix = 'TRX';
        $date = now()->format('YmdHis');
        $random = strtoupper(Str::random(4));

        return $prefix . $date . $random;
    }
}
