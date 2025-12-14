<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - ShareStation</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/icons/sistem/Logo.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#a8d5e2] min-h-screen">
    <!-- Topbar Component -->
    <x-landing.topbar />

    <!-- Contact Section -->
    <section class="pt-24 pb-16 px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Main Contact Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <!-- Left Column: Info Section (Purple Gradient) -->
                    <div class="md:w-5/12 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 p-10 text-white flex flex-col justify-between">
                        <div>
                            <h2 class="text-3xl font-bold mb-4">Get in Touch</h2>
                            <p class="text-blue-100 mb-8 leading-relaxed">
                                Have questions about your charging station or account? We are here to help.
                            </p>

                            <!-- Contact Details -->
                            <div class="space-y-6">
                                <!-- Email -->
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Email</h4>
                                        <p class="text-blue-100">admin@sharestation.com</p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-phone text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Phone</h4>
                                        <p class="text-blue-100">-</p>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold mb-1">Location</h4>
                                        <p class="text-blue-100">Jl. Letjen Suprapto, Jakarta Pusat</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Icons -->
                        <div class="mt-10">
                            <h4 class="font-semibold mb-4">Follow Us</h4>
                            <div class="flex gap-3">
                                <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Contact Form (White) -->
                    <div class="md:w-7/12 p-10">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Send us a Message</h3>
                        
                        <form action="#" method="POST" class="space-y-5">
                            @csrf
                            
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="Enter your name"
                                >
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="your.email@example.com"
                                >
                            </div>

                            <!-- Subject Field -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <select 
                                    id="subject" 
                                    name="subject" 
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                >
                                    <option value="">Select a subject</option>
                                    <option value="technical">Technical Issue</option>
                                    <option value="payment">Payment Problem</option>
                                    <option value="account">Account Help</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Message Field -->
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="5" 
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                                    placeholder="Type your message here..."
                                ></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3.5 rounded-lg shadow-lg transition transform hover:scale-[1.02]"
                            >
                                Send Message
                            </button>
                        </form>
                    </div>
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
