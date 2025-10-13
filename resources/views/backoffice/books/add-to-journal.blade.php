@extends('frontoffice.layouts.app')

@section('title', 'Ajouter au Journal - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-10">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 transition-all duration-700 starting:opacity-0 starting:translate-y-6">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Add to Your Journal
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                Add "<strong class="text-[#f53003] dark:text-[#FF4433]">{{ $book->title }}</strong>" to your reading journal
            </p>
        </div>

        <div class="max-w-lg mx-auto">

            @if($journals->count() > 0)
                <!-- Form Card -->
                <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 mb-6 shadow-sm">
                    <form action="{{ route('books.store-in-journal', $book) }}" method="POST">
                        @csrf

                        <!-- Journal Selection -->
                        <div class="mb-6">
                            <label for="journal_id" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                Choose a Journal <span class="text-[#f53003] dark:text-[#FF4433]">*</span>
                            </label>
                            <select 
                                name="journal_id" 
                                id="journal_id" 
                                required
                                class="w-full px-4 py-3 bg-transparent border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent"
                            >
                                <option value="">-- Select a journal --</option>
                                @foreach($journals as $journal)
                                    <option value="{{ $journal->id }}">{{ $journal->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Book Preview -->
                        <div class="mb-6 p-4 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#fafaf8] dark:bg-[#1D1D1B]">
                            <div class="flex items-start gap-4">
                                @if($book->image)
                                    <img 
                                        src="{{ asset('storage/' . $book->image) }}" 
                                        alt="{{ $book->title }}"
                                        class="rounded-md object-cover w-16 h-20"
                                    >
                                @else
                                    <div class="bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-md w-16 h-20 flex items-center justify-center">
                                        <i class="bi bi-book text-[#706f6c] dark:text-[#A1A09A] text-xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h6 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $book->title }}</h6>
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $book->author }}</p>
                                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full 
                                        {{ $book->availability 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ $book->availability ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg hover:opacity-90 transition">
                                <i class="bi bi-plus-circle"></i>
                                Add to Journal
                            </button>
                            <a href="{{ url('/book') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#d0d0cc] dark:hover:bg-[#2A2A28] transition text-center">
                                <i class="bi bi-arrow-left"></i>
                                Back to Books
                            </a>
                        </div>
                    </form>
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-8 text-center mb-6">
                    <div class="text-[#f53003] dark:text-[#FF4433] text-4xl mb-4">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h5 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                        No Journals Available
                    </h5>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                        You need to create a journal before you can add books to it.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('journals.create') }}" class="flex items-center justify-center gap-2 px-5 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg hover:opacity-90 transition">
                            <i class="bi bi-plus-circle"></i>
                            Create First Journal
                        </a>
                        <a href="{{ url('/book') }}" class="flex items-center justify-center gap-2 px-5 py-3 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#d0d0cc] dark:hover:bg-[#2A2A28] transition">
                            <i class="bi bi-arrow-left"></i>
                            Back to Books
                        </a>
                    </div>
                </div>
            @endif

            <!-- Info Tip -->
            <div class="bg-[#fff8e1] dark:bg-[#2A2500] border-l-4 border-[#ffc107] rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-lightbulb text-[#e6a000] text-lg mt-0.5"></i>
                    <div>
                        <h6 class="font-medium text-[#5a3e00] dark:text-[#FFD54F] mb-1">Did You Know?</h6>
                        <p class="text-sm text-[#5a3e00] dark:text-[#E6C79C]">
                            Adding books to journals helps you organize your reading by themes, projects, or time periods. You can add notes and track your progress for each book.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush