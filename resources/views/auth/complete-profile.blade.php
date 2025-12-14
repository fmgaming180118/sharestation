<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - Share Station</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/icons/sistem/Logo.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #a8d5e2 0%, #7ec4d8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <div class="w-full max-w-md mx-4">
        <!-- Complete Profile Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header Section with Gradient -->
            <div class="bg-gradient-to-r from-[#7b9ec9] to-[#8ba8d4] px-8 py-10 text-center">
                <!-- Logo/Icon -->
                <div class="flex justify-center mb-4">
                    <div class="bg-white/20 rounded-full p-4 backdrop-blur-sm">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl font-bold text-white mb-1">Complete Your Profile</h1>
                <p class="text-white/90 text-sm font-medium">Lengkapi data untuk melanjutkan</p>
            </div>

            <!-- Form Section -->
            <div class="px-8 py-10">
                <!-- Welcome Text -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Hampir Selesai!</h2>
                    <p class="text-gray-500 text-sm">Mohon lengkapi data berikut untuk melanjutkan</p>
                </div>

                <!-- Complete Profile Form -->
                <form method="POST" action="{{ route('auth.complete-profile.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Display Errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $name) }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#7b9ec9] focus:border-transparent outline-none transition text-gray-700"
                            placeholder="Masukkan nama lengkap"
                        >
                    </div>

                    <!-- Email Field (readonly) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ $email }}"
                            readonly
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed"
                        >
                        <p class="mt-1 text-xs text-gray-500">Email dari akun Google Anda</p>
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#7b9ec9] focus:border-transparent outline-none transition text-gray-700"
                            placeholder="Contoh: 081234567890"
                        >
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#7b9ec9] focus:border-transparent outline-none transition text-gray-700"
                            placeholder="Minimal 6 karakter"
                        >
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#7b9ec9] focus:border-transparent outline-none transition text-gray-700"
                            placeholder="Ketik ulang password"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-[#7b9ec9] to-[#8ba8d4] hover:from-[#6b8eb9] hover:to-[#7b98c4] text-white font-semibold py-3.5 rounded-full shadow-lg transition transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Simpan & Lanjutkan
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center">
                <p class="text-xs text-gray-500">Copyright © HIMALESS 2025</p>
            </div>
        </div>
    </div>
</body>
</html>
