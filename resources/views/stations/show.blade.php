<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $station->name }} - Share Station Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }
        
        .step {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        
        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .step.active .step-circle {
            background: #8b5cf6;
            color: white;
        }
        
        .step.completed .step-circle {
            background: #10b981;
            color: white;
        }
        
        .step-label {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        
        .step.active .step-label {
            color: #8b5cf6;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0] min-h-screen">
    <!-- Include TopBar Component -->
    @include('components.driver.topbar')
    
    <div class="pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Single Large Viewcard -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Hero Section - Purple Banner -->
                <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 p-10 relative overflow-hidden">
                    <!-- Decorative Pattern (optional) -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="circuit" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                <circle cx="5" cy="5" r="1" fill="white"/>
                                <circle cx="35" cy="35" r="1" fill="white"/>
                                <path d="M5 5 L35 35" stroke="white" stroke-width="0.5"/>
                            </pattern>
                            <rect width="100%" height="100%" fill="url(#circuit)"/>
                        </svg>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Title & Badges -->
                                <div class="flex items-center gap-3 mb-4">
                                    <h1 class="text-4xl font-bold text-white">{{ $station->name }}</h1>
                                    <span class="bg-green-400 text-white text-sm px-4 py-1.5 rounded-full font-semibold">Available</span>
                                </div>
                                
                                <!-- Subtitle Info -->
                                <p class="text-purple-100 text-base mb-3">{{ $station->address }}</p>
                                
                                <!-- Rating & Distance -->
                                <div class="flex items-center gap-6 text-white">
                                    <!-- Star Rating -->
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-yellow-300 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span class="font-semibold">4.9</span>
                                    </div>
                                    
                                    <!-- Distance -->
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="font-semibold">1.5 KM</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Charging Type Badges -->
                            <div class="flex gap-2">
                                <span class="bg-green-500 text-white text-sm px-4 py-1.5 rounded-full font-medium">Fast charging</span>
                                <span class="bg-yellow-400 text-gray-800 text-sm px-4 py-1.5 rounded-full font-medium">Regular Charging</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content Grid -->
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column (2/3 width) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Row 1: Charging Ports & Pricing -->
                            <div class="grid grid-cols-2 gap-6">
                                <!-- Charging Ports Card -->
                                <div class="bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Charging Ports</h3>
                                    <p class="text-xs text-gray-500 mb-4">Now is running</p>
                                    
                                    <div class="mb-4">
                                        <div class="text-right mb-2">
                                            <span class="text-2xl font-bold text-gray-800">0 of 3</span>
                                        </div>
                                        
                                        <!-- Progress Bars for 3 ports -->
                                        <div class="space-y-2">
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gray-300" style="width: 0%"></div>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gray-300" style="width: 0%"></div>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gray-300" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pricing Card -->
                                <div class="bg-purple-50 rounded-xl p-6 shadow-sm border border-purple-100">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-6">Pricing</h3>
                                    <div class="text-center">
                                        <p class="text-4xl font-bold text-purple-600">Rp {{ number_format($station->price_per_kwh, 0, ',', '.') }}<span class="text-xl text-gray-500">/kWh</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Row 2: Amenities -->
                            <div class="bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-700 mb-6">Amenities</h3>
                                <div class="grid grid-cols-4 gap-4">
                                    <!-- WiFi -->
                                    <div class="text-center">
                                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-700">Free WiFi</p>
                                    </div>
                                    
                                    <!-- Cafe -->
                                    <div class="text-center">
                                        <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-700">Cafe</p>
                                    </div>
                                    
                                    <!-- Parking -->
                                    <div class="text-center">
                                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-700">Parking Lot</p>
                                    </div>
                                    
                                    <!-- Prayer Room -->
                                    <div class="text-center">
                                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-medium text-gray-700">Prayer Room</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Row 3 & 4: Info Grid (2x2) -->
                            <div class="grid grid-cols-2 gap-6">
                                <!-- Address -->
                                <div class="bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Address</h3>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $station->address }}</p>
                                </div>
                                
                                <!-- Phone -->
                                <div class="bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Phone</h3>
                                    <p class="text-sm text-gray-600">0882-1189-9888</p>
                                </div>
                                
                                <!-- Operational Hours -->
                                <div class="bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Operational Hours</h3>
                                    <p class="text-sm text-gray-600">{{ $station->operational_hours ?? '09:00 - 23:00' }}</p>
                                </div>
                                
                                <!-- Available Ports -->
                                <div class="bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Available Ports</h3>
                                    <p class="text-sm text-gray-600">{{ $station->ports ? $station->ports->where('status', 'available')->count() : 0 }} / {{ $station->ports ? $station->ports->count() : 0 }} ports</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column (1/3 width) - Reviews & Actions -->
                        <div class="lg:col-span-1 flex flex-col">
                            <!-- Customer Reviews -->
                            <div class="flex-1 bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-6">Customer Reviews</h3>
                                
                                <div class="space-y-6">
                                    @if($station->reviews && $station->reviews->count() > 0)
                                        @foreach($station->reviews->take(3) as $review)
                                        <div class="{{ !$loop->last ? 'pb-4 border-b border-gray-200' : '' }}">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 bg-{{ ['blue', 'purple', 'green', 'orange'][rand(0, 3)] }}-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                                    {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <p class="font-semibold text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mb-2">{{ $review->created_at->diffForHumans() }}</p>
                                                    
                                                    <!-- Stars -->
                                                    <div class="flex items-center gap-1 mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 24 24">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    
                                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $review->review ?? $review->comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-8 text-gray-500">
                                            <i class="fas fa-star text-3xl mb-2"></i>
                                            <p>No reviews yet</p>
                                            <p class="text-xs mt-1">Be the first to review this station!</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="grid grid-cols-1 gap-4">
                                <button class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 px-6 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                    Navigate Here
                                </button>
                                <button onclick="openCheckoutModal()" class="bg-white hover:bg-gray-50 border-2 border-purple-600 text-purple-600 font-semibold py-4 px-6 rounded-xl transition shadow flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Reserve Slot
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                Copyright © HIMALESS 2025
            </div>
        </div>
    </div>
    
    <!-- Checkout Modal -->
    @include('components.checkout-modal', ['stationName' => $station->name, 'depositAmount' => 50000])
    
    <script>
        function openCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('active');
            showCheckoutStep(1);
        }
        
        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('active');
            resetCheckoutModal();
        }
        
        function showCheckoutStep(stepNumber) {
            // Hide all steps
            for(let i = 1; i <= 3; i++) {
                document.getElementById('checkoutStep' + i).classList.add('hidden');
                document.getElementById('indicator' + i).classList.remove('active', 'completed');
            }
            
            // Show current step
            document.getElementById('checkoutStep' + stepNumber).classList.remove('hidden');
            document.getElementById('indicator' + stepNumber).classList.add('active');
            
            // Mark previous steps as completed
            for(let i = 1; i < stepNumber; i++) {
                document.getElementById('indicator' + i).classList.add('completed');
            }
        }
        
        function goToPaymentStep() {
            const checkbox = document.getElementById('agreeCheckbox');
            if (!checkbox.checked) {
                alert('Silakan centang persetujuan ketentuan booking terlebih dahulu');
                return;
            }
            showCheckoutStep(2);
        }
        
        function backToDepositInfo() {
            showCheckoutStep(1);
        }
        
        function confirmPayment() {
            // Set booking time
            const now = new Date();
            const deadline = new Date(now.getTime() + 60 * 60 * 1000); // +1 hour
            
            document.getElementById('bookingTime').textContent = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            document.getElementById('deadlineTime').textContent = deadline.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            showCheckoutStep(3);
            startCountdown(3600); // 60 minutes in seconds
        }
        
        let countdownInterval;
        
        function startCountdown(seconds) {
            // Clear any existing countdown
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            let remaining = seconds;
            const timerElement = document.getElementById('countdownTimer');
            
            function updateTimer() {
                const minutes = Math.floor(remaining / 60);
                const secs = remaining % 60;
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                
                if (remaining <= 0) {
                    clearInterval(countdownInterval);
                    timerElement.textContent = 'EXPIRED';
                    timerElement.classList.add('text-red-600');
                }
                remaining--;
            }
            
            updateTimer();
            countdownInterval = setInterval(updateTimer, 1000);
        }
        
        function resetCheckoutModal() {
            document.getElementById('agreeCheckbox').checked = false;
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            showCheckoutStep(1);
        }
        
        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('checkoutModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCheckoutModal();
                }
            });
        });
    </script>
</body>
</html>
