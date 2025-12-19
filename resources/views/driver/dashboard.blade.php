<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - Share Station</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #map {
            height: 100%;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
        
        /* Tooltip styling */
        .leaflet-tooltip {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        .leaflet-tooltip-top:before {
            border-top-color: rgba(0, 0, 0, 0.8);
        }
        
        /* Pulse animation for current location */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }
            50% {
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
        
        /* Current location marker with pulsing ring */
        .current-location-marker {
            position: relative;
            width: 20px;
            height: 20px;
            background-color: #3b82f6;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3), 0 2px 8px rgba(0, 0, 0, 0.3);
            animation: pulse 2s infinite;
        }
        
        .station-card {
            transition: all 0.3s ease;
        }
        
        .station-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0]">
    <!-- Include TopBar Component -->
    @include('components.driver.topbar')
    
    <div class="pt-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <!-- Main Layout: Map + Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 lg:items-start">
                <!-- Left: Map Section -->
                <div class="lg:col-span-2 flex">
                    <div class="bg-white rounded-2xl shadow-lg p-6 w-full">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Find your nearest charging station</h2>
                                <p class="text-sm text-gray-500">Nearby Stations Map</p>
                            </div>
                            <a href="{{ route('driver.reservations') }}" class="bg-purple-100 px-4 py-2 rounded-lg flex items-center gap-2 cursor-pointer hover:bg-purple-200 transition">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-semibold text-purple-700">My Reservation</span>
                                @if($pendingReservations > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $pendingReservations }}</span>
                                @endif
                            </a>
                        </div>
                        
                        <!-- Current Location Display -->
                        <div class="mb-4 flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">Current Location:</span>
                            <span id="currentLocationText" class="text-gray-500">Detecting location...</span>
                        </div>
                        
                        <!-- Map Container -->
                        <div style="height: 450px; overflow: hidden; border-radius: 12px;">
                            <div id="map" class="shadow-inner"></div>
                        </div>
                        
                        <!-- Legend -->
                        <div class="mt-4 flex items-center gap-6 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                                <span class="text-gray-600">Available</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 bg-yellow-500 rounded-full"></span>
                                <span class="text-gray-600">Busy</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 bg-red-500 rounded-full"></span>
                                <span class="text-gray-600">Full</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Nearby Stations List -->
                <div class="lg:col-span-1 flex">
                    <div class="bg-white rounded-2xl shadow-lg p-6 w-full flex flex-col">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Nearby Stations</h3>
                        
                        <!-- Station Cards -->
                        <div class="space-y-4 flex-1 overflow-y-auto" style="max-height: 500px;">
                            @forelse($stations as $station)
                            <a href="{{ route('driver.station.show', $station['id']) }}" class="block">
                            <div class="station-card bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-4 shadow-sm cursor-pointer hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800">{{ $station['name'] }}</h4>
                                        @if(isset($station['distance']))
                                        <span class="text-xs text-blue-600 font-medium">{{ number_format($station['distance'], 1) }} km</span>
                                        @endif
                                    </div>
                                    <span class="w-3 h-3 bg-{{ $station['status_color'] }}-500 rounded-full"></span>
                                </div>
                                <p class="text-xs text-gray-500 mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $station['address'] }}
                                </p>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs text-gray-600 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <svg class="w-5 h-5 star-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ $station['average_rating'] }}
                                    </span>
                                    @if($station['available_ports'] > 0)
                                        <span class="text-xs bg-{{ $station['status_color'] }}-100 text-{{ $station['status_color'] }}-700 px-2 py-1 rounded-full font-medium">Port {{ $station['available_ports'] }}/{{ $station['total_ports'] }} Available</span>
                                    @else
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full font-medium">Full</span>
                                    @endif
                                </div>
                                <div class="w-full {{ $station['available_ports'] > 0 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400' }} text-white text-sm font-medium py-2 rounded-lg transition text-center">
                                    View Details
                                </div>
                            </div>
                            </a>
                            @empty
                            <div class="text-center text-gray-500 py-8">
                                <p>No charging stations available nearby.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg p-6 border border-green-200">
                    <h3 class="text-sm font-medium text-green-700 mb-2">Nearest Charging Station</h3>
                    <p class="text-3xl font-bold text-green-800">{{ $nearestDistance }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg p-6 border border-blue-200">
                    <h3 class="text-sm font-medium text-blue-700 mb-2">Total Charging Stations</h3>
                    <p class="text-3xl font-bold text-blue-800">{{ $totalStations }} Found</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-lg p-6 border border-purple-200">
                    <h3 class="text-sm font-medium text-purple-700 mb-2">Available Ports</h3>
                    <p class="text-3xl font-bold text-purple-800">{{ $totalAvailablePorts }} Ports</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                Copyright © HIMALESS 2025
            </div>
        </div>
    </div>
    
    <!-- Reservation Modal -->
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <!-- Step 1: Station Selection -->
            <div id="step1" class="step-content">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Pilih Stasiun Pengisian</h2>
                    <button onclick="closeReservationModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-3 mb-6">
                    @forelse($stations->take(5) as $station)
                    <label class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 cursor-pointer transition">
                        <input type="radio" name="station" value="{{ $station['name'] }}" class="w-5 h-5 text-blue-600">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $station['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $station['available_ports'] }}/{{ $station['total_ports'] }} Ports Available</p>
                        </div>
                    </label>
                    @empty
                    <p class="text-center text-gray-500 py-4">No stations available</p>
                    @endforelse
                </div>
                
                <button onclick="goToPayment()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                    Lanjut ke Pembayaran
                </button>
            </div>
            
            <!-- Step 2: Payment QR -->
            <div id="step2" class="step-content hidden">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Pembayaran Booking</h2>
                    <button onclick="closeReservationModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <strong>Mode Demo:</strong> Ini adalah tampilan demo. Scan QR code di bawah dan klik "Sudah Bayar" untuk melanjutkan.
                    </p>
                </div>
                
                <div class="text-center mb-6">
                    <div class="inline-block bg-white p-6 rounded-2xl shadow-lg">
                        <img src="{{ asset('storage/qr_payment_demo.png') }}" alt="QR Payment Demo" class="w-64 h-64 mx-auto">
                    </div>
                    <p class="mt-4 text-lg font-semibold text-gray-800">Total: Rp 50.000</p>
                    <p class="text-sm text-gray-500">Scan QR code untuk melakukan pembayaran</p>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="backToSelection()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition">
                        Kembali
                    </button>
                    <button onclick="completeBooking()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                        Sudah Bayar
                    </button>
                </div>
            </div>
            
            <!-- Step 3: Confirmation -->
            <div id="step3" class="step-content hidden">
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Reservasi Berhasil!</h2>
                    <p class="text-gray-600 mb-6">Booking stasiun pengisian Anda telah dikonfirmasi</p>
                    
                    <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
                        <h3 class="font-semibold text-gray-800 mb-4">Detail Reservasi:</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Stasiun:</span>
                                <span class="font-medium text-gray-800" id="confirmedStation">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Port:</span>
                                <span class="font-medium text-gray-800">Port #A1</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waktu:</span>
                                <span class="font-medium text-gray-800" id="confirmedTime">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Bayar:</span>
                                <span class="font-medium text-green-600">Rp 50.000</span>
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="closeReservationModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Global variables for map and user location
        let map;
        let userLocation = {lat: -6.186486, lng: 106.834091}; // Default: Jakarta Pusat
        let userMarker = null;
        
        // Initialize map with default center
        map = L.map('map').setView([userLocation.lat, userLocation.lng], 13);
        
        // Add tile layer with better styling
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Fix map display issues
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        
        // Custom marker icons
        const greenIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #22c55e; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        const yellowIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #eab308; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        const redIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #ef4444; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        const currentLocationIcon = L.divIcon({
            className: 'custom-marker',
            html: `<div style="position: relative;">
                        <div class="current-location-marker"></div>
                    </div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        // Function to update user location marker
        function updateUserLocation(lat, lng) {
            userLocation = {lat, lng};
            
            // Remove old marker if exists
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            
            // Add new marker
            userMarker = L.marker([lat, lng], {icon: currentLocationIcon})
                .addTo(map)
                .bindPopup('<b>Your Location</b><br>Current Position');
            
            // Center map on user location
            map.setView([lat, lng], 13);
            
            // Update location text with reverse geocoding
            updateLocationText(lat, lng);
        }
        
        // Function to update location text (reverse geocoding)
        function updateLocationText(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    const locationText = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    document.getElementById('currentLocationText').textContent = locationText;
                })
                .catch(() => {
                    document.getElementById('currentLocationText').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                });
        }
        
        // Get user's current location using Geolocation API
        const urlParams = new URLSearchParams(window.location.search);
        const hasLocationParams = urlParams.has('lat') && urlParams.has('lon');
        
        if (navigator.geolocation && !hasLocationParams) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Reload page with location parameters to sort stations by distance
                    const url = new URL(window.location.href);
                    url.searchParams.set('lat', lat);
                    url.searchParams.set('lon', lng);
                    window.location.href = url.toString();
                },
                (error) => {
                    console.log('Geolocation error:', error);
                    // Use default location if geolocation fails
                    updateUserLocation(userLocation.lat, userLocation.lng);
                    document.getElementById('currentLocationText').textContent = 'Jakarta Pusat (Default)';
                }
            );
        } else if (hasLocationParams) {
            // Use location from URL parameters
            const lat = parseFloat(urlParams.get('lat'));
            const lng = parseFloat(urlParams.get('lon'));
            updateUserLocation(lat, lng);
        } else {
            // Geolocation not supported
            updateUserLocation(userLocation.lat, userLocation.lng);
            document.getElementById('currentLocationText').textContent = 'Jakarta Pusat (Default)';
        }
        
        // Stations from database
        const stations = [
            @foreach($stations as $station)
            {
                id: {{ $station["id"] }},
                name: '{{ addslashes($station["name"]) }}', 
                lat: {{ $station["latitude"] ?? -6.186486 }}, 
                lng: {{ $station["longitude"] ?? 106.834091 }}, 
                address: '{{ addslashes($station["address"]) }}',
                availablePorts: {{ $station["available_ports"] }},
                totalPorts: {{ $station["total_ports"] }},
                icon: {{ $station["available_ports"] == 0 ? 'redIcon' : ($station["available_ports"] <= $station["total_ports"] / 3 ? 'yellowIcon' : 'greenIcon') }}
            }@if(!$loop->last),@endif
            @endforeach
        ];
        
        // Add station markers with tooltips
        stations.forEach(station => {
            const marker = L.marker([station.lat, station.lng], {icon: station.icon})
                .addTo(map);
            
            // Add tooltip that shows on hover
            marker.bindTooltip(`
                <div style="text-align: center; font-weight: bold;">
                    ${station.name}
                </div>
            `, {
                permanent: false,
                direction: 'top',
                offset: [0, -15]
            });
            
            // Also bind popup for click with link to detail
            marker.bindPopup(`
                <div class="text-center">
                    <b>${station.name}</b><br>
                    <small class="text-gray-600">${station.address}</small><br>
                    Ports: <b>${station.availablePorts}/${station.totalPorts}</b> Available<br>
                    <a href="/driver/station/${station.id}" class="text-blue-600 hover:underline">View Details</a>
                </div>
            `);
        });
        
        // Modal functions
        let selectedStation = '';
        
        function openReservationModal() {
            document.getElementById('reservationModal').classList.add('active');
            showStep(1);
        }
        
        function closeReservationModal() {
            document.getElementById('reservationModal').classList.remove('active');
            resetModal();
        }
        
        function showStep(stepNumber) {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step' + stepNumber).classList.remove('hidden');
        }
        
        function goToPayment() {
            const selected = document.querySelector('input[name="station"]:checked');
            if (!selected) {
                alert('Pilih stasiun terlebih dahulu!');
                return;
            }
            selectedStation = selected.value;
            showStep(2);
        }
        
        function backToSelection() {
            showStep(1);
        }
        
        function completeBooking() {
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', { 
                day: '2-digit', 
                month: 'long', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            document.getElementById('confirmedStation').textContent = selectedStation;
            document.getElementById('confirmedTime').textContent = timeString;
            showStep(3);
        }
        
        function resetModal() {
            document.querySelectorAll('input[name="station"]').forEach(input => input.checked = false);
            selectedStation = '';
        }
        
        // Close modal when clicking outside
        document.getElementById('reservationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReservationModal();
            }
        });
    </script>
</body>
</html>
