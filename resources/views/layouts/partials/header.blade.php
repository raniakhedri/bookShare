<header id="header" class="site-header"
    x-data="{ mobileMenuOpen: false, userModalOpen: false, pagesDropdownOpen: false, activeTab: 'signin' }">
    <!-- Top Info Bar -->
    <div class="top-info border-b hidden md:block">
        <div class="container mx-auto">
            <div class="flex flex-wrap">
                <div class="md:w-1/3 w-full">
                    <p class="text-sm my-2 text-center">Need any help? Call us <a href="#"
                            class="hover:text-primary">112233344455</a></p>
                </div>
                <div class="md:w-1/3 w-full border-l border-r">
                    <p class="text-sm my-2 text-center">Summer sale discount off 60% off! <a
                            class="underline hover:text-primary" href="#">Shop Now</a></p>
                </div>
                <div class="md:w-1/3 w-full">
                    <p class="text-sm my-2 text-center">2-3 business days delivery & free returns</p>
                </div>
            </div>
        </div>
    </div>

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
                                href="{{ route('notes') }}">Notes</a></li>
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

                    <!-- Wishlist Dropdown -->
                    <div class="relative group">
                        <a href="#" class="text-dark hover:text-primary flex items-center">
                            <svg class="w-5 h-5">
                                <use xlink:href="#heart"></use>
                            </svg>
                            <span class="text-xs ml-1">(2)</span>
                        </a>
                        <div
                            class="absolute right-0 w-72 bg-white shadow-lg rounded-md p-4 z-50 hidden group-hover:block">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-primary font-medium">Your wishlist</span>
                                <span class="bg-primary text-white text-xs px-2 py-1 rounded-full">2</span>
                            </div>
                            <ul class="space-y-4 mb-4">
                                <li class="flex justify-between">
                                    <div>
                                        <h5 class="font-medium"><a href="#" class="hover:text-primary">The Emerald
                                                Crown</a></h5>
                                        <p class="text-xs text-gray-500">Special discounted price.</p>
                                        <a href="#"
                                            class="text-sm font-medium text-primary hover:underline block mt-1">Add to
                                            cart</a>
                                    </div>
                                    <span class="text-primary">$2000</span>
                                </li>
                                <li class="flex justify-between">
                                    <div>
                                        <h5 class="font-medium"><a href="#" class="hover:text-primary">The Last
                                                Enchantment</a></h5>
                                        <p class="text-xs text-gray-500">Perfect for enlightened people.</p>
                                        <a href="#"
                                            class="text-sm font-medium text-primary hover:underline block mt-1">Add to
                                            cart</a>
                                    </div>
                                    <span class="text-primary">$400</span>
                                </li>
                                <li class="flex justify-between border-t pt-2">
                                    <span class="font-bold">Total (USD)</span>
                                    <strong>$2400</strong>
                                </li>
                            </ul>
                            <div class="space-y-2">
                                <a href="#"
                                    class="block w-full bg-dark text-white py-2 text-center rounded hover:bg-opacity-90">Add
                                    all to cart</a>
                                <a href="#"
                                    class="block w-full bg-primary text-white py-2 text-center rounded hover:bg-opacity-90">View
                                    cart</a>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Dropdown -->
                    <div class="relative group">
                        <a href="#" class="text-dark hover:text-primary flex items-center">
                            <svg class="w-5 h-5">
                                <use xlink:href="#cart"></use>
                            </svg>
                            <span class="text-xs ml-1">(2)</span>
                        </a>
                        <div
                            class="absolute right-0 w-72 bg-white shadow-lg rounded-md p-4 z-50 hidden group-hover:block">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-primary font-medium">Your cart</span>
                                <span class="bg-primary text-white text-xs px-2 py-1 rounded-full">2</span>
                            </div>
                            <ul class="space-y-4 mb-4">
                                <li class="flex justify-between">
                                    <div class="flex">
                                        <img src="{{ asset('template/images/cart-item1.png') }}" alt="cart item"
                                            class="w-12 h-12 mr-3">
                                        <div>
                                            <h5 class="font-medium text-sm">Simple way of piece life</h5>
                                            <p class="text-xs text-gray-500">Qty: 2</p>
                                        </div>
                                    </div>
                                    <span class="text-primary">$40</span>
                                </li>
                                <li class="flex justify-between">
                                    <div class="flex">
                                        <img src="{{ asset('template/images/cart-item2.png') }}" alt="cart item"
                                            class="w-12 h-12 mr-3">
                                        <div>
                                            <h5 class="font-medium text-sm">Great travel at desert</h5>
                                            <p class="text-xs text-gray-500">Qty: 1</p>
                                        </div>
                                    </div>
                                    <span class="text-primary">$40</span>
                                </li>
                                <li class="flex justify-between border-t pt-2">
                                    <span class="font-bold">Total (USD)</span>
                                    <strong>$80</strong>
                                </li>
                            </ul>
                            <div class="space-y-2">
                                <a href="#"
                                    class="block w-full bg-dark text-white py-2 text-center rounded hover:bg-opacity-90">View
                                    cart</a>
                                <a href="#"
                                    class="block w-full bg-primary text-white py-2 text-center rounded hover:bg-opacity-90">Checkout</a>
                            </div>
                        </div>
                    </div>
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

    <!-- User Authentication Modal -->
    <div x-show="userModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
        x-cloak>
        <div class="bg-white rounded-lg p-6 w-96 max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Account</h3>
                <button @click="userModalOpen = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6">
                        <use xlink:href="#close"></use>
                    </svg>
                </button>
            </div>

            <div class="flex mb-4">
                <button @click="activeTab = 'signin'"
                    :class="activeTab === 'signin' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'"
                    class="flex-1 py-2 px-4 rounded-l">Sign In</button>
                <button @click="activeTab = 'signup'"
                    :class="activeTab === 'signup' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'"
                    class="flex-1 py-2 px-4 rounded-r">Sign Up</button>
            </div>

            <div x-show="activeTab === 'signin'">
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-4">
                        <input type="email" name="email" placeholder="Email" class="w-full p-3 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Password" class="w-full p-3 border rounded"
                            required>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded hover:bg-opacity-90">Sign
                        In</button>
                </form>
            </div>

            <div x-show="activeTab === 'signup'">
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="name" placeholder="Full Name" class="w-full p-3 border rounded"
                            required>
                    </div>
                    <div class="mb-4">
                        <input type="email" name="email" placeholder="Email" class="w-full p-3 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Password" class="w-full p-3 border rounded"
                            required>
                    </div>
                    <div class="mb-4">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password"
                            class="w-full p-3 border rounded" required>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded hover:bg-opacity-90">Sign
                        Up</button>
                </form>
            </div>
        </div>
    </div>
</header>