<?php  

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat user dummy untuk customer123
        User::create([
            'name' => 'customer123',
            'email' => 'customer123@example.com',
            'user_type' => 'customer', 
            'alamat' => 'Alamat Customer',
            'nomor_hp' => '08123456789',
            'password' => Hash::make('password123'), 
        ]);

        // Membuat user dummy untuk admin123
        User::create([
            'name' => 'admin123',
            'email' => 'admin123@example.com',
            'user_type' => 'admin', 
            'alamat' => 'Alamat Admin',
            'nomor_hp' => '08987654321',
            'password' => Hash::make('password123'), 
        ]);
    }
}
