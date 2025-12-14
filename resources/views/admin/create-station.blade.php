<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Station - Share Station Admin</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        #map {
            height: 400px;
            border-radius: 12px;
            position: relative;
            z-index: 1;
        }

        /* Ensure Leaflet controls stay within map's z-index */
        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom {
            z-index: auto !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0] min-h-screen">
    <!-- Include Admin Sidebar Component -->
    @include('components.admin.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Include Admin Header Component -->
        @include('components.admin.header')

        <!-- Page Content -->
        <main class="p-6 flex items-center justify-center min-h-[calc(100vh-80px)]">
            <div class="w-full max-w-6xl">
                <!-- Station Form Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Card Header (Purple Gradient) -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
                        <h1 class="text-3xl font-bold text-white">Add New Station</h1>
                        <p class="text-purple-100 mt-1">Create a new charging station location</p>
                    </div>

                    <!-- Card Body (Form) -->
                    <div class="p-8">
                        <form action="{{ route('admin.store-station') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- Error Alert -->
                            @if ($errors->any())
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-red-800">Ada kesalahan dalam form:</p>
                                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Map Section -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                                    Select Location on Map
                                </label>
                                <p class="text-sm text-gray-500 mb-3">Click on the map to select the station location</p>
                                
                                <!-- Map Container -->
                                <div id="map" class="border-2 border-gray-200"></div>
                                
                                <!-- Coordinates Display -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                                        <input type="text" id="latitude" name="latitude" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Click on map to select">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                                        <input type="text" id="longitude" name="longitude" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Click on map to select">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200">

                            <!-- Station Details -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                                Station Details
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Station Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Station Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., Volt Hub Tebet">
                                </div>

                                <!-- Owner/Host -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Owner <span class="text-red-500">*</span>
                                    </label>
                                    <select name="user_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">-- Select Owner --</option>
                                        @foreach($owners as $owner)
                                            <option value="{{ $owner->id }}" {{ old('user_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }} ({{ $owner->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Number of Ports -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Number of Ports <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="num_ports" required min="1" max="10" value="{{ old('num_ports') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., 3">
                                    <p class="mt-1 text-xs text-gray-500">Maximum 10 ports per station</p>
                                </div>

                                <!-- Power Output -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Power Output (kW) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="power_kw" required min="1" value="{{ old('power_kw') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., 150">
                                    <p class="mt-1 text-xs text-gray-500">≥ 100kW = Fast Charging, < 100kW = Regular</p>
                                </div>

                                <!-- Price per kWh -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Price per kWh (IDR) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="price_per_kwh" required min="0" step="0.01" value="{{ old('price_per_kwh') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., 1500">
                                    <p class="mt-1 text-xs text-gray-500">All ports will have the same price</p>
                                </div>

                                <!-- Status -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-toggle-on text-green-600 mr-2"></i>Active Station
                                        </span>
                                    </label>
                                    <p class="ml-8 mt-1 text-xs text-gray-500">Station will be immediately available after creation</p>
                                </div>
                            </div>

                            <!-- Address (Full Width) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Enter complete station address"></textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6">
                                <a href="{{ route('admin.add-station') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition-all transform hover:scale-105">
                                    <i class="fas fa-save mr-2"></i>Save Station
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Copyright Footer -->
                <div class="text-center py-6 text-gray-500 text-sm">
                    Copyright © HIMALESS 2025
                </div>
            </div>
        </main>
    </div>

    <!-- Leaflet Map Script -->
    <script>
        // Initialize map centered on Jakarta, Indonesia
        const map = L.map('map').setView([-6.2088, 106.8456], 11);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Variable to store the marker
        let marker = null;

        // Add click event to map
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            // Update input fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Remove previous marker if exists
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker at clicked location
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            // Add popup to marker
            marker.bindPopup(`<b>Selected Location</b><br>Lat: ${lat}<br>Lng: ${lng}`).openPopup();

            // Update coordinates when marker is dragged
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                const newLat = position.lat.toFixed(6);
                const newLng = position.lng.toFixed(6);
                
                document.getElementById('latitude').value = newLat;
                document.getElementById('longitude').value = newLng;
                
                marker.getPopup().setContent(`<b>Selected Location</b><br>Lat: ${newLat}<br>Lng: ${newLng}`).update();
            });
        });

        // Add search control (optional - if you want to add search location feature)
        // You can integrate with a geocoding service here
    </script>
</body>
</html>
