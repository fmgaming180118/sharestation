<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Detail - ShareStation</title>
    
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
        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Page Container -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-8 text-white">
                    <h2 class="text-3xl font-bold mb-2">Detail Charging History</h2>
                    <p class="text-purple-100">Manage your charging station bookings</p>
                </div>

                <!-- Content Section -->
                <div class="bg-gradient-to-br from-gray-100 to-gray-200 p-8">
                    <!-- Customer Info -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">Customer: {{ $transaction['user'] }}</h3>
                        <p class="text-gray-600">
                            Code Charging: <span class="font-semibold">{{ $transaction['transaction_id'] }}</span>
                        </p>
                    </div>

                    <!-- Transaction Details Grid -->
                    <div class="grid grid-cols-5 gap-3 mb-6">
                        <!-- Date -->
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Date</p>
                            <p class="font-semibold text-gray-800">{{ $transaction['date'] }}</p>
                        </div>

                        <!-- Time -->
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Time</p>
                            <p class="font-semibold text-gray-800">{{ $transaction['time'] }}</p>
                        </div>

                        <!-- Duration -->
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Duration</p>
                            <p class="font-semibold text-gray-800">{{ $transaction['duration'] }}</p>
                        </div>

                        <!-- Total -->
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Total</p>
                            <p class="font-semibold text-gray-800">{{ $transaction['price'] }}</p>
                        </div>

                        <!-- kWh Rate -->
                        <div class="bg-white rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">kWh Rate</p>
                            <p class="font-semibold text-gray-800">{{ $transaction['rate'] }}</p>
                        </div>
                    </div>

                    <!-- Review Section -->
                    <div class="bg-white rounded-xl p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Review:</p>
                        <div class="flex items-start gap-3">
                            <!-- User Avatar -->
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                <i class="fas fa-user"></i>
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-gray-800">{{ $transaction['user'] }}</p>
                                    <div class="flex items-center gap-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $transaction['rating'])
                                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                            @else
                                                <i class="far fa-star text-gray-300 text-sm"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mb-2">{{ $transaction['review_time'] }}</p>
                                <p class="text-sm text-gray-700">{{ $transaction['review'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer with Back Button -->
                <div class="p-6 bg-white flex justify-end">
                    <a href="{{ route('owner.dashboard') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                        Back
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center py-6 text-gray-500 text-sm">
                Copyright © HIMALESS 2025
            </div>
        </div>
    </div>
</body>
</html>
