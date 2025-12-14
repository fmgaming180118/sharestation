<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Share Station Admin</title>
    
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
            <div class="w-full max-w-6xl">
                <!-- User Management Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Card Header (Purple Gradient) -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
                        <h1 class="text-3xl font-bold text-white">User Management</h1>
                        <p class="text-purple-100 mt-1">Manage users with role "Warga" (Owner)</p>
                    </div>

                    <!-- Card Body (Table) -->
                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Username</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Created Date</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @if($users->isEmpty())
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fas fa-users text-4xl mb-2"></i>
                                                <p>No users found with role "Warga"</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($users as $user)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 text-sm text-gray-800">{{ $user->id }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-800 font-medium">{{ $user->name }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $user->username ?? '-' }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('admin.edit-user', $user->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit User">
                                                        <i class="fas fa-edit text-lg"></i>
                                                    </a>
                                                    <button onclick="confirmDeleteUser('{{ addslashes($user->name) }}', {{ $user->id }})" class="text-red-600 hover:text-red-800 transition-colors" title="Delete User">
                                                        <i class="fas fa-trash text-lg"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>

                        <!-- Add User Button -->
                        <div class="flex justify-end mt-8">
                            <a href="{{ route('admin.create-user') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition-all transform hover:scale-105">
                                <i class="fas fa-user-plus mr-2"></i>ADD USER
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright Footer -->
                <div class="text-center py-6 text-gray-500 text-sm">
                    Copyright © HIMALESS 2025
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Script -->
    <script>
        function confirmDeleteUser(userName, userId) {
            // First confirmation
            const firstConfirm = confirm(`Are you sure you want to delete user "${userName}"?\n\nThis action cannot be undone.`);
            
            if (firstConfirm) {
                // Second confirmation
                const secondConfirm = confirm(`FINAL CONFIRMATION\n\nAre you absolutely sure you want to delete user "${userName}"?\n\nThis will permanently remove all data associated with this user.`);
                
                if (secondConfirm) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/delete-user/${userId}`;
                    
                    // CSRF Token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    
                    // Method Spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>
