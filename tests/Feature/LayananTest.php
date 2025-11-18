<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Layanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin dan login
        $this->admin = User::factory()->create([
            'usertype' => 'admin',
        ]);
        $this->actingAs($this->admin);
    }

    /** @test */
    public function TC_LY_01_tambah_layanan_baru_valid()
    {
        $data = [
            'nama_layanan' => 'Cuci Kering',
            'harga' => 15000,
            'deskripsi' => 'Deskripsi layanan'
        ];

        $response = $this->post(route('admin.layanan.store'), $data);

        $response->assertRedirect(route('admin.layanan.index'));
        $this->assertDatabaseHas('layanan', $data);
    }

    /** @test */
    public function TC_LY_02_nama_layanan_kosong_gagal()
    {
        $response = $this->post(route('admin.layanan.store'), [
            'nama_layanan' => '',
            'harga' => 15000,
            'deskripsi' => 'Deskripsi layanan'
        ]);

        $response->assertSessionHasErrors(['nama_layanan']);
    }

    /** @test */
    public function TC_LY_03_harga_kosong_gagal()
    {
        $response = $this->post(route('admin.layanan.store'), [
            'nama_layanan' => 'Cuci Basah',
            'harga' => '',
            'deskripsi' => 'Deskripsi layanan'
        ]);

        $response->assertSessionHasErrors(['harga']);
    }

    /** @test */
    public function TC_LY_04_harga_negatif_gagal()
    {
        $response = $this->post(route('admin.layanan.store'), [
            'nama_layanan' => 'Cuci Basah',
            'harga' => -10000,
            'deskripsi' => 'Deskripsi layanan'
        ]);

        $response->assertSessionHasErrors(['harga']);
    }

    /** @test */
    public function TC_LY_05_deskripsi_kosong_gagal()
    {
        $response = $this->post(route('admin.layanan.store'), [
            'nama_layanan' => 'Cuci Basah',
            'harga' => 15000,
            'deskripsi' => ''
        ]);

        $response->assertSessionHasErrors(['deskripsi']);
    }

    /** @test */
    public function TC_LY_06_tambah_layanan_harga_decimal_valid()
    {
        $data = [
            'nama_layanan' => 'Cuci Setrika',
            'harga' => 12500.50,
            'deskripsi' => 'Deskripsi layanan'
        ];

        $response = $this->post(route('admin.layanan.store'), $data);

        $response->assertRedirect(route('admin.layanan.index'));
        $this->assertDatabaseHas('layanan', $data);
    }

    /** @test */
    public function TC_LY_07_tambah_layanan_nama_trim()
    {
        $data = [
            'nama_layanan' => '  Cuci Kering  ',
            'harga' => 15000,
            'deskripsi' => 'Deskripsi layanan'
        ];

        $response = $this->post(route('admin.layanan.store'), $data);

        $this->assertDatabaseHas('layanan', ['nama_layanan' => trim($data['nama_layanan'])]);
    }

    /** @test */
    public function TC_LY_08_tambah_layanan_duplikat_nama()
    {
        Layanan::factory()->create(['nama_layanan' => 'Cuci Kering']);

        $response = $this->post(route('admin.layanan.store'), [
            'nama_layanan' => 'Cuci Kering',
            'harga' => 20000,
            'deskripsi' => 'Deskripsi'
        ]);

        $response->assertSessionHasErrors(['nama_layanan']);
    }

    /** @test */
    public function TC_LY_09_soft_delete_layanan()
    {
        $layanan = Layanan::factory()->create();
        $response = $this->delete(route('admin.layanan.destroy', $layanan->id_layanan));

        $response->assertRedirect(route('admin.layanan.index'));
        $this->assertSoftDeleted('layanan', ['id_layanan' => $layanan->id_layanan]);
    }

    /** @test */
    public function TC_LY_10_restore_layanan()
    {
        $layanan = Layanan::factory()->create();
        $layanan->delete();

        $response = $this->post(route('admin.layanan.restore', $layanan->id_layanan));

        $response->assertRedirect(route('admin.layanan.archive'));
        $this->assertDatabaseHas('layanan', ['id_layanan' => $layanan->id_layanan, 'deleted_at' => null]);
    }

    /** @test */
    public function TC_LY_11_cek_pagination()
    {
        Layanan::factory(15)->create();

        $response = $this->get(route('admin.layanan.index'));

        $response->assertStatus(200);
        $response->assertSeeText(Layanan::first()->nama_layanan);
    }

    /** @test */
    // public function TC_LY_12_update_layanan_valid()
    // {
    //     $layanan = Layanan::factory()->create();

    //     $data = [
    //         'nama_layanan' => 'Cuci Basah',
    //         'harga' => 25000,
    //         'deskripsi' => 'Deskripsi baru'
    //     ];

    //     // Pastikan route update ada
    //     $response = $this->put(route('admin.layanan.update', $layanan->id_layanan), $data);

    //     $response->assertRedirect(route('admin.layanan.index'));
    //     $this->assertDatabaseHas('layanan', ['nama_layanan' => 'Cuci Basah']);
    // }

    // /** @test */
    // public function TC_LY_13_update_layanan_nama_kosong_gagal()
    // {
    //     $layanan = Layanan::factory()->create();

    //     $response = $this->put(route('admin.layanan.update', $layanan->id_layanan), [
    //         'nama_layanan' => '',
    //         'harga' => 25000,
    //         'deskripsi' => 'Deskripsi baru'
    //     ]);

    //     $response->assertSessionHasErrors(['nama_layanan']);
    // }

    // /** @test */
    // public function TC_LY_14_update_layanan_harga_kosong_gagal()
    // {
    //     $layanan = Layanan::factory()->create();

    //     $response = $this->put(route('admin.layanan.update', $layanan->id_layanan), [
    //         'nama_layanan' => 'Cuci Basah',
    //         'harga' => '',
    //         'deskripsi' => 'Deskripsi baru'
    //     ]);

    //     $response->assertSessionHasErrors(['harga']);
    // }

    // /** @test */
    // public function TC_LY_15_update_layanan_deskripsi_kosong_gagal()
    // {
    //     $layanan = Layanan::factory()->create();

    //     $response = $this->put(route('admin.layanan.update', $layanan->id_layanan), [
    //         'nama_layanan' => 'Cuci Basah',
    //         'harga' => 25000,
    //         'deskripsi' => ''
    //     ]);

    //     $response->assertSessionHasErrors(['deskripsi']);
    // }

    /** @test */
    public function TC_LY_16_hapus_layanan_yang_tidak_ada()
    {
        $response = $this->delete('/admin/layanan/99999');
        $response->assertStatus(404);
    }

    /** @test */
    public function TC_LY_17_restore_layanan_yang_tidak_ada()
    {
        $response = $this->post('/admin/layanan/restore/99999');
        $response->assertStatus(404);
    }

    /** @test */
    public function TC_LY_18_tambah_layanan_nama_terlalu_panjang()
    {
        $response = $this->post(route('admin.layanan.store'), [
            'nama_layanan' => str_repeat('A', 256),
            'harga' => 15000,
            'deskripsi' => 'Deskripsi'
        ]);

        $response->assertSessionHasErrors(['nama_layanan']);
    }

    /** @test */
    public function TC_LY_19_harga_0_valid()
    {
        $data = [
            'nama_layanan' => 'Gratis',
            'harga' => 0,
            'deskripsi' => 'Layanan gratis'
        ];

        $response = $this->post(route('admin.layanan.store'), $data);
        $response->assertRedirect(route('admin.layanan.index'));
        $this->assertDatabaseHas('layanan', ['nama_layanan' => 'Gratis']);
    }

    /** @test */
    public function TC_LY_20_deskripsi_panjang_valid()
    {
        $desc = str_repeat('Deskripsi ', 50);
        $data = [
            'nama_layanan' => 'Cuci Sepatu',
            'harga' => 15000,
            'deskripsi' => $desc
        ];

        $response = $this->post(route('admin.layanan.store'), $data);
        $response->assertRedirect(route('admin.layanan.index'));
        $this->assertDatabaseHas('layanan', ['deskripsi' => $desc]);
    }
}
