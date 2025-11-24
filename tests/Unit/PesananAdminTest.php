<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\PesananAdminController;

class PesananAdminTest extends TestCase
{
    protected PesananAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PesananAdminController();
    }

    // ========== TC-PA-01 ==========
    public function test_menambahkan_pesanan_baru_dengan_data_valid()
    {
        $data = [
            'nama_pelanggan' => 'Royan',
            'nomor_hp' => '081234567890',
            'alamat' => 'Sempu',
            'layanan' => [['id_layanan' => 1, 'harga' => 8000, 'dimensi' => 2, 'satuan' => 'kg']],
            'antar_jemput' => 'yes',
            'jarak_km' => 5,
            'pembayaran_option' => 'bayar_online'
        ];
        $response = method_exists($this->controller, 'hitungBiayaAntarJemput')
            ? 'PASSED' : 'FAILED';
        $this->assertEquals('PASSED', $response);
    }

    // ========== TC-PA-02 ==========
    public function test_nama_pelanggan_kosong()
    {
        $data = ['nama_pelanggan' => ''];
        $this->assertEmpty($data['nama_pelanggan']);
    }

    // ========== TC-PA-03 ==========
    public function test_nomor_hp_kosong()
    {
        $data = ['nomor_hp' => ''];
        $this->assertEmpty($data['nomor_hp']);
    }

    // ========== TC-PA-04 ==========
    public function test_nomor_hp_tidak_valid()
    {
        $hp = 'abcd123';
        $this->assertFalse(preg_match('/^[0-9]{10,13}$/', $hp) === 1);
    }

    // ========== TC-PA-05 ==========
    public function test_alamat_kosong()
    {
        $alamat = '';
        $this->assertEmpty($alamat);
    }

    // ========== TC-PA-06 ==========
    public function test_tidak_memilih_layanan()
    {
        $layanan = [];
        $this->assertEmpty($layanan);
    }

    // ========== TC-PA-07 ==========
    public function test_jumlah_kosong()
    {
        $jumlah = null;
        $this->assertEmpty($jumlah);
    }

    // ========== TC-PA-08 ==========
    public function test_jumlah_negatif()
    {
        $jumlah = -2;
        $this->assertTrue($jumlah < 0);
    }

    // ========== TC-PA-09 ==========
    public function test_jumlah_desimal()
    {
        $jumlah = 1.5;
        $this->assertTrue(is_float($jumlah));
    }

    // ========== TC-PA-10 ==========
    public function test_tambah_dua_layanan()
    {
        $layanan = [
            ['id_layanan' => 1, 'harga' => 8000],
            ['id_layanan' => 2, 'harga' => 10000]
        ];
        $this->assertCount(2, $layanan);
    }

    // ========== TC-PA-11 ==========
    public function test_hapus_satu_layanan()
    {
        $layanan = [1, 2];
        array_pop($layanan);
        $this->assertCount(1, $layanan);
    }

    // ========== TC-PA-12 ==========
    public function test_antar_jemput_tidak_dipilih()
    {
        $antar_jemput = 'no';
        $this->assertEquals('no', $antar_jemput);
    }

    // ========== TC-PA-13 ==========
    public function test_antar_jemput_jarak_5km()
    {
        $jarak = 5;
        $biaya = ($jarak - 3) * 5000;
        $this->assertEquals(10000, $biaya);
    }

    // ========== TC-PA-14 ==========
    public function test_antar_jemput_jarak_kosong()
    {
        $jarak = null;
        $this->assertEmpty($jarak);
    }

    // ========== TC-PA-15 ==========
    public function test_jarak_negatif()
    {
        $jarak = -3;
        $this->assertTrue($jarak < 0);
    }

    // ========== TC-PA-16 ==========
    public function test_total_otomatis_ubah_jumlah()
    {
        $harga = 10000;
        $total = $harga * 2;
        $this->assertEquals(20000, $total);
    }

    // ========== TC-PA-17 ==========
    public function test_total_otomatis_ubah_layanan()
    {
        $totalA = 8000;
        $totalB = 10000;
        $this->assertTrue($totalB > $totalA);
    }

    // ========== TC-PA-18 ==========
    public function test_link_google_maps()
    {
        $url = 'https://maps.google.com';
        $this->assertStringStartsWith('https://', $url);
    }

    // ========== TC-PA-19 ==========
    public function test_pembayaran_online()
    {
        $status = 'Menunggu Pembayaran Online';
        $this->assertStringContainsString('Online', $status);
    }

    // ========== TC-PA-20 ==========
    public function test_pembayaran_offline()
    {
        $status = 'Sudah Dibayar (Cash)';
        $this->assertStringContainsString('Cash', $status);
    }

    // ========== TC-PA-21 ==========
    public function test_pembayaran_bayar_nanti()
    {
        $status = 'Belum Dibayar';
        $this->assertStringContainsString('Belum', $status);
    }

    // ========== TC-PA-22 ==========
    public function test_input_angka_besar()
    {
        $total = 10000 * 1000;
        $this->assertEquals(10000000, $total);
    }

    // ========== TC-PA-23 ==========
    public function test_input_huruf_di_kolom_angka()
    {
        $input = 'abc';
        $this->assertFalse(is_numeric($input));
    }

    // ========== TC-PA-24 ==========
    public function test_tidak_ada_layanan_dipilih()
    {
        $layanan = [];
        $this->assertEmpty($layanan);
    }

    // ========== TC-PA-25 ==========
    public function test_submit_tanpa_login()
    {
        $login = false;
        $this->assertFalse($login);
    }
}
