@extends('layouts.app')

@section('title', 'Explore')

@section('content')

@if(auth()->check())
    <script>
        window.userBoards = @json(auth()->user()->boards()->withCount('pins')->select('id', 'name')->get());
    </script>
@endif
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
                                            onclick="event.preventDefault(); event.stopPropagation(); showSaveModal({{ $pin->id }}, '{{ addslashes($pin->title) }}', '{{ str_starts_with($pin->image_url, 'http') ? $pin->image_url : asset('storage/' . $pin->image_url) }}')"
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

<!-- Save to Board Modal -->
<div id="saveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[80vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Simpan ke papan</h3>
                <button onclick="closeSaveModal()" class="p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                    <i class="fas fa-times text-gray-500"></i>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <!-- Pin Preview -->
            <div id="pinPreview" class="flex items-center space-x-3 mb-4 p-3 bg-gray-50 rounded-xl">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Search Boards -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input
                        type="text"
                        id="boardSearch"
                        placeholder="Cari papan"
                        class="w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onkeyup="filterBoards()"
                    >
                </div>
            </div>

            <!-- Board List -->
            <div class="space-y-2 max-h-64 overflow-y-auto" id="boardsList">
                <!-- Loading state -->
                <div id="boardsLoading" class="flex items-center justify-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                </div>
            </div>

            <!-- Create New Board -->
            <div class="mt-4 pt-4 border-t border-gray-100">
                <button
                    onclick="showCreateBoard()"
                    class="w-full flex items-center justify-center space-x-2 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-colors duration-200"
                >
                    <i class="fas fa-plus text-sm"></i>
                    <span>Buat papan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Board Modal -->
<div id="createBoardModal" class="fixed inset-0 bg-black bg-opacity-60 z-60 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Buat papan</h3>
                <button onclick="closeCreateBoardModal()" class="p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                    <i class="fas fa-times text-gray-500"></i>
                </button>
            </div>
        </div>

        <!-- Create Board Form -->
        <div class="p-6">
            <form id="createBoardForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama papan</label>
                    <input
                        type="text"
                        id="newBoardName"
                        placeholder="Seperti 'Tempat yang ingin dikunjungi'"
                        class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="isPrivate" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Jadikan papan ini rahasia</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Hanya Anda yang bisa melihat papan rahasia</p>
                </div>

                <div class="flex space-x-3">
                    <button
                        type="button"
                        onclick="closeCreateBoardModal()"
                        class="flex-1 py-3 px-4 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-200"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200"
                    >
                        Buat
                    </button>
                </div>
            </form>
        </div>
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
// Save to Board Modal Functionality
let currentPinId = null;
let currentPinTitle = '';
let currentPinImage = '';
let allBoards = [];

function showSaveModal(pinId, title, imageUrl) {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    // Check if user is still authenticated
    if (!document.querySelector('meta[name="csrf-token"]')) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    currentPinId = pinId;
    currentPinTitle = title;
    currentPinImage = imageUrl;

    // Show modal
    document.getElementById('saveModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Update pin preview
    updatePinPreview();

    // Load boards
    loadUserBoards();
}

function closeSaveModal() {
    document.getElementById('saveModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('boardSearch').value = '';
}

function updatePinPreview() {
    const preview = document.getElementById('pinPreview');
    preview.innerHTML = `
        <img src="${currentPinImage}" alt="${currentPinTitle}" class="w-16 h-16 rounded-xl object-cover">
        <div class="flex-1">
            <p class="font-medium text-gray-900 line-clamp-2">${currentPinTitle}</p>
        </div>
    `;
}

async function loadUserBoards() {
    const boardsList = document.getElementById('boardsList');
    const loading = document.getElementById('boardsLoading');

    console.log('Loading boards...'); // Debug log

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        // Use inline boards data if available
        if (window.userBoards) {
            allBoards = window.userBoards;
            loading.style.display = 'none';
            renderBoards(allBoards);
            console.log('Using inline boards data:', allBoards); // Debug log
            return;
        }

        // Fallback to API call
        const response = await fetch('{{ route("api.boards.select") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status); // Debug log

        if (response.status === 401) {
            // Redirect to login if unauthenticated
            window.location.href = '{{ route("login") }}';
            return;
        }

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }

        const data = await response.json();
        console.log('Boards loaded:', data); // Debug log

        if (data.error) {
            throw new Error(data.message || 'Failed to load boards');
        }

        allBoards = Array.isArray(data) ? data : [];

        loading.style.display = 'none';
        renderBoards(allBoards);

    } catch (error) {
        console.error('Error loading boards:', error);
        loading.style.display = 'none';

        boardsList.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-2xl text-red-400 mb-2"></i>
                <p class="text-red-600 text-sm mb-2">Gagal memuat papan</p>
                <button onclick="loadUserBoards()" class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                    Coba lagi
                </button>
            </div>
        `;
    }
}

function renderBoards(boards) {
    const boardsList = document.getElementById('boardsList');

    if (boards.length === 0) {
        boardsList.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-th-large text-3xl text-gray-300 mb-2"></i>
                <p class="text-gray-500 text-sm">Belum ada papan</p>
                <p class="text-gray-400 text-xs">Buat papan pertama Anda</p>
            </div>
        `;
        return;
    }

    boardsList.innerHTML = boards.map(board => `
        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-colors duration-200" onclick="savePinToBoard(${board.id})">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-th-large text-white"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">${board.name}</p>
                    <p class="text-sm text-gray-500">${board.pins_count || 0} pin</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-gray-400"></i>
        </div>
    `).join('');
}

function filterBoards() {
    const search = document.getElementById('boardSearch').value.toLowerCase();
    const filteredBoards = allBoards.filter(board =>
        board.name.toLowerCase().includes(search)
    );
    renderBoards(filteredBoards);
}

async function savePinToBoard(boardId) {
    console.log('Saving pin', currentPinId, 'to board', boardId); // Debug log

    try {
        const response = await fetch(`/pins/${currentPinId}/save-to-board`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ board_id: boardId })
        });

        console.log('Save response status:', response.status); // Debug log

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Save pin error:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Save pin response:', data); // Debug log

        if (data.success) {
            showToast('Pin berhasil disimpan!', 'success');
            closeSaveModal();
        } else {
            showToast(data.message || 'Gagal menyimpan pin', 'error');
        }

    } catch (error) {
        console.error('Error saving pin:', error);
        showToast('Terjadi kesalahan saat menyimpan', 'error');
    }
}

// Create Board Modal Functions
function showCreateBoard() {
    // Hide save modal first
    document.getElementById('saveModal').classList.add('hidden');
    // Show create board modal
    document.getElementById('createBoardModal').classList.remove('hidden');
}

function closeCreateBoardModal() {
    document.getElementById('createBoardModal').classList.add('hidden');
    document.getElementById('createBoardForm').reset();
    // Show save modal again
    document.getElementById('saveModal').classList.remove('hidden');
}

// Handle create board form submission
document.getElementById('createBoardForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.getElementById('newBoardName').value.trim();
    const isPrivate = document.getElementById('isPrivate').checked;

    if (!name) return;

    try {
        const response = await fetch('{{ route("boards.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                name: name,
                is_private: isPrivate
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Create board error:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Create board response:', data); // Debug log

        if (data.success) {
            showToast('Papan berhasil dibuat!', 'success');

            // Close create board modal
            document.getElementById('createBoardModal').classList.add('hidden');
            document.getElementById('createBoardForm').reset();

            // Show save modal again
            document.getElementById('saveModal').classList.remove('hidden');

            // Add new board to boards list
            if (data.board) {
                allBoards.push({
                    id: data.board.id,
                    name: data.board.name,
                    pins_count: 0
                });
                // Update window.userBoards if it exists
                if (window.userBoards) {
                    window.userBoards.push({
                        id: data.board.id,
                        name: data.board.name,
                        pins_count: 0
                    });
                }
            }

            // Auto save pin to new board
            if (currentPinId && data.board && data.board.id) {
                setTimeout(() => {
                    savePinToBoard(data.board.id);
                }, 300);
            }
        } else {
            showToast(data.message || 'Gagal membuat papan', 'error');
        }

    } catch (error) {
        console.error('Error creating board:', error);
        showToast('Terjadi kesalahan saat membuat papan', 'error');
    }
});

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform translate-y-0 opacity-100 transition-all duration-300`;
    toast.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateY(100%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Close modals with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const createBoardModal = document.getElementById('createBoardModal');
        const saveModal = document.getElementById('saveModal');

        if (!createBoardModal.classList.contains('hidden')) {
            closeCreateBoardModal();
        } else if (!saveModal.classList.contains('hidden')) {
            closeSaveModal();
        }
    }
});

// Close modals when clicking outside
document.getElementById('saveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSaveModal();
    }
});

document.getElementById('createBoardModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateBoardModal();
    }
});

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
