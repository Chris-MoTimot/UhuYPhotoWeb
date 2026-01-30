@extends('layouts.app')

@section('title', $board->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Board Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $board->name }}</h1>
                    @if($board->is_private)
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            Private
                        </span>
                    @else
                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm flex items-center">
                            <i class="fas fa-globe mr-1"></i>
                            Publik
                        </span>
                    @endif
                </div>

                @if($board->description)
                    <p class="text-gray-600 text-lg leading-relaxed mb-4">{{ $board->description }}</p>
                @endif

                <!-- Board Meta -->
                <div class="flex items-center space-x-6 text-sm text-gray-500">
                    <span class="flex items-center">
                        <i class="fas fa-thumbtack mr-2"></i>
                        {{ $pins->total() }} pin{{ $pins->total() > 1 ? 's' : '' }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        Oleh {{ $board->user->name }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-calendar mr-2"></i>
                        Dibuat {{ $board->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>

            <!-- Board Actions -->
            @if(Auth::check() && Auth::id() === $board->user_id)
                <div class="flex items-center space-x-3">
                    <a href="{{ route('pins.create') }}?board_id={{ $board->id }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pin
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors duration-200">
                            <i class="fas fa-ellipsis-h text-lg"></i>
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
                            <a href="{{ route('boards.edit', $board) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Board
                            </a>
                            <hr class="my-1">
                            <form action="{{ route('boards.destroy', $board) }}" method="POST" class="block" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus Board
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Board User Info -->
        <div class="flex items-center space-x-4 pt-4 border-t border-gray-200">
            @if($board->user->profile_picture)
                <img src="{{ asset('storage/' . $board->user->profile_picture) }}"
                     alt="{{ $board->user->name }}"
                     class="w-12 h-12 rounded-full object-cover">
            @else
                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
            @endif
            <div>
                <p class="font-semibold text-gray-900">{{ $board->user->name }}</p>
                @if($board->user->username)
                    <p class="text-sm text-gray-500">@{{ $board->user->username }}</p>
                @endif
                @if($board->user->bio)
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($board->user->bio, 100) }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('boards.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Board Saya
        </a>
    </div>

    <!-- Filter and Sort Options -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <!-- Sort Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-sort mr-2"></i>
                    Urutkan
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
                     class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                    <a href="{{ route('boards.show', $board) }}?sort=newest" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-clock mr-2"></i>
                        Terbaru
                    </a>
                    <a href="{{ route('boards.show', $board) }}?sort=oldest" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-history mr-2"></i>
                        Terlama
                    </a>
                    <a href="{{ route('boards.show', $board) }}?sort=popular" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-heart mr-2"></i>
                        Terpopuler
                    </a>
                </div>
            </div>
        </div>

        <!-- View Toggle -->
        <div class="flex items-center space-x-2">
            <button onclick="setView('masonry')" id="masonry-btn" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200 active">
                <i class="fas fa-th text-lg"></i>
            </button>
            <button onclick="setView('list')" id="list-btn" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                <i class="fas fa-list text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Empty State -->
    @if($pins->count() == 0)
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <i class="fas fa-thumbtack text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Board ini masih kosong</h3>
                <p class="text-gray-500 mb-6">Mulai tambahkan pin untuk mengisi board ini!</p>
                @if(Auth::check() && Auth::id() === $board->user_id)
                    <a href="{{ route('pins.create') }}?board_id={{ $board->id }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-colors duration-200 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pin Pertama
                    </a>
                @endif
            </div>
        </div>
    @else
        <!-- Pins Grid (Masonry View) -->
        <div id="masonry-view" class="masonry">
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
                                    @if(Auth::check())
                                        <button onclick="toggleLike({{ $pin->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-medium transition-colors duration-200">
                                            <i class="fas fa-heart mr-1"></i>
                                            Suka
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Pin Content -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 text-sm leading-tight flex-1 pr-2">
                                    <a href="{{ route('pins.show', $pin) }}" class="hover:text-blue-600 transition-colors duration-200">
                                        {{ Str::limit($pin->title, 50) }}
                                    </a>
                                </h3>

                                @if(Auth::check() && Auth::id() === $pin->user_id)
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
                                             class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                                            <a href="{{ route('pins.edit', $pin) }}" class="block px-3 py-1 text-xs text-gray-700 hover:bg-gray-50">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('pins.destroy', $pin) }}" method="POST" class="block" onsubmit="return confirm('Hapus pin ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-3 py-1 text-xs text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($pin->description)
                                <p class="text-gray-600 text-xs mb-3 leading-relaxed">
                                    {{ Str::limit($pin->description, 80) }}
                                </p>
                            @endif

                            <!-- Pin Meta Info -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>{{ $pin->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Pin Stats -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div class="flex items-center space-x-3 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-heart mr-1 text-blue-500"></i>
                                        <span id="likes-count-{{ $pin->id }}">{{ $pin->likes()->count() }}</span>
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-comment mr-1 text-blue-500"></i>
                                        {{ $pin->comments()->count() }}
                                    </span>
                                </div>
                                <a href="{{ route('pins.show', $pin) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- List View (Hidden by default) -->
        <div id="list-view" class="space-y-4" style="display: none;">
            @foreach($pins as $pin)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    <div class="flex">
                        <!-- Pin Image -->
                        <div class="w-48 h-32 flex-shrink-0">
                            @if($pin->image_url)
                                <img src="{{ asset('storage/' . $pin->image_url) }}"
                                     alt="{{ $pin->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Pin Content -->
                        <div class="flex-1 p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-red-600 transition-colors duration-200">
                                    <a href="{{ route('pins.show', $pin) }}">{{ $pin->title }}</a>
                                </h3>
                                <div class="flex items-center space-x-2">
                                    @if(Auth::check())
                                        <button onclick="toggleLike({{ $pin->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    @endif
                                    @if(Auth::check() && Auth::id() === $pin->user_id)
                                        <a href="{{ route('pins.edit', $pin) }}" class="text-blue-500 hover:text-blue-700 transition-colors duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($pin->description)
                                <p class="text-gray-600 mb-3 leading-relaxed">
                                    {{ Str::limit($pin->description, 150) }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-heart mr-1 text-blue-500"></i>
                                        {{ $pin->likes()->count() }} suka
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-comment mr-1 text-blue-500"></i>
                                        {{ $pin->comments()->count() }} komentar
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $pin->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <a href="{{ route('pins.show', $pin) }}" class="text-blue-600 hover:text-blue-800 font-medium">
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
    /* View toggle active state */
    .active {
        @apply text-blue-600 bg-blue-50;
    }

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
// View toggle functionality
function setView(view) {
    const masonryView = document.getElementById('masonry-view');
    const listView = document.getElementById('list-view');
    const masonryBtn = document.getElementById('masonry-btn');
    const listBtn = document.getElementById('list-btn');

    if (view === 'masonry') {
        masonryView.style.display = 'block';
        listView.style.display = 'none';
        masonryBtn.classList.add('active');
        listBtn.classList.remove('active');
    } else {
        masonryView.style.display = 'none';
        listView.style.display = 'block';
        listBtn.classList.add('active');
        masonryBtn.classList.remove('active');
    }

    // Save preference to localStorage
    localStorage.setItem('board-view-preference', view);
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('board-view-preference') || 'masonry';
    setView(savedView);
});

// Toggle like functionality
async function toggleLike(pinId) {
    @guest
        Swal.fire({
            icon: 'info',
            title: 'Masuk Diperlukan',
            text: 'Anda perlu masuk untuk menyukai pin ini.',
            showCancelButton: true,
            confirmButtonText: 'Masuk',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("login") }}';
            }
        });
        return;
    @endguest

    try {
        const response = await fetch(`/pins/${pinId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (response.ok) {
            // Update like count
            const likeCounter = document.getElementById(`likes-count-${pinId}`);
            if (likeCounter) {
                likeCounter.textContent = data.likes_count;
            }

            // Show success feedback
            if (data.liked) {
                // Create a heart animation
                const heart = document.createElement('div');
                heart.innerHTML = '<i class="fas fa-heart text-red-500 text-2xl"></i>';
                heart.classList.add('fixed', 'z-50', 'animate-bounce');
                heart.style.left = '50%';
                heart.style.top = '50%';
                heart.style.transform = 'translate(-50%, -50%)';
                document.body.appendChild(heart);

                setTimeout(() => {
                    document.body.removeChild(heart);
                }, 1000);
            }
        } else {
            throw new Error('Terjadi kesalahan');
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Terjadi kesalahan saat memproses permintaan.',
            confirmButtonColor: '#2563eb'
        });
    }
}

// Confirm delete board
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus board ini? Semua pin di dalamnya akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.');
}

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

// Infinite scroll (optional enhancement)
let isLoading = false;
window.addEventListener('scroll', function() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000 && !isLoading) {
        const nextPageUrl = document.querySelector('.pagination a[rel="next"]');
        if (nextPageUrl) {
            loadNextPage(nextPageUrl.href);
        }
    }
});

async function loadNextPage(url) {
    isLoading = true;
    try {
        const response = await fetch(url);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Extract pins and append to current view
        const newPins = doc.querySelectorAll('.masonry-item');
        const container = document.querySelector('.masonry');

        newPins.forEach(pin => {
            container.appendChild(pin);
        });

        // Update pagination
        const newPagination = doc.querySelector('.pagination');
        const currentPagination = document.querySelector('.pagination');
        if (currentPagination && newPagination) {
            currentPagination.innerHTML = newPagination.innerHTML;
        }
    } catch (error) {
        console.error('Error loading next page:', error);
    } finally {
        isLoading = false;
    }
}
</script>
@endsection
