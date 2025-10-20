@extends('frontoffice.layouts.app')

@section('title', 'AI Book Recommendations')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Recommended Books For You
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                These picks are personalized based on your reviews and preferences.
            </p>
        </div>
        @if($books->isEmpty())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center text-blue-700 font-semibold max-w-xl mx-auto">
                No recommendations available yet.<br>Try reviewing some books!
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
                @foreach($books as $book)
                    <div class="swiper-slide">
                        <div class="card relative p-4 border rounded-xl hover:shadow-lg transition-shadow bg-white dark:bg-[#161615] flex flex-col h-full">
                            @if($book->category)
                                <span class="absolute top-4 left-4 bg-primary py-1 px-3 text-xs text-white rounded-lg z-10">{{ $book->category->name }}</span>
                            @endif
                            <div class="flex justify-center items-center">
                                @if($book->image)
                                    <img src="{{ asset('storage/' . $book->image) }}" style="width:128px; height:180px; object-fit:cover; border-radius:0.5rem; box-shadow:0 1px 4px #0001;" alt="{{ $book->title }}">
                                @else
                                    <div style="width:128px; height:180px; display:flex; align-items:center; justify-content:center; background:#f3f4f6; border-radius:0.5rem;">
                                        <span class="text-4xl text-primary">📚</span>
                                    </div>
                                @endif
                            </div>
                            <h6 class="mt-4 mb-1 font-bold text-base text-center">
                                <a href="{{ route('frontoffice.book.show', $book->id) }}" class="hover:text-primary underline">{{ $book->title }}</a>
                            </h6>
                            <div class="flex items-center justify-center">
                                <p class="my-2 mr-2 text-xs text-gray-500">{{ $book->author }}</p>
                            </div>
                            <div class="flex items-center justify-center mb-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $book->availability ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $book->availability ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                            <div class="flex gap-2 justify-center mt-auto">
                                <a href="{{ route('frontoffice.book.show', $book->id) }}" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700" title="View Book">
                                    <svg class="w-6 h-6"><use xlink:href="#eye"></use></svg>
                                </a>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-6 h-6"><use xlink:href="#heart"></use></svg>
                                </a>
                                <a href="{{ route('books.add-to-journal', $book->id) }}"
                                   class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700 flex items-center justify-center"
                                   title="Add to Journal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
