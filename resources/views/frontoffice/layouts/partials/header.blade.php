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
                                href="{{ route('groups') }}">Groups</a></li>
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

                    <!-- User Modal -->
                    <a href="#" class="text-dark hover:text-primary">
                        <svg class="w-5 h-5">
                            <use xlink:href="#user"></use>
                        </svg>
                    </a>

                    <!-- Sign Out Button (visible if authenticated) -->
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
                    <li><a class="block py-2 text-dark hover:text-primary" href="{{ route('groups') }}">Groups</a></li>
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