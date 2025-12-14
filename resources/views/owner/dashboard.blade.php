<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Analytics Dashboard - ShareStation</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Highcharts CDN -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    
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
        <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Column (Main Content - 75%) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Header with Station Selector and Date Filter -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-800">Usage Chart of My Stations</h2>
                
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Station Selector Dropdown -->
                    <div class="relative">
                        <label class="block text-sm text-gray-600 mb-1">Select Station:</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="stationSelector" class="pl-10 pr-8 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none cursor-pointer min-w-[300px]">
                                @foreach($stations as $station)
                                    <option value="{{ $station['id'] }}" {{ $station['selected'] ? 'selected' : '' }}>
                                        {{ $station['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" id="stationAddress">
                            {{ $stations[0]['address'] }}
                        </p>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="relative">
                        <label class="block text-sm text-gray-600 mb-1">Filter by Date:</label>
                        <div class="flex gap-2">
                            <!-- Start Date -->
                            <div class="relative">
                                <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <input type="date" id="startDate" class="pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" placeholder="Start Date">
                            </div>
                            
                            <!-- Separator -->
                            <div class="flex items-center pt-6">
                                <span class="text-gray-500">to</span>
                            </div>
                            
                            <!-- End Date -->
                            <div class="relative">
                                <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <input type="date" id="endDate" class="pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" placeholder="End Date">
                            </div>
                            
                            <!-- Apply Filter Button (for future functionality) -->
                            <button id="applyDateFilter" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled title="Will be enabled when database is connected">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle"></i> Date filtering will be enabled when connected to database
                        </p>
                    </div>
                </div>
            </div>

                <!-- Stats Cards Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Estimation Revenue Today -->
                    <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-xl p-6 shadow-sm">
                        <p class="text-sm text-gray-600 mb-2">Estimation Revenue Today</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['revenue_today'] }}</p>
                    </div>

                    <!-- Total Charging Activity -->
                    <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl p-6 shadow-sm">
                        <p class="text-sm text-gray-600 mb-2">Total Charging Activity</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_activity'] }}</p>
                    </div>

                    <!-- Available Ports -->
                    <div class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl p-6 shadow-sm">
                        <p class="text-sm text-gray-600 mb-2">Available Ports</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['available_ports'] }}</p>
                    </div>
                </div>

                <!-- Charts Row 1: User Growth & Fast vs Regular -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Growth Chart -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Penggunaan SPKLU 6 bulan terakhir</h3>
                        <div id="userGrowthChart" style="height: 300px;"></div>
                        <div class="flex items-center justify-center space-x-4 mt-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Pengguna</span>
                            </div>
                        </div>
                    </div>

                    <!-- Fast Charge vs Regular Charge -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Fast charge v Regular charge</h3>
                        <div id="chargeTypeChart" style="height: 300px;"></div>
                        <div class="flex items-center justify-center space-x-6 mt-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Regular Charging</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                                <span class="text-sm text-gray-600">Fast Charging</span>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <p class="text-xs text-gray-500">Fast Charging</p>
                            <p class="text-xs text-gray-500">Regular Charging</p>
                        </div>
                    </div>
                </div>

                <!-- Peak Hours Density Chart -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Kepadatan penggunaan Fast charge v Regular charge</h3>
                    <div id="peakHoursChart" style="height: 300px;"></div>
                    <div class="flex items-center justify-center space-x-6 mt-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-pink-400 rounded-full"></div>
                            <span class="text-sm text-gray-600">Fast Charging</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-amber-700 rounded-full"></div>
                            <span class="text-sm text-gray-600">Regular Charging</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (Sidebar - 25%) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl p-6 shadow-sm sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Charging History</h3>
                    
                    <!-- Legend -->
                    <div class="flex items-center space-x-4 mb-4 text-xs">
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-gray-600">Succeeded</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <span class="text-gray-600">On Charging</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-gray-600">Cancelled</span>
                        </div>
                    </div>

                    <!-- Transaction Cards -->
                    <div class="space-y-3 max-h-[600px] overflow-y-auto">
                        @foreach($chargingHistory as $transaction)
                            <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                <!-- Transaction ID -->
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-semibold text-sm text-gray-800">{{ $transaction['transaction_id'] }}</span>
                                    @if($transaction['status'] === 'succeeded')
                                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                                            {{ $transaction['status_text'] }}
                                        </span>
                                    @elseif($transaction['status'] === 'charging')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-600 text-xs rounded-full">
                                            {{ $transaction['status_text'] }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded-full">
                                            {{ $transaction['status_text'] }}
                                        </span>
                                    @endif
                                </div>

                                <!-- User Info -->
                                <div class="text-xs text-gray-600 mb-2">
                                    <p>By: {{ $transaction['user'] }}</p>
                                    <p class="font-mono">{{ $transaction['license_plate'] }}</p>
                                </div>

                                <!-- Progress Bar (for charging status) -->
                                @if($transaction['status'] === 'charging')
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                        <div class="bg-gradient-to-r from-purple-500 to-blue-400 h-1.5 rounded-full" style="width: 65%"></div>
                                    </div>
                                @endif

                                <!-- Duration & Price -->
                                <div class="flex justify-between items-center text-xs mb-2">
                                    <span class="text-gray-600">{{ $transaction['duration'] }}</span>
                                    <span class="font-semibold text-gray-800">{{ $transaction['price'] }}</span>
                                </div>

                                <!-- Detail Button -->
                                <a href="{{ route('owner.transaction.detail', $transaction['transaction_id']) }}" class="block w-full py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer text-center">
                                    DETAIL
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center py-6 text-gray-500 text-sm">
            Copyright © HIMALESS 2025
        </div>
        </div>
    </div>

    <!-- Highcharts Initialization Script -->
    <script>
        // Station selector change handler
        const stationData = @json($stations);
        document.getElementById('stationSelector').addEventListener('change', function(e) {
            const selectedStation = stationData.find(s => s.id == e.target.value);
            if (selectedStation) {
                document.getElementById('stationAddress').textContent = selectedStation.address;
            }
        });

        // User Growth Chart (Bar Chart)
        Highcharts.chart('userGrowthChart', {
            chart: {
                type: 'column',
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                max: 150,
                title: {
                    text: null
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    color: '#10b981'
                }
            },
            series: [{
                name: 'Pengguna',
                data: [95, 110, 105, 115, 125, 135]
            }],
            credits: {
                enabled: false
            }
        });

        // Fast Charge vs Regular Charge (Donut Chart)
        Highcharts.chart('chargeTypeChart', {
            chart: {
                type: 'pie',
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    innerSize: '60%',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: false
                }
            },
            series: [{
                name: 'Charging Type',
                colorByPoint: true,
                data: [{
                    name: 'Fast Charging',
                    y: 45,
                    color: '#60a5fa'
                }, {
                    name: 'Regular Charging',
                    y: 55,
                    color: '#a855f7'
                }]
            }],
            credits: {
                enabled: false
            }
        });

        // Peak Hours Density Chart (Area Chart)
        Highcharts.chart('peakHoursChart', {
            chart: {
                type: 'area',
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['12pm', '13pm', '14pm', '15pm', '16pm', '17pm', '18pm', '19pm', '20pm', '21pm'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                max: 60,
                title: {
                    text: null
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Fast Charging',
                data: [15, 20, 25, 30, 35, 40, 45, 35, 30, 25],
                color: '#f472b6',
                fillOpacity: 0.5
            }, {
                name: 'Regular Charging',
                data: [20, 25, 20, 15, 20, 25, 15, 20, 25, 20],
                color: '#b45309',
                fillOpacity: 0.5
            }],
            credits: {
                enabled: false
            }
        });
    </script>
</body>
</html>
