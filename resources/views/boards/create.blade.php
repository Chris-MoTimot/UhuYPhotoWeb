@extends('layouts.app')

@section('title', 'Buat Board Baru')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Board Baru</h1>
        <p class="text-gray-600">Organisir pin Anda dengan membuat board baru</p>
    </div>

    <form action="{{ route('boards.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @csrf
        <div class="p-8 space-y-6">
            <!-- Board Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-th-large mr-2 text-gray-500"></i>
                    Nama Board <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       placeholder="Berikan nama untuk board Anda"
                       maxlength="255"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 @error('name') border-blue-500 @enderror">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500" id="name-counter">0 / 255 karakter</p>
            </div>

            <!-- Board Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-gray-500"></i>
                    Deskripsi
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          placeholder="Jelaskan tentang apa board ini..."
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

            <!-- Privacy Setting -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">
                    <i class="fas fa-eye mr-2 text-gray-500"></i>
                    Pengaturan Privasi
                </label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                        <input type="radio"
                               name="is_private"
                               value="0"
                               {{ old('is_private', '0') == '0' ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <i class="fas fa-globe text-green-500 mr-2"></i>
                                <span class="font-medium text-gray-900">Publik</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Semua orang dapat melihat board ini
                            </p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                        <input type="radio"
                               name="is_private"
                               value="1"
                               {{ old('is_private') == '1' ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <i class="fas fa-lock text-gray-500 mr-2"></i>
                                <span class="font-medium text-gray-900">Private</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Hanya Anda yang dapat melihat board ini
                            </p>
                        </div>
                    </label>
                </div>
                @error('is_private')
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Board Preview -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-eye mr-2"></i>
                    Preview Board
                </h3>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-th-large text-gray-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900" id="preview-name">Nama Board</h4>
                            <p class="text-sm text-gray-600 mt-1" id="preview-desc">Deskripsi board akan muncul di sini</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-thumbtack mr-1"></i>
                                <span>0 pin</span>
                                <span class="mx-2">•</span>
                                <i id="preview-privacy-icon" class="fas fa-globe mr-1 text-green-500"></i>
                                <span id="preview-privacy-text">Publik</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('boards.index') }}"
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Batal
                </a>

                <button type="submit"
                        class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-btn">
                    <i class="fas fa-save mr-2"></i>
                    <span>Buat Board</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// Character counters
document.getElementById('name').addEventListener('input', function() {
    const counter = document.getElementById('name-counter');
    const preview = document.getElementById('preview-name');
    const count = this.value.length;

    counter.textContent = `${count} / 255 karakter`;
    preview.textContent = this.value || 'Nama Board';

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
    const preview = document.getElementById('preview-desc');
    const count = this.value.length;

    counter.textContent = `${count} / 1000 karakter`;
    preview.textContent = this.value || 'Deskripsi board akan muncul di sini';

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

// Privacy setting preview
document.querySelectorAll('input[name="is_private"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const privacyIcon = document.getElementById('preview-privacy-icon');
        const privacyText = document.getElementById('preview-privacy-text');

        if (this.value === '1') {
            privacyIcon.className = 'fas fa-lock mr-1 text-gray-500';
            privacyText.textContent = 'Private';
        } else {
            privacyIcon.className = 'fas fa-globe mr-1 text-green-500';
            privacyText.textContent = 'Publik';
        }
    });
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const submitBtn = document.getElementById('submit-btn');

    if (!name) {
        e.preventDefault();

        document.getElementById('name').classList.add('border-red-500');

        Swal.fire({
            icon: 'error',
            title: 'Nama Board Diperlukan',
            text: 'Mohon berikan nama untuk board Anda.',
            confirmButtonColor: '#2563eb'
        });

        return false;
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Membuat Board...</span>';
});

// Remove validation errors on input
document.getElementById('name').addEventListener('input', function() {
    this.classList.remove('border-blue-500');
});

// Initialize counters
document.getElementById('name').dispatchEvent(new Event('input'));
document.getElementById('description').dispatchEvent(new Event('input'));
</script>
@endsection
