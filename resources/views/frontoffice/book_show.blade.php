@extends('frontoffice.layouts.app')

@section('title', $book->title . ' - Bookly')

@section('content')
@php
    $book->load(['reviews' => function($query) {
        $query->with('user')->active()->latest();
    }]);
@endphp

<!-- Métadonnées pour le système IA -->
<div data-current-book-id="{{ $book->id }}" style="display: none;"></div>
@if(auth()->check())
    <meta name="user-id" content="{{ auth()->id() }}">
@endif

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
                       
                        
                        @auth
                            <button onclick="toggleFavorite({{ $book->id }})" 
                                    class="favorite-btn px-6 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-semibold hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 flex items-center justify-center gap-3 group"
                                    data-book-id="{{ $book->id }}">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform favorite-icon 
                                    {{ auth()->user()->favorites()->where('book_id', $book->id)->exists() ? 'text-[#f53003] fill-current' : '' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="favorite-text">
                                    {{ auth()->user()->favorites()->where('book_id', $book->id)->exists() ? 'Remove from Favorites' : 'Add to Favorites' }}
                                </span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-6 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-semibold hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 flex items-center justify-center gap-3 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                Login to Add to Favorites
                            </a>
                        @endauth
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
                            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">PDF Preview & Audio Reading</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">Read the book online or listen to it</p>
                                        <p id="audioStatusMessage" class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                            <span class="inline-flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Mode démo : Extraction automatique du PDF en cours...
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Contrôles Audio et Actions -->
                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                    <!-- Contrôles Audio -->
                                    <div class="audio-controls flex items-center gap-2 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl p-2 border border-purple-200 dark:border-purple-700">
                                        <button id="playPauseBtn" class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                            <svg id="playIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                            <svg id="pauseIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                            </svg>
                                        </button>
                                        
                                        <button id="stopBtn" class="flex items-center justify-center w-8 h-8 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 6h12v12H6z"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Contrôle de vitesse -->
                                        <div class="flex items-center gap-1">
                                            <label class="text-xs font-medium text-gray-600 dark:text-gray-300">Speed:</label>
                                            <select id="speedControl" class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1">
                                                <option value="0.5">0.5x</option>
                                                <option value="0.75">0.75x</option>
                                                <option value="1" selected>1x</option>
                                                <option value="1.25">1.25x</option>
                                                <option value="1.5">1.5x</option>
                                                <option value="2">2x</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Sélection de voix -->
                                        <select id="voiceSelect" class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 max-w-32">
                                            <option value="">Default Voice</option>
                                        </select>
                                        
                                        <!-- Contrôle de volume -->
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                                            </svg>
                                            <input 
                                                type="range" 
                                                id="volumeControl" 
                                                min="0" 
                                                max="1" 
                                                step="0.1" 
                                                value="1" 
                                                class="w-16 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                            >
                                        </div>
                                    </div>
                                    
                                    <!-- Bouton de téléchargement -->
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
                            
                            <!-- Progress Bar pour la lecture audio -->
                            <div id="audioProgress" class="mt-4 hidden">
                                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span>Audio Reading Progress</span>
                                        <span id="currentChapter" class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded text-xs font-medium">
                                            Passage 1 / 1
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span id="timeRemaining" class="text-xs text-gray-500">~0 min restant</span>
                                        <span id="progressText" class="font-medium">0%</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 shadow-inner">
                                    <div id="progressBar" class="bg-gradient-to-r from-purple-500 to-indigo-500 h-3 rounded-full transition-all duration-300 relative overflow-hidden" style="width: 0%">
                                        <!-- Effet de brillance -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 animate-shimmer"></div>
                                    </div>
                                </div>
                                
                                <!-- Contrôles supplémentaires -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-2">
                                        <!-- Bouton précédent -->
                                        <button id="prevChapterBtn" class="flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Passage précédent (Ctrl+←)">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Bouton suivant -->
                                        <button id="nextChapterBtn" class="flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Passage suivant (Ctrl+→)">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Séparateur -->
                                        <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-2"></div>
                                        
                                        <!-- Bouton extraction manuelle -->
                                        <button id="retryExtractionBtn" class="flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Essayer d'extraire le texte PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <span>Raccourcis: </span>
                                        <kbd class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">Espace</kbd> = Play/Pause,
                                        <kbd class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">Ctrl+S</kbd> = Stop
                                    </div>
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

        <!-- Widget de recommandations IA -->
        @include('components.ai-recommendations-widget', ['showAiRecommendations' => true])

<!-- Reviews Section - Add this to your book_show.blade.php -->
        <!-- Reviews Section - Enhanced -->
<div class="mt-12">
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] p-8 transition-all duration-500">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h3 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </span>
                    Customer Reviews
                </h3>
                @php
                    $avgRating = $book->reviews()->active()->avg('overall_rating') ?? 0;
                    $reviewCount = $book->reviews()->active()->count();
                @endphp
                @if($reviewCount > 0)
                    <div class="flex items-center gap-3 mt-2">
                        <div class="flex items-center bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 px-4 py-2 rounded-xl border border-yellow-200 dark:border-yellow-800">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }} drop-shadow-sm" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-sm text-[#706f6c] dark:text-[#A1A09A] ml-1">out of 5</span>
                        </div>
                        <span class="px-3 py-1 bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-full text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">
                            {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
                        </span>
                    </div>
                @else
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mt-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        No reviews yet - be the first!
                    </p>
                @endif
            </div>

            @auth
                <a href="{{ route('reviews.create', $book->id) }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-[#f53003] to-[#ff6b47] dark:from-[#FF4433] dark:to-[#ff7766] text-black rounded-xl font-semibold hover:from-[#d42802] hover:to-[#f53003] dark:hover:from-[#dd3322] dark:hover:to-[#FF4433] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Write a Review
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-[#FDFDFC] dark:bg-[#0a0a0a] border-2 border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-xl font-semibold hover:border-[#f53003] dark:hover:border-[#FF4433] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-all duration-300 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Login to Review
                </a>
            @endauth
        </div>

        @if($reviewCount > 0)
            <!-- Review Summary Stats - Enhanced -->
            <div class="bg-gradient-to-br from-[#FDFDFC] to-[#fff2f2] dark:from-[#0a0a0a] dark:to-[#1D0002] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-8 mb-8 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Rating Breakdown - Enhanced -->
                    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                        <h4 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center gap-2 text-lg">
                            <span class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow">
                                <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </span>
                            Rating Breakdown
                        </h4>
                        @for($rating = 5; $rating >= 1; $rating--)
                            @php
                                $ratingCount = $book->reviews()->where('overall_rating', $rating)->count();
                                $percentage = $reviewCount > 0 ? ($ratingCount / $reviewCount) * 100 : 0;
                            @endphp
                            <div class="flex items-center mb-3 group">
                                <span class="text-sm font-semibold text-[#706f6c] dark:text-[#A1A09A] w-8">{{ $rating }}</span>
                                <svg class="w-4 h-4 text-yellow-400 mr-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <div class="flex-1 bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2.5 mr-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] w-10 text-right">{{ $ratingCount }}</span>
                            </div>
                        @endfor
                    </div>

                    <!-- Additional Stats - Enhanced -->
                    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                        <h4 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center gap-2 text-lg">
                            <span class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow">
                                <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Review Stats
                        </h4>
                        <div class="space-y-3 text-sm">
                            @php
                                $avgContent = $book->reviews()->whereNotNull('content_rating')->avg('content_rating');
                                $avgCondition = $book->reviews()->whereNotNull('condition_rating')->avg('condition_rating');
                                $recommendationRate = $book->reviews()->where('recommendation_level', '>=', 4)->count() / max($reviewCount, 1) * 100;
                                $positiveCount = $book->reviews()->where('sentiment', 'positive')->count();
                                $neutralCount = $book->reviews()->where('sentiment', 'neutral')->count();
                                $negativeCount = $book->reviews()->where('sentiment', 'negative')->count();
                            @endphp
                            @if($avgContent)
                                <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <span class="text-[#706f6c] dark:text-[#A1A09A] flex items-center gap-2">
                                        📚 Avg Content Rating:
                                    </span>
                                    <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] text-lg">{{ number_format($avgContent, 1) }}<span class="text-sm">/5</span></span>
                                </div>
                            @endif
                            @if($avgCondition)
                                <div class="flex justify-between items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                                    <span class="text-[#706f6c] dark:text-[#A1A09A] flex items-center gap-2">
                                        📖 Avg Condition:
                                    </span>
                                    <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] text-lg">{{ number_format($avgCondition, 1) }}<span class="text-sm">/5</span></span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                <span class="text-[#706f6c] dark:text-[#A1A09A] flex items-center gap-2">
                                    👍 Would Recommend:
                                </span>
                                <span class="font-bold text-green-600 dark:text-green-400 text-lg">{{ number_format($recommendationRate, 0) }}%</span>
                            </div>
                                                        <!-- Sentiment Counters -->
                            <div class="flex justify-between items-center p-3 bg-gradient-to-r from-green-50 via-gray-50 to-red-50 dark:from-green-900/20 dark:via-gray-900/20 dark:to-red-900/20 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] mt-2">
                                <span class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 font-semibold text-xs mr-2">
                                        😊 Positive: {{ $positiveCount }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-800 font-semibold text-xs mr-2">
                                        😐 Neutral: {{ $neutralCount }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 font-semibold text-xs">
                                        😞 Negative: {{ $negativeCount }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Most Helpful Review Preview - Enhanced -->
                    @php
                        $topReview = $book->reviews()->active()->orderByDesc('helpful_votes')->first();
                    @endphp
                    @if($topReview)
                        <div class="bg-white dark:bg-[#161615] rounded-xl p-6 border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                            <h4 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center gap-2 text-lg">
                                <span class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center shadow">
                                    <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </span>
                                Most Helpful Review
                            </h4>
                            <div class="bg-gradient-to-br from-[#FDFDFC] to-[#fff2f2] dark:from-[#0a0a0a] dark:to-[#1D0002] p-5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-md transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="flex items-center bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 px-3 py-1.5 rounded-lg border border-yellow-200 dark:border-yellow-800 mr-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $topReview->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-semibold text-[#706f6c] dark:text-[#A1A09A]">by {{ $topReview->user->name }}</span>
                                </div>
                                <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] leading-relaxed line-clamp-3 mb-3">
                                    {{ Str::limit($topReview->review_text, 150) }}
                                </p>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                        </svg>
                                        {{ $topReview->helpful_votes }} found helpful
                                    </span>
                                    <a href="{{ route('reviews.show', $topReview) }}" class="text-[#f53003] dark:text-[#FF4433] hover:underline font-medium">
                                        Read full review →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Reviews - Enhanced -->
            <div class="space-y-6">
                <div class="flex items-center justify-between bg-gradient-to-r from-[#FDFDFC] to-white dark:from-[#0a0a0a] dark:to-[#161615] p-6 rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                    <h4 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center gap-3">
                        <span class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </span>
                        Recent Reviews
                    </h4>
                    <a href="{{ route('reviews.index', $book->id) }}" 
                       class="group px-5 py-2.5 bg-gradient-to-r from-[#f53003] to-red-600 hover:from-red-600 hover:to-[#f53003] text-black rounded-xl font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        View All Reviews
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                @php
                    $recentReviews = $book->reviews()
                        ->with(['user:id,name', 'interactions'])
                        ->active()
                        ->latest()
                        ->limit(3)
                        ->get();
                @endphp

                @foreach($recentReviews as $review)
                    @include('frontoffice.reviews.partials.review-card', ['review' => $review])
                @endforeach

                @if($reviewCount > 3)
                    <div class="text-center pt-6">
                        <a href="{{ route('reviews.index', $book->id) }}" 
                           class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#f53003] to-red-600 hover:from-red-600 hover:to-[#f53003] shadow-lg hover:shadow-xl text-black font-bold rounded-2xl transform hover:-translate-y-1 transition-all duration-300">
                            View All {{ $reviewCount }} Reviews
                            <svg class="ml-3 w-5 h-5 transform group-hover:translate-x-2 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- No Reviews State - Enhanced -->
            <div class="bg-gradient-to-br from-[#FDFDFC] to-white dark:from-[#0a0a0a] dark:to-[#161615] rounded-2xl shadow-lg border border-[#e3e3e0] dark:border-[#3E3E3A] p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl flex items-center justify-center shadow-xl transform hover:scale-105 transition-transform">
                        <svg class="w-12 h-12 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">No reviews yet</h4>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-8 text-lg">Be the first to share your thoughts about this book!</p>
                    @auth
                        <a href="{{ route('reviews.create', $book->id) }}" 
                           class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#f53003] to-red-600 hover:from-red-600 hover:to-[#f53003] text-black font-bold rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Write the First Review
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <div class="space-y-4">
                            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                                Want to share your review? 
                            </p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#f53003] to-red-600 hover:from-red-600 hover:to-[#f53003] text-black font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login to Write a Review
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        @endif
    </div>
</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFavorite(bookId) {
    fetch(`/books/${bookId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
    })
    .then(response => response.json())
    .then(data => {
        const button = document.querySelector(`.favorite-btn[data-book-id="${bookId}"]`);
        const icon = button.querySelector('.favorite-icon');
        const text = button.querySelector('.favorite-text');
        const headerCount = document.querySelector('.favorites-count');

        if (data.status) {
            icon.classList.add('text-[#f53003]', 'fill-current');
            text.textContent = 'Remove from Favorites';
        } else {
            icon.classList.remove('text-[#f53003]', 'fill-current');
            text.textContent = 'Add to Favorites';
        }

        // Update the counter in the header
        if (headerCount) {
            headerCount.textContent = data.count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating favorites');
    });
}

// ========================================
// SYSTÈME DE LECTURE AUDIO AVANCÉ
// ========================================

class AudioBookReader {
    constructor() {
        this.synthesis = window.speechSynthesis;
        this.utterance = null;
        this.isReading = false;
        this.currentText = '';
        this.textChunks = [];
        this.currentChunkIndex = 0;
        this.voices = [];
        this.bookTitle = '{{ $book->title }}';
        this.progress = 0;
        
        this.init();
    }
    
    async init() {
        // Attendre que les voix soient chargées
        await this.loadVoices();
        this.bindEvents();
        this.loadReadingPosition();
        
        // Essayer d'extraire le texte du PDF
        await this.extractPdfText();
        
        console.log('AudioBookReader initialized');
    }
    
    async loadVoices() {
        return new Promise((resolve) => {
            const loadVoicesCallback = () => {
                this.voices = this.synthesis.getVoices();
                this.populateVoiceSelect();
                resolve();
            };
            
            if (this.voices.length > 0) {
                loadVoicesCallback();
            } else {
                this.synthesis.addEventListener('voiceschanged', loadVoicesCallback);
                // Timeout de sécurité
                setTimeout(loadVoicesCallback, 1000);
            }
        });
    }
    
    populateVoiceSelect() {
        const voiceSelect = document.getElementById('voiceSelect');
        if (!voiceSelect) return;
        
        // Vider les options existantes sauf la première
        while (voiceSelect.children.length > 1) {
            voiceSelect.removeChild(voiceSelect.lastChild);
        }
        
        // Filtrer et trier les voix
        const preferredVoices = this.voices.filter(voice => 
            voice.lang.startsWith('en') || 
            voice.lang.startsWith('fr') || 
            voice.lang.startsWith('es') ||
            voice.name.includes('Google') ||
            voice.name.includes('Microsoft')
        ).sort((a, b) => {
            // Prioriser les voix premium
            if (a.name.includes('Google') && !b.name.includes('Google')) return -1;
            if (b.name.includes('Google') && !a.name.includes('Google')) return 1;
            if (a.name.includes('Microsoft') && !b.name.includes('Microsoft')) return -1;
            if (b.name.includes('Microsoft') && !a.name.includes('Microsoft')) return 1;
            return a.name.localeCompare(b.name);
        });
        
        preferredVoices.forEach((voice, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = `${voice.name} (${voice.lang})`;
            if (voice.default) option.selected = true;
            voiceSelect.appendChild(option);
        });
    }
    
    async extractPdfText() {
        try {
            const pdfUrl = '{{ asset("storage/" . $book->file) }}';
            
            // Pour une démo, utilisons du texte de remplacement
            // Dans une implémentation complète, vous pourriez utiliser PDF.js
            this.currentText = `
                Bienvenue dans la lecture audio de ${this.bookTitle}.
                
                Cette fonctionnalité vous permet d'écouter le contenu du livre au lieu de le lire.
                
                Vous pouvez contrôler la vitesse de lecture, changer de voix, et suivre votre progression.
                
                L'extracton automatique du texte PDF nécessiterait l'intégration de PDF.js ou d'un service backend.
                
                Pour cette démonstration, nous utilisons ce texte d'exemple qui montre toutes les fonctionnalités audio disponibles.
                
                Vous pouvez mettre en pause, reprendre, changer la vitesse, et même changer de voix pour personnaliser votre expérience d'écoute.
                
                Cette technologie rend les livres accessibles à tous, y compris aux personnes ayant des difficultés de lecture.
            `;
            
            this.prepareTextForReading();
            
        } catch (error) {
            console.error('Erreur lors de l\'extraction du texte PDF:', error);
            this.currentText = `Erreur lors de l'extraction du texte du PDF. ${this.bookTitle} - Texte de démonstration pour la fonctionnalité de lecture audio.`;
            this.prepareTextForReading();
        }
    }
    
    prepareTextForReading() {
        // Diviser le texte en chunks plus petits pour une meilleure gestion
        this.textChunks = this.currentText
            .split(/[.!?]+/)
            .filter(chunk => chunk.trim().length > 0)
            .map(chunk => chunk.trim() + '.');
            
        console.log(`Texte préparé en ${this.textChunks.length} segments`);
    }
    
    bindEvents() {
        const playPauseBtn = document.getElementById('playPauseBtn');
        const stopBtn = document.getElementById('stopBtn');
        const speedControl = document.getElementById('speedControl');
        const voiceSelect = document.getElementById('voiceSelect');
        
        if (playPauseBtn) {
            playPauseBtn.addEventListener('click', () => {
                this.isReading ? this.pause() : this.play();
            });
        }
        
        if (stopBtn) {
            stopBtn.addEventListener('click', () => this.stop());
        }
        
        if (speedControl) {
            speedControl.addEventListener('change', (e) => {
                this.setSpeed(parseFloat(e.target.value));
            });
        }
        
        if (voiceSelect) {
            voiceSelect.addEventListener('change', (e) => {
                this.setVoice(parseInt(e.target.value));
            });
        }
    }
    
    play() {
        if (!this.currentText) {
            this.showNotification('Le texte n\'est pas encore disponible', 'warning');
            return;
        }
        
        if (this.synthesis.speaking && this.synthesis.paused) {
            // Reprendre la lecture en cours
            this.synthesis.resume();
            this.updatePlayPauseButton(true);
            this.isReading = true;
            this.showAudioProgress();
            return;
        }
        
        // Commencer une nouvelle lecture
        this.startReading();
    }
    
    startReading() {
        const textToRead = this.textChunks.slice(this.currentChunkIndex).join(' ');
        
        if (!textToRead) {
            this.showNotification('Fin de la lecture atteinte', 'info');
            this.reset();
            return;
        }
        
        this.utterance = new SpeechSynthesisUtterance(textToRead);
        
        // Configuration de l'utterance
        this.utterance.rate = parseFloat(document.getElementById('speedControl').value);
        this.utterance.volume = 1;
        this.utterance.pitch = 1;
        
        // Sélectionner la voix
        const voiceIndex = parseInt(document.getElementById('voiceSelect').value);
        if (!isNaN(voiceIndex) && this.voices[voiceIndex]) {
            this.utterance.voice = this.voices[voiceIndex];
        }
        
        // Événements
        this.utterance.onstart = () => {
            this.isReading = true;
            this.updatePlayPauseButton(true);
            this.showAudioProgress();
            this.showNotification('Lecture audio démarrée', 'success');
        };
        
        this.utterance.onend = () => {
            this.isReading = false;
            this.updatePlayPauseButton(false);
            this.currentChunkIndex = this.textChunks.length; // Marquer comme terminé
            this.updateProgress(100);
            this.saveReadingPosition();
            this.showNotification('Lecture terminée', 'info');
        };
        
        this.utterance.onerror = (event) => {
            console.error('Erreur de lecture audio:', event);
            this.isReading = false;
            this.updatePlayPauseButton(false);
            this.showNotification('Erreur lors de la lecture audio', 'error');
        };
        
        this.utterance.onboundary = (event) => {
            // Calculer et mettre à jour le progrès
            const progress = ((event.charIndex / textToRead.length) * 100);
            this.updateProgress(progress);
        };
        
        // Commencer la lecture
        this.synthesis.speak(this.utterance);
    }
    
    pause() {
        if (this.synthesis.speaking && !this.synthesis.paused) {
            this.synthesis.pause();
            this.isReading = false;
            this.updatePlayPauseButton(false);
            this.saveReadingPosition();
            this.showNotification('Lecture en pause', 'info');
        }
    }
    
    stop() {
        this.synthesis.cancel();
        this.isReading = false;
        this.updatePlayPauseButton(false);
        this.hideAudioProgress();
        this.currentChunkIndex = 0;
        this.updateProgress(0);
        this.saveReadingPosition();
        this.showNotification('Lecture arrêtée', 'info');
    }
    
    reset() {
        this.stop();
        this.currentChunkIndex = 0;
        this.progress = 0;
        this.updateProgress(0);
    }
    
    setSpeed(speed) {
        if (this.utterance) {
            // Pour changer la vitesse en cours, il faut redémarrer
            if (this.isReading) {
                const wasReading = true;
                this.pause();
                setTimeout(() => {
                    if (wasReading) this.play();
                }, 100);
            }
        }
    }
    
    setVoice(voiceIndex) {
        if (this.voices[voiceIndex]) {
            // Redémarrer avec la nouvelle voix si en cours de lecture
            if (this.isReading) {
                const wasReading = true;
                this.pause();
                setTimeout(() => {
                    if (wasReading) this.play();
                }, 100);
            }
        }
    }
    
    updatePlayPauseButton(isReading) {
        const playIcon = document.getElementById('playIcon');
        const pauseIcon = document.getElementById('pauseIcon');
        
        if (isReading) {
            playIcon?.classList.add('hidden');
            pauseIcon?.classList.remove('hidden');
        } else {
            playIcon?.classList.remove('hidden');
            pauseIcon?.classList.add('hidden');
        }
    }
    
    showAudioProgress() {
        const audioProgress = document.getElementById('audioProgress');
        if (audioProgress) {
            audioProgress.classList.remove('hidden');
        }
    }
    
    hideAudioProgress() {
        const audioProgress = document.getElementById('audioProgress');
        if (audioProgress) {
            audioProgress.classList.add('hidden');
        }
    }
    
    updateProgress(percentage) {
        this.progress = percentage;
        
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
        
        if (progressText) {
            progressText.textContent = `${Math.round(percentage)}%`;
        }
    }
    
    saveReadingPosition() {
        const bookId = '{{ $book->id }}';
        const position = {
            chunkIndex: this.currentChunkIndex,
            progress: this.progress,
            timestamp: Date.now()
        };
        
        localStorage.setItem(`audiobook_position_${bookId}`, JSON.stringify(position));
    }
    
    loadReadingPosition() {
        const bookId = '{{ $book->id }}';
        const savedPosition = localStorage.getItem(`audiobook_position_${bookId}`);
        
        if (savedPosition) {
            try {
                const position = JSON.parse(savedPosition);
                this.currentChunkIndex = position.chunkIndex || 0;
                this.progress = position.progress || 0;
                this.updateProgress(this.progress);
                
                if (this.progress > 0) {
                    this.showNotification(`Position de lecture restaurée (${Math.round(this.progress)}%)`, 'info');
                }
            } catch (error) {
                console.error('Erreur lors de la restauration de la position:', error);
            }
        }
    }
    
    showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-orange-500',
            info: 'bg-blue-500'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        // Suppression automatique
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 4000);
    }
}

// Initialiser le lecteur audio avancé quand la page est chargée
document.addEventListener('DOMContentLoaded', function() {
    @if($book->file)
        // Charger PDF.js d'abord
        const pdfScript = document.createElement('script');
        pdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        pdfScript.onload = function() {
            // Configurer PDF.js
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            
            // Charger le script audio avancé
            const script = document.createElement('script');
            script.src = '{{ asset("js/audio-reader.js") }}';
            script.onload = function() {
            // Initialiser le lecteur audio avancé
            window.audioBookReader = new AdvancedAudioBookReader({
                bookId: {{ $book->id }},
                bookTitle: '{{ addslashes($book->title) }}',
                pdfUrl: '{{ asset("storage/" . $book->file) }}',
                enablePdfExtraction: true,
                autoSave: true,
                
                // Callbacks pour les événements
                onStart: () => {
                    console.log('Lecture audio démarrée');
                    // Marquer le PDF comme actif
                    document.querySelector('.pdf-container')?.classList.add('audio-active');
                    updateAudioStatus('🎵 Lecture en cours...', 'info');
                },
                
                onPause: () => {
                    console.log('Lecture en pause');
                    updateAudioStatus('⏸️ Lecture en pause', 'warning');
                },
                
                onStop: () => {
                    console.log('Lecture arrêtée');
                    document.querySelector('.pdf-container')?.classList.remove('audio-active');
                    updateAudioStatus('⏹️ Lecture arrêtée', 'info');
                },
                
                onEnd: () => {
                    console.log('Lecture terminée');
                    document.querySelector('.pdf-container')?.classList.remove('audio-active');
                    updateAudioStatus('✅ Lecture terminée !', 'success');
                    // Optionnel: marquer le livre comme lu
                    markBookAsRead({{ $book->id }});
                },
                
                onError: (error) => {
                    console.error('Erreur audio:', error);
                    updateAudioStatus('❌ Erreur de lecture audio', 'error');
                },
                
                onProgress: (percentage) => {
                    // Optionnel: synchroniser avec le serveur
                    if (percentage % 10 === 0) { // Sauvegarder tous les 10%
                        saveReadingProgressToServer({{ $book->id }}, percentage);
                    }
                }
            });
            
                // Ajouter la classe fade-in aux contrôles
                document.querySelector('.audio-controls')?.classList.add('fade-in-controls');
            };
            document.head.appendChild(script);
        };
        document.head.appendChild(pdfScript);
        
        // Fonctions utilitaires
        function markBookAsRead(bookId) {
            // Optionnel: marquer le livre comme lu
            console.log('Livre terminé:', bookId);
            @auth
                // Ici vous pourriez ajouter une route pour marquer comme lu
                console.log('Utilisateur connecté - livre marqué comme lu');
            @else
                console.log('Invité - progression sauvegardée localement');
            @endauth
        }

        function updateAudioStatus(message, type = 'info') {
            const statusElement = document.getElementById('audioStatusMessage');
            if (statusElement) {
                const colors = {
                    info: 'text-blue-600 dark:text-blue-400',
                    success: 'text-green-600 dark:text-green-400',
                    warning: 'text-orange-600 dark:text-orange-400',
                    error: 'text-red-600 dark:text-red-400'
                };
                
                const icons = {
                    info: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
                    success: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    warning: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                    error: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                };
                
                statusElement.className = `text-xs ${colors[type]} mt-1`;
                statusElement.innerHTML = `
                    <span class="inline-flex items-center">
                        ${icons[type]}
                        ${message}
                    </span>
                `;
            }
        }
        
        function saveReadingProgressToServer(bookId, percentage) {
            if (window.audioBookReader) {
                const state = window.audioBookReader.getState();
                
                fetch('/audiobook/books/' + bookId + '/reading-position', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        chunk_index: state.currentChunk,
                        position: Math.round(percentage),
                        total_chunks: state.totalChunks,
                        timestamp: Date.now()
                    })
                }).catch(error => console.log('Erreur sauvegarde position:', error));
            }
        }
        
                // Ajouter le gestionnaire pour le bouton d'extraction manuelle
                const retryBtn = document.getElementById('retryExtractionBtn');
                if (retryBtn) {
                    retryBtn.addEventListener('click', async function() {
                        if (window.audioBookReader) {
                            this.disabled = true;
                            this.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                            
                            try {
                                await window.audioBookReader.loadPdfText();
                            } catch (error) {
                                console.error('Erreur lors de la nouvelle tentative:', error);
                            } finally {
                                this.disabled = false;
                                this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"/></svg>';
                            }
                        }
                    });
                }
                
                // Essayer de charger le texte depuis le serveur si possible
                fetch('/audiobook/books/{{ $book->id }}/extract-text', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && window.audioBookReader) {
                        // Vérifier si c'est du vrai contenu ou de la démo
                        if (data.extraction.method !== 'demo' && data.extraction.text.length > 200) {
                            // Mettre à jour le lecteur avec le texte du serveur
                            window.audioBookReader.currentText = data.extraction.text;
                            window.audioBookReader.textChunks = data.extraction.chunks;
                            window.audioBookReader.totalCharacters = data.extraction.stats.total_characters;
                            window.audioBookReader.prepareTextForReading();
                            
                            updateAudioStatus('✅ Texte PDF extrait avec succès !', 'success');
                            console.log('Texte chargé depuis le serveur:', data.extraction.method);
                        } else {
                            updateAudioStatus('⚠️ Mode démo : PDF non extractible automatiquement', 'warning');
                            console.log('Texte de démo détecté, utilisation du fallback local');
                        }
                    }
                })
                .catch(error => {
                    updateAudioStatus('⚠️ Mode démo : Utilisation du texte d\'exemple', 'warning');
                    console.log('Utilisation du texte de fallback:', error);
                    // Le lecteur utilisera le texte de démonstration
                });    @endif
});
</script>
@endpush

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
    .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ========================================
   STYLES POUR LE LECTEUR AUDIO
   ======================================== */

/* Animation des contrôles audio */
.audio-controls {
    animation: slideInFromTop 0.5s ease-out;
}

@keyframes slideInFromTop {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Effets de hover pour les boutons audio */
#playPauseBtn, #stopBtn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#playPauseBtn:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.3);
}

#stopBtn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
}

/* Animation de la barre de progression */
#progressBar {
    transition: width 0.3s ease-out;
    position: relative;
    overflow: hidden;
}

#progressBar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.4), 
        transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Styles pour les sélecteurs */
#speedControl, #voiceSelect {
    transition: all 0.2s ease;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

#speedControl:hover, #voiceSelect:hover {
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

#speedControl:focus, #voiceSelect:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
}

/* Animation de pulsation pour le bouton play actif */
.reading-active {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(147, 51, 234, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(147, 51, 234, 0);
    }
}

/* Responsive design pour les contrôles audio */
@media (max-width: 640px) {
    .audio-controls {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    #voiceSelect {
        max-width: none;
    }
    
    .flex-col.sm\\:flex-row {
        align-items: stretch;
    }
}

/* Style pour les notifications */
.audio-notification {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Animation pour le conteneur PDF */
.pdf-container {
    transition: all 0.3s ease;
}

.pdf-container.audio-active {
    border-color: #8b5cf6;
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.2);
}

/* Indicateur de lecture active */
.reading-indicator {
    position: relative;
}

.reading-indicator::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #8b5cf6, #06b6d4, #10b981, #f59e0b);
    background-size: 400% 400%;
    border-radius: inherit;
    z-index: -1;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Amélioration des tooltips */
[title] {
    position: relative;
}

/* États de chargement */
.loading-audio {
    opacity: 0.7;
    pointer-events: none;
}

.loading-audio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top-color: #8b5cf6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Amélioration de l'accessibilité */
.audio-controls button:focus,
.audio-controls select:focus {
    outline: 2px solid #8b5cf6;
    outline-offset: 2px;
}

/* Style sombre pour les contrôles */
.dark .audio-controls {
    background: rgba(0, 0, 0, 0.3);
}

.dark #speedControl,
.dark #voiceSelect {
    background-color: #374151;
    border-color: #4b5563;
    color: #f3f4f6;
}

.dark #speedControl:hover,
.dark #voiceSelect:hover {
    border-color: #8b5cf6;
    background-color: #4b5563;
}

/* Animation d'entrée pour les contrôles */
.fade-in-controls {
    animation: fadeInControls 0.6s ease-out forwards;
}

@keyframes fadeInControls {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Animation shimmer pour la barre de progression */
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}

.animate-shimmer {
    animation: shimmer 2s infinite;
}

/* Styles pour les contrôles de volume */
input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    height: 8px;
    border-radius: 4px;
    background: linear-gradient(to right, #8b5cf6 0%, #8b5cf6 var(--value, 100%), #e5e7eb var(--value, 100%), #e5e7eb 100%);
    outline: none;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #8b5cf6;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
}

input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(139, 92, 246, 0.3);
}

input[type="range"]::-moz-range-thumb {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #8b5cf6;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Styles pour les boutons de chapitre */
#prevChapterBtn, #nextChapterBtn {
    border-radius: 8px;
    transition: all 0.2s ease;
}

#prevChapterBtn:hover, #nextChapterBtn:hover {
    background-color: rgba(139, 92, 246, 0.1);
    transform: scale(1.05);
}

/* Indicateur de chapitre animé */
#currentChapter {
    transition: all 0.3s ease;
    animation: slideInChapter 0.5s ease-out;
}

@keyframes slideInChapter {
    from {
        opacity: 0;
        transform: translateX(-10px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}

/* Style pour les touches de raccourci */
kbd {
    font-family: inherit;
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    border: 1px solid currentColor;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* État de lecture active pour le conteneur PDF */
.pdf-container.audio-active {
    box-shadow: 0 0 30px rgba(139, 92, 246, 0.3);
    border-color: #8b5cf6;
}

.pdf-container.audio-active iframe {
    filter: brightness(1.05) saturate(1.1);
}

/* Animations fluides pour tous les éléments interactifs */
.audio-controls * {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de focus amélioré */
.audio-controls button:focus-visible,
.audio-controls select:focus-visible,
.audio-controls input:focus-visible {
    outline: 2px solid #8b5cf6;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
}
</style>

<!-- Scripts pour le système IA -->
<script src="{{ asset('js/ai-recommendation-system.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enregistrer l'interaction de vue du livre
    if (window.aiSystem) {
        window.aiSystem.recordInteraction({{ $book->id }}, 'view', 2.0, {
            source: 'book_page',
            book_title: '{{ addslashes($book->title) }}',
            category: '{{ $book->category->name ?? "" }}'
        });
    }

    // Tracker les actions sur cette page
    document.addEventListener('click', function(e) {
        if (!window.aiSystem) return;

        // Favoris
        if (e.target.closest('.favorite-button') || e.target.closest('[data-favorite]')) {
            window.aiSystem.recordInteraction({{ $book->id }}, 'like', 5.0);
        }

        // Téléchargement/Lecture PDF
        if (e.target.closest('.download-btn') || e.target.closest('[data-download]')) {
            window.aiSystem.recordInteraction({{ $book->id }}, 'download', 8.0);
        }

        // Audio
        if (e.target.closest('.audio-controls')) {
            window.aiSystem.recordInteraction({{ $book->id }}, 'read_time', 6.0, {
                audio_interaction: true
            });
        }

        // Review
        if (e.target.closest('[href*="reviews"]')) {
            window.aiSystem.recordInteraction({{ $book->id }}, 'comment', 7.0);
        }
    });

    // Tracker le temps passé sur la page
    let startTime = Date.now();
    let timeTracked = false;

    function trackReadingTime() {
        if (timeTracked) return;
        
        const timeSpent = Math.floor((Date.now() - startTime) / 1000);
        if (timeSpent > 30 && window.aiSystem) { // Plus de 30 secondes
            window.aiSystem.recordInteraction({{ $book->id }}, 'read_time', Math.min(10, timeSpent / 60), {
                time_spent_seconds: timeSpent,
                engagement_type: 'page_reading'
            });
            timeTracked = true;
        }
    }

    // Tracker quand l'utilisateur quitte la page
    window.addEventListener('beforeunload', trackReadingTime);
    
    // Tracker après 2 minutes d'activité
    setTimeout(trackReadingTime, 120000);
});
</script>
@endpush