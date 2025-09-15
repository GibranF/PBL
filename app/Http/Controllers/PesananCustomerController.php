<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use App\Models\User; // Pastikan model User diimpor untuk akses data admin jika diperlukan di Laravel
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client; // Import Guzzle HTTP Client untuk memanggil API Node.js

class PesananCustomerController extends Controller
{
    /**
     * URL dasar untuk Node.js WhatsApp API Anda.
     * Pastikan ini sesuai dengan port dan host di file .env Node.js Anda.
     * Contoh: 'http://localhost:3001'
     */
    private $nodeApiUrl = 'http://localhost:3001'; // SESUAIKAN jika port Node.js API Anda berbeda

    /**
     * Menghasilkan ID transaksi unik.
     *
     * @return string
     */
    private function generateIdTransaksi()
    {
        return 'TRX-' . strtoupper(Str::random(8));
    }

    /**
     * Memanggil Node.js API untuk menghasilkan tautan WhatsApp.
     * Fungsi ini akan mengirimkan data yang diperlukan ke Node.js,
     * yang kemudian akan membuat pesan dan tautan WhatsApp.
     *
     * @param \App\Models\Transaksi $transaksi Objek transaksi yang baru dibuat.
     * @param \App\Models\User $user Objek user yang membuat pesanan.
     * @param array $layananDetails Detail layanan dalam pesanan (dari hasil DetailTransaksi::create).
     * @return string|null Tautan WhatsApp atau null jika gagal.
     */
    private function generateWhatsAppLinkViaNodeJs($transaksi, $user, $layananDetails)
    {
        try {
            $client = new Client();
            $response = $client->post("{$this->nodeApiUrl}/generate-whatsapp-link", [
                'json' => [
                    'transaksi' => [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'biaya_antar' => $transaksi->biaya_antar,
                        'total' => $transaksi->total,
                        'status' => $transaksi->status,
                        'tanggal' => $transaksi->tanggal->format('d-m-Y H:i'), // Format tanggal untuk pesan WA
                    ],
                    'user' => [
                        'name' => $user->name,
                        'nomor_hp' => $user->nomor_hp,
                        'alamat' => $user->alamat,
                    ],
                    // Penting: Sertakan nama_layanan di sini agar Node.js tidak perlu query DB lagi
                    'layananDetails' => collect($layananDetails)->map(function ($detail) {
                        // Ambil nama layanan dari database Laravel, karena Node.js tidak tahu nama layanannya
                        $layanan = Layanan::find($detail->id_layanan);
                        return [
                            'id_layanan' => $detail->id_layanan,
                            'nama_layanan' => $layanan ? $layanan->nama_layanan : 'Layanan Tidak Dikenal', // Fallback jika nama layanan tidak ditemukan
                            'harga' => $detail->harga,
                            'dimensi' => $detail->dimensi,
                            'satuan' => $detail->satuan,
                            'subtotal' => $detail->subtotal,
                        ];
                    })->toArray(),
                ],
                'http_errors' => false, // Jangan melempar exception untuk status HTTP > 400
            ]);

            $statusCode = $response->getStatusCode();
            $bodyContent = $response->getBody()->getContents();
            $body = json_decode($bodyContent);

            // Periksa apakah respons sukses dan mengandung 'whatsappLink'
            if ($statusCode === 200 && isset($body->whatsappLink)) {
                Log::info('WhatsApp link received from Node.js: ' . $body->whatsappLink);
                return $body->whatsappLink;
            }

            // Log error jika respons dari Node.js API tidak sesuai harapan
            Log::error("Node.js API returned error (Status: {$statusCode}): " . $bodyContent);
            return null;
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Tangani error jika koneksi ke Node.js API gagal (misal: Node.js server tidak berjalan)
            Log::error('Koneksi ke Node.js API gagal: ' . $e->getMessage() . ' Pastikan server Node.js berjalan di ' . $this->nodeApiUrl);
            return null;
        } catch (\Exception $e) {
            // Tangani error umum lainnya saat memanggil Node.js API
            Log::error('Gagal memanggil Node.js API: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Menampilkan halaman konfirmasi setelah pesanan dibuat.
     * Halaman ini akan memicu redirect otomatis ke WhatsApp melalui JavaScript.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showConfirmationPage()
    {
        // Pastikan ada link WhatsApp di sesi sebelum menampilkan halaman konfirmasi
        if (!session()->has('whatsapp_confirmation')) {
            return redirect()->route('customer.pesanan.create')
                ->with('error', 'Sesi konfirmasi tidak valid.');
        }

        return view('customer.pesanan.confirm', [
            'whatsappLink' => session()->pull('whatsapp_confirmation') // Ambil dan hapus link dari sesi
        ]);
    }

    /**
     * Menampilkan daftar pesanan yang dibuat oleh pelanggan yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi.layanan')
            ->where('id_user', Auth::id())
            ->whereNotNull('status_pembayaran') // Hanya menampilkan yang sudah ada status pembayaran
            ->latest() // Urutkan dari yang terbaru
            ->get();

        return view('customer.pesanan.index', compact('transaksi'));
    }

    /**
     * Menampilkan formulir untuk membuat pesanan baru.
     * Mengirimkan data user dan layanan ke view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $layanan = Layanan::all(); // Mengambil semua data layanan

        return view('customer.pesanan.create', compact('user', 'layanan'));
    }

    /**
     * Menyimpan pesanan baru ke database.
     * Setelah berhasil disimpan, akan memanggil Node.js API untuk mendapatkan
     * tautan notifikasi WhatsApp untuk admin, lalu mengarahkan pelanggan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        Log::info('Request data:', $request->all());

        // Validasi input dari form
        $validated = $request->validate([
            'layanan' => 'required|array',
            'layanan.*.id_layanan' => 'required|integer|exists:layanan,id_layanan',
            'layanan.*.harga' => 'required|numeric|min:0', // Ini harga per satuan dari frontend
            'layanan.*.dimensi' => 'required|numeric|min:0.1',
            'layanan.*.satuan' => 'required|string|max:10',
            'antar_jemput' => 'nullable|in:yes,no',
            'jarak_km' => 'nullable|numeric|min:0',
            'pembayaran_option' => 'required|in:bayar_online,bayar_offline,bayar_nanti',
        ]);

        DB::beginTransaction(); // Mulai transaksi database untuk memastikan atomisitas
        try {
            $biaya_antar = 0;
            if ($request->antar_jemput === 'yes') {
                $biaya_antar = $this->hitungBiayaAntarJemput($request->jarak_km);
            }

            // Hitung total biaya layanan dari semua item yang dipilih
            $totalLayanan = collect($request->layanan)->sum(fn($item) => $item['harga'] * $item['dimensi']);
            $total = $totalLayanan + $biaya_antar;

            $idTransaksi = $this->generateIdTransaksi();

            // Buat entri Transaksi baru di database
            $transaksi = Transaksi::create([
                'id_transaksi' => $idTransaksi,
                'id_user' => $user->id,
                'nama_pelanggan' => $user->name,
                'alamat' => $user->alamat,
                'nomor_hp' => $user->nomor_hp,
                'status' => 'belum diproses', // Status awal pesanan
                'tanggal' => Carbon::now(),
                'biaya_antar' => $biaya_antar,
                'jarak_km' => $request->jarak_km,
                'total' => $total,
            ]);

            $layananDetails = [];
            // Simpan detail setiap layanan yang dipesan ke tabel detail_transaksi
            foreach ($validated['layanan'] as $item) {
                $detail = DetailTransaksi::create([
                    'id_transaksi' => $idTransaksi,
                    'id_layanan' => $item['id_layanan'],
                    'harga' => $item['harga'], // Harga yang digunakan saat transaksi ini
                    'dimensi' => $item['dimensi'],
                    'satuan' => $item['satuan'],
                    'subtotal' => $item['harga'] * $item['dimensi'],
                ]);
                $layananDetails[] = $detail; // Simpan detail untuk dikirim ke Node.js
            }

            // Atur status pembayaran berdasarkan pilihan user
            if ($request->pembayaran_option === 'bayar_offline') {
                $transaksi->update([
                    'status_pembayaran' => 'belum dibayar',
                    'metode_pembayaran' => 'cash',
                ]);
            } elseif ($request->pembayaran_option === 'bayar_nanti') {
                $transaksi->update([
                    'status_pembayaran' => 'belum dibayar',
                    'metode_pembayaran' => null, // Atur null atau 'transfer' jika ada default
                ]);
            }

            // Panggil Node.js API untuk mendapatkan tautan WhatsApp.
            // Ini akan membuat pesan WhatsApp dan mengembalikan URL wa.me.
            $whatsappLink = $this->generateWhatsAppLinkViaNodeJs($transaksi, $user, $layananDetails);

            DB::commit(); // Commit transaksi jika semua operasi database berhasil

            // Redirect berdasarkan opsi pembayaran yang dipilih pelanggan
            if ($request->pembayaran_option === 'bayar_online') {
                // Untuk pembayaran online, arahkan ke halaman pembayaran dengan ID transaksi
                return redirect()->route('customer.pembayaran.create', ['id_transaksi' => $idTransaksi])
                    ->with('whatsapp_link', $whatsappLink) // Boleh disertakan jika halaman pembayaran membutuhkan link ini
                    ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
            } else {
                // Untuk pembayaran offline atau bayar nanti, arahkan ke halaman konfirmasi.
                // Tautan WhatsApp akan diambil dari sesi di halaman ini dan di-redirect secara otomatis oleh JavaScript.
                session()->put('whatsapp_confirmation', $whatsappLink);
                return redirect()->route('customer.pesanan.confirm')
                    ->with('success', 'Pesanan berhasil dibuat. Mohon tunggu sejenak untuk diarahkan ke WhatsApp Admin.');
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Log::error('Gagal menyimpan pesanan atau memanggil Node.js API: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]); // Log error lebih detail
            // Kembali ke halaman sebelumnya dengan input dan pesan error
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan pesanan: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghitung biaya antar jemput berdasarkan jarak.
     * Gratis untuk 3 KM pertama, kemudian Rp 5.000 per KM.
     *
     * @param float $jarak_km Jarak dalam kilometer.
     * @return int Biaya antar jemput.
     */
    private function hitungBiayaAntarJemput($jarak_km)
    {
        if ($jarak_km <= 3) {
            return 0; // Gratis untuk 3 KM pertama
        } else {
            return ($jarak_km - 3) * 5000; // Rp 5.000 per KM setelah 3 KM pertama
        }
    }

    /**
     * Metode ini adalah rute pembantu jika Anda ingin mengarahkan pengguna
     * ke tautan WhatsApp yang telah didekode dari URL (misalnya, jika link
     * dikirim melalui email atau disimpan di tempat lain).
     *
     * @param string $whatsappLink Tautan WhatsApp yang sudah di-encode.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function whatsappRedirect($whatsappLink)
    {
        // Pastikan pengguna sudah login sebelum melanjutkan
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            // Double decode untuk menangani URL yang di-encode sebagai parameter rute
            $decodedLink = urldecode(urldecode($whatsappLink));

            // Validasi apakah ini URL yang valid dan merupakan URL WhatsApp
            if (!filter_var($decodedLink, FILTER_VALIDATE_URL) || !str_contains($decodedLink, 'wa.me')) {
                throw new \Exception("Format URL tidak valid atau bukan URL WhatsApp.");
            }

            // Tampilkan view yang berisi JavaScript untuk mengarahkan ke tautan WhatsApp
            return view('customer.pesanan.pesan', [
                'whatsappLink' => $decodedLink
            ]);

        } catch (\Exception $e) {
            Log::error("WhatsApp redirect error: " . $e->getMessage());
            return back()->with('error', 'Tautan WhatsApp yang diberikan tidak valid.');
        }
    }
}
