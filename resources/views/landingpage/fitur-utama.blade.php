<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Utama - ShareStation</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/icons/sistem/Logo.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#a8d5e2] min-h-screen">
    <!-- Topbar Component -->
    <x-landing.topbar />

    <!-- Fitur Utama Section -->
    <section class="pt-24 pb-16 px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <p class="text-white text-sm font-medium mb-2">Fitur Utama</p>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">ShareStation</h2>
                <p class="text-gray-700 text-base max-w-2xl mx-auto">
                    Solusi peer-to-peer charging kendaraan listrik untuk Indonesia
                </p>
            </div>

            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Peta Realtime -->
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 flex items-center justify-center">
                            <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="10" y="20" width="35" height="50" rx="4" fill="#4CAF50" stroke="#2E7D32" stroke-width="2"/>
                                <rect x="55" y="30" width="35" height="50" rx="4" fill="#81C784" stroke="#4CAF50" stroke-width="2"/>
                                <path d="M27 30 L27 60 M20 40 L35 40" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                <circle cx="72" cy="50" r="8" fill="#FFC107"/>
                                <path d="M15 75 Q50 70 85 75" stroke="#2196F3" stroke-width="3" fill="none"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 text-center mb-3">Peta Realtime</h3>
                    <p class="text-gray-600 text-sm text-center leading-relaxed">
                        Temukan lokasi stasiun charging terdekat dengan tampilan real-time di maps yang mudah digunakan
                    </p>
                </div>

                <!-- Card 2: Booking 1-Klik -->
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 flex items-center justify-center">
                            <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="25" y="15" width="50" height="70" rx="8" fill="white" stroke="#333" stroke-width="3"/>
                                <rect x="30" y="22" width="40" height="6" rx="2" fill="#E8F5E9"/>
                                <rect x="35" y="35" width="30" height="25" rx="4" fill="#4CAF50"/>
                                <circle cx="50" cy="47.5" r="6" fill="#81C784"/>
                                <text x="50" y="52" text-anchor="middle" fill="white" font-size="8" font-weight="bold">QR</text>
                                <rect x="35" y="65" width="30" height="8" rx="4" fill="#FFC107"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 text-center mb-3">Booking 1-Klik</h3>
                    <p class="text-gray-600 text-sm text-center leading-relaxed">
                        Pesan slot charging dengan mudah, hanya 1-klik. Tanpa ribet, langsung terjadwal!
                    </p>
                </div>

                <!-- Card 3: Smart Pricing & Penghasilan -->
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 flex items-center justify-center">
                            <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="40" width="12" height="45" rx="2" fill="#2196F3"/>
                                <rect x="32" y="30" width="12" height="55" rx="2" fill="#42A5F5"/>
                                <rect x="49" y="20" width="12" height="65" rx="2" fill="#64B5F6"/>
                                <rect x="66" y="25" width="12" height="60" rx="2" fill="#90CAF9"/>
                                <path d="M10 85 L90 85" stroke="#333" stroke-width="2"/>
                                <circle cx="85" cy="20" r="12" fill="#FFC107" stroke="#FF6F00" stroke-width="2"/>
                                <text x="85" y="24" text-anchor="middle" fill="white" font-size="14" font-weight="bold">Rp</text>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 text-center mb-3">Smart Pricing & Penghasilan</h3>
                    <p class="text-gray-600 text-sm text-center leading-relaxed">
                        Pantau biaya secara transparan per-kWh. Dapatkan penghasilan pasif dengan sharing charger Anda!
                    </p>
                </div>

                <!-- Card 4: Aman & Terpercaya -->
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 flex items-center justify-center">
                            <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 10 L80 25 L80 50 C80 70 65 85 50 90 C35 85 20 70 20 50 L20 25 Z" fill="#8B4513" stroke="#5D4037" stroke-width="2"/>
                                <circle cx="35" cy="40" r="8" fill="white" stroke="#333" stroke-width="2"/>
                                <circle cx="65" cy="40" r="8" fill="white" stroke="#333" stroke-width="2"/>
                                <circle cx="35" cy="40" r="3" fill="#333"/>
                                <circle cx="65" cy="40" r="3" fill="#333"/>
                                <path d="M35 55 Q50 65 65 55" stroke="#333" stroke-width="3" fill="none" stroke-linecap="round"/>
                                <path d="M30 30 L40 35 M60 35 L70 30" stroke="#333" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 text-center mb-3">Aman & Terpercaya</h3>
                    <p class="text-gray-600 text-sm text-center leading-relaxed">
                        Sistem keamanan berlapis, data terverifikasi dan terenkripsi untuk kenyamanan Anda
                    </p>
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
