<!-- Admin Sidebar Component -->
<aside id="adminSidebar" class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-indigo-900 to-slate-800 text-white shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 custom-scrollbar overflow-y-auto">
    <!-- Brand -->
    <div class="p-6 border-b border-indigo-700">
        <h1 class="text-2xl font-bold text-white">Share Station</h1>
        <p class="text-indigo-200 text-sm mt-1">Admin Panel</p>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }} rounded-lg transition-colors">
            <i class="fas fa-home w-5"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>

        <a href="{{ route('admin.add-station') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.add-station') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }} rounded-lg transition-colors group">
            <i class="fas fa-charging-station w-5"></i>
            <span class="ml-3 font-medium">Add Station</span>
        </a>

        <a href="{{ route('admin.user-management') }}" class="flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.user-management') || request()->routeIs('admin.create-user') || request()->routeIs('admin.edit-user') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }} rounded-lg transition-colors">
            <i class="fas fa-users w-5"></i>
            <span class="ml-3 font-medium">User Management</span>
        </a>
    </nav>

    <!-- Logout Button -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-indigo-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 hover:bg-red-600 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="ml-3 font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Overlay for mobile -->
<div id="adminOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

<!-- Mobile Menu Button -->
<button id="adminMobileMenuBtn" class="lg:hidden fixed top-4 left-4 z-50 bg-indigo-900 text-white p-3 rounded-lg shadow-lg">
    <i class="fas fa-bars text-xl"></i>
</button>

<style>
    /* Custom scrollbar for sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #1e293b;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 3px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const mobileMenuBtn = document.getElementById('adminMobileMenuBtn');

        if (mobileMenuBtn && sidebar && overlay) {
            // Toggle mobile menu
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            // Close menu when clicking overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });

            // Close menu on window resize if screen is large
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            });
        }
    });
</script>
