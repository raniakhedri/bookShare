@extends('frontoffice.layouts.app')

@section('title', 'My Requests - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
        <!-- Header Section -->
        <div class="bg-white shadow-lg border-b">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            💬 My Requests
                        </h1>
                        <p class="text-lg text-gray-600">
                            Books you've requested from others
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('marketplace.browse') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Browse More Books
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                        </svg>
                        My Books
                    </a>
                    <a href="{{ route('marketplace.my-requests') }}" 
                       class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.my-requests') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        My Requests ({{ $transactions->count() }})
                    </a>
                    <a href="{{ route('marketplace.received-requests') }}" 
                       class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.received-requests') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        Received Requests
                    </a>
                    <a href="{{ route('marketplace.browse') }}" 
                       class="inline-flex items-center px-4 py-4 border-b-2 {{ request()->routeIs('marketplace.browse') ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse All Books
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-12">
            @if($transactions->count() > 0)
                <!-- Statistics Cards -->
                <div class="grid md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Requests</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $transactions->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ $transactions->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Accepted</p>
                                <p class="text-3xl font-bold text-green-600">{{ $transactions->where('status', 'accepted')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Gift Requests</p>
                                <p class="text-3xl font-bold text-purple-600">{{ $transactions->where('type', 'gift')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requests List -->
                <div class="space-y-6">
                    @foreach($transactions as $transaction)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden">
                            <div class="p-8">
                                <div class="grid lg:grid-cols-4 gap-6 items-center">
                                    <!-- Book Image & Info -->
                                    <div class="lg:col-span-1">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0 w-20 h-28 relative">
                                                @if($transaction->marketBook->image_path)
                                                    <img src="{{ asset('storage/' . $transaction->marketBook->image_path) }}" 
                                                         alt="{{ $transaction->marketBook->title }}" 
                                                         class="w-full h-full object-cover rounded-xl shadow-md">
                                                @elseif($transaction->marketBook->image)
                                                    <img src="{{ asset('storage/' . $transaction->marketBook->image) }}" 
                                                         alt="{{ $transaction->marketBook->title }}" 
                                                         class="w-full h-full object-cover rounded-xl shadow-md">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $transaction->marketBook->title }}</h3>
                                                <p class="text-gray-600 font-medium">by {{ $transaction->marketBook->author }}</p>
                                                <p class="text-sm text-gray-500 mt-1">Owner: {{ $transaction->marketBook->owner->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Request Details -->
                                    <div class="lg:col-span-2">
                                        <div class="space-y-4">
                                            <!-- Request Type & Status -->
                                            <div class="flex flex-wrap gap-3">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    {{ $transaction->type === 'gift' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                    @if($transaction->type === 'gift')
                                                        🎁 Gift Request
                                                    @else
                                                        🔄 Exchange Request
                                                    @endif
                                                </span>

                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($transaction->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                                       ($transaction->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </div>

                                            <!-- Message -->
                                            @if($transaction->message)
                                                <div class="bg-gray-50 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Your Message:</h4>
                                                    <p class="text-gray-700 text-sm">{{ $transaction->message }}</p>
                                                </div>
                                            @endif

                                            <!-- Exchange Details -->
                                            @if($transaction->type === 'exchange' && $transaction->exchangeRequest)
                                                <div class="bg-blue-50 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">📚 Your Offered Book:</h4>
                                                    <p class="text-gray-700 text-sm">
                                                        {{ $transaction->exchangeRequest->offeredMarketBook->title }} 
                                                        by {{ $transaction->exchangeRequest->offeredMarketBook->author }}
                                                        ({{ $transaction->exchangeRequest->offeredMarketBook->condition }})
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Response -->
                                            @if($transaction->response_message)
                                                <div class="bg-green-50 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Owner's Response:</h4>
                                                    <p class="text-gray-700 text-sm">{{ $transaction->response_message }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions & Date -->
                                    <div class="lg:col-span-1 text-right">
                                        <div class="space-y-4">
                                            <div class="text-sm text-gray-500">
                                                <p>Sent {{ $transaction->created_at->diffForHumans() }}</p>
                                                @if($transaction->responded_at)
                                                    <p>Responded {{ $transaction->responded_at->diffForHumans() }}</p>
                                                @endif
                                            </div>

                                            @if($transaction->status === 'accepted')
                                                <div class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-medium rounded-xl">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Accepted!
                                                </div>
                                            @elseif($transaction->status === 'rejected')
                                                <div class="inline-flex items-center px-4 py-2 bg-red-500 text-white font-medium rounded-xl">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Declined
                                                </div>
                                            @else
                                                <div class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white font-medium rounded-xl">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Pending
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($transactions, 'links'))
                    <div class="mt-12">
                        {{ $transactions->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mb-8">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">You haven't made any requests yet</h3>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Browse available books and start making requests for books you'd like to read.
                    </p>
                    <a href="{{ route('marketplace.browse') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-red-500 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse Available Books
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection