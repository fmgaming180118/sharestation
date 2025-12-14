<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Share Station</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Ticket card animation */
        .ticket-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Glass effect for button */
        .glass-button {
            background: rgba(147, 197, 253, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .glass-button:hover {
            background: rgba(147, 197, 253, 0.5);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        /* Star rating color */
        .star-icon {
            color: #fbbf24;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0] min-h-screen">
    <!-- Include TopBar Component -->
    @include('components.driver.topbar')
    
    <div class="pt-20 min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-4xl">
            @if($reservation)
            <!-- Main Reservation Ticket Card -->
            <div class="ticket-card bg-white rounded-3xl shadow-2xl overflow-hidden">
                
                <!-- Card Header (Purple Gradient) -->
                <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-purple-600 px-8 py-8 text-white">
                    <h1 class="text-3xl font-bold mb-2">My Reservations</h1>
                    <p class="text-purple-100 text-sm">Manage your charging station bookings</p>
                </div>
                
                <!-- Card Body (Dark Blueish-Purple) -->
                <div class="bg-gradient-to-br from-slate-700 via-slate-600 to-indigo-700 px-8 py-8">
                    <!-- Top Row: Station Info + View Invoice Button -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-6">
                        <!-- Left: Station Info -->
                        <div class="text-white">
                            <h2 class="text-2xl font-bold mb-2">{{ $reservation->station->name }}</h2>
                            <p class="text-slate-300 text-sm mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $reservation->station->address }}
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <svg class="w-5 h-5 star-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-white font-semibold">{{ round($reservation->station->averageRating(), 1) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right: View Invoice Button -->
                        <a href="{{ route('driver.invoice') }}" class="glass-button px-6 py-3 rounded-lg text-white font-semibold transition duration-300 hover:scale-105 flex-shrink-0 text-center">
                            View Invoice
                        </a>
                    </div>
                    
                    <!-- Info Grid (4 Boxes) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Date Box -->
                        <div class="bg-white rounded-xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-medium mb-1">Date</p>
                            <p class="text-gray-800 text-lg font-semibold">{{ $reservation->date->format('d/m/Y') }}</p>
                        </div>
                        
                        <!-- Time Box -->
                        <div class="bg-white rounded-xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-medium mb-1">Time</p>
                            <p class="text-gray-800 text-lg font-semibold">{{ $reservation->start_time->format('H:i') }}</p>
                        </div>
                        
                        <!-- Duration Box -->
                        <div class="bg-white rounded-xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-medium mb-1">Duration</p>
                            <p class="text-gray-800 text-lg font-semibold">{{ $reservation->duration_minutes ? $reservation->duration_minutes . ' mnt' : '-' }}</p>
                        </div>
                        
                        <!-- Total Box -->
                        <div class="bg-white rounded-xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-medium mb-1">Total</p>
                            <p class="text-gray-800 text-lg font-semibold">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <!-- Confirmation Code -->
                    <div class="border-t border-slate-500 pt-4">
                        <p class="text-white text-center text-sm">
                            Confirmation Code: <span class="font-semibold">{{ $reservation->confirmation_code }}</span>
                        </p>
                    </div>
                </div>
                
                <!-- Card Footer (White) -->
                <div class="bg-white px-8 py-6 rounded-b-3xl">
                    <div class="flex justify-center lg:justify-end">
                        <button class="bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white font-bold px-8 py-3 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Navigate here
                        </button>
                    </div>
                </div>
                
            </div>
            @else
            <!-- No Reservations -->  
            <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">No Active Reservations</h2>
                <p class="text-gray-600 mb-6">You don't have any active reservations at the moment.</p>
                <a href="{{ route('driver.dashboard') }}" class="inline-block bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white font-bold px-8 py-3 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg">
                    Find aNearby Station
                </a>
            </div>
            @endif
            
            <!-- Back to Dashboard Link -->
            <div class="text-center mt-8">
                <a href="{{ route('driver.dashboard') }}" class="text-slate-600 hover:text-slate-800 font-medium inline-flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="text-center pb-6 text-gray-500 text-sm">
        Copyright © HIMALESS 2025
    </div>
</body>
</html>
