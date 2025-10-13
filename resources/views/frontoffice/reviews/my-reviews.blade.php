@extends('frontoffice.layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Reviews</h1>
                <p class="text-gray-600 mt-2">{{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }} written</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $reviews->total() }}</div>
                    <div class="text-sm text-blue-600">Total Reviews</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    @php
                        $avgRating = $reviews->avg('overall_rating') ?? 0;
                    @endphp
                    <div class="text-2xl font-bold text-green-600">{{ number_format($avgRating, 1) }}</div>
                    <div class="text-sm text-green-600">Avg Rating</div>
                </div>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="bg-white p-4 rounded-lg shadow-sm border mb-6">
            <div class="flex flex-wrap items-center space-x-4">
                <select id="sortSelect" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest First</option>
                    <option value="rating_high">Highest Rating</option>
                    <option value="rating_low">Lowest Rating</option>
                    <option value="most_helpful">Most Helpful</option>
                </select>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Show:</span>
                    <select id="limitSelect" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Reviews Grid -->
        @if($reviews->count() > 0)
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition duration-200">
                        <div class="p-6">
                            <!-- Review Header with Book Info -->
                            <div class="flex items-start space-x-4 mb-4">
                                <img src="{{ $review->book->image ? asset('storage/' . $review->book->image) : asset('images/default-book.png') }}" 
                                     alt="{{ $review->book->title }}" 
                                     class="w-16 h-22 object-cover rounded-lg shadow-sm flex-shrink-0">
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                <a href="{{ route('books.show', $review->book->id) }}" 
                                                   class="hover:text-blue-600 transition duration-200">
                                                    {{ $review->book->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 text-sm">by {{ $review->book->author }}</p>
                                            
                                            <!-- Rating -->
                                            <div class="flex items-center mt-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                                <span class="ml-1 text-sm text-gray-600">{{ $review->overall_rating }}/5</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2">
                                            @if($review->canBeEditedBy(Auth::user()))
                                                <a href="{{ route('reviews.edit', $review->review_id) }}" 
                                                   class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition duration-200">
                                                    Edit
                                                </a>
                                            @endif
                                            <a href="{{ route('reviews.show', $review->review_id) }}" 
                                               class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition duration-200">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Review Title -->
                            @if($review->review_title)
                                <h4 class="font-medium text-gray-900 mb-2">{{ $review->review_title }}</h4>
                            @endif
                            
                            <!-- Review Excerpt -->
                            <div class="text-gray-700 text-sm mb-4">
                                <p class="line-clamp-3">{{ Str::limit($review->review_text, 200) }}</p>
                            </div>
                            
                            <!-- Review Meta -->
                            <div class="flex items-center justify-between text-sm text-gray-500 pt-4 border-t border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <span>{{ $review->created_at->format('M j, Y') }}</span>
                                    @if($review->updated_at->ne($review->created_at))
                                        <span>(edited)</span>
                                    @endif
                                    @if($review->is_spoiler)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Contains Spoilers</span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    @if($review->helpful_votes > 0)
                                        <span>{{ $review->helpful_votes }} helpful</span>
                                    @endif
                                    @if($review->reply_count > 0)
                                        <span>{{ $review->reply_count }} {{ Str::plural('reply', $review->reply_count) }}</span>
                                    @endif
                                    <span>{{ $review->view_count }} {{ Str::plural('view', $review->view_count) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($reviews->hasPages())
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No reviews yet</h3>
                <p class="text-gray-500 mb-6">You haven't written any book reviews yet.</p>
                <a href="{{ route('books.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Browse Books to Review
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sortSelect');
    const limitSelect = document.getElementById('limitSelect');
    
    // Set current values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('sort')) {
        sortSelect.value = urlParams.get('sort');
    }
    if (urlParams.has('limit')) {
        limitSelect.value = urlParams.get('limit');
    }
    
    // Handle filter changes
    [sortSelect, limitSelect].forEach(select => {
        select.addEventListener('change', function() {
            const currentUrl = new URL(window.location);
            const params = new URLSearchParams(currentUrl.search);
            
            if (sortSelect.value) {
                params.set('sort', sortSelect.value);
            } else {
                params.delete('sort');
            }
            
            if (limitSelect.value !== '10') {
                params.set('limit', limitSelect.value);
            } else {
                params.delete('limit');
            }
            
            params.delete('page'); // Reset to first page when filtering
            
            window.location.href = currentUrl.pathname + '?' + params.toString();
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection