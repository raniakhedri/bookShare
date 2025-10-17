<header id="header" class="site-header"
    x-data="{ mobileMenuOpen: false, pagesDropdownOpen: false }">
   

    <!-- Main Navigation -->
    <nav id="header-nav" class="py-6">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('template/images/main-logo.png') }}" class="logo h-10" alt="Bookly Logo">
                </a>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2" type="button" @click="mobileMenuOpen = !mobileMenuOpen">
                    <svg class="w-6 h-6">
                        <use xlink:href="#navbar-icon"></use>
                    </svg>
                </button>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center">
                    <ul class="flex space-x-6 uppercase">
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}">Home</a></li>
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('book') ? 'active' : '' }}"
                                href="{{ route('book') }}">Book</a></li>
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('notes') ? 'active' : '' }}"
                                href="{{ url('/journals') }}">Journals</a></li>
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('groups') ? 'active' : '' }}"
                                href="{{ route('groups.index') }}">Groups</a></li>
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('marketplace') ? 'active' : '' }}"
                                href="{{ route('marketplace') }}">Marketplace</a></li>
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('blog') ? 'active' : '' }}"
                                href="{{ route('blog') }}">Blog</a></li>
                        <li><a class="text-dark hover:text-primary font-heading font-medium {{ request()->routeIs('community') ? 'active' : '' }}"
                                href="{{ route('community') }}">Community Features</a></li>
                    </ul>
                </div>

                    <!-- User Actions -->
                <div class="hidden md:flex items-center space-x-6">
                    <!-- Search -->
                    <a href="#" class="search-button text-dark hover:text-primary">
                        <svg class="w-5 h-5">
                            <use xlink:href="#search"></use>
                        </svg>
                    </a>

                    <!-- AI Recommendations -->
                    <a href="{{ route('ai.recommendations') }}" 
                       class="group relative p-2.5 rounded-xl hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('ai.recommendations') ? 'bg-orange-100 dark:bg-orange-900/30' : '' }}"
                       title="🤖 IA Recommendations - Découvrez des livres personnalisés">
                        <!-- Icône AI moderne avec animation subtile -->
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-orange-500 transition-all duration-300 {{ request()->routeIs('ai.recommendations') ? 'text-orange-500 animate-pulse' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        
                        <!-- Badge "AI" avec animation -->
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 bg-gradient-to-r from-orange-500 to-red-500 text-white text-[10px] font-bold rounded-full shadow-lg ring-2 ring-white dark:ring-gray-800 animate-bounce" style="animation-duration: 2s;">
                            🤖
                        </span>
                        
                        <!-- Effet de survol avec gradient -->
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-orange-500/10 to-red-500/10 scale-0 group-hover:scale-100 transition-transform duration-200"></div>
                    </a>

                    <!-- Favorites -->
                    @auth
                    <a href="{{ route('favorites.index') }}" 
                       class="group relative p-2.5 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 transform hover:scale-105"
                       title="Mes favoris ({{ auth()->user()->favorites()->count() }})">
                        <!-- Icône cœur moderne -->
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-red-500 transition-colors duration-300" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        
                        <!-- Badge de compteur moderne -->
                        @if(auth()->user()->favorites()->count() > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[20px] h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5 shadow-lg ring-2 ring-white dark:ring-gray-800 favorites-count animate-pulse">
                            {{ auth()->user()->favorites()->count() }}
                        </span>
                        @endif
                        
                        <!-- Effet de survol -->
                        <div class="absolute inset-0 rounded-xl bg-red-500/10 scale-0 group-hover:scale-100 transition-transform duration-200"></div>
                    </a>
                    @endauth

                    <!-- User Modal -->
                    <a href="#" class="text-dark hover:text-primary">
                        <svg class="w-5 h-5">
                            <use xlink:href="#user"></use>
                        </svg>
                    </a>                    <!-- Sign Out Button (visible if authenticated) -->
                    @auth
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <button type="submit" class="text-dark hover:text-primary font-heading font-medium px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 transition">Sign Out</button>
                        </form>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" class="md:hidden mt-4 border-t pt-4">
                <ul class="space-y-2">
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('home') }}">Home</a></li>
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('book') }}">Book</a></li>
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('notes') }}">Notes</a></li>
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('groups.index') }}">Groups</a></li>
                    <li><a class="block py-2 text-dark hover:text-primary"
                            href="{{ route('marketplace') }}">Marketplace</a></li>
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('blog') }}">Blog</a></li>
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('community') }}">Community
                            Features</a></li>
                </ul>
            </div>
        </div>
    </nav>

</header>