<?php

namespace Database\Seeders;

use App\Models\Port;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing warga (owner) or create one
        $owner = User::where('role', 'warga')->first();
        
        if (!$owner) {
            $this->command->error('❌ No user with role "warga" found. Please create one first.');
            return;
        }
        
        $this->command->info("✅ Using owner (warga): {$owner->name} (ID: {$owner->id})");
        
        // Delete existing stations if needed
        $existingCount = Station::count();
        if ($existingCount > 0) {
            $this->command->warn("⚠️  Found {$existingCount} existing stations.");
            // Uncomment below to delete all existing stations
            // Station::truncate();
            // Port::truncate();
            // $this->command->info("✅ Deleted all existing stations and ports.");
        }

        // 10 Stasiun Pengisian di sekitar Jakarta Pusat
        $stations = [
            [
                'name' => 'ShareStation Monas',
                'address' => 'Jl. Medan Merdeka, Jakarta Pusat',
                'latitude' => -6.175110,
                'longitude' => 106.826905,
            ],
            [
                'name' => 'ShareStation Sarinah',
                'address' => 'Jl. MH Thamrin No.11, Menteng, Jakarta Pusat',
                'latitude' => -6.188235,
                'longitude' => 106.823074,
            ],
            [
                'name' => 'ShareStation Bundaran HI',
                'address' => 'Bundaran HI, Jl. MH Thamrin, Jakarta Pusat',
                'latitude' => -6.195020,
                'longitude' => 106.823082,
            ],
            [
                'name' => 'ShareStation Grand Indonesia',
                'address' => 'Jl. MH Thamrin No.1, Jakarta Pusat',
                'latitude' => -6.195017,
                'longitude' => 106.822114,
            ],
            [
                'name' => 'ShareStation Menteng',
                'address' => 'Jl. HOS Cokroaminoto, Menteng, Jakarta Pusat',
                'latitude' => -6.190742,
                'longitude' => 106.832901,
            ],
            [
                'name' => 'ShareStation Cikini',
                'address' => 'Jl. Cikini Raya, Menteng, Jakarta Pusat',
                'latitude' => -6.185978,
                'longitude' => 106.838494,
            ],
            [
                'name' => 'ShareStation Senayan',
                'address' => 'Jl. Asia Afrika, Gelora, Tanah Abang, Jakarta Pusat',
                'latitude' => -6.218486,
                'longitude' => 106.802121,
            ],
            [
                'name' => 'ShareStation Tanah Abang',
                'address' => 'Jl. KH Mas Mansyur, Tanah Abang, Jakarta Pusat',
                'latitude' => -6.200000,
                'longitude' => 106.812500,
            ],
            [
                'name' => 'ShareStation Karet',
                'address' => 'Jl. MT Haryono, Karet Tengsin, Tanah Abang, Jakarta Pusat',
                'latitude' => -6.210000,
                'longitude' => 106.830000,
            ],
            [
                'name' => 'ShareStation Sudirman',
                'address' => 'Jl. Jend. Sudirman, Jakarta Pusat',
                'latitude' => -6.208763,
                'longitude' => 106.815254,
            ],
        ];

        foreach ($stations as $idx => $stationData) {
            $station = Station::create([
                'user_id' => $owner->id,
                'name' => $stationData['name'],
                'address' => $stationData['address'],
                'latitude' => $stationData['latitude'],
                'longitude' => $stationData['longitude'],
                'operational_hours' => '00:00-23:59',
                'is_open' => true,
                'is_active' => true,
                'amenities' => json_encode(['Toilet', 'WiFi', 'Coffee Shop']),
            ]);

            // Create 3 ports for each station
            $portTypes = ['Fast Charging', 'Fast Charging', 'Regular Charging'];
            $portPowers = [100 + ($idx * 10), 130 + ($idx * 10), 50];
            $portPrices = [2000 + ($idx * 100), 2300 + ($idx * 100), 1500];
            $portStatuses = ['available', 'available', 'busy'];

            for ($i = 0; $i < 3; $i++) {
                Port::create([
                    'station_id' => $station->id,
                    'name' => 'Port ' . chr(65 + $i), // Port A, B, C
                    'type' => $portTypes[$i],
                    'power_kw' => $portPowers[$i],
                    'price_per_kwh' => $portPrices[$i],
                    'status' => $portStatuses[$i],
                ]);
            }
        }

        $this->command->info('✅ 10 Stasiun pengisian di Jakarta Pusat berhasil ditambahkan!');
        $this->command->info('✅ Total 30 ports telah dibuat (3 ports per stasiun)');
    }
}
