<meta name="csrf-token" content="{{ csrf_token() }}"> 
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1" data-review-id="{{ $review->review_id }}">
    <!-- Review Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-start space-x-4">
            <!-- User Avatar -->
            <div class="flex-shrink-0">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md ring-2 ring-white">
                    {{ substr($review->user->name, 0, 1) }}
                </div>
            </div>
            
            <!-- Review Info -->
            <div class="flex-1">
                <div class="flex items-center flex-wrap gap-2 mb-2">
                    <h3 class="font-bold text-gray-900 text-lg">{{ $review->user->name }}</h3>
@if($review->sentiment)
    <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm animate-pulse
        {{ $review->sentiment == 'positive' ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-black' : ($review->sentiment == 'negative' ? 'bg-gradient-to-r from-red-400 to-rose-500 text-black' : 'bg-gradient-to-r from-gray-400 to-slate-500 text-black') }}">
        ✨ {{ ucfirst($review->sentiment) }}
    </span>
@endif


                    <span class="text-gray-400 font-bold">•</span>
                    <time class="text-sm text-gray-600 font-medium" datetime="{{ $review->created_at->toISOString() }}">
                        ⏰ {{ $review->created_at->diffForHumans() }}
                    </time>
                    @if($review->updated_at->ne($review->created_at))
                        <span class="text-xs text-gray-500 italic">(edited)</span>
                    @endif
                </div>
                
                <!-- Rating Display -->
                <div class="flex items-center space-x-4 mt-3">
                    <!-- Overall Rating -->
                    <div class="flex items-center bg-gradient-to-r from-yellow-50 to-amber-50 px-3 py-1.5 rounded-lg border border-yellow-200">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} drop-shadow-sm" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm font-bold text-gray-800">{{ $review->overall_rating }}/5</span>
                    </div>
                    
                    <!-- Additional Ratings (if provided) -->
                    @if($review->content_rating || $review->condition_rating)
                        <div class="flex items-center space-x-3 text-sm font-medium">
                            @if($review->content_rating)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-md">📚 Content: {{ $review->content_rating }}/5</span>
                            @endif
                            @if($review->condition_rating)
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-md">📖 Condition: {{ $review->condition_rating }}/5</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Review Actions (for owner) -->
        @auth
            @if($review->user_id === Auth::id())
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                        @if($review->canBeEditedBy(Auth::user()))
                            <a href="{{ route('reviews.edit', $review) }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit Review
                            </a>
                        @endif
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this review?')"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endauth
    </div>
    
    <!-- Review Title -->
    @if($review->review_title)
        <h4 class="text-xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">{{ $review->review_title }}</h4>
    @endif
    
    <!-- Spoiler Warning -->
    @if($review->is_spoiler)
        <div class="mb-4 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-yellow-900 font-bold">⚠️ Spoiler Alert!</span>
            </div>
            @if($review->content_warnings)
                <p class="text-yellow-800 text-sm mt-2 font-medium">{{ $review->content_warnings }}</p>
            @endif
        </div>
    @endif
    
    <!-- Review Content -->
    <div class="prose prose-sm max-w-none mb-5">
        <div class="text-gray-700 leading-relaxed text-base bg-gray-50 p-4 rounded-lg border-l-4 border-blue-400">
            {{ $review->review_text }}
        </div>
    </div>
    
    <!-- Reading Context -->
    @if($review->reading_context)
        <div class="mb-4 text-sm">
            <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full font-medium">
                📍 Context: {{ $review->reading_context }}
            </span>
        </div>
    @endif
    
    <!-- Photos -->
    @if($review->photo_urls && count($review->photo_urls) > 0)
        <div class="mb-5">
            <h5 class="text-sm font-semibold text-gray-700 mb-2">📸 Review Photos</h5>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($review->photo_urls as $photo)
                    <img src="{{ asset('storage/' . $photo) }}" 
                         alt="Review photo" 
                         class="w-full h-28 object-cover rounded-lg cursor-pointer hover:opacity-90 hover:scale-105 transition-all duration-200 shadow-md border-2 border-gray-200"
                         onclick="openImageModal('{{ asset('storage/' . $photo) }}')">
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Interaction Buttons -->
    <div class="flex items-center justify-between pt-5 border-t-2 border-gray-100">
        <div class="flex items-center space-x-6">
            @include('frontoffice.reviews.partials.interaction-buttons', ['review' => $review])
        </div>
        
        <!-- View Count -->
        <div class="text-sm text-gray-500 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
            </svg>
            {{ $review->view_count }} {{ Str::plural('view', $review->view_count) }}
        </div>
    </div>
    
    <!-- Quick Discussion Preview -->
    @if($review->reply_count > 0)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <button onclick="toggleDiscussions({{ $review->review_id }})" 
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View {{ $review->reply_count }} {{ Str::plural('reply', $review->reply_count) }}
            </button>
            
            <!-- Discussion Container (hidden by default) -->
            <div id="discussions-{{ $review->review_id }}" class="hidden mt-3">
                <!-- Discussions will be loaded here via AJAX -->
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleDiscussions(reviewId) {
    const container = document.getElementById(`discussions-${reviewId}`);
    
    if (container.classList.contains('hidden')) {
        // Load discussions via AJAX
        fetch(`/reviews/${reviewId}/discussions`)
            .then(response => response.json())
            .then(data => {
                container.innerHTML = renderDiscussions(data);
                container.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error loading discussions:', error);
            });
    } else {
        container.classList.add('hidden');
    }
}

function renderDiscussions(discussions) {
    // This is a simplified version - you can expand this
    let html = '<div class="space-y-3">';
    discussions.forEach(discussion => {
        html += `
            <div class="bg-gray-50 p-3 rounded-lg">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="font-medium text-sm">${discussion.user.name}</span>
                    <span class="text-xs text-gray-500">${new Date(discussion.created_at).toLocaleDateString()}</span>
                </div>
                <p class="text-sm text-gray-700">${discussion.content}</p>
            </div>
        `;
    }); 
    html += '</div>';
    return html;
}

function openImageModal(imageUrl) {
    // Simple image modal - you can enhance this
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="max-w-4xl max-h-full p-4">
            <img src="${imageUrl}" class="max-w-full max-h-full object-contain">
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="absolute top-4 right-4 text-black text-2xl">&times;</button>
        </div>
    `;
    document.body.appendChild(modal);
}
</script>
@endpush