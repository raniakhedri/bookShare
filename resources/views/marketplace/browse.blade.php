@extends('frontoffice.layouts.app')

@section('title', 'Browse Books - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
        <!-- Header Section -->
        <div class="bg-white shadow-lg border-b">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        📚 Browse Books
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Discover amazing books shared by our community members
                    </p>
                </div>
            </div>
        </div>

        <!-- Search and Filters Section -->
        <div class="bg-white border-b shadow-sm">
            <div class="container mx-auto px-4 py-6">
                <form method="GET" action="{{ route('marketplace.browse') }}" class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        <!-- Search Input -->
                        <div class="md:col-span-6">
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                🔍 Search by Title or Author
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search"
                                   value="{{ request('search') }}" 
                                   placeholder="Enter book title or author name..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-colors text-gray-900 bg-white">
                        </div>
                        
                        <!-- Condition Filter -->
                        <div class="md:col-span-3">
                            <label for="condition" class="block text-sm font-semibold text-gray-700 mb-2">
                                📖 Condition
                            </label>
                            <select id="condition" 
                                    name="condition"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-colors text-gray-900 bg-white">
                                <option value="">All conditions</option>
                                <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>New</option>
                                <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair" {{ request('condition') === 'fair' ? 'selected' : '' }}>Fair</option>
                                <option value="poor" {{ request('condition') === 'poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                        </div>
                        
                        <!-- Search Button -->
                        <div class="md:col-span-3">
                            <div class="flex gap-2">
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-primary to-red-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-primary-dark hover:to-red-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Search
                                </button>
                                <a href="{{ route('marketplace.browse') }}" 
                                   class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Info -->
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-wrap items-center justify-between mb-8">
                <div class="flex items-center space-x-2">
                    <div class="bg-white rounded-xl px-4 py-2 shadow-md border">
                        <span class="text-gray-600">Found</span>
                        <span class="font-bold text-primary text-lg">{{ $books->total() }}</span>
                        <span class="text-gray-600">books</span>
                    </div>
                    @if(request()->hasAny(['search', 'condition']))
                        <div class="bg-blue-50 rounded-xl px-4 py-2 border border-blue-200">
                            <span class="text-blue-700 font-medium">
                                Filtered results
                                @if(request('search'))
                                    for "<span class="font-semibold">{{ request('search') }}</span>"
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
                
                @if(request()->hasAny(['search', 'condition']))
                    <a href="{{ route('marketplace.browse') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear Filters
                    </a>
                @endif
            </div>
        </div>

        <!-- Books Grid -->
        <div class="container mx-auto px-4 pb-16">
            @if($books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($books as $book)
                        <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 overflow-hidden">
                            <!-- Book Image -->
                            <div class="relative overflow-hidden h-64">
                                @if($book->image_path)
                                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                                            </svg>
                                            <p class="text-blue-500 font-medium">No Image</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Condition Badge -->
                                <div class="absolute top-4 left-4">
                                    @php
                                        $conditionColors = [
                                            'new' => 'bg-green-500',
                                            'good' => 'bg-blue-500', 
                                            'fair' => 'bg-yellow-500',
                                            'poor' => 'bg-red-500'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold text-white rounded-full {{ $conditionColors[strtolower($book->condition)] ?? 'bg-gray-500' }} shadow-lg">
                                        {{ ucfirst($book->condition) }}
                                    </span>
                                </div>

                                <!-- Availability Status -->
                                <div class="absolute top-4 right-4">
                                    @if($book->is_available ?? true)
                                        <span class="px-2 py-1 text-xs font-bold text-white bg-green-500 rounded-full shadow-lg">
                                            Available
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full shadow-lg">
                                            Reserved
                                        </span>
                                    @endif
                                </div>

                                <!-- Price/Free Badge -->
                                <div class="absolute bottom-4 left-4">
                                    @if(isset($book->price) && $book->price > 0)
                                        <span class="px-3 py-1 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-full shadow-lg">
                                            ${{ number_format($book->price, 2) }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-bold text-white bg-gradient-to-r from-green-500 to-emerald-500 rounded-full shadow-lg">
                                            FREE
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Book Details -->
                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                        {{ $book->title }}
                                    </h3>
                                    <p class="text-gray-600 font-medium mb-2">by {{ $book->author }}</p>
                                    @if(!empty($book->description))
                                        <p class="text-sm text-gray-500 line-clamp-3">{{ $book->description }}</p>
                                    @endif
                                </div>

                                <!-- Owner Info -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-bold">
                                                {{ substr($book->owner->name ?? 'Unknown', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">{{ $book->owner->name ?? 'Unknown Owner' }}</p>
                                            <p class="text-xs text-gray-500">{{ $book->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('marketplace.books.show', $book) }}" 
                                       class="flex-1 text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                                        👁️ View
                                    </a>
                                    
                                    @auth
                                        @if($book->owner_id !== auth()->id())
                                            <a href="{{ route('marketplace.transactions.create', $book) }}" 
                                               class="flex-1 text-center bg-gradient-to-r from-primary to-red-500 text-white py-2 px-4 rounded-xl font-semibold text-sm hover:from-primary-dark hover:to-red-600 transition-all duration-300 transform hover:scale-105">
                                                📚 Request
                                            </a>
                                        @else
                                            <button class="flex-1 bg-gradient-to-r from-gray-400 to-gray-500 text-white py-2 px-4 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                Your Book
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="flex-1 text-center bg-gradient-to-r from-green-500 to-emerald-600 text-white py-2 px-4 rounded-xl font-semibold text-sm hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105">
                                            🔐 Login to Request
                                        </a>
                                    @endauth
                                </div>
                            </div>

                            <!-- Hover Effect Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl"></div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16 flex justify-center">
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        {{ $books->appends(request()->query())->links() }}
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mb-8">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">No books found</h3>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        @if(request()->hasAny(['search', 'condition']))
                            Try adjusting your search criteria or check back later for new books.
                        @else
                            Be the first to share a book with the community!
                        @endif
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @if(request()->hasAny(['search', 'condition']))
                            <a href="{{ route('marketplace.browse') }}" 
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                        
                        @auth
                            <a href="{{ route('marketplace.books.create') }}" 
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-red-500 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Your Book
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                Join Community
                            </a>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection