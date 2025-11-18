<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ulasan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UlasanRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function menambahkan_ulasan_dengan_data_valid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('ulasan.store'), [
            'komentar' => 'Sangat baik pelayanannya',
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ulasan', [
            'id_user' => $user->id,
            'pesan' => 'Sangat baik pelayanannya',
            'rating' => 5,
        ]);
    }

    /** @test */
    public function gagal_menambahkan_ulasan_tanpa_login()
    {
        $response = $this->post(route('ulasan.store'), [
            'komentar' => 'Tanpa login',
            'rating' => 5,
        ]);

        $response->assertRedirect('/login'); // Middleware auth
    }

    /** @test */
    public function gagal_menambahkan_ulasan_tanpa_komentar()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('ulasan.store'), [
            'komentar' => '',
            'rating' => 4,
        ]);

        $response->assertSessionHasErrors(['komentar']);
    }

    /** @test */
    public function gagal_menambahkan_ulasan_tanpa_rating()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('ulasan.store'), [
            'komentar' => 'Layanan cepat',
            // rating kosong
        ]);

        $response->assertSessionHasErrors(['rating']);
    }

    /** @test */
    public function gagal_menambahkan_ulasan_dengan_karakter_spesial()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('ulasan.store'), [
            'komentar' => 'Bagus banget!!!@@@###',
            'rating' => 4,
        ]);

        $this->assertDatabaseHas('ulasan', [
            'pesan' => 'Bagus banget!!!@@@###',
        ]);
    }

    /** @test */
    public function menambahkan_ulasan_dengan_komentar_panjang()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $longComment = str_repeat('a', 350);

        $response = $this->post(route('ulasan.store'), [
            'komentar' => $longComment,
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ulasan', [
            'id_user' => $user->id,
            'rating' => 5,
        ]);
    }

    /** @test */
    public function user_dapat_mengedit_ulasan_yang_dibuatnya()
    {
        $user = User::factory()->create();
        $ulasan = Ulasan::factory()->create([
            'id_user' => $user->id,
            'pesan' => 'Pelayanan biasa saja',
            'rating' => 3,
        ]);

        $response = $this->actingAs($user)->put(route('ulasan.update', $ulasan->id_ulasan), [
            'komentar' => 'Pelayanan sangat bagus',
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ulasan', [
            'id_ulasan' => $ulasan->id_ulasan,
            'pesan' => 'Pelayanan sangat bagus',
            'rating' => 5,
        ]);
    }

    /** @test */
    public function user_dapat_menghapus_ulasan_yang_dibuatnya()
    {
        $user = User::factory()->create();
        $ulasan = Ulasan::factory()->create(['id_user' => $user->id]);

        $response = $this->actingAs($user)->delete(route('ulasan.destroy', $ulasan->id_ulasan));

        $response->assertRedirect();
        $this->assertDatabaseMissing('ulasan', ['id_ulasan' => $ulasan->id_ulasan]);
    }

    /** @test */
    public function admin_dapat_menghapus_ulasan_user()
    {
        $admin = User::factory()->create(['usertype' => 'admin']);
        $ulasan = Ulasan::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.ulasan.destroy', $ulasan->id_ulasan));

        $response->assertRedirect();
        $this->assertDatabaseMissing('ulasan', ['id_ulasan' => $ulasan->id_ulasan]);
    }

    /** @test */
    public function urutan_ulasan_dapat_ditampilkan_berdasarkan_rating_atau_waktu()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Ulasan::factory()->create(['id_user' => $user->id, 'rating' => 5, 'created_at' => now()]);
        Ulasan::factory()->create(['id_user' => $user->id, 'rating' => 2, 'created_at' => now()->subDay()]);

        $latest = Ulasan::orderBy('created_at', 'desc')->first();
        $highestRating = Ulasan::orderBy('rating', 'desc')->first();

        $this->assertEquals(5, $highestRating->rating);
        $this->assertTrue($latest->created_at >= $highestRating->created_at);
    }
}
