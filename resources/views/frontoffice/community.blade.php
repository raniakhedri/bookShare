@extends('frontoffice.layouts.app')

@section('title', 'Community Features - Bookly')

@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <!-- Add this section to your existing community.blade.php -->

<!-- Community Reviews Section -->
<div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Recent Book Reviews</h2>
            <p class="text-gray-600 mt-1">See what our community is reading and reviewing</p>
        </div>
        <a href="{{ url('/reviews') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium">
            View All Reviews
        </a>
    </div>

    @php
        $recentReviews = App\Models\Review::with(['user:id,name', 'book:id,title,author,image'])
            ->active()
            ->latest()
            ->limit(5)
            ->get();
    @endphp

    @if($recentReviews->count() > 0)
        <div class="space-y-6">
            @foreach($recentReviews as $review)
                <div class="flex items-start space-x-4 p-4 bg-gradient-to-br from-[#FDFDFC] to-white dark:from-[#0a0a0a] dark:to-[#161615] rounded-xl shadow border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-lg transition duration-300">
                    <!-- Book Cover -->
                    <div class="flex-shrink-0">
                        <img src="{{ $review->book->image ? asset('storage/' . $review->book->image) : asset('images/default-book.png') }}" 
                             alt="{{ $review->book->title }}" 
                             class="w-14 h-20 object-cover rounded-lg shadow-md border-2 border-[#f53003]">
                    </div>
                    <!-- Review Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-base font-bold text-[#1b1b18] dark:text-[#EDEDEC] truncate">
                                {{ $review->book->title }}
                            </h3>
                            <span class="text-gray-400">•</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <!-- Sentiment Badge -->
                            @php
                                $sentiment = $review->sentiment ?? 'neutral';
                                $sentimentMap = [
                                    'positive' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '😊', 'label' => 'Positive'],
                                    'negative' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '😞', 'label' => 'Negative'],
                                    'neutral'  => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '😐', 'label' => 'Neutral'],
                                ];
                                $s = $sentimentMap[$sentiment];
                            @endphp
                            <span class="ml-2 px-3 py-1 rounded-full text-xs font-semibold {{ $s['bg'] }} {{ $s['text'] }} flex items-center gap-1 shadow">
                                <span>{{ $s['icon'] }}</span> {{ $s['label'] }}
                            </span>
                        </div>
                        <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] line-clamp-2 mb-2">
                            {{ Str::limit($review->review_text, 120) }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            <div class="flex items-center space-x-3">
                                <span>by {{ $review->user->name }}</span>
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($review->helpful_votes > 0)
                                    <span>{{ $review->helpful_votes }} helpful</span>
                                @endif
                                <a href="{{ route('reviews.show', $review) }}" 
                                   class="text-[#f53003] dark:text-[#FF4433] hover:underline font-medium">
                                    Read more
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <div class="text-gray-400 text-4xl mb-4">📚</div>
            <p class="text-gray-500">No reviews yet. Be the first to share your thoughts!</p>
        </div>
    @endif
</div>

<!-- Community Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ App\Models\Review::active()->count() }}
                </h3>
                <p class="text-sm text-gray-600">Total Reviews</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ App\Models\ReviewInteraction::where('interaction_type', 'reply')->count() }}
                </h3>
                <p class="text-sm text-gray-600">Discussions</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    @php
                        $avgRating = App\Models\Review::active()->avg('overall_rating') ?? 0;
                    @endphp
                    {{ number_format($avgRating, 1) }}/5
                </h3>
                <p class="text-sm text-gray-600">Average Rating</p>
            </div>
        </div>
    </div>
</div>

<!-- Top Reviewers -->
<div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Top Reviewers This Month</h2>
    
    @php
        $topReviewers = App\Models\User::withCount(['reviews' => function($query) {
                $query->where('created_at', '>=', now()->startOfMonth());
            }])
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_count')
            ->limit(5)
            ->get();
    @endphp
    
    @if($topReviewers->count() > 0)
        <div class="space-y-3">
            @foreach($topReviewers as $index => $reviewer)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-black font-bold">
                                {{ substr($reviewer->name, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $reviewer->name }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $reviewer->reviews_count }} {{ Str::plural('review', $reviewer->reviews_count) }} this month
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @if($index === 0)
                            <span class="text-yellow-500 text-xl">🏆</span>
                        @elseif($index === 1)
                            <span class="text-gray-400 text-xl">🥈</span>
                        @elseif($index === 2)
                            <span class="text-orange-500 text-xl">🥉</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-4">No reviews this month yet!</p>
    @endif
</div>
        </div>
    </div>
@endsection
