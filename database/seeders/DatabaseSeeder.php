<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Station;
use App\Models\Port;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call individual seeders
        $this->call([
            RoleUserSeeder::class,
            AdminUserSeeder::class,
            StationSeeder::class,
        ]);
        
        // User 1: Driver - Irwansyah
        $driver = User::create([
            'name' => 'Irwansyah',
            'username' => 'irwansyah',
            'email' => 'driver@test.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'phone_number' => '081234567890',
            'address' => 'Jl. Kebon Jeruk No. 15, Jakarta Barat',
            'role' => 'driver',
            'is_host' => false,
            'avatar' => null,
        ]);

        // User 2: Owner - Ismail bin mail
        $owner = User::create([
            'name' => 'Ismail bin mail',
            'username' => 'ismail',
            'email' => 'owner@test.com',
            'password' => Hash::make('password'),
            'phone' => '081298765432',
            'phone_number' => '081298765432',
            'address' => 'Jl. Cempaka Putih Tengah, Jakarta Pusat',
            'role' => 'warga',
            'is_host' => true,
            'avatar' => null,
        ]);

        // Get admin user from AdminUserSeeder
        $admin = User::where('email', 'admin@sharestation.com')->first();

        // ============================================
        // STATIONS - Create Volt Express owned by Ismail
        // ============================================
        
        $voltExpress = Station::create([
            'user_id' => $owner->id,
            'name' => 'Volt Express',
            'address' => 'Jl. Cempaka Putih Tengah, Cempaka Putih, Jakarta Pusat',
            'latitude' => -6.1750,
            'longitude' => 106.8650,
            'operational_hours' => '09.00 - 23.00',
            'is_open' => true,
            'amenities' => ['Wifi', 'Cafe', 'Prayer Room', 'Toilet'],
            'image' => null,
            'is_active' => true,
        ]);

        // Create additional stations for variety
        $voltStation = Station::create([
            'user_id' => $owner->id,
            'name' => 'Volt Station - Senen Raya',
            'address' => 'Jl. Senen Raya No. 135, Jakarta Pusat',
            'latitude' => -6.1845,
            'longitude' => 106.8420,
            'operational_hours' => '08.00 - 22.00',
            'is_open' => true,
            'amenities' => ['Wifi', 'Toilet'],
            'image' => null,
            'is_active' => true,
        ]);

        $voltHub = Station::create([
            'user_id' => $owner->id,
            'name' => 'Volt Hub - Kelapa Gading',
            'address' => 'Jl. Boulevard Raya, Kelapa Gading, Jakarta Utara',
            'latitude' => -6.1548,
            'longitude' => 106.9080,
            'operational_hours' => '24 Hours',
            'is_open' => true,
            'amenities' => ['Wifi', 'Cafe', 'Prayer Room', 'Toilet', 'Parking'],
            'image' => null,
            'is_active' => true,
        ]);

        // ============================================
        // PORTS - Create charging ports for each station
        // ============================================
        
        // Volt Express Ports
        $portA = Port::create([
            'station_id' => $voltExpress->id,
            'name' => 'Port A',
            'type' => 'Fast Charging',
            'power_kw' => 150,
            'price_per_kwh' => 2500,
            'status' => 'available',
        ]);

        $portB = Port::create([
            'station_id' => $voltExpress->id,
            'name' => 'Port B',
            'type' => 'Regular Charging',
            'power_kw' => 50,
            'price_per_kwh' => 1500,
            'status' => 'available',
        ]);

        $portC = Port::create([
            'station_id' => $voltExpress->id,
            'name' => 'Port C',
            'type' => 'Fast Charging',
            'power_kw' => 150,
            'price_per_kwh' => 2500,
            'status' => 'busy',
        ]);

        // Volt Station Ports
        Port::create([
            'station_id' => $voltStation->id,
            'name' => 'Port 1',
            'type' => 'Regular Charging',
            'power_kw' => 50,
            'price_per_kwh' => 1500,
            'status' => 'available',
        ]);

        Port::create([
            'station_id' => $voltStation->id,
            'name' => 'Port 2',
            'type' => 'Fast Charging',
            'power_kw' => 100,
            'price_per_kwh' => 2000,
            'status' => 'available',
        ]);

        // Volt Hub Ports
        Port::create([
            'station_id' => $voltHub->id,
            'name' => 'Port Alpha',
            'type' => 'Fast Charging',
            'power_kw' => 200,
            'price_per_kwh' => 3000,
            'status' => 'available',
        ]);

        Port::create([
            'station_id' => $voltHub->id,
            'name' => 'Port Beta',
            'type' => 'Fast Charging',
            'power_kw' => 200,
            'price_per_kwh' => 3000,
            'status' => 'available',
        ]);

        Port::create([
            'station_id' => $voltHub->id,
            'name' => 'Port Gamma',
            'type' => 'Regular Charging',
            'power_kw' => 50,
            'price_per_kwh' => 1500,
            'status' => 'maintenance',
        ]);

        // ============================================
        // TRANSACTIONS - Create the specific invoice TRF20251209
        // ============================================
        
        $transaction1 = Transaction::create([
            'transaction_code' => 'TRF20251209',
            'user_id' => $driver->id,
            'station_id' => $voltExpress->id,
            'port_id' => $portA->id,
            'date' => now()->subDays(5)->toDateString(),
            'start_time' => now()->subDays(5)->setTime(14, 30),
            'end_time' => null,
            'duration_minutes' => null,
            'total_kwh' => null,
            'total_price' => 20000,
            'payment_status' => 'pending',
            'confirmation_code' => 'Je092jej20jK',
        ]);

        // Create additional transactions for testing
        $transaction2 = Transaction::create([
            'transaction_code' => 'TRF20251210',
            'user_id' => $driver->id,
            'station_id' => $voltExpress->id,
            'port_id' => $portB->id,
            'date' => now()->subDays(3)->toDateString(),
            'start_time' => now()->subDays(3)->setTime(10, 15),
            'end_time' => now()->subDays(3)->setTime(12, 15),
            'duration_minutes' => 120,
            'total_kwh' => 30.5,
            'total_price' => 45750,
            'payment_status' => 'paid',
            'confirmation_code' => 'Ke092jef20hL',
        ]);

        $transaction3 = Transaction::create([
            'transaction_code' => 'TRF20251211',
            'user_id' => $driver->id,
            'station_id' => $voltStation->id,
            'port_id' => null,
            'date' => now()->subDays(1)->toDateString(),
            'start_time' => now()->subDays(1)->setTime(16, 0),
            'end_time' => now()->subDays(1)->setTime(17, 30),
            'duration_minutes' => 90,
            'total_kwh' => 25.0,
            'total_price' => 37500,
            'payment_status' => 'paid',
            'confirmation_code' => 'Le092jef20iM',
        ]);

        // ============================================
        // REVIEWS - Create sample reviews
        // ============================================
        
        Review::create([
            'transaction_id' => $transaction2->id,
            'user_id' => $driver->id,
            'station_id' => $voltExpress->id,
            'rating' => 5,
            'comment' => 'Layanan yang sangat baik dan pengecasan yang sangat ramah lingkungan. Tempatnya bersih dan nyaman!',
        ]);

        Review::create([
            'transaction_id' => $transaction3->id,
            'user_id' => $driver->id,
            'station_id' => $voltStation->id,
            'rating' => 4,
            'comment' => 'Proses charging cepat dan efisien. Lokasi strategis dan mudah diakses.',
        ]);

        // ============================================
        // CONTACT MESSAGES - Create sample support messages
        // ============================================
        
        ContactMessage::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Pertanyaan tentang tarif',
            'message' => 'Halo, saya ingin menanyakan tentang tarif charging di Volt Express. Apakah ada promo untuk pelanggan baru?',
            'is_read' => false,
        ]);

        ContactMessage::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'subject' => 'Kendala pembayaran',
            'message' => 'Saya mengalami kendala saat melakukan pembayaran. Bisa dibantu?',
            'is_read' => true,
        ]);

        ContactMessage::create([
            'name' => 'Ahmad Yusuf',
            'email' => 'ahmad@example.com',
            'subject' => 'Saran fitur aplikasi',
            'message' => 'Aplikasi sangat bagus! Saya sarankan untuk menambahkan fitur notifikasi ketika port sudah tersedia.',
            'is_read' => false,
        ]);
        
        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
