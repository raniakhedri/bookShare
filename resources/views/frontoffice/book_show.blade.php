@extends('frontoffice.layouts.app')

@section('title', $book->title . ' - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <!-- Breadcrumb amélioré -->
        <nav class="mb-8">
            <div class="flex items-center space-x-3 text-sm">
                <a href="{{ route('book') }}" class="flex items-center gap-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Books
                </a>
                <span class="text-[#dbdbd7] dark:text-[#3E3E3A]">/</span>
                <span class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium truncate">{{ Str::limit($book->title, 40) }}</span>
            </div>
        </nav>

        <!-- Carte principale améliorée -->
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] overflow-hidden transition-all duration-500 starting:opacity-0 starting:translate-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 p-6 lg:p-8">
                <!-- Colonne image améliorée -->
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
                        
                        <!-- Badge de disponibilité -->
                        <div class="absolute -top-2 -right-2">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm {{ $book->availability ? 'bg-green-100/90 text-green-800 dark:bg-green-900/90 dark:text-green-200 border border-green-200 dark:border-green-800' : 'bg-red-100/90 text-red-800 dark:bg-red-900/90 dark:text-red-200 border border-red-200 dark:border-red-800' }}">
                                {{ $book->availability ? '🟢 Available' : '🔴 Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Colonne informations améliorée -->
                <div class="lg:col-span-3">
                    <!-- En-tête -->
                    <div class="mb-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 leading-tight tracking-tight">
                            {{ $book->title }}
                        </h1>
                        <p class="text-xl text-[#f53003] dark:text-[#FF4433] font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0M12 11a3 3 0 100-6 3 3 0 000 6zm-6 8a3 3 0 100-6 3 3 0 000 6z"/>
                            </svg>
                            by {{ $book->author }}
                        </p>
                    </div>

                    <!-- Badges améliorés -->
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

                    <!-- Description améliorée -->
                    <div class="bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 mb-6">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#f53003] dark:text-[#FF4433]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Description
                        </h3>
                        <div class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed prose prose-gray dark:prose-invert max-w-none">
                            @if($book->description)
                                <p class="text-base">{{ $book->description }}</p>
                            @else
                                <p class="italic text-[#dbdbd7] dark:text-[#3E3E3A]">No description available for this book.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions principales -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($book->availability)
                            <button class="flex-1 px-6 py-3 bg-gradient-to-r from-[#1b1b18] to-[#2d2d2a] dark:from-[#eeeeec] dark:to-[#ffffff] text-white dark:text-[#1C1C1A] rounded-xl font-semibold hover:from-black hover:to-[#1b1b18] dark:hover:from-white dark:hover:to-[#f0f0f0] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Reserve This Book
                            </button>
                        @else
                            <button class="flex-1 px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center gap-3" disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Currently Unavailable
                            </button>
                        @endif
                        
                        <button class="px-6 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-semibold hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 flex items-center justify-center gap-3 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Add to Favorites
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section PDF améliorée -->
            @if($book->file)
            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] mt-8">
                <div class="p-6 lg:p-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border-2 border-blue-200 dark:border-blue-700 shadow-lg overflow-hidden">
                        <!-- En-tête PDF -->
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
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $book->file) }}" 
                                       download 
                                       class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 flex items-center gap-2 shadow hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Lecteur PDF -->
                        <div class="p-4">
                            <div class="rounded-lg overflow-hidden border border-blue-300 dark:border-blue-600 bg-white dark:bg-gray-900 shadow-inner">
                                <iframe src="{{ asset('storage/' . $book->file) }}#toolbar=0&view=fitH" 
                                        width="100%" 
                                        height="600" 
                                        class="border-0"
                                        loading="lazy"
                                        style="min-height: 500px;">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Navigation améliorée -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-3 px-6 py-3 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-medium hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group shadow hover:shadow-lg">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Books List
            </a>
            
            <div class="flex gap-3">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-lg hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share Book
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A] rounded-lg hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                    More Options
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .prose {
        max-width: none;
        line-height: 1.7;
    }
    
    .prose p {
        margin-bottom: 1rem;
    }
    
    /* Animation de fondu améliorée */
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
    
    .starting {
        animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    /* Effets de hover améliorés */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
    
    /* Amélioration du lecteur PDF */
    iframe {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .dark iframe {
        background: #1a1a1a;
    }
    
    /* Ombres portées améliorées */
    .shadow-lg {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Responsive amélioré */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .grid {
            gap: 1.5rem;
        }
        
        .flex-wrap {
            justify-content: center;
        }
    }
</style>
@endpush