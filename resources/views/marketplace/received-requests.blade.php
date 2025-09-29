@extends('frontoffice.layouts.app')

@section('title', 'Received Requests - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
        <!-- Header Section -->
        <div class="bg-white shadow-lg border-b">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            📥 Received Requests
                        </h1>
                        <p class="text-lg text-gray-600">
                            Requests for your books
                        </p>
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
                        My Books
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
                        Received Requests ({{ $transactions->count() }})
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-3xl font-bold text-yellow-600">
                                    {{ $transactions->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Responded</p>
                                <p class="text-3xl font-bold text-green-600">
                                    {{ $transactions->whereNotNull('responded_at')->count() }}</p>
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
                                <p class="text-sm font-medium text-gray-600">Exchange Requests</p>
                                <p class="text-3xl font-bold text-purple-600">
                                    {{ $transactions->where('type', 'exchange')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requests List -->
                <div class="space-y-6">
                    @foreach($transactions as $transaction)
                        <div
                            class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden">
                            <div class="p-8">
                                <div class="grid lg:grid-cols-12 gap-6 items-start">
                                    <!-- Book Image & Info -->
                                    <div class="lg:col-span-3">
                                        <div class="flex lg:flex-col items-center lg:items-start gap-4">
                                            <div class="flex-shrink-0 w-20 h-28 lg:w-full lg:h-40 relative">
                                                @if($transaction->marketBook->image_path)
                                                    <img src="{{ asset('storage/' . $transaction->marketBook->image_path) }}"
                                                        alt="{{ $transaction->marketBook->title }}"
                                                        class="w-full h-full object-cover rounded-xl shadow-md">
                                                @elseif($transaction->marketBook->image)
                                                    <img src="{{ asset('storage/' . $transaction->marketBook->image) }}"
                                                        alt="{{ $transaction->marketBook->title }}"
                                                        class="w-full h-full object-cover rounded-xl shadow-md">
                                                @else
                                                    <div
                                                        class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
                                                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 lg:flex-none lg:w-full lg:text-center lg:mt-4">
                                                <h3 class="text-lg font-bold text-gray-900 lg:truncate">
                                                    {{ $transaction->marketBook->title }}</h3>
                                                <p class="text-gray-600 font-medium">by {{ $transaction->marketBook->author }}</p>
                                                <div class="mt-2">
                                                    @php
                                                        $conditionColors = [
                                                            'new' => 'bg-green-100 text-green-800',
                                                            'good' => 'bg-blue-100 text-blue-800',
                                                            'fair' => 'bg-yellow-100 text-yellow-800',
                                                            'poor' => 'bg-gray-100 text-gray-800'
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $conditionColors[strtolower($transaction->marketBook->condition)] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($transaction->marketBook->condition) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Request Details -->
                                    <div class="lg:col-span-6">
                                        <div class="space-y-4">
                                            <!-- Requester Info -->
                                            <div class="flex items-center gap-3 mb-4">
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-primary to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ substr($transaction->requester->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900">{{ $transaction->requester->name }}</h4>
                                                    <p class="text-gray-500 text-sm">
                                                        {{ $transaction->created_at->format('M d, Y \a\t g:i A') }}</p>
                                                </div>
                                            </div>

                                            <!-- Request Type & Status -->
                                            <div class="flex flex-wrap gap-3">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                                {{ $transaction->type === 'gift' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                    @if($transaction->type === 'gift')
                                                        🎁 Gift Request
                                                    @else
                                                        🔄 Exchange Request
                                                    @endif
                                                </span>

                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                                {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                        ($transaction->status === 'accepted' ? 'bg-green-100 text-green-800' :
                            ($transaction->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </div>

                                            <!-- Message -->
                                            @if($transaction->message)
                                                <div class="bg-gray-50 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">📝 Message:</h4>
                                                    <p class="text-gray-700 text-sm">{{ $transaction->message }}</p>
                                                </div>
                                            @endif

                                            <!-- Exchange Details -->
                                            @if($transaction->type === 'exchange' && $transaction->exchangeRequest)
                                                <div class="bg-blue-50 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">📚 Offered in Exchange:</h4>
                                                    <p class="text-gray-700 text-sm">
                                                        {{ $transaction->exchangeRequest->offeredMarketBook->title }}
                                                        by {{ $transaction->exchangeRequest->offeredMarketBook->author }}
                                                        <span
                                                            class="inline-block ml-2 px-2 py-1 text-xs bg-blue-200 text-blue-800 rounded">
                                                            {{ $transaction->exchangeRequest->offeredMarketBook->condition }}
                                                        </span>
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Your Response -->
                                            @if($transaction->response_message)
                                                <div class="bg-green-50 rounded-xl p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">✅ Your Response:</h4>
                                                    <p class="text-gray-700 text-sm">{{ $transaction->response_message }}</p>
                                                    <p class="text-gray-500 text-xs mt-2">Responded
                                                        {{ $transaction->responded_at->diffForHumans() }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="lg:col-span-3">
                                        @if($transaction->status === 'pending')
                                            <div class="space-y-3">
                                                <h4 class="font-semibold text-gray-900 text-sm mb-4">Response Required</h4>

                                                <!-- Accept/Reject Form -->
                                                <form action="{{ route('marketplace.transactions.respond', $transaction) }}"
                                                    method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PATCH')

                                                    <textarea name="response_message" rows="3"
                                                        placeholder="Optional response message..."
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-primary focus:outline-none"></textarea>

                                                    <div class="flex gap-2">
                                                        <button type="submit" name="status" value="accepted"
                                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                                            ✅ Accept
                                                        </button>
                                                        <button type="submit" name="status" value="rejected"
                                                            class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors">
                                                            ❌ Decline
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                @if($transaction->status === 'accepted')
                                                    <div
                                                        class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-xl font-medium">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Accepted
                                                    </div>
                                                @else
                                                    <div
                                                        class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-xl font-medium">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Declined
                                                    </div>
                                                @endif

                                                @if($transaction->responded_at)
                                                    <p class="text-gray-500 text-xs mt-2">
                                                        {{ $transaction->responded_at->diffForHumans() }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
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
                    <div
                        class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mb-8">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">No requests received yet</h3>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        When someone requests one of your books, it will appear here.
                    </p>
                    <a href="{{ route('marketplace.books.create') }}"
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary to-red-500 text-white font-semibold text-lg rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Books to Get Started
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection