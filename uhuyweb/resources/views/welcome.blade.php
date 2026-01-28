<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - UhuY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full">
                        <i class="fas fa-images text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">UhuY</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-blue-600 font-medium hover:text-blue-700 transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-600 rounded-full mb-6">
                    <i class="fas fa-images text-white text-5xl"></i>
                </div>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                Welcome to <span class="text-blue-600">UhuY</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-700 mb-8 leading-relaxed">
                Share your inspiration, discover amazing ideas, and connect with creative minds around the world.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-share-alt"></i></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Share Pins</h3>
                    <p class="text-gray-600">Upload and share your favorite content with the community</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-heart"></i></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Like & Save</h3>
                    <p class="text-gray-600">Collect and organize your favorite pins into boards</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-users"></i></div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Connect</h3>
                    <p class="text-gray-600">Follow other users and discover inspiring content</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                    Get Started
                </a>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold text-lg">
                    Sign In
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600">
                <p>© 2024 UhuY. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Redirect to dashboard if already logged in
        if (localStorage.getItem('token')) {
            window.location.href = '/dashboard';
        }
    </script>
</body>
</html>
