@extends('frontoffice.layouts.app')

@section('title', 'Request Book - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Request Book
                </h1>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                    Send a request to the book owner
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Book Information -->
                <div class="bg-white dark:bg-[#161615] rounded-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-6">
                    <h2 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Book Details</h2>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($book->image_path)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->title }}" class="w-32 h-40 object-cover rounded-sm shadow-md">
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $book->title }}</h3>
                            <p class="text-[#706f6c] dark:text-[#A1A09A] mb-3">by {{ $book->author }}</p>
                            
                            @if($book->isbn)
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-2">ISBN: {{ $book->isbn }}</p>
                            @endif
                            
                            @if($book->description)
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-3">{{ $book->description }}</p>
                            @endif
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="text-xs px-2 py-1 bg-[#f8f8f7] dark:bg-[#2a2a26] text-[#1b1b18] dark:text-[#EDEDEC] rounded">{{ ucfirst($book->condition) }}</span>
                                @if($book->price)
                                    <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">${{ number_format($book->price, 2) }} ref.</span>
                                @endif
                            </div>
                            
                            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-3">
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    <strong>Owner:</strong> {{ $book->owner->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request Form -->
                <div class="bg-white dark:bg-[#161615] rounded-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-6">
                    <h2 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Send Request</h2>
                    
                    <form action="{{ route('marketplace.transactions.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="market_book_id" value="{{ $book->id }}">

                        <!-- Request Type -->
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Request Type *</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="type" 
                                        value="gift" 
                                        checked
                                        onchange="toggleExchangeOptions()"
                                        class="h-4 w-4 text-[#f53003] focus:ring-[#f53003] border-[#e3e3e0] dark:border-[#3E3E3A]"
                                    >
                                    <span class="ml-2 text-[#1b1b18] dark:text-[#EDEDEC]">Gift Request</span>
                                </label>
                                <p class="ml-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">Request the book as a gift</p>
                                
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="type" 
                                        value="exchange"
                                        onchange="toggleExchangeOptions()"
                                        class="h-4 w-4 text-[#f53003] focus:ring-[#f53003] border-[#e3e3e0] dark:border-[#3E3E3A]"
                                    >
                                    <span class="ml-2 text-[#1b1b18] dark:text-[#EDEDEC]">Exchange Request</span>
                                </label>
                                <p class="ml-6 text-sm text-[#706f6c] dark:text-[#A1A09A]">Propose to exchange with one of your books</p>
                            </div>
                        </div>

                        <!-- Exchange Book Selection -->
                        <div id="exchangeOptions" class="hidden">
                            <label for="exchange_book_id" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Select Your Book to Exchange</label>
                            <select 
                                id="exchange_book_id" 
                                name="exchange_book_id"
                                class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] transition-colors"
                            >
                                <option value="">Choose a book to offer</option>
                                @foreach($userBooks as $userBook)
                                    <option value="{{ $userBook->id }}">{{ $userBook->title }} by {{ $userBook->author }}</option>
                                @endforeach
                            </select>
                            @if($userBooks->isEmpty())
                                <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                    You don't have any available books to exchange. 
                                    <a href="{{ route('marketplace.books.create') }}" class="text-[#f53003] hover:text-[#d42b02] underline">Add a book</a> first.
                                </p>
                            @endif
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Message *</label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="4"
                                required
                                class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-transparent text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] focus:border-[#f53003] dark:focus:border-[#FF4433] transition-colors @error('message') border-red-500 @enderror"
                                placeholder="Write a message to the book owner..."
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button 
                                type="submit" 
                                class="flex-1 px-6 py-3 bg-[#f53003] hover:bg-[#d42b02] text-white font-medium rounded-sm transition-colors duration-300"
                            >
                                Send Request
                            </button>
                            <a 
                                href="{{ route('marketplace.browse') }}" 
                                class="px-6 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f8f8f7] dark:hover:bg-[#161615] font-medium rounded-sm transition-colors duration-300"
                            >
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleExchangeOptions() {
            const exchangeRadio = document.querySelector('input[name="type"][value="exchange"]');
            const exchangeOptions = document.getElementById('exchangeOptions');
            
            if (exchangeRadio.checked) {
                exchangeOptions.classList.remove('hidden');
            } else {
                exchangeOptions.classList.add('hidden');
            }
        }
    </script>
@endsection