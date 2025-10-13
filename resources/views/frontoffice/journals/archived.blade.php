@extends('frontoffice.layouts.app')

@section('title', 'Archived Books - ' . $journal->name . ' - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-10">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 transition-all duration-700 starting:opacity-0 starting:translate-y-6">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Archived Books
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                {{ $journal->name }}
            </p>

            <!-- Stats -->
            <div class="mt-8">
                <div class="inline-block px-4 py-2 bg-[#f53003]/10 dark:bg-[#FF4433]/10 rounded-full text-sm font-medium text-[#f53003] dark:text-[#FF4433]">
                    {{ $archivedBooks->count() }} archived book{{ $archivedBooks->count() !== 1 ? 's' : '' }}
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">

            @if($archivedBooks->count() > 0)
                <!-- Books Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    @foreach($archivedBooks as $book)
                        <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-5 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    <i class="bi bi-archive"></i>
                                    Archived
                                </span>
                            </div>

                            <h3 class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC] text-lg mb-2">
                                {{ $book->title }}
                            </h3>

                            @if($book->description)
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                                    {{ Str::limit($book->description, 120) }}
                                </p>
                            @endif

                            <form action="{{ route('journals.unarchive-book', [$journal->id, $book->id]) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline flex items-center gap-1">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Unarchive
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-10 text-center mb-10 max-w-md mx-auto">
                    <div class="text-[#f53003] dark:text-[#FF4433] text-4xl mb-4">
                        <i class="bi bi-archive"></i>
                    </div>
                    <h3 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                        No Archived Books
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        There are no archived books in this journal. Books you archive will appear here.
                    </p>
                </div>
            @endif

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('journals.show', $journal) }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#d0d0cc] dark:hover:bg-[#2A2A28] transition">
                    <i class="bi bi-arrow-left"></i>
                    Back to Journal
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush