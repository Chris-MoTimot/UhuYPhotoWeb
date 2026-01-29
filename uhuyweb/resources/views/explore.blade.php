@extends('layouts.app')

@section('title', 'Explore')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Jelajahi Inspirasi</h1>
                <p class="text-gray-600">Temukan ide-ide kreatif dari komunitas kami</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($pins->count() > 0)
            <!-- Pinterest-style Masonry Grid -->
            <div class="pinterest-grid">
                @foreach($pins as $pin)
                    <div class="pinterest-card">
                        <a href="{{ route('pins.show', $pin->id) }}" class="block group">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                                <!-- Pin Image -->
                                <div class="relative overflow-hidden">
                                    @if($pin->image_url && (str_starts_with($pin->image_url, 'http') || file_exists(public_path('storage/' . $pin->image_url))))
                                        <img
                                            src="{{ str_starts_with($pin->image_url, 'http') ? $pin->image_url : asset('storage/' . $pin->image_url) }}"
                                            alt="{{ $pin->title }}"
                                            class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        >
                                    @else
                                        <!-- Placeholder for missing images -->
                                        <div class="w-full aspect-[3/4] bg-gradient-to-br from-blue-100 via-blue-50 to-indigo-100 flex items-center justify-center">
                                            <div class="text-center">
                                                <i class="fas fa-image text-4xl text-blue-300 mb-2"></i>
                                                <p class="text-blue-400 font-medium">{{ $pin->title }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-end justify-between p-4 opacity-0 group-hover:opacity-100">
                                        <!-- Like Button -->
                                        <button
                                            onclick="event.preventDefault(); event.stopPropagation(); toggleLike({{ $pin->id }})"
                                            class="bg-white bg-opacity-90 hover:bg-opacity-100 text-red-500 p-2 rounded-full transition-all duration-200"
                                            id="like-btn-{{ $pin->id }}"
                                        >
                                            <i class="fas fa-heart text-sm"></i>
                                        </button>

                                        <!-- Save Button -->
                                        <button
                                            onclick="event.preventDefault(); event.stopPropagation();"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-medium transition-all duration-200"
                                        >
                                            Simpan
                                        </button>
                                    </div>
                                </div>

                                <!-- Pin Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                        {{ $pin->title }}
                                    </h3>

                                    @if($pin->description)
                                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                            {{ $pin->description }}
                                        </p>
                                    @endif

                                    <!-- User Info -->
                                    <div class="flex items-center space-x-2">
                                        @if($pin->user->profile_picture)
                                            <img
                                                src="{{ asset('storage/' . $pin->user->profile_picture) }}"
                                                alt="{{ $pin->user->name }}"
                                                class="w-6 h-6 rounded-full object-cover"
                                            >
                                        @else
                                            <div class="w-6 h-6 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                        @endif
                                        <span class="text-xs text-gray-500 font-medium">{{ $pin->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            @if($pins->hasPages())
                <div class="text-center mt-12">
                    @if($pins->hasMorePages())
                        <button
                            onclick="loadMorePins()"
                            id="loadMoreBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-medium transition-all duration-200 transform hover:scale-105"
                        >
                            <span id="loadMoreText">Muat Lebih Banyak</span>
                            <i class="fas fa-spinner fa-spin ml-2 hidden" id="loadMoreSpinner"></i>
                        </button>
                    @endif

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $pins->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada pin untuk dijelajahi</h3>
                <p class="text-gray-600 mb-6">Jadilah yang pertama membuat pin dan berbagi inspirasi!</p>
                @auth
                    <a href="{{ route('pins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Pin Pertama
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-all duration-200 inline-flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Bergabung Sekarang
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>

<style>
/* Pinterest-style Masonry Grid */
.pinterest-grid {
    column-count: 5;
    column-gap: 1.5rem;
    column-fill: balance;
}

@media (max-width: 1536px) {
    .pinterest-grid { column-count: 4; }
}

@media (max-width: 1280px) {
    .pinterest-grid { column-count: 3; }
}

@media (max-width: 1024px) {
    .pinterest-grid { column-count: 2; }
}

@media (max-width: 640px) {
    .pinterest-grid {
        column-count: 1;
        column-gap: 1rem;
    }
}

.pinterest-card {
    display: inline-block;
    width: 100%;
    margin-bottom: 1.5rem;
    break-inside: avoid;
    page-break-inside: avoid;
}

.pinterest-card img {
    width: 100%;
    height: auto;
    display: block;
}

/* Line clamp utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Loading animation */
.loading-shimmer {
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e0e0e0 50%,
        #f0f0f0 75%
    );
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Smooth transitions */
.pinterest-card a {
    transition: transform 0.2s ease;
}

.pinterest-card:hover a {
    transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
// Like functionality
async function toggleLike(pinId) {
    try {
        const response = await fetch(`/pins/${pinId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        const likeBtn = document.getElementById(`like-btn-${pinId}`);
        const heartIcon = likeBtn.querySelector('i');

        if (data.liked) {
            heartIcon.classList.remove('far');
            heartIcon.classList.add('fas');
            likeBtn.classList.add('text-red-500');
        } else {
            heartIcon.classList.remove('fas');
            heartIcon.classList.add('far');
            likeBtn.classList.remove('text-red-500');
        }

    } catch (error) {
        console.error('Error toggling like:', error);
    }
}

// Infinite scroll / Load more functionality
let isLoading = false;
let currentPage = 1;
const nextPageUrl = '{{ $pins->nextPageUrl() }}';

async function loadMorePins() {
    if (isLoading || !nextPageUrl) return;

    isLoading = true;
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const loadMoreText = document.getElementById('loadMoreText');
    const loadMoreSpinner = document.getElementById('loadMoreSpinner');

    // Show loading state
    loadMoreText.textContent = 'Memuat...';
    loadMoreSpinner.classList.remove('hidden');
    loadMoreBtn.disabled = true;

    try {
        const response = await fetch(nextPageUrl);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Extract new pins from response
        const newPins = doc.querySelectorAll('.pinterest-card');
        const container = document.querySelector('.pinterest-grid');

        // Append new pins
        newPins.forEach(pin => {
            container.appendChild(pin.cloneNode(true));
        });

        // Update pagination
        currentPage++;
        const newPagination = doc.querySelector('.pagination');
        const currentPagination = document.querySelector('.pagination');
        if (currentPagination && newPagination) {
            currentPagination.innerHTML = newPagination.innerHTML;
        }

        // Hide load more button if no more pages
        if (!doc.querySelector('[rel="next"]')) {
            loadMoreBtn.style.display = 'none';
        }

    } catch (error) {
        console.error('Error loading more pins:', error);
    } finally {
        // Reset loading state
        isLoading = false;
        loadMoreText.textContent = 'Muat Lebih Banyak';
        loadMoreSpinner.classList.add('hidden');
        loadMoreBtn.disabled = false;
    }
}

// Auto-load on scroll (optional)
window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
        loadMorePins();
    }
});

// Image lazy loading enhancement
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[loading="lazy"]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});
</script>
@endsection
