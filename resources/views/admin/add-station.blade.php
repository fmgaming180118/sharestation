<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Station - Share Station Admin</title>
    
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
            <div class="w-full max-w-5xl">
                <!-- Location Data Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Card Header (Purple Gradient) -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
                        <h1 class="text-3xl font-bold text-white">Location Data</h1>
                        <p class="text-purple-100 mt-1">Manage charging station locations</p>
                    </div>

                    <!-- Card Body (Table) -->
                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Operational Hours</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Price/kWh</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Power Output</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Port</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Owner</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @if($stations->isEmpty())
                                        <tr>
                                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fas fa-charging-station text-4xl mb-2"></i>
                                                <p>No stations found</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($stations as $index => $station)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 text-sm text-gray-800">{{ $index + 1 }}. {{ $station->name }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $station->operational_hours ?? '-' }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">-</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $station->ports()->max('power_kw') ?? '-' }} kW</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $station->ports()->count() }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $station->host->name ?? '-' }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-600">{{ $station->phone ?? '-' }}</td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('admin.edit-station', $station->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit Station">
                                                        <i class="fas fa-edit text-lg"></i>
                                                    </a>
                                                    <button onclick="confirmDelete('{{ addslashes($station->name) }}', {{ $station->id }})" class="text-red-600 hover:text-red-800 transition-colors" title="Delete Station">
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

                        <!-- Add Location Button -->
                        <div class="flex justify-end mt-8">
                            <a href="{{ route('admin.create-station') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition-all transform hover:scale-105">
                                ADD LOCATION
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
        function confirmDelete(stationName, stationId) {
            // First confirmation
            const firstConfirm = confirm(`Are you sure you want to delete "${stationName}"?\n\nThis action cannot be undone.`);
            
            if (firstConfirm) {
                // Second confirmation
                const secondConfirm = confirm(`FINAL CONFIRMATION\n\nAre you absolutely sure you want to delete "${stationName}"?\n\nThis will permanently remove all data associated with this station.`);
                
                if (secondConfirm) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/delete-station/${stationId}`;
                    
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
