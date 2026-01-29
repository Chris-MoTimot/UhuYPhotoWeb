@extends('layouts.app')

@section('title', $pin->title . ' - Pin Detail')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header with back button -->
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ url()->previous() }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-medium">Kembali</span>
                </a>

                <div class="flex items-center space-x-3">
                    <!-- Share Button -->
                    <button onclick="sharePin()" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all duration-200">
                        <i class="fas fa-share-alt text-lg"></i>
                    </button>

                    <!-- More Options -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all duration-200">
                            <i class="fas fa-ellipsis-h text-lg"></i>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">

                            @if($pin->user_id === auth()->id())
                                <a href="{{ route('pins.edit', $pin->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-edit mr-3 text-gray-400"></i>
                                    Edit Pin
                                </a>
                                <button onclick="deletePin()" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-trash mr-3 text-red-400"></i>
                                    Hapus Pin
                                </button>
                            @else
                                <button class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-flag mr-3 text-gray-400"></i>
                                    Laporkan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">

            <!-- Pin Image -->
            <div class="lg:sticky lg:top-24 h-fit">
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="relative group">
                        @if($pin->image_url && (str_starts_with($pin->image_url, 'http') || file_exists(public_path('storage/' . $pin->image_url))))
                            <img
                                src="{{ str_starts_with($pin->image_url, 'http') ? $pin->image_url : asset('storage/' . $pin->image_url) }}"
                                alt="{{ $pin->title }}"
                                class="w-full h-auto object-cover max-h-[80vh]"
                            >
                        @else
                            <div class="w-full aspect-[4/5] bg-gradient-to-br from-blue-100 via-blue-50 to-indigo-100 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-6xl text-blue-300 mb-4"></i>
                                    <p class="text-blue-400 font-medium text-lg">{{ $pin->title }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Zoom overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <button onclick="openImageModal()" class="bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 px-4 py-2 rounded-full font-medium transition-all duration-200">
                                <i class="fas fa-expand mr-2"></i>
                                Perbesar
                            </button>
                        </div>
                    </div>

                    <!-- Action buttons overlay -->
                    <div class="absolute top-4 right-4 flex flex-col space-y-2">
                        <!-- Like Button -->
                        <button
                            onclick="toggleLike({{ $pin->id }})"
                            id="like-btn-{{ $pin->id }}"
                            class="bg-white hover:bg-gray-50 p-3 rounded-full shadow-lg transition-all duration-200 {{ auth()->check() && auth()->user()->likedPins->contains($pin->id) ? 'text
-red-500' : 'text-gray-600' }}"
                        >
                            <i class="fas fa-heart text-lg"></i>
                        </button>

                        <!-- Save to Board Button -->
                        <button
                            onclick="showSaveBoardModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-all duration-200"
                        >
                            <i class="fas fa-bookmark text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pin Details -->
            <div class="space-y-6">
                <!-- Title and Description -->
                <div class="
bg-white rounded-2xl p-6 shadow-sm">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $pin->title }}</h1>

                    @if($pin->description)
                        <p class="text-gray-700 text-lg leading-relaxed mb-6">{{ $pin->description }}</p>
                    @endif

                    @if($pin->link)
                        <a href="{{ $pin->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            <i
 class="fas fa-external-link-alt mr-2"></i>
                            Kunjungi Sumber
                        </a>
                    @endif
                </div>

                <!-- Creator Info -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if($pin->user->profile_picture)
                                <img
                                    src="{{ asset('storage/' . $pin->user->profile_picture) }}"
                                    alt="{{ $pin->user->name }}"
                                    class="w-12 h-12 rounded-full object-cover"
                                >
                            @else
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                            @endif

                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $pin->user->name }}</h3>
                                @if($pin->user->bio)
                                    <p class="text-sm text-gray-600">{{ $pin->user->bio }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $pin->user->pins()->count() }} pins</p>
                            </div>
                        </div>

                        @if($pin->user_id !== auth()->id())
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-medium transition-all duration-200">
                                Ikuti
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Board Info -->
                @if($pin->board)
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="font-semibold text-gray-900 mb-3">Tersimpan di Board</h3>
                        <a href="{{ route('boards.show', $pin->board->id) }}" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-th-large text-white"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $pin->board->name }}</p>
                                <p class="text-sm text-gray-600">{{ $pin->board->pins()->count() }} pins</p>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Stats -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between text-center">
                        <div>
                            <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-2">
                                <i class="fas fa-heart text-red-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900" id="likes-count">{{ $pin->likes()->count() }}</p>
                            <p class="text-sm text-gray-600">Suka</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-2">
                                <i class="fas fa-eye text-blue-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ rand(50, 500) }}</p>
                            <p class="text-sm text-gray-600">Views</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-2">
                                <i class="fas fa-bookmark text-green-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ rand(10, 100) }}</p>
                            <p class="text-sm text-gray-600">Saves</p>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-comments mr-2 text-blue-500"></i>
                        Komentar ({{ $pin->comments()->count() }})
                    </h3>

                    @auth
                        <!-- Add Comment Form -->
                        <form action="#" method="POST" class="mb-6">
                            @csrf
                            <div class="flex space-x-3">
                                @if(auth()->user()->profile_picture)
                                    <img
                                        src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                        alt="Your avatar"
                                        class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                    >
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <textarea
                                        name="comment"
                                        placeholder="Tulis komentar..."
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                        rows="3"
                                    ></textarea>
                                    <div class="flex justify-end mt-2">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Kirim
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl mb-6">
                            <p class="text-gray-600 mb-3">Masuk untuk memberikan komentar</p>
                            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-medium transition-colors duration-200">
                                Masuk
                            </a>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="space-y-4">
                        @forelse($pin->comments()->with('user')->latest()->get() as $comment)
                            <div class="flex space-x-3">
                                @if($comment->user->profile_picture)
                                    <img
                                        src="{{ asset('storage/' . $comment->user->profile_picture) }}"
                                        alt="{{ $comment->user->name }}"
                                        class="w-10 h-10 rounded-full object-cover flex-shrink-0"
                                    >
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Pins Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Pin Serupa</h2>

            <div class="pinterest-grid">
                @php
                    $relatedPins = \App\Models\Pin::where('id', '!=', $pin->id)
                                                  ->where('user_id', $pin->user_id)
                                                  ->orWhere('board_id', $pin->board_id)
                                                  ->with('user')
                                                  ->latest()
                                                  ->limit(12)
                                                  ->get();
                @endphp

                @foreach($relatedPins as $relatedPin)
                    <div class="pinterest-card">
                        <a href="{{ route('pins.show', $relatedPin->id) }}" class="block group">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                                <div class="relative overflow-hidden">
                                    @if($relatedPin->image_url && (str_starts_with($relatedPin->image_url, 'http') || file_exists(public_path('storage/' . $relatedPin->image_url))))
                                        <img
                                            src="{{ str_starts_with($relatedPin->image_url, 'http') ? $relatedPin->image_url : asset('storage/' . $relatedPin->image_url) }}"
                                            alt="{{ $relatedPin->title }}"
                                            class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="w-full aspect-[3/4] bg-gradient-to-br from-blue-100 via-blue-50 to-indigo-100 flex items-center justify-center">
                                            <div class="text-center">
                                                <i class="fas fa-image text-4xl text-blue-300 mb-2"></i>
                                                <p class="text-blue-400 font-medium">{{ $relatedPin->title }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                        {{ $relatedPin->title }}
                                    </h3>

                                    <div class="flex items-center space-x-2">
                                        @if($relatedPin->user->profile_picture)
                                            <img
                                                src="{{ asset('storage/' . $relatedPin->user->profile_picture) }}"
                                                alt="{{ $relatedPin->user->name }}"
                                                class="w-6 h-6 rounded-full object-cover"
                                            >
                                        @else
                                            <div class="w-6 h-6 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                        @endif
                                        <span class="text-xs text-gray-500 font-medium">{{ $relatedPin->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-screen max-h-screen">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>

        @if($pin->image_url && (str_starts_with($pin->image_url, 'http') || file_exists(public_path('storage/' . $pin->image_url))))
            <img
                src="{{ str_starts_with($pin->image_url, 'http') ? $pin->image_url : asset('storage/' . $pin->image_url) }}"
                alt="{{ $pin->title }}"
                class="max-w-full max-h-full object-contain"
            >
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-2xl text-red-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Pin</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus pin ini? Tindakan ini tidak dapat dibatalkan.</p>

            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors duration-200">
                    Batal
                </button>
                <form action="{{ route('pins.destroy', $pin->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Pinterest-style grid for related pins */
.pinterest-grid {
    column-count: 4;
    column-gap: 1.5rem;
    column-fill: balance;
}

@media (max-width: 1280px) {
    .pinterest-grid { column-count: 3; }
}

@media (max-width: 1024px) {
    .pinterest-grid { column-count: 2; }
}

@media (max-width: 640px) {
    .pinterest-grid { column-count: 1; }
}

.pinterest-card {
    display: inline-block;
    width: 100%;
    margin-bottom: 1.5rem;
    break-inside: avoid;
    page-break-inside: avoid;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

/* Smooth transitions */
.pinterest-card a {
    transition: transform 0.2s ease;
}

.pinterest-card:hover a {
    transform: translateY(-2px);
}
</style>

<script>
// Like functionality
async function toggleLike(pinId) {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

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
        const likesCountElement = document.getElementById('likes-count');

        if (data.liked) {
            likeBtn.classList.add('text-red-500');
            likeBtn.classList.remove('text-gray-600');
        } else {
            likeBtn.classList.remove('text-red-500');
            likeBtn.classList.add('text-gray-600');
        }

        if (likesCountElement) {
            likesCountElement.textContent = data.likes_count;
        }

    } catch (error) {
        console.error('Error toggling like:', error);
    }
}

// Image modal functions
function openImageModal() {
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Delete modal functions
function deletePin() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Share functionality
function sharePin() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $pin->title }}',
            text: '{{ $pin->description }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Show toast
 notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = 'Link berhasil disalin!';
            document.body.appendChild(toast);

            setTimeout(() => {
                document.body.removeChild(toast);
            }, 3000);
        });
    }
}

// Save to board modal
function showSaveBoardModal() {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    // Implementation for save to board functionality
    alert('Fitur simpan ke board akan segera tersedia!');
}

// Close modals with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closeDeleteModal();
    }
});

// Close image modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close delete modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successToast">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('successToast');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(100%)';
                setTimeout(() => toast.remove(), 300
);
            }
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorToast">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('errorToast');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    </script>
@endif
@endsection
