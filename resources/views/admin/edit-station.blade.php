<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Station - Share Station Admin</title>
    
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
        }

        /* Style for disabled input to make it more obvious */
        input:disabled, input[readonly] {
            cursor: not-allowed;
            opacity: 0.6;
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
                        <h1 class="text-3xl font-bold text-white">Edit Station</h1>
                        <p class="text-purple-100 mt-1">Update charging station information</p>
                    </div>

                    <!-- Card Body (Form) -->
                    <div class="p-8">
                        <form action="#" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <!-- Info Alert -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Station Code and Location Cannot Be Changed</p>
                                        <p class="text-sm text-blue-700 mt-1">The station point code and GPS coordinates are locked for data integrity. All other fields can be updated.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Section (VIEW ONLY) -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                                    Current Station Location
                                    <span class="text-orange-600 text-xs ml-2">
                                        <i class="fas fa-lock"></i> Locked
                                    </span>
                                </label>
                                <p class="text-sm text-gray-500 mb-3">Location cannot be changed for existing stations</p>
                                
                                <!-- Map Container (Non-interactive) -->
                                <div id="map" class="border-2 border-gray-300 opacity-75 pointer-events-none"></div>
                                
                                <!-- Coordinates Display (LOCKED) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Latitude
                                            <span class="text-orange-600 text-xs ml-2">
                                                <i class="fas fa-lock"></i> Locked
                                            </span>
                                        </label>
                                        <input type="text" id="latitude" name="latitude" value="-6.2088" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" title="Location cannot be changed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Longitude
                                            <span class="text-orange-600 text-xs ml-2">
                                                <i class="fas fa-lock"></i> Locked
                                            </span>
                                        </label>
                                        <input type="text" id="longitude" name="longitude" value="106.8456" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" title="Location cannot be changed">
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
                                    <input type="text" name="name" required value="1. XYZ" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Station Point (DISABLED - CANNOT BE EDITED) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Station Point Code
                                        <span class="text-orange-600 text-xs ml-2">
                                            <i class="fas fa-lock"></i> Locked
                                        </span>
                                    </label>
                                    <input type="text" name="station_point" value="TLS FBT" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-200 cursor-not-allowed" title="Station code cannot be changed">
                                </div>

                                <!-- Operational Hours Start -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Opening Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="opening_time" required value="09:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Operational Hours End -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Closing Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="closing_time" required value="23:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Price per kWh -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Price per kWh (IDR) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="price_kwh" required value="1000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Power Output -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Power Output (kW) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="power_output" required value="130" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Number of Ports -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Number of Ports <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="ports" required min="1" value="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Contact Phone
                                    </label>
                                    <input type="tel" name="phone" value="123" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Address (Full Width) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">Jl. Example Street No. 123, Jakarta</textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6">
                                <a href="{{ route('admin.add-station') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition-all transform hover:scale-105">
                                    <i class="fas fa-save mr-2"></i>Update Station
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

    <!-- Leaflet Map Script (VIEW ONLY - No Interaction) -->
    <script>
        // Initialize map with existing station location (VIEW ONLY)
        const map = L.map('map', {
            dragging: false,        // Disable map dragging
            touchZoom: false,       // Disable touch zoom
            scrollWheelZoom: false, // Disable scroll wheel zoom
            doubleClickZoom: false, // Disable double click zoom
            boxZoom: false,         // Disable box zoom
            keyboard: false,        // Disable keyboard navigation
            zoomControl: false      // Hide zoom controls
        }).setView([-6.2088, 106.8456], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Add fixed marker at the station location (NOT DRAGGABLE)
        const marker = L.marker([-6.2088, 106.8456], {
            draggable: false  // Marker cannot be dragged
        }).addTo(map);

        marker.bindPopup('<b>Station Location (Locked)</b><br>Location cannot be changed').openPopup();

        // No click events - map is completely view-only
    </script>
</body>
</html>
