@extends('frontoffice.layouts.app')

@section('title', 'My Books - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
        <!-- Header Section -->
        <div class="bg-white shadow-lg border-b">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            📚 My Library
                        </h1>
                        <p class="text-lg text-gray-600">
                            Manage your books, requests, and reading connections
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('marketplace.books.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Book
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mini Navigation Tabs -->
        <div class="bg-white border-b sticky top-0 z-40">
            <div class="container mx-auto px-4">
                <nav class="flex space-x-8 overflow-x-auto">
                    <a href="{{ route('marketplace.books.index') }}"
                        class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.books.index') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                            </path>
                        </svg>
                        My Books ({{ $books->count() }})
                    </a>
                    <a href="{{ route('marketplace.my-requests') }}"
                        class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.my-requests') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        My Requests
                    </a>
                    <a href="{{ route('marketplace.received-requests') }}"
                        class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.received-requests') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        Received Requests
                    </a>
                    <a href="{{ route('marketplace.browse') }}"
                        class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.browse') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse All Books
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-12">
            @if($books->count() > 0)
                <!-- Statistics Cards -->
                <div class="grid md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Books</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $books->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Available</p>
                                <p class="text-3xl font-bold text-green-600">{{ $books->where('is_available', true)->count() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Unavailable</p>
                                <p class="text-3xl font-bold text-gray-500">{{ $books->where('is_available', false)->count() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">New Condition</p>
                                <p class="text-3xl font-bold text-purple-600">{{ $books->where('condition', 'New')->count() }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Books Grid -->
                <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
                    @foreach($books as $book)
                        <div
                            class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                            <!-- Book Image -->
                            <div class="relative aspect-[3/4] overflow-hidden">
                                @if($book->image_path)
                                    <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @elseif($book->image)
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Condition Badge -->
                                <div class="absolute top-4 left-4">
                                    @php
                                        $conditionColors = [
                                            'new' => 'bg-green-500',
                                            'good' => 'bg-blue-500',
                                            'fair' => 'bg-yellow-500',
                                            'poor' => 'bg-gray-500'
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-xs font-bold text-white rounded-full {{ $conditionColors[strtolower($book->condition)] ?? 'bg-gray-500' }} shadow-lg">
                                        {{ ucfirst($book->condition) }}
                                    </span>
                                </div>

                                <!-- Availability Badge -->
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 text-xs font-bold text-white rounded-full {{ $book->is_available ? 'bg-green-500' : 'bg-red-500' }} shadow-lg">
                                        {{ $book->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>

                                <!-- Action Buttons Overlay -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="flex gap-2">
                                        <a href="{{ route('marketplace.books.edit', $book) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-xl shadow-lg transition-colors"
                                            title="Edit Book">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('marketplace.books.toggle-availability', $book) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="{{ $book->is_available ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white p-3 rounded-xl shadow-lg transition-colors"
                                                title="{{ $book->is_available ? 'Mark Unavailable' : 'Mark Available' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($book->is_available)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                        </path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('marketplace.books.destroy', $book) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this book?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-xl shadow-lg transition-colors"
                                                title="Delete Book">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Book Details -->
                            <div class="p-6">
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-gray-600 mb-3 font-medium">
                                    by {{ $book->author }}
                                </p>

                                @if($book->description)
                                    <p class="text-gray-500 text-sm mb-4 line-clamp-3">
                                        {{ Str::limit($book->description, 100) }}
                                    </p>
                                @endif

                                @if($book->price)
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-primary">${{ number_format($book->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">Reference Price</span>
                                    </div>
                                @endif

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <span>Added {{ $book->created_at->diffForHumans() }}</span>
                                        <span>{{ $book->requests_count ?? 0 }} requests</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $books->links() }}
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div
                        class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mb-8">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">No Books Yet</h3>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Start building your library! Add your first book and connect with fellow readers in the community.
                    </p>
                    <a href="{{ route('marketplace.books.create') }}"
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-red-500 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Your First Book
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection