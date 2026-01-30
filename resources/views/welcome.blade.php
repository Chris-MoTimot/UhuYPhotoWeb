<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - UhuY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

                @auth
                    <!-- Authenticated User Navigation -->
                    <div class="flex items-center space-x-4">
                        <!-- Main Navigation -->
                        <div class="hidden md:flex items-center space-x-6">
                            <a href="{{ route('pins.my-pins') }}" class="flex items-center px-3 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-thumbtack mr-2"></i>
                                My Pins
                            </a>
                            <a href="{{ route('boards.index') }}" class="flex items-center px-3 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-th-large mr-2"></i>
                                Boards
                            </a>
                        </div>

                        <!-- Create Pin Button -->
                        <a href="{{ route('pins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Create Pin
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                                @if(Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                    </div>
                                @endif
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('pins.my-pins') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-thumbtack mr-3 text-gray-400"></i>
                                        My Pins
                                    </a>
                                    <a href="{{ route('boards.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-th-large mr-3 text-gray-400"></i>
                                        My Boards
                                    </a>
                                </div>

                                <div class="border-t border-gray-100 py-1">
                                    <form action="{{ route('logout') }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-blue-600 font-medium hover:text-blue-700 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Sign Up
                        </a>
                    </div>
                @endauth
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

            @auth
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Welcome back, <span class="text-blue-600">{{ Auth::user()->name }}!</span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-700 mb-8 leading-relaxed">
                    Ready to create and discover amazing content today?
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-thumbtack"></i></div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">My Pins</h3>
                        <p class="text-gray-600">You have {{ Auth::user()->pins()->count() }} pins</p>
                        <a href="{{ route('pins.my-pins') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-medium">View All →</a>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-th-large"></i></div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">My Boards</h3>
                        <p class="text-gray-600">You have {{ Auth::user()->boards()->count() }} boards</p>
                        <a href="{{ route('boards.index') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-medium">View All →</a>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-heart"></i></div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Liked Pins</h3>
                        <p class="text-gray-600">You liked {{ Auth::user()->likedPins()->count() }} pins</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-medium">View Stats →</a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('pins.create') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                        Create New Pin
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold text-lg">
                        Go to Dashboard
                    </a>
                </div>
            @else
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
            @endauth
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
</body>
</html>
