<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'user_name' => 'Admin',
            'user_phone_number' => '1234567890',
            'user_address' => '123 Main Street',
            'user_password' => bcrypt('password123'), 
            'user_sweetword' => 'mySweetWord',
            'user_hash_pass' => bcrypt('password123'), 
            'user_module_id' => null,
            'user_permission_id' => null,
            'is_delete' => false,
        ]);
    }
}
