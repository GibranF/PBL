<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Layanan;
use Carbon\Carbon;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user owner untuk akses laporan
        $this->owner = User::factory()->create([
            'usertype' => 'owner'
        ]);
    }

    /** @test */
    public function TC_LP_01_menampilkan_seluruh_laporan_tanpa_filter()
    {
        Transaksi::factory(5)->create();

        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan'));

        $response->assertStatus(200);
        $response->assertViewHasAll([
            'todayIncome', 'weeklyIncome', 'monthlyIncome', 'yearlyIncome', 'totalSales'
        ]);
    }

    /** @test */
    public function TC_LP_02_filter_berdasarkan_rentang_tanggal()
    {
        $t1 = Transaksi::factory()->create(['created_at' => '2025-09-22']);
        $t2 = Transaksi::factory()->create(['created_at' => '2025-09-24']);
        $t3 = Transaksi::factory()->create(['created_at' => '2025-09-26']);

        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan', [
            'start_date' => '2025-09-22',
            'end_date' => '2025-09-25'
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('dailyTotals', function($totals) use ($t1, $t2) {
            return $totals->contains($t1->total) && $totals->contains($t2->total);
        });
    }

    /** @test */
    public function TC_LP_03_filter_satu_tanggal_saja()
    {
        $t1 = Transaksi::factory()->create(['created_at' => '2025-09-22']);
        $t2 = Transaksi::factory()->create(['created_at' => '2025-09-23']);

        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan', [
            'start_date' => '2025-09-22'
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('dailyTotals', function($totals) use ($t1) {
            return $totals->contains($t1->total);
        });
    }

    /** @test */
    public function TC_LP_04_filter_berdasarkan_layanan_tertentu()
    {
        $layanan = Layanan::factory()->create(['nama_layanan' => 'Cuci Kering']);
        $transaksi = Transaksi::factory()->create();
        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $layanan->id_layanan,
            'subtotal' => 5000
        ]);

        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan', [
            'layanan' => $layanan->id_layanan
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('serviceLabels', function($labels) use ($layanan) {
            return $labels->contains($layanan->nama_layanan);
        });
    }

    /** @test */
    public function TC_LP_05_filter_kombinasi_tanggal_dan_layanan()
    {
        $layanan = Layanan::factory()->create(['nama_layanan' => 'Setrika Saja']);
        $transaksi = Transaksi::factory()->create(['created_at' => '2025-09-23']);
        DetailTransaksi::factory()->create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_layanan' => $layanan->id_layanan,
            'subtotal' => 15000
        ]);

        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan', [
            'start_date' => '2025-09-22',
            'end_date' => '2025-09-25',
            'layanan' => $layanan->id_layanan
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('serviceTotals', function($totals) {
            return $totals->sum() > 0;
        });
    }

    /** @test */
    public function TC_LP_06_reset_filter()
    {
        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan'));

        $response->assertStatus(200);
        $response->assertViewHasAll(['todayIncome', 'totalSales']);
    }

    /** @test */
    public function TC_LP_07_format_tanggal_tidak_valid()
    {
        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan', [
            'start_date' => '31/31/2025',
        ]));

        $response->assertSessionHasErrors(['start_date']);
    }

    /** @test */
    public function TC_LP_08_filter_tanpa_data_sesuai_kriteria()
    {
        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan', [
            'start_date' => '2050-01-01',
            'end_date' => '2050-01-02'
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('serviceLabels', function($labels) {
            return $labels->isEmpty();
        });
    }

    /** @test */
    public function TC_LP_09_tampilan_tabel_periode()
    {
        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan'));
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'dailyLabels','weeklyLabels','monthlyLabels','yearlyLabels'
        ]);
    }

    /** @test */
    public function TC_LP_10_total_pendapatan_harian()
    {
        $transaksi = Transaksi::factory()->create(['created_at' => Carbon::today(), 'total' => 50000]);

        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan'));
        $response->assertStatus(200);
        $response->assertViewHas('todayIncome', 50000);
    }

    /** @test */
    public function TC_LP_11_validasi_tampilan_laporan_kosong()
    {
        $response = $this->actingAs($this->owner)->get(route('owner.laporan.laporan'));

        $response->assertStatus(200);
        $response->assertViewHas('serviceLabels', function($labels) {
            return $labels->isEmpty();
        });
    }
}
