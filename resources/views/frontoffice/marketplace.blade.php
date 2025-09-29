@extends('frontoffice.layouts.app')

@section('title', 'BookShare Marketplace - Share, Exchange & Discover Books')

@section('content')
    <!-- Hero Section with Quick Actions -->
    <section id="marketplace-hero" class="relative py-20 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 w-20 h-20 bg-primary rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-blue-400 rounded-full animate-bounce"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-purple-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-32 right-1/3 w-8 h-8 bg-pink-400 rounded-full animate-pulse"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-primary font-medium text-sm mb-6">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    {{ $totalBooks }} Book(s) Available for Sharing
                </div>
                <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                    📚 BookShare 
                    <span class="bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">
                        Marketplace
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Connect with fellow book lovers • Share your collection • Discover amazing reads • Build a vibrant reading community
                </p>
            </div>

            @auth
            <!-- Quick Action Cards -->
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-purple-600 rounded-2xl opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200 animate-pulse"></div>
                    <a href="{{ route('marketplace.books.create') }}" 
                       class="relative block p-8 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-primary to-red-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Add Your Book</h3>
                            <p class="text-gray-600 leading-relaxed">Share a book with the community and start connecting with fellow readers</p>
                            <div class="mt-4 text-primary font-semibold group-hover:text-purple-600 transition-colors">
                                Get Started →
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200 animate-pulse"></div>
                    <a href="{{ route('marketplace.browse') }}" 
                       class="relative block p-8 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Browse Books</h3>
                            <p class="text-gray-600 leading-relaxed">Discover amazing books shared by our vibrant community of readers</p>
                            <div class="mt-4 text-blue-600 font-semibold group-hover:text-cyan-600 transition-colors">
                                Explore Now →
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200 animate-pulse"></div>
                    <a href="{{ route('marketplace.my-books') }}" 
                       class="relative block p-8 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">My Library</h3>
                            <p class="text-gray-600 leading-relaxed">Manage your shared books, requests, and reading connections</p>
                            <div class="mt-4 text-green-600 font-semibold group-hover:text-emerald-600 transition-colors">
                                View Library →
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @else
            <!-- Guest CTA -->
            <div class="text-center mb-16">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-purple-600 rounded-3xl opacity-75 animate-pulse"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-12 max-w-3xl mx-auto border border-gray-100">
                        <div class="mb-8">
                            <div class="text-6xl mb-4">📚</div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">Join Our Book Community</h3>
                            <p class="text-xl text-gray-600 mb-8 leading-relaxed">Sign up to share books, make requests, and connect with fellow readers around the world</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center">
                            <a href="{{ route('register') }}" 
                               class="px-10 py-4 bg-gradient-to-r from-primary to-purple-600 text-white rounded-xl hover:shadow-lg transition-all duration-300 font-bold text-lg transform hover:-translate-y-1">
                                🚀 Create Account
                            </a>
                            <a href="{{ route('login') }}" 
                               class="px-10 py-4 border-2 border-primary text-primary rounded-xl hover:bg-primary hover:text-white transition-all duration-300 font-bold text-lg">
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </section>

    <!-- Dynamic Statistics Section -->
    <section class="py-20 bg-white relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <div class="absolute top-20 left-10 w-32 h-32 border-4 border-primary rounded-full"></div>
            <div class="absolute bottom-20 right-10 w-24 h-24 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Community <span class="text-primary">Impact</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    See how our community is growing and sharing knowledge together
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Total Available Books -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                +{{ rand(5, 15) }} today
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ number_format($totalBooks) }}</div>
                        <div class="text-gray-600 font-medium mb-1">Available Books</div>
                        <div class="text-sm text-gray-500">Ready to share & discover</div>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" style="width: {{ min(100, ($totalBooks / 1000) * 100) }}%"></div>
                        </div>
                    </div>
                </div>

                @auth
                <!-- User's Books -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                Your library
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ $userBooks }}</div>
                        <div class="text-gray-600 font-medium mb-1">Your Books</div>
                        <div class="text-sm text-gray-500">In marketplace</div>
                        <a href="{{ route('marketplace.my-books') }}" class="inline-block mt-4 text-green-600 hover:text-emerald-600 font-semibold text-sm transition-colors">
                            Manage →
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                This week
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ $recentBooks->count() }}</div>
                        <div class="text-gray-600 font-medium mb-1">New Additions</div>
                        <div class="text-sm text-gray-500">Fresh books added</div>
                        <a href="{{ route('marketplace.browse') }}" class="inline-block mt-4 text-purple-600 hover:text-pink-600 font-semibold text-sm transition-colors">
                            Explore →
                        </a>
                    </div>
                </div>

                <!-- Community Growth -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-orange-600 bg-orange-100 px-2 py-1 rounded-full">
                                Growing
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 mb-2">Active</div>
                        <div class="text-gray-600 font-medium mb-1">Community</div>
                        <div class="text-sm text-gray-500">Join fellow readers</div>
                        <a href="{{ route('register') }}" class="inline-block mt-4 text-orange-600 hover:text-red-600 font-semibold text-sm transition-colors">
                            Join Us →
                        </a>
                    </div>
                </div>
                @else
                <!-- Join Community -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <a href="{{ route('register') }}" class="relative block bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="text-center">
                            <div class="p-4 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl inline-block mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-2">Join</div>
                            <div class="text-gray-600 font-medium mb-1">Community</div>
                            <div class="text-sm text-gray-500">Start sharing books</div>
                        </div>
                    </a>
                </div>

                <!-- Free Exchange -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="text-center">
                            <div class="p-4 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl inline-block mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-2">Free</div>
                            <div class="text-gray-600 font-medium mb-1">Exchange</div>
                            <div class="text-sm text-gray-500">No cost involved</div>
                        </div>
                    </div>
                </div>

                <!-- Easy Process -->
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                    <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="text-center">
                            <div class="p-4 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl inline-block mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-2">Easy</div>
                            <div class="text-gray-600 font-medium mb-1">Process</div>
                            <div class="text-sm text-gray-500">Simple & quick</div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </section>

    <!-- Recently Added Books -->
    @if($recentBooks->count() > 0)
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-100 to-transparent rounded-full transform translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-purple-100 to-transparent rounded-full transform -translate-x-20 translate-y-20"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-block p-2 bg-white rounded-2xl shadow-lg mb-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Latest <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Arrivals</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Discover fresh additions to our community library. These books were recently shared by fellow readers.
                </p>
            </div>
            
            <!-- Books Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
                @foreach($recentBooks as $book)
                <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 overflow-hidden">
                    <!-- Book Image -->
                    <div class="relative overflow-hidden h-64">
                        @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @elseif($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" 
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

                        <!-- New Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-2 py-1 text-xs font-bold text-white bg-gradient-to-r from-pink-500 to-red-500 rounded-full shadow-lg animate-pulse">
                                NEW
                            </span>
                        </div>

                        <!-- Price Badge if applicable -->
                        @if(isset($book->price) && $book->price > 0)
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-full shadow-lg">
                                ${{ number_format($book->price, 2) }}
                            </span>
                        </div>
                        @else
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 text-sm font-bold text-white bg-gradient-to-r from-green-500 to-emerald-500 rounded-full shadow-lg">
                                FREE
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Book Details -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $book->title }}
                            </h3>
                            <p class="text-gray-600 font-medium mb-1">by {{ $book->author }}</p>
                            @if(!empty($book->description))
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $book->description }}</p>
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
                            
                            <!-- Availability Status -->
                            @if(isset($book->is_available) ? $book->is_available : true)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></div>
                                    Available
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    <div class="w-2 h-2 bg-red-400 rounded-full mr-1"></div>
                                    Reserved
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            @auth
                                @if($book->owner_id !== auth()->id())
                                    <a href="{{ route('marketplace.transactions.create', $book) }}" 
                                       class="flex-1 text-center bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 px-4 rounded-xl font-semibold text-sm hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Request Book
                                    </a>
                                    <button class="bg-white border-2 border-gray-200 text-gray-700 py-2 px-4 rounded-xl font-semibold text-sm hover:border-blue-300 hover:text-blue-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button class="flex-1 bg-gradient-to-r from-gray-400 to-gray-500 text-white py-2 px-4 rounded-xl font-semibold text-sm cursor-not-allowed">
                                        Your Book
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="flex-1 text-center bg-gradient-to-r from-green-500 to-emerald-600 text-white py-2 px-4 rounded-xl font-semibold text-sm hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105">
                                    Login to Request
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Hover Effect Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl"></div>
                </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center">
                <a href="{{ route('marketplace.browse') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <span>Browse All Books</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- How It Works Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How BookShare Works</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Join thousands of readers sharing their favorite books in just three simple steps
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg">1</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Share Your Books</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Upload photos and details of books you'd like to share. Set them as gifts or available for exchange.
                    </p>
                    @auth
                    <a href="{{ route('marketplace.books.create') }}" class="inline-block mt-4 text-primary hover:text-primary-dark font-semibold transition-colors">
                        Add a Book →
                    </a>
                    @endauth
                </div>
                
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg">2</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Discover Amazing Books</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Browse through our collection of shared books. Use filters to find exactly what you're looking for.
                    </p>
                    <a href="{{ route('marketplace.browse') }}" class="inline-block mt-4 text-primary hover:text-primary-dark font-semibold transition-colors">
                        Browse Books →
                    </a>
                </div>
                
                <div class="text-center group">
                    <div class="relative mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg">3</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Connect & Exchange</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Request books as gifts or propose exchanges. Connect with fellow book lovers in your community.
                    </p>
                    @auth
                    <a href="{{ route('marketplace.my-requests') }}" class="inline-block mt-4 text-primary hover:text-primary-dark font-semibold transition-colors">
                        My Requests →
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-primary to-primary-dark">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to Start Your Book Journey?
            </h2>
            <p class="text-xl text-white opacity-90 mb-8 max-w-2xl mx-auto">
                Join our community of book lovers and discover your next favorite read today
            </p>
            @auth
                <a href="{{ route('marketplace.books.create') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-primary rounded-lg hover:bg-gray-100 transition-colors font-bold text-lg shadow-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Your First Book
                </a>
            @else
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-primary rounded-lg hover:bg-gray-100 transition-colors font-bold text-lg shadow-lg">
                    Join BookShare Community
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            @endauth
        </div>
    </section>

@endsection
