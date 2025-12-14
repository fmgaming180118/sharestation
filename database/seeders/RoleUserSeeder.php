<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@sharestation.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_host' => false,
        ]);

        // Create driver user
        User::create([
            'name' => 'Driver User',
            'username' => 'driver',
            'email' => 'driver@sharestation.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'is_host' => false,
        ]);

        // Create warga user
        User::create([
            'name' => 'Warga User',
            'username' => 'warga',
            'email' => 'warga@sharestation.com',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'is_host' => false,
        ]);
    }
}
