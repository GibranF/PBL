<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CetakStrukTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['usertype' => 'customer']);
        $this->layanan = Layanan::factory()->create();
    }

    /** @test */
    public function tc_cs_01_tampil_data_transaksi_pada_struk()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
            'total' => 150000,
            'status_pembayaran' => 'sudah dibayar',
        ]);

        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 3,
            'satuan' => 'kg',
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertStatus(200);
        $response->assertSee($transaksi->id_transaksi);
        $response->assertSee($this->layanan->nama_layanan);
        $response->assertSee('150.000'); // subtotal
    }

    /** @test */
    public function tc_cs_02_cetak_struk_berhasil()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
            'total' => 150000,
            'status_pembayaran' => 'sudah dibayar',
        ]);

        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 3,
            'satuan' => 'kg',
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertStatus(200);
        $response->assertSee('Cetak');
    }

    /** @test */
    public function tc_cs_03_tombol_kembali_berfungsi()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertStatus(200);
        $response->assertSee('Kembali');
    }

    /** @test */
    public function tc_cs_04_cetak_struk_tanpa_data_transaksi()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertStatus(200);
        $response->assertSee('Belum ada layanan');
    }

    /** @test */
    public function tc_cs_05_validasi_format_tampilan_cetak()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
            'total' => 150000,
            'status_pembayaran' => 'sudah dibayar',
        ]);

        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 3,
            'satuan' => 'kg',
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertSee('Total');
        $response->assertSee('Terima kasih');
    }

    /** @test */
    public function tc_cs_06_validasi_alignment_teks()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
        ]);

        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 3,
            'satuan' => 'kg',
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertSee('<td>', false);
    }

    /** @test */
    public function tc_cs_07_pengujian_cetak_berulang()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
        ]);

        DetailTransaksi::factory()->count(2)->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 3,
            'satuan' => 'kg',
            'subtotal' => 150000,
        ]);

        $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));
        $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $this->assertDatabaseHas('detail_transaksi', [
            'id_transaksi' => $transaksi->id_transaksi,
        ]);
    }

    /** @test */
    public function tc_cs_08_cetak_struk_nilai_total_besar()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
            'total' => 10000000,
            'status_pembayaran' => 'sudah dibayar',
        ]);

        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 10,
            'satuan' => 'kg',
            'subtotal' => 10000000,
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertSee('10.000.000');
    }

    /** @test */
    public function tc_cs_09_validasi_font_dan_ukuran_huruf()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->user->id,
            'status' => 'pesanan selesai',
        ]);

        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $this->layanan->id_layanan,
            'dimensi' => 3,
            'satuan' => 'kg',
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($this->user)->get(route('customer.pesanan.show', $transaksi->id_transaksi));

        $response->assertSee('font-family');
        $response->assertSee('font-size');
    }
}
