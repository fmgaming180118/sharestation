<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Share Station</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Invoice card animation */
        .invoice-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0] min-h-screen">
    <!-- Include TopBar Component -->
    @include('components.driver.topbar')
    
    <div class="pt-20 min-h-screen flex items-center justify-center px-6 py-12">
        @if($transaction)
        <div class="w-full max-w-4xl">
            <!-- Invoice Card -->
            <div class="invoice-card bg-white rounded-3xl shadow-2xl overflow-hidden">
                
                <!-- Card Header (Purple) -->
                <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-purple-600 px-8 py-8">
                    <h1 class="text-3xl font-bold text-white">Invoice code: {{ $transaction->transaction_code }}</h1>
                </div>
                
                <!-- Card Body (Dark Slate Blue) -->
                <div class="bg-gradient-to-br from-slate-600 via-slate-500 to-slate-600 px-8 py-8">
                    <!-- Station Info Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white mb-1">{{ $transaction->station->name }}</h2>
                        <p class="text-slate-200 text-sm">{{ $transaction->station->address }}</p>
                    </div>
                    
                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Left Column: Itemized List (5 boxes) -->
                        <div class="space-y-4">
                            <!-- Date -->
                            <div class="bg-white rounded-lg px-4 py-3 flex justify-between items-center">
                                <span class="text-gray-500 text-sm font-medium">Date</span>
                                <span class="text-gray-800 font-semibold">{{ $transaction->date->format('d/m/Y') }}</span>
                            </div>
                            
                            <!-- Time -->
                            <div class="bg-white rounded-lg px-4 py-3 flex justify-between items-center">
                                <span class="text-gray-500 text-sm font-medium">Time</span>
                                <span class="text-gray-800 font-semibold">{{ $transaction->start_time->format('H:i') }}</span>
                            </div>
                            
                            <!-- Duration -->
                            <div class="bg-white rounded-lg px-4 py-3 flex justify-between items-center">
                                <span class="text-gray-500 text-sm font-medium">Duration</span>
                                <span class="text-gray-800 font-semibold">{{ $transaction->duration_minutes ? $transaction->duration_minutes . ' mnt' : '-' }}</span>
                            </div>
                            
                            <!-- kWh Rate -->
                            <div class="bg-white rounded-lg px-4 py-3 flex justify-between items-center">
                                <span class="text-gray-500 text-sm font-medium">kWh Rate</span>
                                <span class="text-gray-800 font-semibold">{{ $transaction->port ? 'Rp ' . number_format($transaction->port->price_per_kwh, 0, ',', '.') . '/kWh' : '-' }}</span>
                            </div>
                            
                            <!-- Service Fee -->
                            <div class="bg-white rounded-lg px-4 py-3 flex justify-between items-center">
                                <span class="text-gray-500 text-sm font-medium">Service Fee</span>
                                <span class="text-gray-800 font-semibold">Rp 0</span>
                            </div>
                        </div>
                        
                        <!-- Right Column: Summary & Status -->
                        <div class="space-y-4">
                            <!-- Total Price Box -->
                            <div class="bg-white rounded-lg px-6 py-6">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-gray-600 text-sm font-medium">Total price</span>
                                    <span class="text-gray-800 text-3xl font-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-end">
                                    @if($transaction->payment_status == 'pending')
                                        <span class="bg-yellow-300 text-yellow-900 text-xs font-semibold px-3 py-1 rounded-full">
                                            Payment pending
                                        </span>
                                    @elseif($transaction->payment_status == 'paid')
                                        <span class="bg-green-300 text-green-900 text-xs font-semibold px-3 py-1 rounded-full">
                                            Paid
                                        </span>
                                    @else
                                        <span class="bg-red-300 text-red-900 text-xs font-semibold px-3 py-1 rounded-full">
                                            Cancelled
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Confirmation Code Box -->
                            <div class="bg-green-300 rounded-lg px-6 py-4">
                                <p class="text-gray-800 text-sm font-medium">
                                    Confirmation Code: <span class="font-bold">{{ $transaction->confirmation_code }}</span>
                                </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Card Footer (White) -->
                <div class="bg-white px-8 py-6 rounded-b-3xl">
                    <div class="flex flex-col sm:flex-row justify-end gap-4">
                        <button onclick="printInvoice()" class="no-print px-8 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Invoice
                        </button>
                        <a href="{{ route('driver.reservations') }}" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 rounded-xl text-white font-bold transition duration-300 transform hover:scale-105 shadow-lg text-center">
                            Done
                        </a>
                    </div>
                </div>
                
            </div>
            
            <!-- Back to Reservations Link -->
            <div class="text-center mt-8 no-print">
                <a href="{{ route('driver.reservations') }}" class="text-slate-600 hover:text-slate-800 font-medium inline-flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to My Reservations
                </a>
            </div>
        </div>
        @else
        <!-- No Transaction -->
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">No Invoice Found</h2>
                <p class="text-gray-600 mb-6">You don't have any transactions yet.</p>
                <a href="{{ route('driver.dashboard') }}" class="inline-block bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white font-bold px-8 py-3 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg">
                    Find a Station
                </a>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Footer -->
    <div class="text-center pb-6 text-gray-500 text-sm no-print">
        Copyright © HIMALESS 2025
    </div>
    
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</body>
</html>
