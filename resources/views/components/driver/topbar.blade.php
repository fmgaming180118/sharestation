<!-- Driver TopBar Component -->
<header class="bg-gradient-to-r from-[#a8d5e2] to-[#91c8da] shadow-md fixed top-0 left-0 right-0 z-[9999]">
    <div class="max-w-7xl mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <!-- Left: Logo & Title -->
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    <img src="{{ asset('storage/icons/sistem/Logo.jpg') }}" alt="Share Station Logo" class="w-8 h-8 object-contain">
                </div>
                <h1 class="text-white text-xl font-bold">Share Station Dashboard</h1>
            </div>

            <!-- Right: Menu, Navigation & Profile -->
            <div class="flex items-center gap-6">
                <!-- Menu Button - Hidden for driver role -->
                @if(auth()->user()->role !== 'driver')
                <button class="flex items-center gap-2 text-white hover:text-white/80 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="font-medium">Menu</span>
                </button>
                @endif

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('driver.dashboard') }}" class="flex items-center gap-2 text-white hover:text-white/80 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="font-medium">Home</span>
                    </a>

                    <!-- Settings - Hidden for driver role -->
                    @if(auth()->user()->role !== 'driver')
                    <a href="#" class="flex items-center gap-2 text-white hover:text-white/80 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium">Settings</span>
                    </a>
                    @endif
                </nav>

                <!-- User Profile Dropdown -->
                <div class="relative" id="profileDropdown">
                    <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 hover:opacity-90 transition">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-pink-500 flex items-center justify-center shadow-lg">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=f97316&color=fff" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full">
                        </div>
                        <div class="hidden lg:block">
                            <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-white/80 text-xs capitalize">{{ auth()->user()->role ?? 'Driver' }}</p>
                        </div>
                        <svg class="w-5 h-5 text-white transition-transform duration-200" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-2xl overflow-hidden z-[10000] border border-gray-100">
                        <div class="py-2">
                            <!-- Profile Option -->
                            <a href="{{ route('driver.profile') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 transition group">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">My Profile</p>
                                    <p class="text-xs text-gray-500">Edit your information</p>
                                </div>
                            </a>
                            
                            <hr class="my-2">
                            
                            <!-- Logout Option -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition group">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-800 text-sm">Logout</p>
                                        <p class="text-xs text-gray-500">Sign out of your account</p>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        const arrow = document.getElementById('dropdownArrow');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const arrow = document.getElementById('dropdownArrow');
        
        if (dropdown && !dropdown.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    });
</script>
