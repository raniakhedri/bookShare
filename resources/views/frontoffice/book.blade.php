@extends('frontoffice.layouts.app')

@section('title', 'Our Books - Bookly')

@push('head')
<!-- CSRF Token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-8">
        <div class="container mx-auto px-4 lg:px-8">
            <!-- Animated header avec statistiques -->
            <div class="text-center mb-12 transition-all duration-750 starting:opacity-0 starting:translate-y-6">
                <h1 class="text-4xl lg:text-6xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 bg-gradient-to-r from-[#f87171] to-[#f87171] dark:from-[#FF4433] dark:to-[#e5391b] bg-clip-text text-transparent">
                    Notre Bibliothèque
                </h1>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto mb-8">
                    Découvrez notre collection de livres partagés. Chaque livre raconte une histoire et attend son nouveau lecteur.
                </p>

                <!-- Statistiques rapides -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white transform hover:scale-105 transition-all duration-300 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total des livres</p>
                                <p class="text-3xl font-bold">{{ $books->total() }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white transform hover:scale-105 transition-all duration-300 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Disponibles</p>
                                <p class="text-3xl font-bold">{{ $books->where('availability', true)->count() }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white transform hover:scale-105 transition-all duration-300 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Catégories</p>
                                <p class="text-3xl font-bold">{{ $categories->count() }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c1.35 0 2.56.48 3.52 1.28M8 7l1.67 1.67M15 4v1.5m4 0V8a2 2 0 01-2 2h-1.5m-4.5 0V8c0-1.1-.9-2-2-2H7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl p-6 text-white transform hover:scale-105 transition-all duration-300 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Nouveautés</p>
                                <p class="text-3xl font-bold">{{ $books->where('created_at', '>=', now()->subWeek())->count() }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and search - Section moderne -->
            <div class="mb-8 bg-white dark:bg-[#161615] rounded-xl shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden">
                <!-- En-tête de la section filtres -->
                <div class="bg-gradient-to-r from-[#f87171] to-[#f87171] dark:from-[#FF4433] dark:to-[#e5391b] px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Recherche et Filtres</h2>
                            <p class="text-white/80 text-sm">Trouvez exactement ce que vous cherchez</p>
                        </div>
                    </div>
                </div>

                <!-- Contenu des filtres -->
                <div class="p-6 space-y-6">
                    <!-- Barre de recherche principale -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#706f6c] dark:text-[#A1A09A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Recherchez un livre, un auteur ou une catégorie..."
                            class="w-full pl-12 pr-12 py-4 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl bg-gray-50 dark:bg-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:bg-white dark:focus:bg-[#161615] focus:ring-4 focus:ring-orange-100 dark:focus:ring-orange-900/50 transition-all duration-300"
                        >
                        <div id="searchSpinner" class="hidden absolute right-4 top-1/2 transform -translate-y-1/2">
                            <div class="animate-spin rounded-full h-6 w-6 border-2 border-[#f53003] border-t-transparent"></div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Filtre par catégorie -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c1.35 0 2.56.48 3.52 1.28M8 7l1.67 1.67M15 4v1.5m4 0V8a2 2 0 01-2 2h-1.5m-4.5 0V8c0-1.1-.9-2-2-2H7"></path>
                                </svg>
                                Catégorie
                            </label>
                            <select id="categoryFilter" class="w-full px-4 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-4 focus:ring-orange-100 dark:focus:ring-orange-900/50 transition-all duration-300">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre par état -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                État
                            </label>
                            <select id="conditionFilter" class="w-full px-4 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-4 focus:ring-orange-100 dark:focus:ring-orange-900/50 transition-all duration-300">
                                <option value="">Tous les états</option>
                                <option value="New">Neuf</option>
                                <option value="Very good">Très bon</option>
                                <option value="Good">Bon</option>
                                <option value="Acceptable">Acceptable</option>
                            </select>
                        </div>

                        <!-- Filtre par disponibilité -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Disponibilité
                            </label>
                            <select id="availabilityFilter" class="w-full px-4 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-4 focus:ring-orange-100 dark:focus:ring-orange-900/50 transition-all duration-300">
                                <option value="">Tous</option>
                                <option value="available">Disponible</option>
                                <option value="unavailable">Non disponible</option>
                            </select>
                        </div>

                        <!-- Tri -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                                </svg>
                                Trier par
                            </label>
                            <select id="sortFilter" class="w-full px-4 py-3 border-2 border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-4 focus:ring-orange-100 dark:focus:ring-orange-900/50 transition-all duration-300">
                                <option value="newest">Plus récent</option>
                                <option value="oldest">Plus ancien</option>
                                <option value="title_asc">Titre A-Z</option>
                                <option value="title_desc">Titre Z-A</option>
                                <option value="author_asc">Auteur A-Z</option>
                                <option value="author_desc">Auteur Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button onclick="performSearch()" class="flex items-center gap-2 px-6 py-3 bg-[#f87171] dark:bg-[#FF4433] text-white rounded-lg font-medium hover:bg-[#d42a03] dark:hover:bg-[#e5391b] transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        <button onclick="clearFilters()" class="flex items-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Effacer
                        </button>
                        <button id="toggleAdvanced" onclick="toggleAdvancedFilters()" class="flex items-center gap-2 px-4 py-3 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                            <svg class="w-4 h-4 transform transition-transform" id="advancedIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <span id="advancedText">Filtres avancés</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- AI Book Recommendation Section -->
            <div class="mb-8 p-6 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg border-2 border-orange-200 dark:border-orange-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-full">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">🤖 AI Book Recommendation</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Describe what you're looking for and let our AI suggest the perfect book!</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Description Input -->
                    <div>
                        <label for="bookDescription" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            What kind of book are you looking for?
                        </label>
                        <textarea 
                            id="bookDescription" 
                            rows="3" 
                            placeholder="e.g., 'I want a thrilling mystery novel set in Victorian London with a strong female protagonist' or 'Looking for a sci-fi book about space exploration with deep philosophical themes'..."
                            class="w-full px-4 py-3 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#1a1a1a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-800 transition-all resize-none"
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Be as specific as possible for better recommendations</span>
                            <span id="charCount" class="text-xs text-[#706f6c] dark:text-[#A1A09A]">0/500</span>
                        </div>
                    </div>

                    <!-- Quick Suggestion Buttons -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mr-2">Quick suggestions:</span>
                        <button type="button" onclick="fillSuggestion('Romance')" class="px-3 py-1 text-xs bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200 rounded-full hover:bg-pink-200 dark:hover:bg-pink-800 transition-colors">
                            💕 Romance
                        </button>
                        <button type="button" onclick="fillSuggestion('Mystery')" class="px-3 py-1 text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors">
                            🔍 Mystery
                        </button>
                        <button type="button" onclick="fillSuggestion('Sci-Fi')" class="px-3 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                            🚀 Sci-Fi
                        </button>
                        <button type="button" onclick="fillSuggestion('Fantasy')" class="px-3 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                            ⚔️ Fantasy
                        </button>
                        <button type="button" onclick="fillSuggestion('Historical')" class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors">
                            🏛️ Historical
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button 
                            id="getRecommendationBtn"
                            onclick="getAIRecommendation()" 
                            type="button"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Get AI Recommendation</span>
                        </button>
                        <button 
                            onclick="clearRecommendation()" 
                            type="button"
                            class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <!-- AI Recommendation Results -->
                <div id="aiRecommendationResults" class="hidden mt-6 p-4 bg-white dark:bg-[#1a1a1a] rounded-lg border border-orange-200 dark:border-orange-700">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <h4 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">AI Recommendation</h4>
                    </div>
                    <div id="recommendationContent">
                        <!-- Content will be dynamically inserted -->
                    </div>
                </div>

                <!-- Loading State -->
                <div id="aiLoadingState" class="hidden mt-6 p-6 bg-white dark:bg-[#1a1a1a] rounded-lg border border-orange-200 dark:border-orange-700">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                        <div class="text-center">
                            <p class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">🤖 AI is analyzing your preferences...</p>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">This may take a few seconds</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Container principal des résultats -->
            <div id="booksContainer" class="mb-12">
                <!-- Message "Aucun résultat" -->
                <div id="noResults" class="hidden text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun livre trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Essayez de modifier vos critères de recherche</p>
                </div>

                <!-- Statistiques et contrôles d'affichage -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="flex items-center gap-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg class="w-5 h-5 text-[#f53003] dark:text-[#FF4433]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="font-semibold">{{ $books->total() }}</span>
                            <span>livres trouvés</span>
                        </div>
                        <div class="h-6 w-px bg-gray-300 dark:bg-gray-600"></div>
                        <div class="flex items-center gap-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">{{ $books->where('availability', true)->count() }}</span>
                            <span>disponibles</span>
                        </div>
                    </div>

                    <!-- Toggle de vue -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Vue :</span>
                        <div class="flex bg-white dark:bg-[#161615] rounded-lg p-1 border border-gray-200 dark:border-gray-700">
                            <button id="gridViewBtn" onclick="toggleView('grid')" class="px-3 py-2 rounded-md text-sm font-medium transition-all bg-[#f87171] text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button id="listViewBtn" onclick="toggleView('list')" class="px-3 py-2 rounded-md text-sm font-medium transition-all text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grille de livres modernisée -->
                <div id="booksGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 transition-all duration-500">
                    @foreach($books as $book)
                        <div class="book-card transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white dark:bg-[#161615] rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full">
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
                                @auth
                                <button onclick="toggleFavorite({{ $book->id }}, this)" 
                                        class="group relative p-3 bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 favorite-btn"
                                        data-book-id="{{ $book->id }}"
                                        title="{{ auth()->user()->favorites()->where('book_id', $book->id)->exists() ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                    @if(auth()->user()->favorites()->where('book_id', $book->id)->exists())
                                        <!-- Cœur plein (favori actif) -->
                                        <svg class="w-5 h-5 text-red-500 transition-all duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    @else
                                        <!-- Cœur vide (non favori) -->
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-all duration-300 group-hover:text-red-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    @endif
                                    <!-- Effet de pulse au clic -->
                                    <div class="absolute inset-0 rounded-full bg-red-500/20 scale-0 group-active:scale-100 transition-transform duration-200"></div>
                                </button>
                                @else
                                <a href="{{ route('login') }}" 
                                   class="group relative p-3 bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
                                   title="Connectez-vous pour ajouter aux favoris">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-all duration-300 group-hover:text-red-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </a>
                                @endauth
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

            <!-- Pagination et contrôles d'affichage -->
            @if($books->hasPages())
                <div class="mt-12 space-y-6">
                    <!-- Informations sur les résultats -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">Affichage :</span>
                            <span class="px-2 py-1 bg-[#f87171] dark:bg-[#FF4433] text-white rounded-md">
                                {{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }}
                            </span>
                            <span>sur</span>
                            <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $books->total() }}</span>
                            <span>livres</span>
                        </div>

                        <!-- Sélecteur du nombre d'éléments par page -->
                        <div class="flex items-center gap-2">
                            <label for="perPage" class="font-medium whitespace-nowrap">Livres par page :</label>
                            <select id="perPage" onchange="changePerPage(this.value)" 
                                    class="px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-800 transition-all">
                                <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24</option>
                                <option value="36" {{ request('per_page', 12) == 36 ? 'selected' : '' }}>36</option>
                                <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pagination moderne -->
                    <div class="flex justify-center">
                        <div class="flex items-center space-x-1">
                            {{-- Bouton Précédent --}}
                            @if ($books->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 dark:text-gray-600 cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $books->previousPageUrl() }}" 
                                   class="px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-[#f53003] hover:text-white dark:hover:bg-[#FF4433] transition-all duration-200 hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            @endif

                            {{-- Numéros de page --}}
                            @php
                                $start = max(1, $books->currentPage() - 2);
                                $end = min($books->lastPage(), $books->currentPage() + 2);
                            @endphp

                            {{-- Première page --}}
                            @if($start > 1)
                                <a href="{{ $books->url(1) }}" 
                                   class="px-4 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-[#f53003] hover:text-white dark:hover:bg-[#FF4433] transition-all duration-200 hover:scale-105">
                                    1
                                </a>
                                @if($start > 2)
                                    <span class="px-2 py-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">...</span>
                                @endif
                            @endif

                            {{-- Pages du milieu --}}
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $books->currentPage())
                                    <span class="px-4 py-2 text-sm font-bold text-white bg-[#f87171] dark:bg-[#FF4433] rounded-lg shadow-lg transform scale-105">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $books->url($i) }}" 
                                       class="px-4 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-[#f53003] hover:text-white dark:hover:bg-[#FF4433] transition-all duration-200 hover:scale-105">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Dernière page --}}
                            @if($end < $books->lastPage())
                                @if($end < $books->lastPage() - 1)
                                    <span class="px-2 py-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">...</span>
                                @endif
                                <a href="{{ $books->url($books->lastPage()) }}" 
                                   class="px-4 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-[#f53003] hover:text-white dark:hover:bg-[#FF4433] transition-all duration-200 hover:scale-105">
                                    {{ $books->lastPage() }}
                                </a>
                            @endif

                            {{-- Bouton Suivant --}}
                            @if ($books->hasMorePages())
                                <a href="{{ $books->nextPageUrl() }}" 
                                   class="px-3 py-2 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-[#f53003] hover:text-white dark:hover:bg-[#FF4433] transition-all duration-200 hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm text-gray-400 dark:text-gray-600 cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation rapide -->
                    <div class="flex justify-center">
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-[#706f6c] dark:text-[#A1A09A]">Aller à la page :</span>
                            <input type="number" 
                                   id="gotoPage" 
                                   min="1" 
                                   max="{{ $books->lastPage() }}" 
                                   placeholder="{{ $books->currentPage() }}"
                                   class="w-16 px-2 py-1 text-center border border-[#e3e3e0] dark:border-[#3E3E3A] rounded bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#f53003] dark:focus:border-[#FF4433] focus:ring-1 focus:ring-orange-200 dark:focus:ring-orange-800 transition-all">
                            <button onclick="gotoPage()" 
                                    class="px-3 py-1 bg-[#f53003] dark:bg-[#FF4433] text-white rounded hover:bg-[#d42a03] dark:hover:bg-[#e5391b] transition-colors">
                                Go
                            </button>
                        </div>
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

@push('scripts')
<script>
    // Définir la variable d'authentification globale
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    // Fonction pour échapper les caractères HTML
    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
// Fonction de debounce pour limiter les appels à l'API
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Fonction principale de recherche
function performSearch() {
    const searchValue = document.getElementById('searchInput').value;
    const categoryValue = document.getElementById('categoryFilter').value;
    const conditionValue = document.getElementById('conditionFilter').value;
    const availabilityValue = document.getElementById('availabilityFilter').value;
    const sortValue = document.getElementById('sortFilter').value;
    const spinner = document.getElementById('searchSpinner');
    const booksGrid = document.getElementById('booksGrid');
    const noResults = document.getElementById('noResults');

    // Afficher le spinner
    spinner.classList.remove('hidden');

    // Construire l'URL avec les paramètres
    const params = new URLSearchParams({
        search: searchValue,
        category: categoryValue,
        condition: conditionValue,
        availability: availabilityValue,
        sort: sortValue
    });

    // Faire la requête AJAX
    fetch(`{{ route('book') }}?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(response => {
        if (!response.success) {
            throw new Error(response.message || 'Une erreur est survenue');
        }

        // Transition de fondu
        booksGrid.style.opacity = '0';
        
        setTimeout(() => {
            try {
                // Vider la grille
                booksGrid.innerHTML = '';
                
                if (!response.books || response.books.length === 0) {
                    // Afficher le message "Aucun résultat"
                    noResults.classList.remove('hidden');
                    booksGrid.classList.add('hidden');
                } else {
                    // Cacher le message "Aucun résultat" et afficher la grille
                    noResults.classList.add('hidden');
                    booksGrid.classList.remove('hidden');
                    
                    // Générer les cartes de livres
                    response.books.forEach(book => {
                        try {
                            const bookCard = createBookCard(book);
                            booksGrid.appendChild(bookCard);
                        } catch (err) {
                            console.error('Erreur lors de la création de la carte:', err);
                        }
                    });
                }
                
                // Animation de fondu
                booksGrid.style.opacity = '1';
                
                // Réinitialiser les événements
                initializeBookCardEvents();
            } catch (err) {
                console.error('Erreur lors du traitement des résultats:', err);
                showNotification('Une erreur est survenue lors de l\'affichage des résultats', 'error');
            }
            
            // Cacher le spinner dans tous les cas
            spinner.classList.add('hidden');
        }, 300);
        })
        .catch(error => {
            console.error('Error:', error);
            spinner.classList.add('hidden');
            booksGrid.style.opacity = '1';
            
            // Afficher un message d'erreur approprié
            let errorMessage = 'Une erreur est survenue lors de la recherche';
            if (error.message) {
                errorMessage += ': ' + error.message;
            }
            
            showNotification(errorMessage, 'error');
            
            // Réinitialiser l'affichage
            if (booksGrid.children.length === 0) {
                noResults.classList.remove('hidden');
                booksGrid.classList.add('hidden');
            }
        });
}

// Initialiser la recherche avec debounce
const debouncedSearch = debounce(performSearch, 300);

// Écouteurs d'événements
document.getElementById('searchInput').addEventListener('input', debouncedSearch);
document.getElementById('categoryFilter').addEventListener('change', performSearch);
document.getElementById('conditionFilter').addEventListener('change', performSearch);
document.getElementById('availabilityFilter').addEventListener('change', performSearch);
document.getElementById('sortFilter').addEventListener('change', performSearch);

// Pagination Functions
function changePerPage(perPage) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page
    
    window.location.href = currentUrl.toString();
}

function gotoPage() {
    const pageInput = document.getElementById('gotoPage');
    const pageNumber = parseInt(pageInput.value);
    const maxPage = parseInt(pageInput.getAttribute('max'));
    
    if (pageNumber && pageNumber >= 1 && pageNumber <= maxPage) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', pageNumber);
        window.location.href = currentUrl.toString();
    } else {
        showNotification('Numéro de page invalide', 'error');
        pageInput.value = '';
    }
}

// Advanced Filters Functions
function toggleAdvancedFilters() {
    const icon = document.getElementById('advancedIcon');
    const text = document.getElementById('advancedText');
    const isExpanded = icon.classList.contains('rotate-180');
    
    if (isExpanded) {
        icon.classList.remove('rotate-180');
        text.textContent = 'Filtres avancés';
    } else {
        icon.classList.add('rotate-180');
        text.textContent = 'Masquer filtres';
    }
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('conditionFilter').value = '';
    document.getElementById('availabilityFilter').value = '';
    document.getElementById('sortFilter').value = 'newest';
    
    performSearch();
    showNotification('Filtres effacés', 'info');
}

// View Toggle Functions
function toggleView(viewType) {
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');
    const booksGrid = document.getElementById('booksGrid');
    
    // Update button states
    if (viewType === 'grid') {
        gridBtn.className = 'px-3 py-2 rounded-md text-sm font-medium transition-all bg-[#f53003] text-white';
        listBtn.className = 'px-3 py-2 rounded-md text-sm font-medium transition-all text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]';
        booksGrid.className = 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 transition-all duration-500';
    } else {
        listBtn.className = 'px-3 py-2 rounded-md text-sm font-medium transition-all bg-[#f53003] text-white';
        gridBtn.className = 'px-3 py-2 rounded-md text-sm font-medium transition-all text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]';
        booksGrid.className = 'space-y-4 transition-all duration-500';
    }
    
    // Save preference
    localStorage.setItem('bookViewType', viewType);
    
    // Re-render books with new layout
    const currentBooks = Array.from(booksGrid.children);
    if (currentBooks.length > 0) {
        booksGrid.innerHTML = '';
        currentBooks.forEach(bookElement => {
            if (viewType === 'list') {
                bookElement.className = 'book-card-list bg-white dark:bg-[#161615] rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden';
            } else {
                bookElement.className = 'book-card transform hover:scale-105 transition-all duration-300';
            }
            booksGrid.appendChild(bookElement);
        });
    }
}

// Initialize view on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('bookViewType') || 'grid';
    toggleView(savedView);
});

// AI Recommendation Functions
function fillSuggestion(type) {
    const textarea = document.getElementById('bookDescription');
    const suggestions = {
        'Romance': "I'm looking for a heartwarming romance novel with strong character development and emotional depth. I prefer contemporary settings with relatable protagonists.",
        'Mystery': "I want a gripping mystery novel with clever plot twists and a detective or investigator protagonist. I enjoy psychological suspense and unexpected revelations.",
        'Sci-Fi': "I'm interested in science fiction that explores futuristic technology, space exploration, or dystopian societies. I like thought-provoking themes about humanity's future.",
        'Fantasy': "I'm seeking an epic fantasy adventure with magical worlds, complex mythology, and heroic quests. I enjoy rich world-building and memorable characters.",
        'Historical': "I want a historical fiction novel that brings past eras to life with authentic details and compelling characters navigating historical events."
    };
    
    textarea.value = suggestions[type] || '';
    updateCharCount();
    textarea.focus();
}

function updateCharCount() {
    const textarea = document.getElementById('bookDescription');
    const charCount = document.getElementById('charCount');
    const length = textarea.value.length;
    charCount.textContent = `${length}/500`;
    
    if (length > 500) {
        charCount.classList.add('text-red-500');
        textarea.value = textarea.value.substring(0, 500);
    } else {
        charCount.classList.remove('text-red-500');
    }
}

function getAIRecommendation() {
    const description = document.getElementById('bookDescription').value.trim();
    const btn = document.getElementById('getRecommendationBtn');
    const loadingState = document.getElementById('aiLoadingState');
    const resultsDiv = document.getElementById('aiRecommendationResults');
    
    if (!description) {
        showNotification('Please describe what kind of book you\'re looking for!', 'error');
        return;
    }
    
    if (description.length < 10) {
        showNotification('Please provide a more detailed description (at least 10 characters)', 'error');
        return;
    }
    
    // Show loading state
    btn.disabled = true;
    loadingState.classList.remove('hidden');
    resultsDiv.classList.add('hidden');
    
    // Call the actual API
    fetch('/api/ai/book-recommendations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            description: description,
            limit: 3
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data.recommendations.length > 0) {
            displayAPIRecommendations(data.data.recommendations, data.data.analysis);
        } else {
            showNotification(data.message || 'No recommendations found. Try a different description.', 'warning');
            // Fallback to mock data if API fails
            const fallbackRecommendation = generateAIRecommendation(description);
            displayRecommendation(fallbackRecommendation);
        }
    })
    .catch(error => {
        console.error('API Error:', error);
        showNotification('Error getting recommendations. Showing sample results.', 'warning');
        // Fallback to mock data
        const fallbackRecommendation = generateAIRecommendation(description);
        displayRecommendation(fallbackRecommendation);
    })
    .finally(() => {
        // Hide loading and show results
        loadingState.classList.add('hidden');
        resultsDiv.classList.remove('hidden');
        btn.disabled = false;
    });
}

function generateAIRecommendation(description) {
    // This is a mock AI recommendation generator
    // In a real implementation, you would call your AI service/API
    
    const bookRecommendations = [
        {
            title: "The Seven Husbands of Evelyn Hugo",
            author: "Taylor Jenkins Reid",
            reason: "Based on your interest in character-driven stories, this novel offers deep emotional complexity and compelling character development.",
            genre: "Contemporary Fiction",
            rating: 4.8,
            availability: true,
            description: "A reclusive Hollywood icon reveals her secrets in this captivating tale of ambition, love, and sacrifice."
        },
        {
            title: "The Thursday Murder Club",
            author: "Richard Osman",
            reason: "Perfect for mystery lovers, this cozy mystery combines clever plotting with charming characters in a retirement community setting.",
            genre: "Mystery",
            rating: 4.6,
            availability: true,
            description: "Four unlikely friends meet weekly to investigate cold cases, but soon find themselves hunting a killer."
        },
        {
            title: "Project Hail Mary",
            author: "Andy Weir",
            reason: "An excellent choice for sci-fi enthusiasts who enjoy scientific accuracy mixed with humor and human determination.",
            genre: "Science Fiction",
            rating: 4.9,
            availability: false,
            description: "A lone astronaut must save humanity in this thrilling blend of science, mystery, and adventure."
        }
    ];
    
    // Simple keyword matching for demo purposes
    let selectedBook;
    const lowerDesc = description.toLowerCase();
    
    if (lowerDesc.includes('romance') || lowerDesc.includes('love') || lowerDesc.includes('relationship')) {
        selectedBook = bookRecommendations[0];
    } else if (lowerDesc.includes('mystery') || lowerDesc.includes('detective') || lowerDesc.includes('crime')) {
        selectedBook = bookRecommendations[1];
    } else if (lowerDesc.includes('sci-fi') || lowerDesc.includes('science') || lowerDesc.includes('space') || lowerDesc.includes('future')) {
        selectedBook = bookRecommendations[2];
    } else {
        selectedBook = bookRecommendations[Math.floor(Math.random() * bookRecommendations.length)];
    }
    
    return selectedBook;
}

function displayRecommendation(book) {
    const contentDiv = document.getElementById('recommendationContent');
    
    const template = `
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-16 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-white text-2xl">
                        📚
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h5 class="font-bold text-lg text-[#1b1b18] dark:text-[#EDEDEC]">${book.title}</h5>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                            book.availability ? 
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }">
                            ${book.availability ? '✅ Available' : '❌ Not Available'}
                        </span>
                    </div>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-2">by <strong>${book.author}</strong></p>
                    <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] mb-3">${book.description}</p>
                    
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full">
                            ${book.genre}
                        </span>
                        <div class="flex items-center gap-1">
                            <span class="text-yellow-400">⭐</span>
                            <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">${book.rating}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-[#161615] rounded-lg p-3 mb-3">
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">🤖 AI Analysis:</strong> ${book.reason}
                        </p>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="searchForBook('${book.title}')" class="px-4 py-2 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg text-sm font-medium hover:bg-[#d42a03] dark:hover:bg-[#e5391b] transition-colors">
                            🔍 Find This Book
                        </button>
                        <button onclick="getAnotherRecommendation()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            🔄 Another Suggestion
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    contentDiv.innerHTML = template;
}

function displayAPIRecommendations(recommendations, analysis) {
    const contentDiv = document.getElementById('recommendationContent');
    
    if (!recommendations || recommendations.length === 0) {
        contentDiv.innerHTML = `
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🤖</div>
                <p class="text-gray-600 dark:text-gray-400">No recommendations found for your description.</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Try describing your preferences differently.</p>
            </div>
        `;
        return;
    }

    const analysisInfo = analysis ? `
        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-400">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <strong>🧠 AI Analysis:</strong> 
                ${analysis.primary_genre ? `Detected genre: ${analysis.primary_genre}` : ''}
                ${analysis.primary_mood ? `• Mood: ${analysis.primary_mood}` : ''}
            </p>
        </div>
    ` : '';

    const recommendationsHTML = recommendations.map((rec, index) => {
        const book = rec.book;
        const reason = rec.reason || 'Recommended based on your description';
        const score = rec.score ? Math.round(rec.score * 100) : 75;
        
        return `
            <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-4 border border-green-200 dark:border-green-700 mb-4">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-20 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                            #${index + 1}
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h5 class="font-bold text-lg text-[#1b1b18] dark:text-[#EDEDEC]">${book.title}</h5>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                book.availability ? 
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            }">
                                ${book.availability ? '✅ Available' : '❌ Not Available'}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                ${score}% Match
                            </span>
                        </div>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-2">by <strong>${book.author}</strong></p>
                        ${book.description ? `<p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] mb-3">${book.description}</p>` : ''}
                        
                        <div class="flex items-center gap-4 mb-3">
                            ${book.category ? `
                                <span class="text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded-full">
                                    ${book.category.name}
                                </span>
                            ` : ''}
                        </div>
                        
                        <div class="bg-white dark:bg-[#161615] rounded-lg p-3 mb-3">
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                <strong class="text-[#1b1b18] dark:text-[#EDEDEC]">🤖 Why this recommendation:</strong> ${reason}
                            </p>
                        </div>
                        
                        <div class="flex gap-2">
                            <button onclick="searchForBook('${book.title}')" class="px-4 py-2 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg text-sm font-medium hover:bg-[#d42a03] dark:hover:bg-[#e5391b] transition-colors">
                                🔍 Find This Book
                            </button>
                            <button onclick="viewBookDetails(${book.id})" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                👁️ View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    contentDiv.innerHTML = `
        ${analysisInfo}
        <div class="space-y-4">
            ${recommendationsHTML}
        </div>
        <div class="flex justify-center mt-6">
            <button onclick="getAnotherRecommendation()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105">
                🔄 Get More Recommendations
            </button>
        </div>
    `;
}

function searchForBook(title) {
    document.getElementById('searchInput').value = title;
    performSearch();
    
    // Scroll to results
    document.getElementById('booksContainer').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
    
    showNotification(`Searching for "${title}"...`, 'info');
}

function getAnotherRecommendation() {
    getAIRecommendation();
}

function viewBookDetails(bookId) {
    // Redirect to book details page
    window.location.href = `/books/${bookId}`;
}

function clearRecommendation() {
    document.getElementById('bookDescription').value = '';
    document.getElementById('aiRecommendationResults').classList.add('hidden');
    document.getElementById('aiLoadingState').classList.add('hidden');
    updateCharCount();
}

// Initialize character counter
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('bookDescription');
    textarea.addEventListener('input', updateCharCount);
    textarea.setAttribute('maxlength', '500');
});

</script>
<script>
function toggleFavorite(bookId, button) {
    // Animation de chargement
    button.style.transform = 'scale(0.95)';
    button.style.transition = 'all 0.2s ease';
    
    // Désactiver le bouton temporairement
    button.disabled = true;
    
    fetch(`/books/${bookId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const svg = button.querySelector('svg');
        const headerCount = document.querySelector('.favorites-count');
        
        if (data.status) {
            // Ajouté aux favoris - Cœur plein rouge
            svg.outerHTML = `
                <svg class="w-5 h-5 text-red-500 transition-all duration-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            `;
            button.title = 'Retirer des favoris';
            
            // Animation de succès
            button.style.animation = 'pulse 0.6s ease-in-out';
            showNotification('💖 Ajouté aux favoris !', 'success');
        } else {
            // Retiré des favoris - Cœur vide
            svg.outerHTML = `
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 transition-all duration-300 group-hover:text-red-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            `;
            button.title = 'Ajouter aux favoris';
            showNotification('💔 Retiré des favoris', 'info');
        }

        // Mise à jour du compteur dans l'en-tête
        if (headerCount) {
            headerCount.textContent = data.count;
        }
        
        // Rétablir l'état du bouton
        setTimeout(() => {
            button.style.transform = 'scale(1)';
            button.style.animation = '';
            button.disabled = false;
        }, 300);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Une erreur est survenue', 'error');
        
        // Rétablir l'état du bouton en cas d'erreur
        button.style.transform = 'scale(1)';
        button.disabled = false;
    });
}

function createBookCard(book) {
    if (!book || typeof book !== 'object') {
        console.error('Données de livre invalides:', book);
        return null;
    }

    const card = document.createElement('div');
    card.className = 'book-card transform hover:scale-105 transition-all duration-300';
    
    const template = `
        <div class="bg-white dark:bg-[#161615] rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col h-full">
            <div class="relative h-64 overflow-hidden">
                ${book.category && book.category.name ? `
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-primary px-3 py-1.5 text-xs font-semibold text-white rounded-full shadow-lg">
                            ${escapeHtml(book.category.name)}
                        </span>
                    </div>
                ` : ''}
                
                <div class="w-full h-full flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                    ${book.image ? `
                        <img src="{{ asset('storage/') }}/${book.image}" 
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                             alt="${book.title}"
                             loading="lazy">
                    ` : `
                        <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700">
                            <span class="text-5xl">📚</span>
                        </div>
                    `}
                </div>
            </div>

            <div class="p-4 flex flex-col flex-grow">
                <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white line-clamp-2 hover:text-primary transition-colors">
                    <a href="/livre/${book.id}" class="hover:underline">
                        ${book.title}
                    </a>
                </h3>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    by <span class="font-medium">${book.author}</span>
                </p>

                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${
                        book.availability ? 
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                    }">
                        ${book.availability ? '🟢 Available' : '🔴 Unavailable'}
                    </span>
                </div>

                <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex space-x-3">
                        ${window.isAuthenticated ? `
                            <button onclick="toggleFavorite(${book.id}, this)" 
                                    class="group relative inline-flex items-center justify-center p-2.5 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-red-300 dark:hover:border-red-500 transition-all duration-300 transform hover:scale-105 favorite-btn"
                                    data-book-id="${book.id}"
                                    title="Ajouter aux favoris">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-red-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <!-- Indicateur de survol -->
                                <div class="absolute inset-0 rounded-xl bg-red-500/10 scale-0 group-hover:scale-100 transition-transform duration-200"></div>
                            </button>
                        ` : `
                            <a href="{{ route('login') }}" 
                               class="group relative inline-flex items-center justify-center p-2.5 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-red-300 dark:hover:border-red-500 transition-all duration-300 transform hover:scale-105"
                               title="Connectez-vous pour ajouter aux favoris">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-red-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <div class="absolute inset-0 rounded-xl bg-red-500/10 scale-0 group-hover:scale-100 transition-transform duration-200"></div>
                            </a>
                        ` }
                    </div>

                    <a href="/books/${book.id}/add-to-journal"
                       class="inline-flex items-center justify-center p-2 text-gray-700 hover:text-primary transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    `;
    
    card.innerHTML = template;
    return card;
}

function initializeBookCardEvents() {
    // Réinitialiser les événements de hover et d'animation
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Initialiser les événements au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initializeBookCardEvents();
});

function showNotification(message, type) {
    const notif = document.createElement('div');
    notif.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg text-white transform translate-y-0 opacity-100 transition-all duration-500 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    }`;
    notif.textContent = message;
    document.body.appendChild(notif);

    setTimeout(() => {
        notif.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => notif.remove(), 500);
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
    /* Utilitaires de texte */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Styles pour la pagination moderne */
    .pagination-modern {
        background: linear-gradient(135deg, rgba(245, 48, 3, 0.1) 0%, rgba(255, 68, 51, 0.1) 100%);
        border-radius: 1rem;
        padding: 1rem;
        backdrop-filter: blur(10px);
    }

    /* Animations pour les boutons de pagination */
    .pagination-btn {
        position: relative;
        overflow: hidden;
        transform: translateZ(0);
    }

    .pagination-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .pagination-btn:hover::before {
        left: 100%;
    }

    /* Styles pour les filtres */
    .filter-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(245, 48, 3, 0.1);
    }

    .dark .filter-section {
        background: linear-gradient(135deg, #161615 0%, #1a1a1a 100%);
        border: 1px solid rgba(255, 68, 51, 0.2);
    }

    /* Animation de chargement moderne */
    .loading-spinner {
        border: 2px solid #f3f4f6;
        border-top: 2px solid #f87171;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Hover effects pour les cartes */
    .book-card-modern {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
    }

    .book-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Styles pour les badges */
    .badge-modern {
        background: linear-gradient(135deg, #f87171 0%, #f87171 100%);
        box-shadow: 0 4px 14px 0 rgba(245, 48, 3, 0.3);
        transform: translateZ(0);
    }

    .badge-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px 0 rgba(245, 48, 3, 0.4);
    }

    /* Animation de survol pour les inputs */
    .input-modern {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .input-modern:focus {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(245, 48, 3, 0.2);
    }

    /* Styles pour le mode sombre */
    .dark .badge-modern {
        background: linear-gradient(135deg, #f87171 0%, #f87171 100%);
        box-shadow: 0 4px 14px 0 rgba(255, 68, 51, 0.3);
    }

    .dark .input-modern:focus {
        box-shadow: 0 10px 25px -5px rgba(255, 68, 51, 0.2);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Styles des cartes de livres */
    .book-card {
        animation: scaleIn 0.5s ease-out;
        transition: all 0.3s ease;
    }

    .book-card:hover {
        transform: translateY(-5px);
    }

    /* Style des images */
    .book-card img {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .book-card:hover img {
        transform: scale(1.08);
    }

    /* Animations des boutons */
    .book-card button, .book-card a {
        transition: all 0.2s ease;
    }

    .book-card button:hover, .book-card a:hover {
        transform: translateY(-2px);
    }

    .book-card button:active, .book-card a:active {
        transform: translateY(0);
    }

    /* Style de la pagination */
    .pagination {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
    }

    .pagination .page-item .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e3e3e0;
        border-radius: 0.5rem;
        color: #1b1b18;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: #f87171;
        border-color: #f87171;
        color: white;
        box-shadow: 0 4px 6px -1px rgb(245 48 3 / 0.2);
    }

    .pagination .page-item:not(.active) .page-link:hover {
        background-color: #fff2f2;
        border-color: #f87171;
        color: #f87171;
        transform: translateY(-1px);
    }

    /* Dark mode styles */
    .dark .pagination .page-item .page-link {
        background-color: #161615;
        border-color: #3E3E3A;
        color: #EDEDEC;
    }

    .dark .pagination .page-item.active .page-link {
        background-color: #f87171;
        border-color: #f87171;
        color: white;
    }

    .dark .pagination .page-item:not(.active) .page-link:hover {
        background-color: #1D0002;
        border-color: #f87171;
        color: #f87171;
    }

    /* Loading states */
    .loading {
        position: relative;
        pointer-events: none;
    }

    .loading::after {
        content: '';
        position: absolute;
        inset: 0;
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(2px);
        border-radius: 0.75rem;
    }

    .dark .loading::after {
        background-color: rgba(0, 0, 0, 0.7);
    }

    /* Empty state animations */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .empty-state-icon {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Animations pour les boutons de favoris */
    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.15); }
        50% { transform: scale(1.05); }
        75% { transform: scale(1.15); }
    }

    @keyframes favoriteAdded {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); filter: brightness(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes ripple {
        0% { transform: scale(0); opacity: 1; }
        100% { transform: scale(4); opacity: 0; }
    }

    .favorite-btn:hover {
        animation: heartBeat 0.8s ease-in-out;
    }

    .favorite-btn.added {
        animation: favoriteAdded 0.6s ease-in-out;
    }

    .favorite-btn.active svg {
        color: #f87171 !important;
        filter: drop-shadow(0 2px 4px rgba(220, 38, 38, 0.3));
    }

    /* Effet de ripple au clic */
    .favorite-btn.ripple-effect::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(239, 68, 68, 0.5);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: ripple 0.6s linear;
    }

    /* Style spécial pour les favoris actifs */
    .favorite-btn.is-favorite {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-color: #fca5a5;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }

    .favorite-btn.is-favorite:hover {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-color: #f87171;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.25);
    }
</style>
@endpush