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
        // Membuat user dummy untuk admin123
        User::create([
            'name' => 'edwink',
            'email' => 'edwin@gmail.com',
            'usertype' => 'admin', 
            'alamat' => 'Alamat Admin',
            'nomor_hp' => '085235589635',
            'password' => Hash::make('password123'), 
        ]);
    }
}
