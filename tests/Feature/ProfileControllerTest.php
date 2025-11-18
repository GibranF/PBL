<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function TC_PRO_01_ubah_nama_dengan_data_valid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
        ]);
    }

    /** @test */
    public function TC_PRO_02_kosongkan_nama_lalu_simpan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function TC_PRO_03_ubah_email_format_benar()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'gibranf@gmail.com',
        ]);
    }

    /** @test */
    public function TC_PRO_04_masukkan_email_tidak_sesuai_format()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranfgmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function TC_PRO_05_kosongkan_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => '',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function TC_PRO_06_masukkan_alamat_baru()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Perum Griya Giri Mulya Klatak',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');
    }

    /** @test */
    public function TC_PRO_07_kosongkan_alamat()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => '',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['alamat']);
    }

    /** @test */
    public function TC_PRO_08_masukkan_nomor_hp_format_benar()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');
    }

    /** @test */
    public function TC_PRO_09_masukkan_nomor_tidak_valid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '08123abc',
        ]);

        $response->assertSessionHasErrors(['nomor_hp']);
    }

    /** @test */
    public function TC_PRO_10_masukkan_nomor_terlalu_pendek()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '0812',
        ]);

        $response->assertSessionHasErrors(['nomor_hp']);
    }

    /** @test */
    public function TC_PRO_11_hapus_foto_profil()
    {
        Storage::fake('public');
        $user = User::factory()->create(['profile_photo' => 'profile-photos/old_photo.jpg']);
        Storage::disk('public')->put('profile-photos/old_photo.jpg', 'fake image');

        $response = $this->actingAs($user)->delete(route('profile.delete-photo'));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Foto profil berhasil dihapus.');
        Storage::disk('public')->assertMissing('profile-photos/old_photo.jpg');
    }

    /** @test */
    public function TC_PRO_12_upload_file_gambar_format_benar()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
            'profile_photo' => $file,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');
        Storage::disk('public')->assertExists('profile-photos/' . $file->hashName());
    }

    /** @test */
    public function TC_PRO_13_upload_file_bukan_gambar()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'GibranF',
            'email' => 'gibranf@gmail.com',
            'alamat' => 'Alamat Test',
            'nomor_hp' => '081234567890',
            'profile_photo' => $file,
        ]);

        $response->assertSessionHasErrors(['profile_photo']);
    }

    /** @test */
    public function TC_PRO_14_ubah_password_dengan_data_valid()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'password-updated');
        $this->assertTrue(Hash::check('Password123', $user->fresh()->password));
    }

    /** @test */
    public function TC_PRO_15_password_baru_dan_konfirmasi_berbeda()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'Password123',
            'password_confirmation' => 'Password456',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function TC_PRO_16_password_baru_sama_dengan_lama()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'Gibbb123',
            'password_confirmation' => 'Gibbb123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function TC_PRO_17_password_terlalu_pendek()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'abc',
            'password_confirmation' => 'abc',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function TC_PRO_18_password_tanpa_angka()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'CustomerPass',
            'password_confirmation' => 'CustomerPass',
        ]);

        // Sesuai permintaan: password hanya huruf diizinkan → redirect sukses
        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'password-updated');
        $this->assertTrue(Hash::check('CustomerPass', $user->fresh()->password));
    }

    /** @test */
    public function TC_PRO_19_password_tanpa_simbol()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'Customer123',
            'password_confirmation' => 'Customer123',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('status', 'password-updated');
        $this->assertTrue(Hash::check('Customer123', $user->fresh()->password));
    }

    /** @test */
    public function TC_PRO_20_kosongkan_field_lama()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => '',
            'password' => 'NewPass123',
            'password_confirmation' => 'NewPass123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    /** @test */
    public function TC_PRO_21_kosongkan_field_baru()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function TC_PRO_22_kosongkan_konfirmasi()
    {
        $user = User::factory()->create(['password' => bcrypt('Gibbb123')]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'Gibbb123',
            'password' => 'NewPass123',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
