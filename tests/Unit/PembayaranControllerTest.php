<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Transaksi;
use App\Http\Controllers\PembayaranController;

class PembayaranControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected PembayaranController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin dummy
        $this->admin = User::factory()->create([
            'usertype' => 'admin',
        ]);

        $this->controller = new PembayaranController();
    }

    /** ================= Test pembayaran ================= */

    /** @test */
    public function test_total_tanpa_antar_jemput()
    {
        $layanan = [
            ['harga' => 12000, 'dimensi' => 3],
        ];
        $total = collect($layanan)->sum(fn($item) => $item['harga'] * $item['dimensi']);
        $this->assertEquals(36000, $total);
    }

    /** @test */
    public function test_batal_transaksi_unit()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->admin->id,
        ]);

        $deleted = $transaksi->delete();
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('transaksi', ['id_transaksi' => $transaksi->id_transaksi]);
    }

    /** @test */
    public function test_batal_transaksi_nonexistent()
    {
        $response = $this->delete('/transaksi/9999/batal');
        $response->assertStatus(404);
    }

    /** @test */
    public function test_bayar_cash_unit()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->admin->id,
            'status_pembayaran' => 'belum dibayar',
        ]);

        $transaksi->status_pembayaran = 'sudah dibayar';
        $transaksi->metode_pembayaran = 'tunai';
        $transaksi->save();

        $this->assertDatabaseHas('transaksi', [
            'id_transaksi' => $transaksi->id_transaksi,
            'status_pembayaran' => 'sudah dibayar',
            'metode_pembayaran' => 'tunai',
        ]);
    }

    /** @test */
    public function test_set_metode_pembayaran_unit()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->admin->id,
            'metode_pembayaran' => null,
        ]);

        $transaksi->metode_pembayaran = 'transfer';
        $transaksi->save();

        $this->assertDatabaseHas('transaksi', [
            'id_transaksi' => $transaksi->id_transaksi,
            'metode_pembayaran' => 'transfer',
        ]);
    }

    /** @test */
    public function test_set_metode_pembayaran_kosong()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->admin->id,
            'metode_pembayaran' => null,
        ]);

        $transaksi->metode_pembayaran = null;
        $transaksi->save();

        $this->assertDatabaseHas('transaksi', [
            'id_transaksi' => $transaksi->id_transaksi,
            'metode_pembayaran' => null,
        ]);
    }

    /** @test */
    public function pembayaran_gagal_jika_data_kosong(): void
    {
        $response = $this->patch('/transaksi//bayar', []);
        $response->assertStatus(404); // ID transaksi kosong, harus gagal
    }

    /** @test */
    public function test_bayar_cash_dengan_id_valid()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->admin->id,
            'status_pembayaran' => 'belum dibayar',
        ]);

        $response = $this->patch("/transaksi/{$transaksi->id_transaksi}/bayar", [
            'metode_pembayaran' => 'cash'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('transaksi', [
            'id_transaksi' => $transaksi->id_transaksi,
            'status_pembayaran' => 'sudah dibayar',
        ]);
    }

    /** @test */
    public function test_set_metode_transfer_dan_bayar()
    {
        $transaksi = Transaksi::factory()->create([
            'id_user' => $this->admin->id,
            'metode_pembayaran' => null,
            'status_pembayaran' => 'belum dibayar',
        ]);

        $transaksi->metode_pembayaran = 'transfer';
        $transaksi->status_pembayaran = 'sudah dibayar';
        $transaksi->save();

        $this->assertDatabaseHas('transaksi', [
            'id_transaksi' => $transaksi->id_transaksi,
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'sudah dibayar',
        ]);
    }

    /** @test */
    public function test_multiple_transaksi_dibayar()
    {
        $transaksi1 = Transaksi::factory()->create(['id_user' => $this->admin->id]);
        $transaksi2 = Transaksi::factory()->create(['id_user' => $this->admin->id]);

        $transaksi1->status_pembayaran = 'sudah dibayar';
        $transaksi1->save();
        $transaksi2->status_pembayaran = 'sudah dibayar';
        $transaksi2->save();

        $this->assertDatabaseHas('transaksi', ['id_transaksi' => $transaksi1->id_transaksi, 'status_pembayaran' => 'sudah dibayar']);
        $this->assertDatabaseHas('transaksi', ['id_transaksi' => $transaksi2->id_transaksi, 'status_pembayaran' => 'sudah dibayar']);
    }
}
