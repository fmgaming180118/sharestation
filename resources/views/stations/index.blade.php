<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stasiun Pengisian - Share Station</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Stasiun Pengisian Tersedia</h1>
                    <p class="text-sm text-gray-500">Temukan stasiun pengisian terdekat</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/my-bookings" class="text-blue-600 hover:text-blue-700 font-medium">
                        Booking Saya
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 py-8">
            @if($stations->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-yellow-800 mb-2">Belum Ada Stasiun Tersedia</h2>
                    <p class="text-yellow-600">Saat ini belum ada stasiun pengisian yang aktif.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stations as $station)
                        <a href="{{ route('stations.show', $station->id) }}" class="block">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition cursor-pointer">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800">{{ $station->name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                                {{ $station->address }}
                                            </p>
                                        </div>
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">
                                            Aktif
                                        </span>
                                    </div>

                                    <div class="space-y-2 mb-4">
                                        @if($station->ports && $station->ports->count() > 0)
                                            @php
                                                $maxPower = $station->ports->max('power_kw');
                                                $minPrice = $station->ports->min('price_per_kwh');
                                                $availablePorts = $station->ports->where('status', 'available')->count();
                                            @endphp
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Max Daya:</span>
                                                <span class="font-semibold text-gray-800">{{ $maxPower }} kW</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Harga Mulai:</span>
                                                <span class="font-semibold text-gray-800">Rp {{ number_format($minPrice, 0, ',', '.') }}/kWh</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Ports Tersedia:</span>
                                                <span class="font-semibold text-green-600">{{ $availablePorts }}/{{ $station->ports->count() }}</span>
                                            </div>
                                        @else
                                            <div class="text-center text-sm text-gray-500">
                                                No ports available
                                            </div>
                                        @endif
                                        @if($station->host)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Host:</span>
                                                <span class="font-medium text-gray-700">{{ $station->host->name }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 rounded-lg transition text-center">
                                        Lihat Detail
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</body>
</html>
