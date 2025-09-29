@extends('frontoffice.layouts.app')

@section('title', $book->title . ' - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    📖 Book Details
                </h1>
                <p class="text-lg text-gray-600">
                    Everything you need to know about this book
                </p>
            </div>

            <!-- Book Details -->
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="grid lg:grid-cols-2 gap-0">
                    <!-- Book Image -->
                    <div class="relative aspect-[4/5] lg:aspect-auto">
                        @if($book->image_path)
                            <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->title }}"
                                class="w-full h-full object-cover">
                        @elseif($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                    </path>
                                </svg>
                            </div>
                        @endif

                        <!-- Badges Overlay -->
                        <div class="absolute top-6 left-6 flex flex-col gap-3">
                            <!-- Condition Badge -->
                            @php
                                $conditionColors = [
                                    'new' => 'bg-green-500',
                                    'good' => 'bg-blue-500',
                                    'fair' => 'bg-yellow-500',
                                    'poor' => 'bg-gray-500'
                                ];
                            @endphp
                            <span
                                class="px-4 py-2 text-sm font-bold text-white rounded-full {{ $conditionColors[strtolower($book->condition)] ?? 'bg-gray-500' }} shadow-lg">
                                {{ ucfirst($book->condition) }} Condition
                            </span>

                            <!-- Availability Badge -->
                            <span
                                class="px-4 py-2 text-sm font-bold text-white rounded-full {{ $book->is_available ? 'bg-green-500' : 'bg-red-500' }} shadow-lg">
                                {{ $book->is_available ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>

                    <!-- Book Information -->
                    <div class="p-8 lg:p-12">
                        <!-- Title and Author -->
                        <div class="mb-8">
                            <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                                {{ $book->title }}
                            </h1>
                            <p class="text-2xl text-gray-600 font-medium mb-6">
                                by {{ $book->author }}
                            </p>
                        </div>

                        <!-- Owner Information -->
                        <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-primary to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    {{ substr($book->owner->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $book->owner->name }}</h3>
                                    <p class="text-gray-600">Book Owner</p>
                                </div>
                            </div>
                        </div>

                        <!-- Price (if available) -->
                        @if($book->price)
                            <div class="bg-gradient-to-r from-primary/10 to-purple-600/10 rounded-2xl p-6 mb-8">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">Reference Price</p>
                                        <p class="text-3xl font-bold text-primary">${{ number_format($book->price, 2) }}</p>
                                    </div>
                                    <div class="text-primary">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Description -->
                        @if($book->description)
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Description</h3>
                                <div class="prose prose-gray max-w-none">
                                    <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Book Details -->
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-2xl p-6">
                                <h4 class="font-bold text-gray-900 mb-3">📅 Book Details</h4>
                                <div class="space-y-2 text-gray-600">
                                    <p><span class="font-medium">Added:</span> {{ $book->created_at->format('M d, Y') }}</p>
                                    <p><span class="font-medium">Last Updated:</span>
                                        {{ $book->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-2xl p-6">
                                <h4 class="font-bold text-gray-900 mb-3">📊 Request Stats</h4>
                                <div class="space-y-2 text-gray-600">
                                    <p><span class="font-medium">Total Requests:</span> {{ $book->transactions->count() }}
                                    </p>
                                    <p><span class="font-medium">Pending:</span>
                                        {{ $book->transactions->where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                @if($book->owner_id === Auth::id())
                                    <!-- Owner Actions -->
                                    <a href="{{ route('marketplace.books.edit', $book) }}"
                                        class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit Book
                                    </a>

                                    <form action="{{ route('marketplace.books.toggle-availability', $book) }}" method="POST"
                                        class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-6 py-4 {{ $book->is_available ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-green-500 to-green-600' }} text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($book->is_available)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                    </path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @endif
                                            </svg>
                                            {{ $book->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                                        </button>
                                    </form>
                                @else
                                    <!-- Request Actions -->
                                    @if($book->is_available)
                                        <a href="{{ route('marketplace.transactions.create', $book) }}"
                                            class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary to-red-500 text-white font-semibold text-lg rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                </path>
                                            </svg>
                                            Request This Book
                                        </a>
                                    @else
                                        <div
                                            class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gray-400 text-white font-semibold text-lg rounded-xl cursor-not-allowed">
                                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                </path>
                                            </svg>
                                            Not Available
                                        </div>
                                    @endif
                                @endif
                            @else
                                <!-- Guest User -->
                                <a href="{{ route('login') }}"
                                    class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary to-red-500 text-white font-semibold text-lg rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Login to Request Book
                                </a>
                            @endauth

                            <a href="{{ route('marketplace.browse') }}"
                                class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 text-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Browse
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests (if owner) -->
            @if(Auth::check() && $book->owner_id === Auth::id() && $book->transactions->count() > 0)
                <div class="mt-12 bg-white rounded-3xl shadow-2xl border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">📬 Recent Requests</h2>
                    <div class="space-y-4">
                        @foreach($book->transactions->take(5) as $transaction)
                                <div class="bg-gray-50 rounded-2xl p-6 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-primary to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($transaction->requester->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $transaction->requester->name }}</h3>
                                            <p class="text-gray-600 text-sm">
                                                {{ $transaction->type === 'gift' ? '🎁 Gift Request' : '🔄 Exchange Request' }}</p>
                                            <p class="text-gray-500 text-sm">{{ $transaction->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full
                                                        {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                            ($transaction->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                        @if($transaction->status === 'pending')
                                            <a href="{{ route('marketplace.transactions.show', $transaction) }}"
                                                class="text-primary hover:text-primary-dark font-medium text-sm">
                                                Respond →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                        @endforeach
                    </div>

                    @if($book->transactions->count() > 5)
                        <div class="mt-6 text-center">
                            <a href="{{ route('marketplace.received-requests') }}"
                                class="text-primary hover:text-primary-dark font-medium">
                                View All {{ $book->transactions->count() }} Requests →
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection