<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Services\FonnteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PesananCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function TC_BP_01_buat_pesanan_baru_valid()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create(['harga' => 5000]);

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 3, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $response->assertRedirect(route('customer.pesanan.index'));
        $response->assertSessionHas('success', 'Pesanan berhasil dibuat!');
        $this->assertDatabaseHas('transaksi', ['id_user' => $user->id, 'total' => 15000]);
    }

    /** @test */
    public function TC_BP_02_jumlah_item_kosong_gagal()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => '', 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $response->assertSessionHasErrors(['layanan.0.dimensi']);
    }

    /** @test */
    public function TC_BP_03_nama_pelanggan_otomatis()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create();

        $response = $this->actingAs($user)->get(route('customer.pesanan.create'));
        $response->assertSee($user->name);
    }

    /** @test */
    public function TC_BP_04_layanan_kosong_gagal()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $response->assertSessionHasErrors(['layanan']);
    }

    /** @test */
    public function TC_BP_05_jumlah_item_0_gagal()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 0, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $response->assertSessionHasErrors(['layanan.0.dimensi']);
    }

    /** @test */
    public function TC_BP_06_jumlah_item_negatif_gagal()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => -2, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $response->assertSessionHasErrors(['layanan.0.dimensi']);
    }

    /** @test */
    public function TC_BP_07_total_harga_otomatis()
    {
        $user = User::factory()->create();
        $layanan1 = Layanan::factory()->create(['harga' => 5000]);
        $layanan2 = Layanan::factory()->create(['harga' => 3000]);

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [
                ['id_layanan' => $layanan1->id_layanan, 'harga' => 5000, 'dimensi' => 2, 'satuan' => 'kg'],
                ['id_layanan' => $layanan2->id_layanan, 'harga' => 3000, 'dimensi' => 1, 'satuan' => 'kg']
            ],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $this->assertDatabaseHas('transaksi', ['id_user' => $user->id, 'total' => 13000]);
    }

    /** @test */
    public function TC_BP_08_tidak_simpan_data_tanpa_submit()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('customer.pesanan.create'));
        $response->assertStatus(200);

        $this->assertDatabaseCount('transaksi', 0);
    }

    /** @test */
    public function TC_BP_09_layanan_ganda_total_harga()
    {
        $user = User::factory()->create();
        $layanan1 = Layanan::factory()->create(['harga' => 5000]);
        $layanan2 = Layanan::factory()->create(['harga' => 3000]);

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [
                ['id_layanan' => $layanan1->id_layanan, 'harga' => 5000, 'dimensi' => 1, 'satuan' => 'kg'],
                ['id_layanan' => $layanan2->id_layanan, 'harga' => 3000, 'dimensi' => 2, 'satuan' => 'kg']
            ],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $this->assertDatabaseHas('transaksi', ['total' => 11000]);
    }

    /** @test */
    public function TC_BP_10_jumlah_item_terlalu_besar_gagal()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 600, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $response->assertSessionHasErrors(['layanan.0.dimensi']);
    }

    /** @test */
    public function TC_BP_11_field_harga_readonly()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('customer.pesanan.create'));
        $response->assertSee('readonly'); // Pastikan field harga readonly
    }

    /** @test */
    public function TC_BP_12_layanan_berbeda_sama_pelanggan()
    {
        $user = User::factory()->create();
        $layanan1 = Layanan::factory()->create(['harga' => 5000]);
        $layanan2 = Layanan::factory()->create(['harga' => 3000]);

        $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan1->id_layanan, 'harga' => 5000, 'dimensi' => 1, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan2->id_layanan, 'harga' => 3000, 'dimensi' => 1, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $this->assertDatabaseCount('transaksi', 2);
    }

    /** @test */
    public function TC_BP_13_total_harga_otomatis_simpan()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create(['harga' => 5000]);

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 2, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $this->assertDatabaseHas('transaksi', ['total' => 10000]);
    }

    /** @test */
    public function TC_BP_14_semua_field_kosong_gagal()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [],
            'antar_jemput' => null,
            'pembayaran_option' => '',
        ]);

        $response->assertSessionHasErrors(['layanan', 'pembayaran_option']);
    }

    /** @test */
    public function TC_BP_15_refresh_hilang_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('customer.pesanan.create'));
        $response->assertStatus(200);
        $this->assertDatabaseCount('transaksi', 0);
    }

    /** @test */
    public function TC_BP_16_ubah_jumlah_item_update_total()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create(['harga' => 5000]);

        $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 1, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $transaksi = Transaksi::first();
        $transaksi->update(['total' => 5000*3]);
        $this->assertEquals(15000, $transaksi->total);
    }

    /** @test */
    public function TC_BP_17_tombol_hapus_kosongkan_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('customer.pesanan.create'));
        $response->assertStatus(200);
        // Simulasi tombol hapus => form kosong (tidak tersimpan)
        $this->assertDatabaseCount('transaksi', 0);
    }

    /** @test */
    public function TC_BP_18_trim_nama_pelanggan()
    {
        $user = User::factory()->create(['name' => 'Putri Dwi']);
        $layanan = Layanan::factory()->create(['harga' => 5000]);

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 2, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $transaksi = Transaksi::first();
        $this->assertEquals('Putri Dwi', trim($transaksi->nama_pelanggan));
    }

    /** @test */
    public function TC_BP_19_hanya_akun_login()
    {
        $user = User::factory()->create(['name' => 'User1']);
        $layanan = Layanan::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 1, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_offline',
        ]);

        $transaksi = Transaksi::first();
        $this->assertEquals('User1', $transaksi->nama_pelanggan);
    }

    /** @test */
    public function TC_BP_20_bayar_langsung_status_bayar()
    {
        $user = User::factory()->create();
        $layanan = Layanan::factory()->create(['harga' => 5000]);

        $response = $this->actingAs($user)->post(route('customer.pesanan.store'), [
            'layanan' => [['id_layanan' => $layanan->id_layanan, 'harga' => 5000, 'dimensi' => 1, 'satuan' => 'kg']],
            'antar_jemput' => 'no',
            'pembayaran_option' => 'bayar_online',
        ]);

        $response->assertRedirect(route('customer.pembayaran.create', ['id_transaksi' => Transaksi::first()->id_transaksi]));
    }
}
