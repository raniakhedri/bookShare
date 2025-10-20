@extends('frontoffice.layouts.app')

@section('title', "Review: {$book->title}")

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header - Enhanced -->
        <div class="flex items-center gap-6 mb-10">
            <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('images/default-book.png') }}" 
                 alt="{{ $book->title }}" 
                 class="w-24 h-32 object-cover rounded-2xl shadow-xl border-2 border-[#f53003]">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </span>
                    <h1 class="text-3xl font-extrabold text-[#1b1b18] dark:text-[#EDEDEC]">Write a Review</h1>
                </div>
                <h2 class="text-xl text-[#706f6c] dark:text-[#A1A09A] font-semibold">{{ $book->title }}</h2>
                <p class="text-[#A1A09A] dark:text-[#706f6c]">by {{ $book->author }}</p>
            </div>
        </div>

        <!-- Review Form - Enhanced -->
        <div class="bg-gradient-to-br from-[#FDFDFC] to-white dark:from-[#0a0a0a] dark:to-[#161615] rounded-2xl shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-10">
            <form action="{{ route('reviews.store', $book->id) }}" method="POST" enctype="multipart/form-data" id="reviewForm">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                <!-- Add this error display section -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Add success message display too -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                <!-- Overall Rating (Required) - Enhanced -->
                <div class="mb-8">
                    <label class="block text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 flex items-center gap-2">
                        <span class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-lg flex items-center justify-center shadow">
                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </span>
                        Overall Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    onclick="setRating('overall_rating', {{ $i }})"
                                    class="star-button text-gray-300 hover:text-yellow-400 transition duration-200 scale-100 hover:scale-125"
                                    data-rating="{{ $i }}"
                                    data-field="overall_rating">
                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                        <span id="overall_rating_text" class="ml-3 text-[#706f6c] dark:text-[#A1A09A] font-semibold">Click to rate</span>
                    </div>
                    <input type="hidden" name="overall_rating" id="overall_rating" value="{{ old('overall_rating') }}">
                    @error('overall_rating')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Ratings - Enhanced -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- Content Rating -->
                    <div>
                        <label class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center shadow">
                                <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </span>
                            Content Quality
                        </label>
                        <div class="flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        onclick="setRating('content_rating', {{ $i }})"
                                        class="star-button text-gray-300 hover:text-yellow-400 transition duration-200"
                                        data-rating="{{ $i }}"
                                        data-field="content_rating">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="content_rating" id="content_rating" value="{{ old('content_rating') }}">
                    </div>

                    <!-- Condition Rating -->
                    <div>
                        <label class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center shadow">
                                <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </span>
                            Book Condition
                        </label>
                        <div class="flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        onclick="setRating('condition_rating', {{ $i }})"
                                        class="star-button text-gray-300 hover:text-yellow-400 transition duration-200"
                                        data-rating="{{ $i }}"
                                        data-field="condition_rating">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="condition_rating" id="condition_rating" value="{{ old('condition_rating') }}">
                        <p class="text-xs text-gray-500 mt-1">Rate the physical condition of this book</p>
                    </div>
                </div>

                <!-- Review Title - Enhanced -->
                <div class="mb-8">
                    <label for="review_title" class="block text-sm font-medium text-gray-900 mb-2">
                        Review Title (Optional)
                    </label>
                    <input type="text" 
                           name="review_title" 
                           id="review_title"
                           value="{{ old('review_title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Give your review a title..."
                           maxlength="200">
                    @error('review_title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Review Text - Enhanced -->
                <div class="mb-8">
                    <label for="review_text" class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center gap-2">
                        <span class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow">
                            <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                        Your Review <span class="text-red-500">*</span>
                    </label>
                    <textarea name="review_text" 
                              id="review_text"
                              rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical"
                              placeholder="Share your thoughts about this book..."
                              required>{{ old('review_text') }}</textarea>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-xs text-gray-500">Minimum 10 characters</span>
                        <span class="text-xs text-gray-500" id="charCount">0 / 5000</span>
                    </div>
                    @error('review_text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reading Context - Enhanced -->
                <div class="mb-8">
                    <label for="reading_context" class="block text-sm font-medium text-gray-900 mb-2">
                        Reading Context (Optional)
                    </label>
                    <input type="text" 
                           name="reading_context" 
                           id="reading_context"
                           value="{{ old('reading_context') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Read as audiobook, Part of book club, Summer reading..."
                           maxlength="500">
                    <p class="text-xs text-gray-500 mt-1">Share how you read this book</p>
                    @error('reading_context')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spoiler Warning - Enhanced -->
                <div class="mb-8">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_spoiler"
                               id="is_spoiler"
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               {{ old('is_spoiler') ? 'checked' : '' }}>
                        <label for="is_spoiler" class="ml-2 block text-sm text-gray-900">
                            This review contains spoilers
                        </label>
                    </div>
                </div>

                <!-- Content Warnings - Enhanced -->
                <div class="mb-8" id="contentWarningsDiv" style="display: none;">
                    <label for="content_warnings" class="block text-sm font-medium text-gray-900 mb-2">
                        Content Warnings
                    </label>
                    <input type="text" 
                           name="content_warnings" 
                           id="content_warnings"
                           value="{{ old('content_warnings') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Violence, Strong language, Adult themes..."
                           maxlength="200">
                    <p class="text-xs text-gray-500 mt-1">Help others know what to expect</p>
                    @error('content_warnings')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo Upload - Enhanced -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Photos (Optional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload photos</span>
                                    <input id="photos" name="photos[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each (max 5 photos)</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4"></div>
                    @error('photos')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('photos.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions - Enhanced -->
                <div class="flex items-center justify-between pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <a href="{{ route('books.show', $book->id) }}" 
                       class="group px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-300 dark:from-[#232321] dark:to-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-semibold shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="group px-8 py-3 bg-gradient-to-r from-[#f53003] to-red-600 hover:from-red-600 hover:to-[#f53003] text-black font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        Publish Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewTextarea = document.getElementById('review_text');
    const charCountSpan = document.getElementById('charCount');
    const spoilerCheckbox = document.getElementById('is_spoiler');
    const contentWarningsDiv = document.getElementById('contentWarningsDiv');

    // Character count for review text
    function updateCharCount() {
        const count = reviewTextarea.value.length;
        charCountSpan.textContent = `${count} / 5000`;
        
        if (count < 10) {
            charCountSpan.classList.add('text-red-500');
        } else if (count > 4500) {
            charCountSpan.classList.add('text-yellow-500');
            charCountSpan.classList.remove('text-red-500');
        } else {
            charCountSpan.classList.remove('text-red-500', 'text-yellow-500');
        }
    }

    reviewTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count

    // Toggle content warnings field
    spoilerCheckbox.addEventListener('change', function() {
        if (this.checked) {
            contentWarningsDiv.style.display = 'block';
        } else {
            contentWarningsDiv.style.display = 'none';
            document.getElementById('content_warnings').value = '';
        }
    });

    // Initialize spoiler state
    if (spoilerCheckbox.checked) {
        contentWarningsDiv.style.display = 'block';
    }
});

function setRating(field, rating) {
    // Set the hidden input value
    document.getElementById(field).value = rating;
    
    // Update star display
    const stars = document.querySelectorAll(`[data-field="${field}"]`);
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });

    // Update text for overall rating
    if (field === 'overall_rating') {
        const ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
        document.getElementById('overall_rating_text').textContent = ratingTexts[rating];
    }
}

function previewImages(input) {
    const previewDiv = document.getElementById('imagePreview');
    previewDiv.innerHTML = '';

    if (input.files) {
        Array.from(input.files).slice(0, 5).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <button type="button" onclick="removeImage(${index})" 
                            class="absolute -top-2 -right-2 bg-red-500 text-black rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                        ×
                    </button>
                `;
                previewDiv.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeImage(index) {
    const input = document.getElementById('photos');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    previewImages(input);
}
</script>
@endpush
@endsection