<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $station->name }} - Share Station</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0]">
    <!-- Include TopBar Component -->
    @include('components.driver.topbar')
    
    <div class="pt-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <!-- Back Button -->
            <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 mb-6 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="font-medium">Back to Dashboard</span>
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Station Info & Map -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Station Header -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $station->name }}</h1>
                                <p class="text-gray-600 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $station->address }}
                                </p>
                            </div>
                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">
                                {{ $station->ports_available }}/{{ $station->ports_total }} Ports Available
                            </span>
                        </div>

                        <!-- Distance Badge -->
                        <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $station->distance }} KM away
                        </div>
                    </div>

                    <!-- Station Details -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Station Information</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            @if($station->ports && $station->ports->count() > 0)
                                @php
                                    $maxPower = $station->ports->max('power_kw');
                                    $minPrice = $station->ports->min('price_per_kwh');
                                    $availablePorts = $station->ports->where('status', 'available')->count();
                                @endphp
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-sm text-gray-600 mb-1">Max Power Output</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $maxPower }} kW</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-sm text-gray-600 mb-1">Price from</p>
                                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-sm text-gray-600 mb-1">Available Ports</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $availablePorts }}/{{ $station->ports->count() }}</p>
                                </div>
                            @else
                                <div class="col-span-2 text-center text-gray-500">
                                    <p>No port information available</p>
                                </div>
                            @endif
                            
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-600 mb-1">Operational Hours</p>
                                <p class="text-lg font-bold text-gray-800">{{ $station->operational_hours ?? '24/7' }}</p>
                            </div>
                                <p class="text-sm text-gray-600 mb-1">Status</p>
                                <p class="text-2xl font-bold text-green-600">Active</p>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Location on Map</h2>
                        <div style="height: 400px; border-radius: 12px; overflow: hidden;">
                            <div id="stationMap" style="height: 100%; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Right: Action Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Quick Actions</h2>
                        
                        <!-- Book Now Button -->
                        <button class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-bold py-4 rounded-xl transition shadow-lg mb-4">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Book Now</span>
                            </div>
                        </button>

                        <!-- Navigate Button -->
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition mb-4">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <span>Navigate Here</span>
                            </div>
                        </button>

                        <!-- Share Button -->
                        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                <span>Share Location</span>
                            </div>
                        </button>

                        <!-- Estimated Time -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600">Estimated Arrival</span>
                                <span class="font-semibold text-gray-800">~5 minutes</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Distance</span>
                                <span class="font-semibold text-gray-800">{{ $station->distance }} KM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Initialize map centered on station
        const stationMap = L.map('stationMap').setView([{{ $station->latitude }}, {{ $station->longitude }}], 15);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(stationMap);
        
        // Add station marker
        const stationIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #22c55e; width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 2px 12px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;"><svg style="width: 20px; height: 20px; color: white;" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
        
        L.marker([{{ $station->latitude }}, {{ $station->longitude }}], {icon: stationIcon})
            .addTo(stationMap)
            .bindPopup('<b>{{ $station->name }}</b><br>{{ $station->address }}')
            .openPopup();
    </script>
</body>
</html>
