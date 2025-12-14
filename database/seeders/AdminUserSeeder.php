<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed only the admin user.
     */
    public function run(): void
    {
        // Check if admin already exists
        $existingAdmin = User::where('email', 'admin@sharestation.com')->first();
        
        if (!$existingAdmin) {
            User::create([
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@sharestation.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_host' => false,
            ]);
            
            echo "Admin user created successfully!\n";
            echo "Email: admin@sharestation.com\n";
            echo "Password: password\n";
        } else {
            echo "Admin user already exists!\n";
            echo "Email: admin@sharestation.com\n";
        }
    }
}
