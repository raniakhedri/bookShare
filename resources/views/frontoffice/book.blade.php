@extends('frontoffice.layouts.app')

@section('title', 'Our Books - Bookly')

@section('content')
    <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
        <div class="container mx-auto px-4 lg:px-8">
            <!-- Animated header -->
            <div class="text-center mb-12 transition-all duration-750 starting:opacity-0 starting:translate-y-6">
                <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Our Library
                </h1>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                    Discover our collection of shared books. Each book tells a story and is waiting for its new reader.
                </p>
            </div>

            <!-- Filters and search -->
            <div class="mb-8 p-6 bg-white dark:bg-[#161615] rounded-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex-1 w-full">
                        <input 
                            type="text" 
                            placeholder="Search for a book, author or category..."
                            class="w-full px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-transparent text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] focus:border-[#f53003] dark:focus:border-[#FF4433] transition-colors"
                        >
                    </div>
                    <div class="flex gap-3">
                        <select class="px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-transparent text-[#1b1b18] dark:text-[#EDEDEC]">
                            <option>All categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select class="px-4 py-2.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-transparent text-[#1b1b18] dark:text-[#EDEDEC]">
                            <option>All conditions</option>
                            <option>New</option>
                            <option>Very good</option>
                            <option>Good</option>
                            <option>Acceptable</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Grille de livres -->
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
                                    @if($book->type === 'pdf' && $book->file)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $book->file) }}" target="_blank" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Read PDF</a>
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
                                <button class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700" {{ !$book->availability ? 'disabled' : '' }}>
                                    <svg class="w-6 h-6">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-6 h-6">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                                <!-- Nouvelle icône Ajouter au journal -->
                                <a href="{{ route('books.add-to-journal', $book->id) }}"
                                   class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700 flex items-center justify-center"
                                   title="Ajouter au journal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($books->hasPages())
                <div class="flex justify-center">
                    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-4">
                        {{ $books->links() }}
                    </div>
                </div>
            @endif

            <!-- Call to Action -->
            <div class="text-center mt-16">
                <div class="bg-[#fff2f2] dark:bg-[#1D0002] rounded-lg p-8 max-w-2xl mx-auto">
                    <h3 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Do you have books to share?
                    </h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                        Join our sharing community and give your books a second life.
                    </p>
                    <button class="inline-flex items-center gap-2 px-6 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-sm font-medium hover:bg-[#d42a03] dark:hover:bg-[#e5391b] transition-colors">
                        <span>Suggest a book</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="w-4 h-4">
                            <path d="M8 3.33334V12.6667M3.33333 8H12.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Animation d'apparition progressive */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .book-card {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Style personnalisé pour la pagination */
    .pagination {
        display: flex;
        gap: 0.5rem;
    }
    
    .pagination .page-item .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e3e3e0;
        border-radius: 0.25rem;
        color: #1b1b18;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #1b1b18;
        border-color: #1b1b18;
        color: white;
    }
    
    .pagination .page-item:hover .page-link {
        border-color: #f53003;
    }
    
    .dark .pagination .page-item .page-link {
        border-color: #3E3E3A;
        color: #EDEDEC;
    }
    
    .dark .pagination .page-item.active .page-link {
        background-color: #FF4433;
        border-color: #FF4433;
    }
</style>
@endpush