<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Share Station</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0] min-h-screen">
    <!-- Include Admin Sidebar Component -->
    @include('components.admin.sidebar')

    <!-- Main Content Wrapper -->
    <div class="lg:ml-64">
        <!-- Include Admin Header Component -->
        @include('components.admin.header')

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Users</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                            <p class="text-gray-400 text-xs mt-2">All registered users</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Stations Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Active Stations</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $activeStations }}</p>
                            <p class="text-gray-400 text-xs mt-2">Across Indonesia</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <i class="fas fa-charging-station text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users and Stations Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Registered Users -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h2 class="text-xl font-bold text-gray-800">Recent Registered Users</h2>
                        <p class="text-sm text-gray-600 mt-1">Latest users who joined the platform</p>
                    </div>

                    <!-- Search Bar for Users -->
                    <div class="px-6 pt-4">
                        <div class="relative">
                            <input type="text" id="userSearch" placeholder="Search users..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Scrollable User List -->
                    <div class="p-6 max-h-96 overflow-y-auto" id="userList">
                        @if($recentUsers->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-users text-4xl mb-2"></i>
                                <p>No users found</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($recentUsers as $user)
                                <div class="user-item flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" data-name="{{ strtolower($user['name']) }}" data-email="{{ strtolower($user['email']) }}">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ $user['initials'] }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $user['name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 bg-{{ $user['role'] === 'driver' ? 'blue' : 'purple' }}-100 text-{{ $user['role'] === 'driver' ? 'blue' : 'purple' }}-700 text-xs font-semibold rounded-full">
                                            {{ ucfirst($user['role']) }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $user['created_at'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Station List -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h2 class="text-xl font-bold text-gray-800">Active Stations</h2>
                        <p class="text-sm text-gray-600 mt-1">List of all charging stations</p>
                    </div>

                    <!-- Search Bar for Stations -->
                    <div class="px-6 pt-4">
                        <div class="relative">
                            <input type="text" id="stationSearch" placeholder="Search stations..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Scrollable Station List -->
                    <div class="p-6 max-h-96 overflow-y-auto" id="stationList">
                        @if($stations->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-charging-station text-4xl mb-2"></i>
                                <p>No stations found</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($stations as $station)
                                <div class="station-item p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" data-name="{{ strtolower($station['name']) }}" data-address="{{ strtolower($station['address']) }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                                                {{ $station['initials'] }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">{{ $station['name'] }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                            Active
                                        </span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-600 mt-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                        {{ $station['address'] }} · {{ $station['ports_count'] }} Ports
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Search Functionality Script -->
    <script>
        // User Search
        document.getElementById('userSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = document.querySelectorAll('.user-item');
            
            userItems.forEach(item => {
                const name = item.getAttribute('data-name');
                const email = item.getAttribute('data-email');
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Station Search
        document.getElementById('stationSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const stationItems = document.querySelectorAll('.station-item');
            
            stationItems.forEach(item => {
                const name = item.getAttribute('data-name');
                const address = item.getAttribute('data-address');
                
                if (name.includes(searchTerm) || address.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
