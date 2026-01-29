<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left side - Logo and main navigation -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full">
                        <i class="fas fa-images text-white text-lg"></i>
                    </div>
                    <span class="ml-2 text-xl font-bold text-gray-900">UhuY</span>
                </a>

                <!-- Main Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('explore.explore') }}" class="flex items-center px-3 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('explore.explore') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-compass mr-2"></i>
                        Explore
                    </a>

                    @auth
                        <a href="{{ route('pins.my-pins') }}" class="flex items-center px-3 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('pins.my-pins') ? 'bg-gray-100 font-semibold' : '' }}">
                            <i class="fas fa-thumbtack mr-2"></i>
                            Pin Saya
                        </a>

                        <a href="{{ route('boards.index') }}" class="flex items-center px-3 py-2 rounded-full text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ request()->routeIs('boards.*') ? 'bg-gray-100 font-semibold' : '' }}">
                            <i class="fas fa-th-large mr-2"></i>
                            Board
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Center - Search Bar -->
            <div class="flex-1 max-w-2xl mx-8">
                <form action="{{ route('pins.search') }}" method="GET" class="relative">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari pin, board, atau ide..."
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white sm:text-sm">
                    </div>
                </form>
            </div>

            <!-- Right side - User menu and actions -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Create Pin Button -->
                    <a href="{{ route('pins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Pin
                    </a>

                    <!-- Notifications (placeholder) -->
                    <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fas fa-bell text-xl"></i>
                    </button>

                    <!-- Messages (placeholder) -->
                    <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fas fa-envelope text-xl"></i>
                    </button>

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
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    Profil Saya
                                </a>
                                <a href="{{ route('pins.my-pins') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-thumbtack mr-3 text-gray-400"></i>
                                    Pin Saya
                                </a>
                                <a href="{{ route('boards.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-th-large mr-3 text-gray-400"></i>
                                    Board Saya
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog mr-3 text-gray-400"></i>
                                    Pengaturan
                                </a>
                            </div>

                            <div class="border-t border-gray-100 py-1">
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-data="{ mobileOpen: false }">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <button @click="mobileOpen = !mobileOpen" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div x-show="mobileOpen" class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-home mr-2"></i>
                    Beranda
                </a>
                @auth
                    <a href="{{ route('pins.my-pins') }}" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-thumbtack mr-2"></i>
                        Pin Saya
                    </a>
                    <a href="{{ route('boards.index') }}" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-th-large mr-2"></i>
                        Board
                    </a>
                    <a href="{{ route('pins.create') }}" class="text-gray-700 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Pin
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Alpine.js for dropdown functionality -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
