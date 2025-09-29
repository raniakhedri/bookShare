@extends('frontoffice.layouts.app')

@section('title', $book->title . ' - Bookly')

@section('content')
@php
    $book->load(['reviews' => function($query) {
        $query->with('user')->active()->latest();
    }]);
@endphp
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <!-- Breadcrumb amélioré -->
        <nav class="mb-8">
            <div class="flex items-center space-x-3 text-sm">
                <a href="{{ route('book') }}" class="flex items-center gap-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Books
                </a>
                <span class="text-[#dbdbd7] dark:text-[#3E3E3A]">/</span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium truncate">{{ Str::limit($book->title, 40) }}</span>
            </div>
        </nav>

        <!-- Carte principale améliorée -->
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] overflow-hidden transition-all duration-500 starting:opacity-0 starting:translate-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 p-6 lg:p-8">
                <!-- Colonne image améliorée -->
                <div class="lg:col-span-1 flex justify-center lg:justify-start">
                    <div class="relative group">
                        @if($book->image)
                            <div class="bg-gradient-to-br from-[#fff2f2] to-[#fdfdfc] dark:from-[#1D0002] dark:to-[#0a0a0a] p-3 rounded-2xl shadow-inner border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <img src="{{ asset('storage/' . $book->image) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-48 h-64 object-cover rounded-xl shadow-lg transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-[#fff2f2] to-[#fdfdfc] dark:from-[#1D0002] dark:to-[#0a0a0a] rounded-2xl p-8 flex items-center justify-center w-48 h-64 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <div class="text-6xl text-[#f53003] dark:text-[#FF4433] opacity-40 transition-opacity group-hover:opacity-60">
                                    📚
                                </div>
                            </div>
                        @endif
                        
                        <!-- Badge de disponibilité -->
                        <div class="absolute -top-2 -right-2">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm {{ $book->availability ? 'bg-green-100/90 text-green-800 dark:bg-green-900/90 dark:text-green-200 border border-green-200 dark:border-green-800' : 'bg-red-100/90 text-red-800 dark:bg-red-900/90 dark:text-red-200 border border-red-200 dark:border-red-800' }}">
                                {{ $book->availability ? '🟢 Available' : '🔴 Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Colonne informations améliorée -->
                <div class="lg:col-span-3">
                    <!-- En-tête -->
                    <div class="mb-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 leading-tight tracking-tight">
                            {{ $book->title }}
                        </h1>
                        <p class="text-xl text-[#f53003] dark:text-[#FF4433] font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0M12 11a3 3 0 100-6 3 3 0 000 6zm-6 8a3 3 0 100-6 3 3 0 000 6z"/>
                            </svg>
                            by {{ $book->author }}
                        </p>
                    </div>

                    <!-- Badges améliorés -->
                    <div class="flex flex-wrap gap-3 mb-6">
                        <span class="inline-flex items-center px-3 py-2 bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] text-sm font-medium rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                            <span class="w-2 h-2 bg-[#f53003] dark:bg-[#FF4433] rounded-full mr-2"></span>
                            {{ $book->category->name ?? 'Uncategorized' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-2 bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] text-sm font-medium rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            {{ $book->publication_year ? date('Y', strtotime($book->publication_year)) : 'Unknown' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-2 bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] text-sm font-medium rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            {{ $book->condition }}
                        </span>
                    </div>

                    <!-- Description améliorée -->
                    <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#f53003] dark:text-[#FF4433]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Description
                        </h3>
                        <div class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed prose prose-gray dark:prose-invert max-w-none">
                            @if($book->description)
                                <p class="text-base">{{ $book->description }}</p>
                            @else
                                <p class="italic text-[#dbdbd7] dark:text-[#3E3E3A]">No description available for this book.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions principales -->
                    <div class="flex flex-col sm:flex-row gap-3">
                       
                        
                        <button class="px-6 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-semibold hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 flex items-center justify-center gap-3 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Add to Favorites
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section PDF améliorée -->
            @if($book->file)
            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-8">
                <div class="p-6 lg:p-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border-2 border-blue-200 dark:border-blue-700 shadow-lg overflow-hidden">
                        <!-- En-tête PDF -->
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm px-6 py-4 border-b border-blue-200 dark:border-blue-700">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">PDF Preview</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Read the book online</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $book->file) }}" 
                                       download 
                                       class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 flex items-center gap-2 shadow hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Lecteur PDF -->
                        <div class="p-4">
                            <div class="rounded-lg overflow-hidden border border-blue-300 dark:border-blue-600 bg-white dark:bg-gray-900 shadow-inner">
                                <iframe src="{{ asset('storage/' . $book->file) }}#toolbar=0&view=fitH" 
                                        width="100%" 
                                        height="600" 
                                        class="border-0"
                                        loading="lazy"
                                        style="min-height: 500px;">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Navigation améliorée -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-3 px-6 py-3 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-medium hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group shadow hover:shadow-lg">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Books List
            </a>
            
            <div class="flex gap-3">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-lg hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share Book
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-lg hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                    More Options
                </button>
            </div>
        </div>
<!-- Reviews Section - Add this to your book_show.blade.php -->
<div class="mt-12">
    <div class="border-t border-gray-200 pt-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Reviews</h3>
                @php
                    $avgRating = $book->reviews()->active()->avg('overall_rating') ?? 0;
                    $reviewCount = $book->reviews()->active()->count();
                @endphp
                @if($reviewCount > 0)
                    <div class="flex items-center mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-gray-600">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                    </div>
                @else
                    <p class="text-gray-600 mt-2">No reviews yet</p>
                @endif
            </div>

            @auth
                <a href="{{ route('reviews.create', $book->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-black font-medium py-2 px-4 rounded-lg transition duration-200">
                    Write Review
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-black font-medium py-2 px-4 rounded-lg transition duration-200">
                    Login to Review
                </a>
            @endauth
        </div>

        @if($reviewCount > 0)
            <!-- Review Summary Stats -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Rating Breakdown -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Rating Breakdown</h4>
                        @for($rating = 5; $rating >= 1; $rating--)
                            @php
                                $ratingCount = $book->reviews()->where('overall_rating', $rating)->count();
                                $percentage = $reviewCount > 0 ? ($ratingCount / $reviewCount) * 100 : 0;
                            @endphp
                            <div class="flex items-center mb-2">
                                <span class="text-sm text-gray-600 w-8">{{ $rating }}</span>
                                <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600 w-8">{{ $ratingCount }}</span>
                            </div>
                        @endfor
                    </div>

                    <!-- Additional Stats -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Review Stats</h4>
                        <div class="space-y-2 text-sm">
                            @php
                                $avgContent = $book->reviews()->whereNotNull('content_rating')->avg('content_rating');
                                $avgCondition = $book->reviews()->whereNotNull('condition_rating')->avg('condition_rating');
                                $recommendationRate = $book->reviews()->where('recommendation_level', '>=', 4)->count() / max($reviewCount, 1) * 100;
                            @endphp
                            @if($avgContent)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Avg Content Rating:</span>
                                    <span class="font-medium">{{ number_format($avgContent, 1) }}/5</span>
                                </div>
                            @endif
                            @if($avgCondition)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Avg Condition:</span>
                                    <span class="font-medium">{{ number_format($avgCondition, 1) }}/5</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Would Recommend:</span>
                                <span class="font-medium">{{ number_format($recommendationRate, 0) }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Most Helpful Review Preview -->
                    @php
                        $topReview = $book->reviews()->with('user')->active()->orderByDesc('helpful_votes')->first();
                    @endphp
                    @if($topReview)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Most Helpful Review</h4>
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex items-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $topReview->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">by {{ $topReview->user?->name ?? 'Anonymous' }}</span>
                                </div>
                                <p class="text-sm text-gray-700 line-clamp-3">
                                    {{ Str::limit($topReview->review_text, 150) }}
                                </p>
                                <div class="mt-2 text-xs text-gray-500">
                                    {{ $topReview->helpful_votes }} found helpful
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900">Recent Reviews</h4>
                    <a href="{{ route('reviews.index', $book->id) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All Reviews
                    </a>
                </div>

                @php
                    $recentReviews = $book->reviews()
                        ->with(['user:id,name', 'interactions'])
                        ->active()
                        ->latest()
                        ->limit(3)
                        ->get();
                @endphp

                @foreach($recentReviews as $review)
                    @include('frontoffice.reviews.partials.review-card', ['review' => $review])
                @endforeach

                @if($reviewCount > 3)
                    <div class="text-center pt-6">
                        <a href="{{ route('reviews.index', $book->id) }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View All {{ $reviewCount }} Reviews
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- No Reviews State -->
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">📝</div>
                <h4 class="text-xl font-semibold text-gray-600 mb-2">No reviews yet</h4>
                <p class="text-gray-500 mb-6">Be the first to share your thoughts about this book!</p>
                @auth
                    <a href="{{ route('reviews.create', $book->id) }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-black bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Write the First Review
                    </a>
                @else
                    <p class="text-gray-500">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Login</a> 
                        to write the first review
                    </p>
                @endauth
            </div>
        @endif
    </div>
</div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .prose {
        max-width: none;
        line-height: 1.7;
    }
    
    .prose p {
        margin-bottom: 1rem;
    }
    
    /* Animation de fondu améliorée */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .starting {
        animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    /* Effets de hover améliorés */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
    
    /* Amélioration du lecteur PDF */
    iframe {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .dark iframe {
        background: #1a1a1a;
    }
    
    /* Ombres portées améliorées */
    .shadow-lg {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Responsive amélioré */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .grid {
            gap: 1.5rem;
        }
        
        .flex-wrap {
            justify-content: center;
        }
    }
    .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush