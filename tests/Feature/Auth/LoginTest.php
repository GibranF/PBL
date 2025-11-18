<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_dengan_kredensial_valid()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'user@example.com', // sesuaikan dengan nama field di form
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_dengan_email_tidak_terdaftar_gagal()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => 'notfound@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** @test */
    public function login_dengan_password_salah_gagal()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'login' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** @test */
    public function login_tanpa_mengisi_salah_satu_field_gagal()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** @test */
    public function login_tanpa_mengisi_kedua_field_gagal()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['login', 'password']);
        $this->assertGuest();
    }

    /** @test */
    public function login_dengan_username_lebih_dari_50_karakter_gagal()
    {
        $longLogin = str_repeat('a', 51) . '@example.com';
        $response = $this->from('/login')->post('/login', [
            'login' => $longLogin,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** @test */
    public function login_dengan_format_email_tidak_valid_gagal()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => 'Customer123',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** @test */
    public function fitur_lupa_password_mengirim_email()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com'
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertNotEmpty(session('status'));
    }

    /** @test */
    public function tautan_daftar_sekarang_menampilkan_halaman_register()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    /** @test */
public function password_tidak_terlihat_di_form_login()
{
    $response = $this->get('/login');
    $response->assertStatus(200);

    // Cek kalau ada input field type password
    $response->assertSee('<input', false); // cek ada tag input
    $this->assertMatchesRegularExpression(
        '/<input[^>]+type=["\']password["\'][^>]*>/i',
        $response->getContent()
    );
}
}
