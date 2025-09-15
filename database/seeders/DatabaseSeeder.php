<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Layanan;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            User::create([
            'name' => 'owner123',
            'email' => 'owner123@example.com',
            'usertype' => 'owner', 
            'alamat' => 'Alamat Owner',
            'nomor_hp' => '08123029347',
            'password' => Hash::make('password123'), 
        ]);

        
            User::create([
            'name' => 'customer123',
            'email' => 'customer123@example.com',
            'usertype' => 'customer', 
            'alamat' => 'Alamat Customer',
            'nomor_hp' => '08123456789',
            'password' => Hash::make('password123'), 
        ]);

        // Membuat user dummy untuk admin123
        User::create([
            'name' => 'admin123',
            'email' => 'admin123@example.com',
            'usertype' => 'admin', 
            'alamat' => 'Alamat Admin',
            'nomor_hp' => '08987654321',
            'password' => Hash::make('password123'), 
        ]);

        Layanan::create([
            'nama_layanan' => 'Cuci Kering',
            'harga' => 15000.00,
            'deskripsi' => 'Pencucian dan pengeringan pakaian dengan mesin.',
        ]);

        Layanan::create([
            'nama_layanan' => 'Setrika',
            'harga' => 5000.00,
            'deskripsi' => 'Penyetrikaan pakaian hingga rapi.',
        ]);

        Layanan::create([
            'nama_layanan' => 'Cuci Lipat',
            'harga' => 20000.00,
            'deskripsi' => 'Pencucian, pengeringan, dan pelipatan pakaian.',
        ]);

        Layanan::create([
            'nama_layanan' => 'Testing',
            'harga' => 1000.00,
            'deskripsi' => 'Testing',
        ]);
    
    }
}
