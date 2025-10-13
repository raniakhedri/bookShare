@extends('frontoffice.layouts.app')

@section('title', isset($book) ? "Reviews for {$book->title}" : 'All Reviews')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        @if(isset($book))
            <div class="flex items-center space-x-4 mb-6">
                <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('images/default-book.png') }}" 
                     alt="{{ $book->title }}" 
                     class="w-20 h-28 object-cover rounded-lg shadow-lg">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reviews for "{{ $book->title }}"</h1>
                    <p class="text-gray-600">by {{ $book->author }}</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center">
                            @php
                                $avgRating = $book->reviews()->avg('overall_rating') ?? 0;
                                $reviewCount = $book->reviews()->count();
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-2 text-gray-600">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
            
            @auth
                <div class="mb-6">
                    <a href="{{ route('reviews.create', $book->id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-black font-bold py-2 px-6 rounded-lg transition duration-200">
                        Write a Review
                    </a>
                </div>
            @else
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-blue-800">
                        <a href="{{ route('login') }}" class="font-semibold hover:underline">Login</a> 
                        to write a review for this book.
                    </p>
                </div>
            @endauth
        @else
            <h1 class="text-3xl font-bold text-gray-900 mb-4">All Reviews</h1>
        @endif

        <!-- Filter and Sort Options -->
        <div class="flex flex-wrap items-center justify-between bg-white p-4 rounded-lg shadow-sm border mb-6">
            <div class="flex space-x-4 mb-4 md:mb-0">
                <select id="sortSelect" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="helpful">Most Helpful</option>
                    <option value="recent">Most Recent</option>
                    <option value="rating_high">Highest Rating</option>
                    <option value="rating_low">Lowest Rating</option>
                </select>
                
                <select id="ratingFilter" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4+ Stars</option>
                    <option value="3">3+ Stars</option>
                    <option value="2">2+ Stars</option>
                    <option value="1">1+ Stars</option>
                </select>
            </div>
            
            <div class="text-gray-600">
                Showing {{ $reviews->count() }} of {{ $reviews->total() }} reviews
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div id="reviewsList" class="space-y-6">
        @forelse($reviews as $review)
            @include('reviews.partials.review-card', ['review' => $review])
        @empty
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">📚</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No reviews yet</h3>
                <p class="text-gray-500">
                    @if(isset($book))
                        Be the first to review "{{ $book->title }}"!
                    @else
                        No reviews have been written yet.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="mt-8">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sortSelect');
    const ratingFilter = document.getElementById('ratingFilter');
    
    // Set current values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('sort')) {
        sortSelect.value = urlParams.get('sort');
    }
    if (urlParams.has('min_rating')) {
        ratingFilter.value = urlParams.get('min_rating');
    }
    
    // Handle filter changes
    [sortSelect, ratingFilter].forEach(select => {
        select.addEventListener('change', function() {
            const currentUrl = new URL(window.location);
            const params = new URLSearchParams(currentUrl.search);
            
            if (sortSelect.value) {
                params.set('sort', sortSelect.value);
            } else {
                params.delete('sort');
            }
            
            if (ratingFilter.value) {
                params.set('min_rating', ratingFilter.value);
            } else {
                params.delete('min_rating');
            }
            
            params.delete('page'); // Reset to first page when filtering
            
            window.location.href = currentUrl.pathname + '?' + params.toString();
        });
    });
});
</script>
@endpush
@endsection