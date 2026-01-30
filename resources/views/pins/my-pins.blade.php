@extends('layouts.app')

@section('title', 'Pin Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pin Saya</h1>
            <p class="text-gray-600 mt-2">{{ $pins->total() }} pin yang Anda simpan</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Filter Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                    <i class="fas fa-chevron-down ml-2 text-sm"></i>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                    <a href="{{ route('pins.my-pins') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-list mr-2"></i>
                        Semua Pin
                    </a>
                    <a href="{{ route('pins.my-pins', ['sort' => 'newest']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-clock mr-2"></i>
                        Terbaru
                    </a>
                    <a href="{{ route('pins.my-pins', ['sort' => 'oldest']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-history mr-2"></i>
                        Terlama
                    </a>
                </div>
            </div>

            <!-- Create Pin Button -->
            <a href="{{ route('pins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Buat Pin Baru
            </a>
        </div>
    </div>

    <!-- Empty State -->
    @if($pins->count() == 0)
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <i class="fas fa-thumbtack text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada pin</h3>
                <p class="text-gray-500 mb-6">Mulai buat pin pertama Anda dan simpan ide-ide menarik!</p>
                <a href="{{ route('pins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Pin Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Pins Grid -->
        <div class="masonry">
            @foreach($pins as $pin)
                <div class="masonry-item">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
                        <!-- Pin Image -->
                        <div class="relative overflow-hidden">
                            @if($pin->image_url)
                                <img src="{{ asset('storage/' . $pin->image_url) }}"
                                     alt="{{ $pin->title }}"
                                     class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                </div>
                            @endif

                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex space-x-2">
                                    <a href="{{ route('pins.show', $pin) }}" class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-full font-medium transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat
                                    </a>
                                    <a href="{{ route('pins.edit', $pin) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-medium transition-colors duration-200">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Pin Content -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 text-sm leading-tight flex-1 pr-2">
                                    {{ Str::limit($pin->title, 50) }}
                                </h3>

                                <!-- Pin Actions Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                                        <i class="fas fa-ellipsis-h text-gray-500"></i>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         @click.away="open = false"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                                        <a href="{{ route('pins.show', $pin) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-eye mr-2"></i>
                                            Lihat Detail
                                        </a>
                                        <a href="{{ route('pins.edit', $pin) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit Pin
                                        </a>
                                        <hr class="my-1">
                                        <form action="{{ route('pins.destroy', $pin) }}" method="POST" class="block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pin ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                                <i class="fas fa-trash mr-2"></i>
                                                Hapus Pin
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @if($pin->description)
                                <p class="text-gray-600 text-xs mb-3 leading-relaxed">
                                    {{ Str::limit($pin->description, 80) }}
                                </p>
                            @endif

                            <!-- Pin Meta Info -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-th-large mr-1"></i>
                                    <span>{{ $pin->board->name ?? 'Tanpa Board' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $pin->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            @if($pin->link)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <a href="{{ $pin->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs flex items-center">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        Lihat Sumber
                                    </a>
                                </div>
                            @endif

                            <!-- Pin Stats -->
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-heart mr-1 text-blue-500"></i>
                                        {{ $pin->likes()->count() }} suka
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-comment mr-1 text-blue-500"></i>
                                        {{ $pin->comments()->count() }} komentar
                                    </span>
                                </div>
                                <a href="{{ route('pins.show', $pin) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $pins->links() }}
        </div>
    @endif
</div>

<style>
    /* Custom pagination styling */
    .pagination {
        @apply flex items-center justify-center space-x-2;
    }

    .pagination a,
    .pagination span {
        @apply px-4 py-2 text-sm border border-gray-300 rounded-lg;
    }

    .pagination a:hover {
        @apply bg-gray-50;
    }

    .pagination .active span {
        @apply bg-blue-600 text-white border-blue-600;
    }

    .pagination .disabled span {
        @apply text-gray-400 cursor-not-allowed;
    }
</style>
@endsection

@section('scripts')
<script>
// Auto-hide flash messages
setTimeout(function() {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(function(alert) {
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 300);
    });
}, 5000);

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>
@endsection
