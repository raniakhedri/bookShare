@extends('layouts.app')

@section('title', 'Shop - Bookly Bookstore')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">Book Shop</h1>
                <p class="text-xl text-gray-600">
                    Discover thousands of books across all genres
                </p>
            </div>
        </div>
    </section>

    <!-- Shop Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <!-- Sidebar -->
                <div class="w-full lg:w-1/4 pr-8 mb-8 lg:mb-0">
                    <div class="sticky top-4">
                        <!-- Search -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Search Books</h3>
                            <div class="relative">
                                <input type="text" placeholder="Search for books..."
                                    class="w-full pl-4 pr-10 py-2 border rounded-lg">
                                <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-5 h-5 text-gray-400">
                                        <use xlink:href="#search"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Categories</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-600 hover:text-primary">Fiction (150)</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-primary">Science & Technology (89)</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-primary">Romance (234)</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-primary">Mystery & Thriller (178)</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-primary">Biography (95)</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-primary">Self-Help (112)</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-primary">Children's Books (203)</a></li>
                            </ul>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Price Range</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-gray-600">Under $10 (45)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-gray-600">$10 - $20 (128)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-gray-600">$20 - $30 (89)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-gray-600">$30 - $50 (67)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <span class="text-gray-600">Over $50 (23)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Rating</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <div class="flex items-center">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 text-yellow-400 fill-current">
                                                <use xlink:href="#star-fill"></use>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-gray-600">(125)</span>
                                    </div>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2">
                                    <div class="flex items-center">
                                        @for($i = 0; $i < 4; $i++)
                                            <svg class="w-4 h-4 text-yellow-400 fill-current">
                                                <use xlink:href="#star-fill"></use>
                                            </svg>
                                        @endfor
                                        <svg class="w-4 h-4 text-gray-300">
                                            <use xlink:href="#star-empty"></use>
                                        </svg>
                                        <span class="ml-2 text-gray-600">& up (89)</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="w-full lg:w-3/4">
                    <!-- Sort and View Options -->
                    <div class="flex justify-between items-center mb-8">
                        <p class="text-gray-600">Showing 1-12 of 352 results</p>
                        <div class="flex items-center space-x-4">
                            <select class="border rounded px-4 py-2">
                                <option>Sort by popularity</option>
                                <option>Sort by latest</option>
                                <option>Sort by price: low to high</option>
                                <option>Sort by price: high to low</option>
                                <option>Sort by rating</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @for($i = 1; $i <= 12; $i++)
                            <div class="product-item border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="relative">
                                    <img src="{{ asset('template/images/product-item' . (($i % 12) + 1) . '.png') }}"
                                        alt="Book {{ $i }}" class="w-full h-64 object-cover">
                                    <div class="absolute top-2 right-2">
                                        <button class="bg-white p-2 rounded-full shadow hover:bg-gray-50">
                                            <svg class="w-4 h-4">
                                                <use xlink:href="#heart"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    @if($i % 3 == 0)
                                        <div class="absolute top-2 left-2">
                                            <span class="bg-red-500 text-white px-2 py-1 text-xs rounded">Sale</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <a href="{{ route('product.show', $i) }}" class="block">
                                        <h3 class="font-semibold mb-2 hover:text-primary">{{ 'Book Title ' . $i }}</h3>
                                    </a>
                                    <p class="text-gray-600 text-sm mb-2">by Author Name {{ $i }}</p>
                                    <div class="flex items-center mb-2">
                                        @for($j = 0; $j < 5; $j++)
                                            <svg class="w-4 h-4 {{ $j < 4 ? 'text-yellow-400' : 'text-gray-300' }}">
                                                <use xlink:href="{{ $j < 4 ? '#star-fill' : '#star-empty' }}"></use>
                                            </svg>
                                        @endfor
                                        <span class="text-gray-600 text-sm ml-2">({{ rand(10, 100) }})</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($i % 3 == 0)
                                                <span class="text-gray-400 line-through text-sm">${{ 25 + ($i * 3) }}</span>
                                                <span class="text-primary font-bold ml-2">${{ 20 + ($i * 2) }}</span>
                                            @else
                                                <span class="text-primary font-bold">${{ 20 + ($i * 3) }}</span>
                                            @endif
                                        </div>
                                        <button
                                            class="bg-primary text-white px-4 py-2 text-sm rounded hover:bg-primary-dark transition-colors">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-12">
                        <nav class="flex space-x-2">
                            <a href="#" class="px-3 py-2 border rounded hover:bg-gray-50">Previous</a>
                            <a href="#" class="px-3 py-2 border rounded bg-primary text-white">1</a>
                            <a href="#" class="px-3 py-2 border rounded hover:bg-gray-50">2</a>
                            <a href="#" class="px-3 py-2 border rounded hover:bg-gray-50">3</a>
                            <span class="px-3 py-2">...</span>
                            <a href="#" class="px-3 py-2 border rounded hover:bg-gray-50">30</a>
                            <a href="#" class="px-3 py-2 border rounded hover:bg-gray-50">Next</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection