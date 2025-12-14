<!-- Admin Header Component -->
<header class="bg-white shadow-sm border-b sticky top-0 z-20">
    <div class="px-6 py-4 flex justify-between items-center">
        <!-- Search Bar -->
        <div class="flex-1 max-w-xl ml-0 lg:ml-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Search users, stations, reports..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>

        <!-- Admin Profile -->
        <div class="flex items-center space-x-4 ml-4">
            <div class="text-right hidden md:block">
                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">Super Admin</p>
            </div>
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>
