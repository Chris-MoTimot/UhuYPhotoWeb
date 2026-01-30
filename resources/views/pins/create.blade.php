@extends('layouts.app')

@section('title', 'Buat Pin Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Pin Baru</h1>
        <p class="text-gray-600">Bagikan ide dan inspirasi Anda dengan dunia</p>
    </div>

    <form action="{{ route('pins.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @csrf
        <div class="grid md:grid-cols-2 gap-8 p-8">
            <!-- Left Column - Image Upload -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-image mr-2 text-gray-500"></i>
                        Upload Gambar
                    </label>

                    <!-- Image Upload Area -->
                    <div class="relative">
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="hidden"
                               onchange="previewImage(this)">

                        <label for="image"
                               class="flex flex-col items-center justify-center w-full h-96 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                               id="image-upload-area">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-4"></i>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 5MB</p>
                            </div>

                            <!-- Image Preview -->
                            <img id="image-preview" class="hidden w-full h-full object-cover rounded-xl" alt="Preview">
                        </label>
                    </div>

                    @error('image')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <!-- Image Actions -->
                    <div class="flex items-center space-x-3 mt-4" id="image-actions" style="display: none;">
                        <button type="button"
                                onclick="removeImage()"
                                class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Gambar
                        </button>
                        <button type="button"
                                onclick="document.getElementById('image').click()"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Ganti Gambar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column - Pin Details -->
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2 text-gray-500"></i>
                        Judul Pin <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           placeholder="Berikan judul yang menarik untuk pin Anda"
                           maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 @error('title') border-blue-500 @enderror">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500" id="title-counter">0 / 255 karakter</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-500"></i>
                        Deskripsi
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              placeholder="Ceritakan lebih detail tentang pin ini..."
                              maxlength="1000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 resize-none @error('description') border-blue-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500" id="desc-counter">0 / 1000 karakter</p>
                </div>

                <!-- Board Selection -->
                <div>
                    <label for="board_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-th-large mr-2 text-gray-500"></i>
                        Pilih Board <span class="text-red-500">*</span>
                    </label>
                    <select name="board_id"
                            id="board_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 @error('board_id') border-blue-500 @enderror">
                        <option value="">Pilih board untuk pin ini...</option>
                        @foreach($boards as $board)
                            <option value="{{ $board->id }}" {{ old('board_id') == $board->id ? 'selected' : '' }}>
                                {{ $board->name }}
                                @if($board->is_private)
                                    <i class="fas fa-lock"></i> (Private)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('board_id')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <!-- Create New Board Link -->
                    <div class="mt-2">
                        <a href="{{ route('boards.create') }}"
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Board Baru
                        </a>
                    </div>
                </div>

                <!-- Link (Optional) -->
                <div>
                    <label for="link" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-link mr-2 text-gray-500"></i>
                        Link Sumber (Opsional)
                    </label>
                    <input type="url"
                           name="link"
                           id="link"
                           value="{{ old('link') }}"
                           placeholder="https://example.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 @error('link') border-blue-500 @enderror">
                    @error('link')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Tambahkan link ke sumber asli jika ada</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('pins.my-pins') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Batal
                    </a>

                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                            id="submit-btn">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Pin</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// Image preview functionality
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');
        const actions = document.getElementById('image-actions');

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.style.display = 'none';
            actions.style.display = 'flex';
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Remove image functionality
function removeImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('upload-placeholder');
    const actions = document.getElementById('image-actions');

    input.value = '';
    preview.src = '';
    preview.classList.add('hidden');
    placeholder.style.display = 'flex';
    actions.style.display = 'none';
}

// Character counters
document.getElementById('title').addEventListener('input', function() {
    const counter = document.getElementById('title-counter');
    const count = this.value.length;
    counter.textContent = `${count} / 255 karakter`;

    if (count > 200) {
        counter.classList.add('text-yellow-600');
    }
    if (count > 240) {
        counter.classList.remove('text-yellow-600');
        counter.classList.add('text-red-600');
    }
    if (count <= 200) {
        counter.classList.remove('text-yellow-600', 'text-red-600');
    }
});

document.getElementById('description').addEventListener('input', function() {
    const counter = document.getElementById('desc-counter');
    const count = this.value.length;
    counter.textContent = `${count} / 1000 karakter`;

    if (count > 800) {
        counter.classList.add('text-yellow-600');
    }
    if (count > 950) {
        counter.classList.remove('text-yellow-600');
        counter.classList.add('text-red-600');
    }
    if (count <= 800) {
        counter.classList.remove('text-yellow-600', 'text-red-600');
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const image = document.getElementById('image').files[0];
    const boardId = document.getElementById('board_id').value;
    const submitBtn = document.getElementById('submit-btn');

    if (!title || !image || !boardId) {
        e.preventDefault();

        // Show validation errors
        if (!title) {
            document.getElementById('title').classList.add('border-red-500');
        }
        if (!image) {
            document.getElementById('image-upload-area').classList.add('border-red-500');
        }
        if (!boardId) {
            document.getElementById('board_id').classList.add('border-red-500');
        }

        // Show alert
        Swal.fire({
            icon: 'error',
            title: 'Form Tidak Lengkap',
            text: 'Mohon lengkapi semua field yang wajib diisi.',
            confirmButtonColor: '#2563eb'
        });

        return false;
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Menyimpan...</span>';
});

// Drag and drop functionality
const uploadArea = document.getElementById('image-upload-area');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight(e) {
    uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    if (files.length > 0) {
        document.getElementById('image').files = files;
        previewImage(document.getElementById('image'));
    }
}

// Remove validation errors on input
document.getElementById('title').addEventListener('input', function() {
    this.classList.remove('border-blue-500');
});

document.getElementById('board_id').addEventListener('change', function() {
    this.classList.remove('border-blue-500');
});

document.getElementById('image').addEventListener('change', function() {
    document.getElementById('image-upload-area').classList.remove('border-blue-500');
});
</script>
@endsection
