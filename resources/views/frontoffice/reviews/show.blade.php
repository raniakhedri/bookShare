@extends('frontoffice.layouts.app')

@section('title', 'Review: ' . $review->book->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('books.show', $review->book->id) }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to {{ $review->book->title }}
            </a>
        </div>

        <!-- Book Info Header -->
        <div class="flex items-center space-x-4 mb-8 p-6 bg-white rounded-lg shadow-sm border">
            <img src="{{ $review->book->image ? asset('storage/' . $review->book->image) : asset('images/default-book.png') }}" 
                 alt="{{ $review->book->title }}" 
                 class="w-16 h-22 object-cover rounded-lg shadow-md">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $review->book->title }}</h1>
                <p class="text-gray-600">by {{ $review->book->author }}</p>
            </div>
        </div>

        <!-- Review Content -->
        <div class="bg-white rounded-lg shadow-sm border p-8">
            <!-- Review Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $review->user->name }}</h2>
                        <div class="flex items-center space-x-2 mt-1">
                            <time class="text-sm text-gray-500" datetime="{{ $review->created_at->toISOString() }}">
                                {{ $review->created_at->format('F j, Y') }}
                            </time>
                            @if($review->updated_at->ne($review->created_at))
                                <span class="text-xs text-gray-400">(edited {{ $review->updated_at->diffForHumans() }})</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Review Actions -->
                @auth
                    @if($review->user_id === Auth::id())
                        <div class="flex space-x-2">
                            @if($review->canBeEditedBy(Auth::user()))
                                <a href="{{ route('reviews.edit', $review->review_id) }}" 
                                   class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                    Edit
                                </a>
                            @endif
                            <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this review?')"
                                        class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Rating Display -->
            <div class="mb-6">
                <div class="flex items-center space-x-6">
                    <!-- Overall Rating -->
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 mr-2">Overall:</span>
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-lg font-semibold text-gray-700">{{ $review->overall_rating }}/5</span>
                    </div>

                    <!-- Additional Ratings -->
                    @if($review->content_rating || $review->condition_rating)
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            @if($review->content_rating)
                                <span>Content: {{ $review->content_rating }}/5</span>
                            @endif
                            @if($review->condition_rating)
                                <span>Condition: {{ $review->condition_rating }}/5</span>
                            @endif
                            @if($review->recommendation_level)
                                <span>Recommend: {{ $review->recommendation_level }}/5</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Title -->
            @if($review->review_title)
                <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $review->review_title }}</h3>
            @endif

            <!-- Spoiler Warning -->
            @if($review->is_spoiler)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <span class="text-yellow-800 font-medium text-lg">Spoiler Warning</span>
                            @if($review->content_warnings)
                                <p class="text-yellow-700 mt-1">{{ $review->content_warnings }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Review Content -->
            <div class="prose prose-lg max-w-none mb-6">
                <div class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $review->review_text }}</div>
            </div>

            <!-- Reading Context -->
            @if($review->reading_context)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Reading Context:</h4>
                    <p class="text-gray-700">{{ $review->reading_context }}</p>
                </div>
            @endif

            <!-- Photos -->
            @if($review->photo_urls && count($review->photo_urls) > 0)
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Photos</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($review->photo_urls as $photo)
                            <img src="{{ asset('storage/' . $photo) }}" 
                                 alt="Review photo" 
                                 class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-75 transition duration-200"
                                 onclick="openImageModal('{{ asset('storage/' . $photo) }}')">
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Review Stats -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-6">
                    @include('frontoffice.reviews.partials.interaction-buttons', ['review' => $review])
                </div>
                
                <div class="text-sm text-gray-500">
                    {{ $review->view_count }} {{ Str::plural('view', $review->view_count) }}
                </div>
            </div>
        </div>

        <!-- Discussions Section -->
        @if($review->reply_count > 0 || auth()->check())
            <div class="mt-8 bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Discussion ({{ $review->reply_count }} {{ Str::plural('reply', $review->reply_count) }})
                </h3>
                
                <!-- Load discussions here -->
                <div id="discussions-container">
                    <!-- Discussions will be loaded via AJAX or server-side -->
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="max-w-4xl max-h-full p-4">
        <img id="modalImage" src="" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300">&times;</button>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endpush
@endsection