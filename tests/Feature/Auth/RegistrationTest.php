<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pendaftaran_customer_dengan_data_valid_berhasil() // TC-01
    {
        $response = $this->post('/register', [
            'name' => 'Gibran Fitratullah',
            'email' => 'gibran@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 12, Banyuwangi',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'gibran@mail.com']);
    }

    /** @test */
    public function gagal_ketika_nama_kosong() // TC-02
    {
        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'user@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 1',
            'nomor_hp' => '081234567891',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function gagal_ketika_nama_mengandung_simbol() // TC-03
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Rina123#',
            'email' => 'rina@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati No. 3',
            'nomor_hp' => '081234567892',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function pendaftaran_customer_dengan_nama_1_karakter_berhasil() // TC-04
    {
        $response = $this->post('/register', [
            'name' => 'A',
            'email' => 'user1char@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Anggrek No. 2',
            'nomor_hp' => '081234567893',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'user1char@mail.com']);
    }

    /** @test */
    public function gagal_ketika_nama_lebih_dari_100_karakter() // TC-05
    {
        $name = str_repeat('A', 101);
        $response = $this->from('/register')->post('/register', [
            'name' => $name,
            'email' => 'toolong@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 2',
            'nomor_hp' => '081234567894',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function gagal_ketika_email_kosong() // TC-06
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Test',
            'email' => '',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati No. 2',
            'nomor_hp' => '081234567895',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function gagal_email_tidak_valid() // TC-07
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Test',
            'email' => 'user@abc',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 3',
            'nomor_hp' => '081234567896',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_valid_berhasil() // TC-08
    {
        $response = $this->post('/register', [
            'name' => 'User Email Valid',
            'email' => 'uservalid@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati No. 4',
            'nomor_hp' => '081234567897',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'uservalid@mail.com']);
    }

    /** @test */
    public function gagal_email_sudah_terdaftar() // TC-09
    {
        User::factory()->create(['email' => 'used@mail.com']);

        $response = $this->from('/register')->post('/register', [
            'name' => 'User Baru',
            'email' => 'used@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Kenanga No. 7',
            'nomor_hp' => '081234567898',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function gagal_password_kurang_dari_8_karakter() // TC-10
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Short Pass',
            'email' => 'shortpass@mail.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
            'alamat' => 'Jl. Anggrek No. 5',
            'nomor_hp' => '081234567899',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_8_karakter_berhasil() // TC-11
    {
        $response = $this->post('/register', [
            'name' => 'User 8 Char',
            'email' => 'user8char@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 6',
            'nomor_hp' => '081234567800',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'user8char@mail.com']);
    }

    /** @test */
    public function password_lebih_dari_8_karakter_berhasil() // TC-12
    {
        $response = $this->post('/register', [
            'name' => 'User Long Pass',
            'email' => 'userlong@mail.com',
            'password' => 'abcd12345',
            'password_confirmation' => 'abcd12345',
            'alamat' => 'Jl. Melati No. 5',
            'nomor_hp' => '081234567801',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'userlong@mail.com']);
    }

    /** @test */
    public function password_hanya_huruf_berhasil() // TC-13
    {
        $response = $this->post('/register', [
            'name' => 'User Huruf',
            'email' => 'userhuruf@mail.com',
            'password' => 'abcdefgh',
            'password_confirmation' => 'abcdefgh',
            'alamat' => 'Jl. Mawar No. 7',
            'nomor_hp' => '081234567802',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'userhuruf@mail.com']);
    }

    /** @test */
    public function password_hanya_angka_berhasil() // TC-14
    {
        $response = $this->post('/register', [
            'name' => 'User Angka',
            'email' => 'userangka@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'alamat' => 'Jl. Melati No. 6',
            'nomor_hp' => '081234567803',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'userangka@mail.com']);
    }

    /** @test */
    public function gagal_konfirmasi_password_tidak_sesuai() // TC-15
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Konfirmasi',
            'email' => 'userkonfirmasi@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd123',
            'alamat' => 'Jl. Mawar No. 8',
            'nomor_hp' => '081234567804',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function konfirmasi_password_cocok_berhasil() // TC-16
    {
        $response = $this->post('/register', [
            'name' => 'User Konfirmasi Benar',
            'email' => 'userkonfirmbenar@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati No. 7',
            'nomor_hp' => '081234567805',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'userkonfirmbenar@mail.com']);
    }

    /** @test */
    public function gagal_alamat_mengandung_simbol() // TC-17
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Alamat',
            'email' => 'useralamat@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati *@#',
            'nomor_hp' => '081234567806',
        ]);

        $response->assertSessionHasErrors('alamat');
    }

    /** @test */
    public function gagal_alamat_lebih_255_karakter() // TC-18
    {
        $alamat = str_repeat('A', 256);
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Alamat Panjang',
            'email' => 'useralamatpanjang@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => $alamat,
            'nomor_hp' => '081234567807',
        ]);

        $response->assertSessionHasErrors('alamat');
    }

    /** @test */
    public function alamat_valid_berhasil() // TC-19
    {
        $response = $this->post('/register', [
            'name' => 'User Alamat Valid',
            'email' => 'useralamatvalid@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 12, Jakarta',
            'nomor_hp' => '081234567808',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'useralamatvalid@mail.com']);
    }

    /** @test */
    public function gagal_no_hp_kurang_dari_12_digit() // TC-20
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User HP Kurang',
            'email' => 'userhpkurang@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 13',
            'nomor_hp' => '08123456',
        ]);

        $response->assertSessionHasErrors('nomor_hp');
    }

    /** @test */
    public function no_hp_12_digit_berhasil() // TC-21
    {
        $response = $this->post('/register', [
            'name' => 'User HP 12 Digit',
            'email' => 'userhp12@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati No. 8',
            'nomor_hp' => '081234567887',
        ]);

        $response->assertRedirect(route('halaman.landing-page'));
        $this->assertDatabaseHas('users', ['email' => 'userhp12@mail.com']);
    }

    /** @test */
    public function gagal_no_hp_lebih_dari_15_digit() 
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User HP Panjang',
            'email' => 'userhppanjang@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Mawar No. 14',
            'nomor_hp' => '0812345678901234',
        ]);

        $response->assertSessionHasErrors('nomor_hp');
    }

    /** @test */
    public function gagal_no_hp_sudah_terdaftar() // TC-23
    {
        User::factory()->create(['nomor_hp' => '081234567890']);

        $response = $this->from('/register')->post('/register', [
            'name' => 'User HP Terdaftar',
            'email' => 'userhpsudah@mail.com',
            'password' => 'abcd1234',
            'password_confirmation' => 'abcd1234',
            'alamat' => 'Jl. Melati No. 9',
            'nomor_hp' => '081234567890',
        ]);

        $response->assertSessionHasErrors('nomor_hp');
    }
}
