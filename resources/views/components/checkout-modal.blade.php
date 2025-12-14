<!-- Checkout Modal Component -->
<div id="checkoutModal" class="modal">
    <div class="modal-content">
        <!-- Step Indicators -->
        <div class="step-indicator">
            <div class="step" id="indicator1">
                <div class="step-circle">1</div>
                <div class="step-label">Info Deposit</div>
            </div>
            <div class="step" id="indicator2">
                <div class="step-circle">2</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step" id="indicator3">
                <div class="step-circle">3</div>
                <div class="step-label">Konfirmasi</div>
            </div>
        </div>
        
        <!-- Step 1: Informasi Deposit -->
        <div id="checkoutStep1" class="step-content">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Informasi Booking</h2>
                <button onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Station Info -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 mb-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h3 class="font-bold text-gray-800">{{ $stationName }}</h3>
                </div>
                <p class="text-sm text-gray-600">Jl. Letjen Suprapto, Cempaka Putih</p>
            </div>
            
            <!-- Deposit Information -->
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-5 mb-6">
                <div class="flex items-start gap-3 mb-4">
                    <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-bold text-yellow-900 mb-2">Ketentuan Booking:</h4>
                        <ul class="space-y-2 text-sm text-yellow-800">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Deposit sebesar <strong>Rp {{ number_format($depositAmount, 0, ',', '.') }}</strong> harus dibayar terlebih dahulu</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span>Anda harus <strong>mulai mengisi dalam 1 jam</strong> setelah booking dikonfirmasi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong class="text-red-700">Jika tidak mengisi dalam 1 jam, deposit akan hangus</strong> dan booking otomatis dibatalkan</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Deposit ini adalah <strong>uang muka</strong>, bukan biaya penuh charging</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Deposit Amount -->
            <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 mb-6 text-white text-center">
                <p class="text-sm opacity-90 mb-2">Total Deposit</p>
                <p class="text-4xl font-bold mb-1">Rp {{ number_format($depositAmount, 0, ',', '.') }}</p>
                <p class="text-xs opacity-80">Uang Muka Booking</p>
            </div>
            
            <!-- Confirmation Checkbox -->
            <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl mb-6 cursor-pointer hover:bg-gray-100 transition">
                <input type="checkbox" id="agreeCheckbox" class="w-5 h-5 text-purple-600 rounded mt-0.5">
                <span class="text-sm text-gray-700">Saya memahami dan menyetujui ketentuan booking di atas, termasuk konsekuensi jika tidak memulai pengisian dalam 1 jam.</span>
            </label>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="closeCheckoutModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition">
                    Batal
                </button>
                <button onclick="goToPaymentStep()" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-xl transition shadow-lg">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </div>
        
        <!-- Step 2: Pembayaran -->
        <div id="checkoutStep2" class="step-content hidden">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Pembayaran Deposit</h2>
                <button onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Payment Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Cara Pembayaran:</p>
                        <ol class="list-decimal list-inside space-y-1 text-blue-700">
                            <li>Scan QR code di bawah menggunakan aplikasi e-wallet Anda</li>
                            <li>Konfirmasi pembayaran sebesar <strong>Rp {{ number_format($depositAmount, 0, ',', '.') }}</strong></li>
                            <li>Klik tombol "Sudah Bayar" setelah pembayaran berhasil</li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="text-center mb-6">
                <div class="inline-block bg-white p-6 rounded-2xl shadow-xl border-2 border-gray-100">
                    <div class="w-64 h-64 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center">
                        <!-- Placeholder QR Code -->
                        <div class="text-center">
                            <svg class="w-48 h-48 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">QR Code Payment</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Amount -->
                <div class="mt-6">
                    <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                    <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($depositAmount, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <p class="text-xs text-gray-600 mb-3 text-center">Metode Pembayaran yang Diterima:</p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        <span class="text-sm font-semibold text-blue-600">GoPay</span>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        <span class="text-sm font-semibold text-green-600">OVO</span>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        <span class="text-sm font-semibold text-red-600">DANA</span>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        <span class="text-sm font-semibold text-purple-600">ShopeePay</span>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="backToDepositInfo()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition">
                    Kembali
                </button>
                <button onclick="confirmPayment()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sudah Bayar
                </button>
            </div>
        </div>
        
        <!-- Step 3: Konfirmasi -->
        <div id="checkoutStep3" class="step-content hidden">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Booking Berhasil!</h2>
                <p class="text-gray-600 mb-6">Reservasi slot charging Anda telah dikonfirmasi</p>
                
                <!-- Countdown Timer Warning -->
                <div class="bg-gradient-to-br from-orange-50 to-red-50 border-2 border-orange-300 rounded-xl p-5 mb-6">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="font-bold text-orange-900">Segera Mulai Pengisian!</h3>
                    </div>
                    <p class="text-sm text-orange-800 mb-3">Anda harus mulai mengisi dalam:</p>
                    <div class="text-4xl font-bold text-orange-600 mb-2">
                        <span id="countdownTimer">60:00</span>
                    </div>
                    <p class="text-xs text-orange-700">
                        <strong>PERHATIAN:</strong> Deposit akan hangus jika melewati batas waktu!
                    </p>
                </div>
                
                <!-- Booking Details -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left">
                    <h3 class="font-semibold text-gray-800 mb-4 text-center">Detail Reservasi</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Stasiun:</span>
                            <span class="font-semibold text-gray-800">{{ $stationName }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Lokasi:</span>
                            <span class="font-medium text-gray-800">Jl. Letjen Suprapto</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Port:</span>
                            <span class="font-medium text-gray-800">Port #A1</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Waktu Booking:</span>
                            <span class="font-medium text-gray-800" id="bookingTime">-</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Batas Waktu:</span>
                            <span class="font-medium text-red-600" id="deadlineTime">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Deposit:</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Navigate ke Stasiun
                    </button>
                    <button onclick="closeCheckoutModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
