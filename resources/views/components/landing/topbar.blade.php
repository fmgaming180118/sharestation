<header class="fixed top-0 left-0 right-0 z-50 bg-[#a8d5e2] shadow-sm">
    <div class="max-w-7xl mx-auto px-8 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="/beranda" class="flex items-center">
                <img src="{{ asset('storage/icons/sistem/Logo.jpg') }}" alt="Share Station" class="h-12 rounded-md shadow-sm">
            </a>

            <!-- Navigation Menu -->
            <nav class="hidden md:flex items-center gap-10">
                <a href="/beranda" class="text-white font-medium hover:text-white/80 transition text-base">Beranda</a>
                <a href="/inovasi" class="text-white font-medium hover:text-white/80 transition text-base">Inovasi</a>
                <a href="/fitur-utama" class="text-white font-medium hover:text-white/80 transition text-base">Fitur Utama</a>
                <a href="/kontak" class="text-white font-medium hover:text-white/80 transition text-base">Kontak</a>
            </nav>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</header>
