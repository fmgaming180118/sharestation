<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Share Station Admin</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#e8f4f8] to-[#d5e9f0] min-h-screen">
    <!-- Include Admin Sidebar Component -->
    @include('components.admin.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Include Admin Header Component -->
        @include('components.admin.header')

        <!-- Page Content -->
        <main class="p-6 flex items-center justify-center min-h-[calc(100vh-80px)]">
            <div class="w-full max-w-3xl">
                <!-- User Form Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Card Header (Purple Gradient) -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
                        <h1 class="text-3xl font-bold text-white">Add New User</h1>
                        <p class="text-purple-100 mt-1">Create a new user with role "Warga" (Owner)</p>
                    </div>

                    <!-- Card Body (Form) -->
                    <div class="p-8">
                        <form action="{{ route('admin.store-user') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- Info Alert -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Auto-assigned Role</p>
                                        <p class="text-sm text-blue-700 mt-1">This user will automatically be assigned the "Warga" (Owner) role.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- User Details -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-user text-indigo-600 mr-2"></i>
                                User Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., Ahmad Susanto">
                                </div>

                                <!-- Username -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Username <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="username" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., ahmad_s">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., ahmad@example.com">
                                </div>

                                <!-- Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Minimum 8 characters">
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Re-enter password">
                                </div>
                            </div>

                            <!-- Hidden Role Field (auto-assigned) -->
                            <input type="hidden" name="role" value="warga">

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6">
                                <a href="{{ route('admin.user-management') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition-all transform hover:scale-105">
                                    <i class="fas fa-user-plus mr-2"></i>Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Copyright Footer -->
                <div class="text-center py-6 text-gray-500 text-sm">
                    Copyright © HIMALESS 2025
                </div>
            </div>
        </main>
    </div>
</body>
</html>
