<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;
use Illuminate\Support\Facades\Log;

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

        // Pastikan transaksi belum dibayar
        if ($transaksi->status_pembayaran === 'sudah dibayar') {
            return redirect()->back()->with('error', 'Transaksi ini sudah dibayar.');
        }

        // Data Snap
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

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Kirim ke view sesuai role
        if (Auth::user()->usertype === 'admin') {
            return view('admin.pembayaran.create', compact('transaksi', 'snapToken'));
        } else {
            return view('customer.pembayaran.create', compact('transaksi', 'snapToken'));
        }
    }

    public function store(Request $request, $id_transaksi)
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

            // Perbarui status pembayaran di tabel transaksi
            $transaksi->update([
                'status_pembayaran' => 'sudah dibayar',
                'tanggal_pembayaran' => Carbon::now(),
                'metode_pembayaran' => 'Transfer', 
                'snap_token' => $request->snap_token ?? null,
            ]);

            // Pastikan data tersimpan
            $transaksi->refresh();

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
    $transaksi = Transaksi::findOrFail($id_transaksi);

    // Cek metode pembayaran harus cash dulu
    if ($transaksi->metode_pembayaran != 'cash') {
        return redirect()->back()->with('error', 'Metode pembayaran bukan cash.');
    }

    // Update status pembayaran dan tanggal bayar
    $transaksi->update([
        'status_pembayaran' => 'sudah dibayar',
        'tanggal_pembayaran' => Carbon::now(),
    ]);

    return redirect()->route('admin.pesanan.index')->with('success', 'Pembayaran cash berhasil diproses.');
}

public function setMetodeDanBayar(Request $request, $id_transaksi)
{
    $request->validate([
        'metode_pembayaran' => 'required|in:cash,online',
    ]);

    $transaksi = Transaksi::findOrFail($id_transaksi);
    $transaksi->metode_pembayaran = $request->metode_pembayaran;

    // Tandai status pembayaran jika metode langsung bayar (opsional)
    if ($request->metode_pembayaran === 'cash') {
        $transaksi->status_pembayaran = 'belum dibayar'; // atau langsung 'sudah dibayar'
    }

    $transaksi->save();

    return redirect()->route('admin.pesanan.index')->with('success', 'Metode pembayaran telah ditentukan.');
}


}