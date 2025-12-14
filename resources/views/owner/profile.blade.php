<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - ShareStation</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0]">
    <!-- Include Owner TopBar Component -->
    @include('components.owner.topbar')
    
    <!-- Main Content with padding for fixed topbar -->
    <div class="pt-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">My Profile</h1>
                <p class="text-gray-600">Manage your account information and view your charging stations</p>
            </div>

            <!-- Profile Information Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex items-center gap-6">
                    <!-- User Avatar -->
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $user->name }}</h2>
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-envelope mr-2"></i>{{ $user->email }}
                        </p>
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm font-medium">
                                <i class="fas fa-user-tie mr-1"></i>Station Owner
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>Member since {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Stations Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">My Charging Stations</h2>
                    <span class="text-sm text-gray-600">{{ count($ownedStations) }} Stations</span>
                </div>
            </div>

            <!-- Stations Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ownedStations as $station)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <!-- Station Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4 text-white">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold">{{ $station['name'] }}</h3>
                                <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">
                                    {{ ucfirst($station['status']) }}
                                </span>
                            </div>
                            <p class="text-sm text-blue-100">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $station['address'] }}
                            </p>
                        </div>

                        <!-- Station Stats -->
                        <div class="p-4">
                            <!-- Ports Status -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 mb-1">Available Ports</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $station['available_ports'] }}</p>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 mb-1">Total Ports</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $station['total_ports'] }}</p>
                                </div>
                            </div>

                            <!-- Revenue & Transactions -->
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>Total Revenue
                                    </span>
                                    <span class="font-semibold text-gray-800">{{ $station['total_revenue'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-charging-station mr-2 text-blue-500"></i>Transactions
                                    </span>
                                    <span class="font-semibold text-gray-800">{{ $station['total_transactions'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-star mr-2 text-yellow-500"></i>Rating
                                    </span>
                                    <span class="font-semibold text-gray-800">{{ number_format($station['rating'], 1) }} / 5.0</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('owner.dashboard') }}" class="flex-1 text-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-chart-line mr-1"></i>View Analytics
                                </a>
                                <button class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition-colors" title="Settings">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Copyright -->
            <div class="text-center py-6 text-gray-500 text-sm mt-8">
                Copyright © HIMALESS 2025
            </div>
        </div>
    </div>
</body>
</html>
