@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@guest
    <script>
        window.location.href = '{{ route("login") }}';
    </script>
@endguest
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    @auth
                        Selamat datang, {{ Auth::user()->name }}! 👋
                    @else
                        Selamat datang, Guest! 👋
                    @endauth
                </h1>
                <p class="text-gray-600 text-lg">
                    Siap untuk membuat pin yang menginspirasi hari ini?
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-images text-white text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Pins -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Pin</p>
                    @auth
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->pins()->count() }}</p>
                    @else
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    @endauth
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-thumbtack text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('pins.my-pins') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Total Boards -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Board</p>
                    @auth
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->boards()->count() }}</p>
                    @else
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    @endauth
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-th-large text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('boards.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Total Likes -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Suka</p>
                    @auth
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->pins()->withCount('likes')->get()->sum('likes_count') }}</p>
                    @else
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    @endauth
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-heart text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pin Saya yang Disukai -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pin Disukai</p>
                    @auth
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->likedPins()->count() }}</p>
                    @else
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    @endauth
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Create Pin -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Buat Pin Baru</h3>
                    <p class="text-blue-100">Bagikan ide dan inspirasi Anda</p>
                </div>
                <i class="fas fa-plus text-3xl text-blue-200"></i>
            </div>
            <a href="{{ route('pins.create') }}" class="inline-flex items-center bg-white text-blue-600 px-4 py-2 rounded-full font-medium hover:bg-blue-50 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Buat Pin
            </a>
        </div>

        <!-- Create Board -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Buat Board Baru</h3>
                    <p class="text-green-100">Organisir pin-pin Anda</p>
                </div>
                <i class="fas fa-th-large text-3xl text-green-200"></i>
            </div>
            <a href="{{ route('boards.create') }}" class="inline-flex items-center bg-white text-green-600 px-4 py-2 rounded-full font-medium hover:bg-green-50 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Buat Board
            </a>
        </div>

        <!-- Explore -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Jelajahi</h3>
                    <p class="text-purple-100">Temukan inspirasi baru</p>
                </div>
                <i class="fas fa-compass text-3xl text-purple-200"></i>
            </div>
            <a href="{{ route('explore.explore') }}" class="inline-flex items-center bg-white text-purple-600 px-4 py-2 rounded-full font-medium hover:bg-purple-50 transition-colors duration-200">
                <i class="fas fa-compass mr-2"></i>
                Jelajahi
            </a>
        </div>
    </div>

    <!-- Recent Pins -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Pin Terbaru Anda</h2>
            <a href="{{ route('pins.my-pins') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                Lihat Semua
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        @php
            $recentPins = Auth::check() ? Auth::user()->pins()->with('board')->latest()->take(6)->get() : collect();
        @endphp

        @if($recentPins->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($recentPins as $pin)
                    <div class="group cursor-pointer">
                        <a href="{{ route('pins.show', $pin) }}">
                            <div class="relative overflow-hidden rounded-lg bg-gray-100 aspect-square">
                                @if($pin->image_url)
                                    <img src="{{ asset('storage/' . $pin->image_url) }}"
                                         alt="{{ $pin->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200 truncate">
                                {{ $pin->title }}
                            </h3>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $pin->board->name ?? 'Tanpa Board' }}
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-thumbtack text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada pin</h3>
                <p class="text-gray-500 mb-4">Mulai buat pin pertama Anda sekarang!</p>
                <a href="{{ route('pins.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Pin Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Aktivitas Terbaru</h2>

        <div class="space-y-4">
            @php
                $activities = [];

                // Get recent pins
                $recentPins = Auth::check() ? Auth::user()->pins()->latest()->take(3)->get() : collect();
                foreach($recentPins as $pin) {
                    $activities[] = [
                        'type' => 'pin_created',
                        'message' => 'Anda membuat pin "' . $pin->title . '"',
                        'time' => $pin->created_at,
                        'icon' => 'fas fa-thumbtack',
                        'color' => 'text-blue-500',
                        'bg' => 'bg-blue-100'
                    ];
                }

                // Get recent boards
                $recentBoards = Auth::check() ? Auth::user()->boards()->latest()->take(2)->get() : collect();
                foreach($recentBoards as $board) {
                    $activities[] = [
                        'type' => 'board_created',
                        'message' => 'Anda membuat board "' . $board->name . '"',
                        'time' => $board->created_at,
                        'icon' => 'fas fa-th-large',
                        'color' => 'text-green-500',
                        'bg' => 'bg-green-100'
                    ];
                }

                // Sort by time
                usort($activities, function($a, $b) {
                    return $b['time']->timestamp - $a['time']->timestamp;
                });

                $activities = array_slice($activities, 0, 5);
            @endphp

            @if(count($activities) > 0)
                @foreach($activities as $activity)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 {{ $activity['bg'] }} rounded-full flex items-center justify-center">
                            <i class="{{ $activity['icon'] }} {{ $activity['color'] }}"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 font-medium">{{ $activity['message'] }}</p>
                            <p class="text-sm text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-history text-3xl mb-2 opacity-50"></i>
                    <p>Belum ada aktivitas</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

/* Card hover effects */
.hover-lift:hover {
    transform: translateY(-2px);
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endsection

@section('scripts')
<script>
// Add some interactive animations
document.addEventListener('DOMContentLoaded', function() {
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects
    document.querySelectorAll('.hover-lift').forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Auto-refresh activity every 5 minutes
setInterval(() => {
    // You can implement auto-refresh here if needed
}, 5 * 60 * 1000);
</script>
@endsection
