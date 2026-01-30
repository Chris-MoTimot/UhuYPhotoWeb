@extends('layouts.app')

@section('title', 'Board Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Board Saya</h1>
            <p class="text-gray-600 mt-2">{{ $boards->total() }} board untuk mengorganisir pin Anda</p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Create Board Button -->
            <a href="{{ route('boards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Buat Board Baru
            </a>
        </div>
    </div>

    <!-- Empty State -->
    @if($boards->count() == 0)
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <i class="fas fa-th-large text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada board</h3>
                <p class="text-gray-500 mb-6">Buat board untuk mengorganisir pin-pin Anda dengan lebih baik!</p>
                <a href="{{ route('boards.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Board Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Boards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($boards as $board)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
                    <!-- Board Cover -->
                    <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                        @if($board->pins->count() > 0)
                            <!-- Show preview of pins -->
                            <div class="grid grid-cols-2 gap-1 h-full p-2">
                                @foreach($board->pins()->latest()->limit(4)->get() as $index => $pin)
                                    @if($pin->image_url)
                                        <img src="{{ asset('storage/' . $pin->image_url) }}"
                                             alt="{{ $pin->title }}"
                                             class="w-full h-full object-cover rounded-lg {{ $index >= 2 ? 'hidden' : '' }}">
                                    @endif
                                @endforeach

                                @if($board->pins->count() == 1)
                                    <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Empty board placeholder -->
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <i class="fas fa-th-large text-gray-300 text-4xl mb-2"></i>
                                    <p class="text-gray-400 text-sm">Board Kosong</p>
                                </div>
                            </div>
                        @endif

                        <!-- Overlay Actions -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex space-x-2">
                                <a href="{{ route('boards.show', $board) }}" class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 rounded-full font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat
                                </a>
                                <a href="{{ route('boards.edit', $board) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                            </div>
                        </div>

                        <!-- Privacy Badge -->
                        @if($board->is_private)
                            <div class="absolute top-3 left-3">
                                <span class="bg-gray-800 bg-opacity-75 text-white px-2 py-1 rounded-full text-xs flex items-center">
                                    <i class="fas fa-lock mr-1"></i>
                                    Private
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Board Content -->
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">
                                    {{ $board->name }}
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $board->pins_count }} pin{{ $board->pins_count > 1 ? 's' : '' }}
                                </p>
                            </div>

                            <!-- Board Actions Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200">
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
                                    <a href="{{ route('boards.show', $board) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-eye mr-2"></i>
                                        Lihat Board
                                    </a>
                                    <a href="{{ route('boards.edit', $board) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit Board
                                    </a>
                                    <hr class="my-1">
                                    <form action="{{ route('boards.destroy', $board) }}" method="POST" class="block" onsubmit="return confirmDelete('{{ $board->name }}', {{ $board->pins_count }})">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                            <i class="fas fa-trash mr-2"></i>
                                            Hapus Board
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if($board->description)
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                {{ Str::limit($board->description, 80) }}
                            </p>
                        @endif

                        <!-- Board Meta Info -->
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                <span>Dibuat {{ $board->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center">
                                @if($board->is_private)
                                    <i class="fas fa-lock mr-1 text-gray-400"></i>
                                @else
                                    <i class="fas fa-globe mr-1 text-green-500"></i>
                                @endif
                                <span>{{ $board->is_private ? 'Private' : 'Publik' }}</span>
                            </div>
                        </div>

                        <!-- Quick Action -->
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('boards.show', $board) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center w-full py-2 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                Lihat Semua Pin →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $boards->links() }}
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

// Confirm delete function
function confirmDelete(boardName, pinsCount) {
    if (pinsCount > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Board Tidak Bisa Dihapus',
            text: `Board "${boardName}" memiliki ${pinsCount} pin. Hapus semua pin terlebih dahulu sebelum menghapus board.`,
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Mengerti'
        });
        return false;
    } else {
        return confirm(`Apakah Anda yakin ingin menghapus board "${boardName}"? Tindakan ini tidak dapat dibatalkan.`);
    }
}
</script>
@endsection
