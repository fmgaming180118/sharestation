<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Station - Platform Peer-to-Peer EV Charging</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/icons/sistem/Logo.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#a8d5e2] min-h-screen">
    <!-- Topbar Component -->
    <x-landing.topbar />

    <!-- Hero Section -->
    <section class="pt-24 pb-16 px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="space-y-5">
                    <h1 class="text-5xl md:text-6xl font-bold text-white">
                        SHARE STATION
                    </h1>
                    
                    <p class="text-white text-base leading-relaxed">
                        Selamat datang di ShareStation, platform peer-to-peer emergency charging per-menit di Indonesia yang dirancang untuk mengatasi keterbatasan infrastruktur SPKLU. Kami memungkinkan pemilik EV untuk berbagi akses charging tanpa investasi besar di infrastruktur, dengan sistem berbasis daya kendaraan listrik dalam hitungan menit, sehingga pengguna EV dapat berkendara tanpa takut mogok, sekaligus menciptakan peluang penghasilan pasif bagi masyarakat.
                    </p>

                    <div class="flex gap-3 pt-2">
                        <button class="bg-[#4a7c9e] hover:bg-[#3d6680] text-white font-medium px-6 py-2.5 rounded-full shadow-md transition">
                            Informasi
                        </button>
                        <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-2.5 rounded-full shadow-md transition inline-flex items-center">
                            Masuk
                        </a>
                    </div>
                </div>

                <!-- Right Content - EV Charging Station Image -->
                <div class="flex justify-center items-center">
                    <img src="{{ asset('storage/icons/sistem/POM.png') }}" alt="EV Charging Station" class="w-full max-w-md h-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/" target="_blank" class="fixed bottom-6 right-6 z-50 bg-[#25D366] hover:bg-[#20ba5a] text-white rounded-full w-14 h-14 flex items-center justify-center shadow-xl transition transform hover:scale-110">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>
</html>
