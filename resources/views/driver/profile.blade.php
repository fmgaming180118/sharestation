<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Share Station</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            background: linear-gradient(135deg, #e8f4f8 0%, #d5e9f0 100%);
        }
        
        .profile-stat {
            transition: transform 0.2s;
        }
        
        .profile-stat:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Include TopBar Component -->
    @include('components.driver.topbar')
    
    <div class="pt-24 pb-12 px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">My Profile</h1>
                <p class="text-gray-600">View your account information and booking history</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Profile Summary Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-lg p-6 sticky top-24">
                        <!-- Avatar Section -->
                        <div class="flex flex-col items-center mb-6">
                            <div class="w-36 h-36 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-xl">
                                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&size=150&background=a78bfa&color=fff" 
                                     alt="{{ $user->name }}" 
                                     class="w-full h-full rounded-full">
                            </div>
                            
                            <!-- User Identity -->
                            <h2 class="text-2xl font-bold text-gray-800 mt-4 text-center">{{ $user->name }}</h2>
                            <span class="inline-block mt-2 px-4 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold capitalize">
                                {{ $user->role ?? 'Driver' }}
                            </span>
                            <p class="text-gray-500 text-sm mt-2">Member since {{ $user->created_at->format('Y') }}</p>
                        </div>

                        <hr class="my-6">

                        <!-- Stats Section -->
                        <div class="space-y-3 mb-6">
                            <div class="profile-stat bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-blue-600 font-medium">Total Bookings</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ $bookings->count() }}</p>
                                    </div>
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="profile-stat bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-green-600 font-medium">Active Reservations</p>
                                        <p class="text-2xl font-bold text-green-800">{{ $bookings->where('status', 'active')->count() }}</p>
                                    </div>
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <a href="{{ route('driver.profile.edit') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-purple-800 shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profile
                            </a>

                            <a href="{{ route('driver.profile.password') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Change Password
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: User Details & Booking History -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- User Details Section (Read-Only) -->
                    <div class="bg-white rounded-3xl shadow-lg p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
                                <p class="text-sm text-gray-500">Your account details</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800">
                                    {{ $user->name }}
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800">
                                    {{ $user->email }}
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800">
                                    {{ $user->phone ?? 'Not set' }}
                                </div>
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800">
                                    {{ $user->address ?? 'Not set' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking History Section -->
                    <div class="bg-white rounded-3xl shadow-lg p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Booking History</h2>
                                <p class="text-sm text-gray-500">Your past and current reservations</p>
                            </div>
                        </div>

                        @if($bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Station</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date/Time</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="px-4 py-4">
                                            <p class="font-medium text-gray-800">{{ $booking->station->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">Station #{{ $booking->station_id }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $booking->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'active' => 'bg-green-100 text-green-700 border-green-200',
                                                    'completed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                                ];
                                                $color = $statusColors[$booking->status->value] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border {{ $color }} capitalize">
                                                {{ $booking->status->value }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            <p class="font-bold text-gray-800">Rp {{ number_format($booking->amount, 0, ',', '.') }}</p>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No booking history yet</p>
                            <p class="text-sm text-gray-400 mt-1">Your reservations will appear here</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                Copyright © HIMALESS 2025
            </div>
        </div>
    </div>
</body>
</html>
