<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::create([
            'role_name' => 'Customer',
            'role_status' => 1, // 1: Active
        ]);

        UserRole::create([
            'role_name' => 'Salesman',
            'role_status' => 1, // 1: Active
        ]);

        UserRole::create([
            'role_name' => 'Manager',
            'role_status' => 1, // 1: Active
        ]);
        
    }
}
