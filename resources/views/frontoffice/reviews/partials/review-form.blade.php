<!-- Review Form Partial - can be included in both create and edit views -->
<meta name="csrf-token" content="{{ csrf_token() }}"> 
<!-- Overall Rating (Required) -->
<div class="mb-6">
    <label class="block text-lg font-medium text-gray-900 mb-3">
        Overall Rating <span class="text-red-500">*</span>
    </label>
    <div class="flex items-center space-x-2">
        @for($i = 1; $i <= 5; $i++)
            <button type="button" 
                    onclick="setRating('overall_rating', {{ $i }})"
                    class="star-button {{ isset($review) && $i <= old('overall_rating', $review->overall_rating) ? 'text-yellow-400' : ($i <= old('overall_rating', 0) ? 'text-yellow-400' : 'text-gray-300') }} hover:text-yellow-400 transition duration-200"
                    data-rating="{{ $i }}"
                    data-field="overall_rating">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </button>
        @endfor
        <span id="overall_rating_text" class="ml-3 text-gray-600 font-medium">
            @if(isset($review) && $review->overall_rating)
                @php
                    $ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                @endphp
                {{ $ratingTexts[$review->overall_rating] ?? 'Click to rate' }}
            @else
                Click to rate
            @endif
        </span>
    </div>
    <input type="hidden" name="overall_rating" id="overall_rating" value="{{ old('overall_rating', $review->overall_rating ?? '') }}">
    @error('overall_rating')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Additional Ratings -->
<div class="grid md:grid-cols-3 gap-6 mb-6">
    <!-- Content Rating -->
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-2">Content Quality</label>
        <div class="flex items-center space-x-1">
            @for($i = 1; $i <= 5; $i++)
                <button type="button" 
                        onclick="setRating('content_rating', {{ $i }})"
                        class="star-button {{ isset($review) && $i <= old('content_rating', $review->content_rating) ? 'text-yellow-400' : ($i <= old('content_rating', 0) ? 'text-yellow-400' : 'text-gray-300') }} hover:text-yellow-400 transition duration-200"
                        data-rating="{{ $i }}"
                        data-field="content_rating">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            @endfor
        </div>
        <input type="hidden" name="content_rating" id="content_rating" value="{{ old('content_rating', $review->content_rating ?? '') }}">
    </div>

    <!-- Condition Rating -->
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-2">Book Condition</label>
        <div class="flex items-center space-x-1">
            @for($i = 1; $i <= 5; $i++)
                <button type="button" 
                        onclick="setRating('condition_rating', {{ $i }})"
                        class="star-button {{ isset($review) && $i <= old('condition_rating', $review->condition_rating) ? 'text-yellow-400' : ($i <= old('condition_rating', 0) ? 'text-yellow-400' : 'text-gray-300') }} hover:text-yellow-400 transition duration-200"
                        data-rating="{{ $i }}"
                        data-field="condition_rating">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            @endfor
        </div>
        <input type="hidden" name="condition_rating" id="condition_rating" value="{{ old('condition_rating', $review->condition_rating ?? '') }}">
        <p class="text-xs text-gray-500 mt-1">Rate the physical condition</p>
    </div>

    <!-- Recommendation Level -->
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-2">Recommendation</label>
        <div class="flex items-center space-x-1">
            @for($i = 1; $i <= 5; $i++)
                <button type="button" 
                        onclick="setRating('recommendation_level', {{ $i }})"
                        class="star-button {{ isset($review) && $i <= old('recommendation_level', $review->recommendation_level) ? 'text-yellow-400' : ($i <= old('recommendation_level', 0) ? 'text-yellow-400' : 'text-gray-300') }} hover:text-yellow-400 transition duration-200"
                        data-rating="{{ $i }}"
                        data-field="recommendation_level">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            @endfor
        </div>
        <input type="hidden" name="recommendation_level" id="recommendation_level" value="{{ old('recommendation_level', $review->recommendation_level ?? '') }}">
        <p class="text-xs text-gray-500 mt-1">Would you recommend?</p>
    </div>
</div>

<!-- Review Title -->
<div class="mb-6">
    <label for="review_title" class="block text-sm font-medium text-gray-900 mb-2">
        Review Title (Optional)
    </label>
    <input type="text" 
           name="review_title" 
           id="review_title"
           value="{{ old('review_title', $review->review_title ?? '') }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
           placeholder="Give your review a title..."
           maxlength="200">
    @error('review_title')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Review Text -->
<div class="mb-6">
    <label for="review_text" class="block text-sm font-medium text-gray-900 mb-2">
        Your Review <span class="text-red-500">*</span>
    </label>
    <textarea name="review_text" 
              id="review_text"
              rows="6"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical"
              placeholder="Share your thoughts about this book..."
              required>{{ old('review_text', $review->review_text ?? '') }}</textarea>
    <div class="mt-2 flex justify-between items-center">
        <span class="text-xs text-gray-500">Minimum 10 characters</span>
        <span class="text-xs text-gray-500" id="charCount">0 / 5000</span>
    </div>
    @error('review_text')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Reading Context -->
<div class="mb-6">
    <label for="reading_context" class="block text-sm font-medium text-gray-900 mb-2">
        Reading Context (Optional)
    </label>
    <input type="text" 
           name="reading_context" 
           id="reading_context"
           value="{{ old('reading_context', $review->reading_context ?? '') }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
           placeholder="e.g., Read as audiobook, Part of book club, Summer reading..."
           maxlength="500">
    <p class="text-xs text-gray-500 mt-1">Share how you read this book</p>
    @error('reading_context')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Spoiler Warning -->
<div class="mb-6">
    <div class="flex items-center">
        <input type="checkbox" 
               name="is_spoiler" 
               id="is_spoiler"
               value="1"
               {{ old('is_spoiler', $review->is_spoiler ?? false) ? 'checked' : '' }}
               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
        <label for="is_spoiler" class="ml-2 block text-sm text-gray-900">
            This review contains spoilers
        </label>
    </div>
</div>

<!-- Content Warnings -->
<div class="mb-6" id="contentWarningsDiv" style="display: {{ old('is_spoiler', $review->is_spoiler ?? false) ? 'block' : 'none' }};">
    <label for="content_warnings" class="block text-sm font-medium text-gray-900 mb-2">
        Content Warnings
    </label>
    <input type="text" 
           name="content_warnings" 
           id="content_warnings"
           value="{{ old('content_warnings', $review->content_warnings ?? '') }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
           placeholder="e.g., Violence, Strong language, Adult themes..."
           maxlength="200">
    <p class="text-xs text-gray-500 mt-1">Help others know what to expect</p>
    @error('content_warnings')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
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

    if (reviewTextarea) {
        reviewTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
    }

    // Toggle content warnings field
    if (spoilerCheckbox) {
        spoilerCheckbox.addEventListener('change', function() {
            if (this.checked) {
                contentWarningsDiv.style.display = 'block';
            } else {
                contentWarningsDiv.style.display = 'none';
                document.getElementById('content_warnings').value = '';
            }
        });
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
        const textElement = document.getElementById('overall_rating_text');
        if (textElement) {
            textElement.textContent = ratingTexts[rating];
        }
    }
}
</script>
@endpush