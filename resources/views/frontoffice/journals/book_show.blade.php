@extends('frontoffice.layouts.app')

@section('title', $book->title . ' - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <div class="flex items-center space-x-3 text-sm">
                <a href="{{ route('journals.show', $journal->id) }}" class="flex items-center gap-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ $journal->name }}
                </a>
                <span class="text-[#dbdbd7] dark:text-[#3E3E3A]">/</span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium truncate">{{ Str::limit($book->title, 40) }}</span>
            </div>
        </nav>

        <!-- Carte principale -->
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] overflow-hidden transition-all duration-500 starting:opacity-0 starting:translate-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 p-6 lg:p-8">
                <!-- Image -->
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
                        
                        <!-- Badge disponibilité -->
                        <div class="absolute -top-2 -right-2">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm {{ $book->availability ? 'bg-green-100/90 text-green-800 dark:bg-green-900/90 dark:text-green-200 border border-green-200 dark:border-green-800' : 'bg-red-100/90 text-red-800 dark:bg-red-900/90 dark:text-red-200 border border-red-200 dark:border-red-800' }}">
                                {{ $book->availability ? '🟢 Available' : '🔴 Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Informations -->
                <div class="lg:col-span-3">
                    <div class="mb-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                            {{ $book->title }}
                        </h1>
                        <p class="text-xl text-[#f53003] dark:text-[#FF4433] font-semibold mb-4 flex items-center gap-2">
                            by {{ $book->author }}
                        </p>
                    </div>

                    <!-- Badges -->
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

                    <!-- Description -->
                    <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#f53003] dark:text-[#FF4433]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Description
                        </h3>
                        <div class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                            @if($book->description)
                                <p class="text-base">{{ $book->description }}</p>
                            @else
                                <p class="italic text-[#dbdbd7] dark:text-[#3E3E3A]">No description available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section PDF -->
            @if($book->file)
            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-8 p-6 lg:p-8">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border-2 border-blue-200 dark:border-blue-700 shadow-lg overflow-hidden">
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
                            <a href="{{ asset('storage/' . $book->file) }}" download 
                               class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 transition">
                                Download PDF
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="rounded-lg overflow-hidden border border-blue-300 dark:border-blue-600 bg-white dark:bg-gray-900 shadow-inner">
                            <iframe src="{{ asset('storage/' . $book->file) }}#toolbar=0&view=fitH" 
                                    width="100%" height="500" class="border-0">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Notes & Comments Section (CORRIGÉE) -->
        <div class="mt-8">
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Notes & Discussion</h4>

                <!-- Add Note Form -->
                <form action="{{ route('notes.store', [$journal->id, $book->id]) }}" method="POST" class="mb-4">
                    @csrf
                    <textarea 
                        name="content" 
                        required
                        class="w-full px-3 py-2 text-sm bg-transparent border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC]"
                        rows="2"
                        placeholder="Add your thoughts about this book...">{{ old('content') }}</textarea>
                    <button type="submit" class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433] hover:underline flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Add Note
                    </button>
                </form>

                <!-- Display Notes -->
                @if($notes->count() > 0)
                    <div class="space-y-4 mt-4">
                        @foreach($notes as $note)
                            <div class="bg-[#fafaf8] dark:bg-[#1D1D1B] rounded-lg p-3 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-medium text-[#f53003] dark:text-[#FF4433]">
                                        {{ $note->user->email }}
                                    </span>
                                    <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                        {{ $note->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] mb-3">{{ $note->content }}</p>

                                <!-- Comments -->
                                @if($note->comments->count() > 0)
                                    <div class="ml-4 space-y-2 mb-3">
                                        @foreach($note->comments as $comment)
                                            <div class="flex gap-2">
                                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] whitespace-nowrap">
                                                    {{ $comment->user->email }}:
                                                </span>
                                                <p class="text-xs text-[#1b1b18] dark:text-[#EDEDEC]">{{ $comment->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Add Comment Form -->
                                <form action="{{ route('comments.store', $note->id) }}" method="POST" class="flex gap-2 mt-2">
                                    @csrf
                                    <input 
                                        type="text" 
                                        name="content" 
                                        required
                                        class="flex-1 text-xs px-2 py-1 bg-transparent border border-[#e3e3e0] dark:border-[#3E3E3A] rounded text-[#1b1b18] dark:text-[#EDEDEC]"
                                        placeholder="Write a comment...">
                                    <button type="submit" class="text-xs text-[#f53003] dark:text-[#FF4433] hover:underline">Reply</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] italic">No notes yet. Be the first to share your thoughts!</p>
                @endif
            </div>
        </div>

        <!-- Navigation -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('journals.show', $journal->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">
                <i class="bi bi-arrow-left"></i> Back to Journal
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.starting { animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush