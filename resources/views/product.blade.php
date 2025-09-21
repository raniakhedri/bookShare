@extends('layouts.app')

@section('title', 'Product Details - Bookly Bookstore')

@section('content')
    <!-- Breadcrumb -->
    <section class="py-4 border-b">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a   class="hover:text-primary">Shop</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">The Midnight Library</span>
            </nav>
        </div>
    </section>

    <!-- Product Details -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <!-- Product Images -->
                <div class="w-full lg:w-1/2 mb-8">
                    <div class="relative">
                        <img id="mainImage" src="{{ asset('template/images/item-image1.jpg') }}" alt="The Midnight Library"
                            class="w-full h-96 lg:h-[500px] object-cover rounded-lg shadow-lg">
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">-15%</span>
                        </div>
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="flex space-x-4 mt-4">
                        @for($i = 1; $i <= 4; $i++)
                            <img src="{{ asset('template/images/item-image' . $i . '.jpg') }}" alt="Book view {{ $i }}"
                                class="w-20 h-20 object-cover rounded border-2 border-transparent hover:border-primary cursor-pointer thumbnail"
                                onclick="changeMainImage(this.src)">
                        @endfor
                    </div>
                </div>

                <!-- Product Info -->
                <div class="w-full lg:w-1/2 lg:pl-12">
                    <div class="mb-4">
                        <span class="text-primary text-sm">Fiction • Bestseller</span>
                    </div>

                    <h1 class="text-3xl lg:text-4xl font-bold mb-4">The Midnight Library</h1>
                    <p class="text-gray-600 mb-4">by Matt Haig</p>

                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-gray-600">(4.8) 324 reviews</span>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center space-x-4">
                            <span class="text-3xl font-bold text-primary">$19.99</span>
                            <span class="text-xl text-gray-500 line-through">$23.49</span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Save 15%</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Description</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Between life and death there is a library, and within that library, the shelves go on forever.
                            Every book provides a chance to try another life you could have lived. To see how things would
                            be
                            if you had made other choices... Would you have done anything different, if you had the chance
                            to undo your regrets?
                        </p>
                    </div>

                    <!-- Product Options -->
                    <div class="space-y-6 mb-8">
                        <!-- Format Selection -->
                        <div>
                            <h4 class="font-semibold mb-3">Format:</h4>
                            <div class="flex space-x-3">
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="paperback" class="mr-2" checked>
                                    <span>Paperback</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="hardcover" class="mr-2">
                                    <span>Hardcover (+$5.00)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="ebook" class="mr-2">
                                    <span>E-book (-$3.00)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <h4 class="font-semibold mb-3">Quantity:</h4>
                            <div class="flex items-center space-x-3">
                                <button type="button"
                                    class="w-10 h-10 border rounded flex items-center justify-center hover:bg-gray-50"
                                    onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" value="1" min="1"
                                    class="w-16 text-center border rounded py-2">
                                <button type="button"
                                    class="w-10 h-10 border rounded flex items-center justify-center hover:bg-gray-50"
                                    onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4 mb-8">
                        <button
                            class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                            Add to Cart
                        </button>
                        <div class="flex space-x-4">
                            <button
                                class="flex-1 border border-primary text-primary py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                                Wishlist
                            </button>
                            <button
                                class="flex-1 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92S19.61 16.08 18 16.08z" />
                                </svg>
                                Share
                            </button>
                        </div>
                    </div>

                    <!-- Product Features -->
                    <div class="border-t pt-6">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <span>In Stock</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20,8h-3V4H3C1.89,4 1,4.89 1,6v12c0,1.11 0.89,2 2,2h14l4-4V10C21,8.89 20.11,8 20,8z M15,18H3V6h12V18z" />
                                </svg>
                                <span>Free Shipping</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2.5-9H18V0h-2v2H8V0H6v2H4.5C3.12 2 2 3.12 2 4.5v15C2 20.88 3.12 22 4.5 22h15c1.38 0 2.5-1.12 2.5-2.5v-15C22 3.12 20.88 2 19.5 2z" />
                                </svg>
                                <span>30-Day Returns</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" />
                                </svg>
                                <span>Secure Payment</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="mt-16">
                <div class="border-b">
                    <nav class="flex space-x-8">
                        <button class="tab-button active border-b-2 border-primary text-primary py-2 px-1"
                            onclick="showTab('description')">
                            Description
                        </button>
                        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-primary py-2 px-1"
                            onclick="showTab('reviews')">
                            Reviews (324)
                        </button>
                        <button class="tab-button border-b-2 border-transparent text-gray-500 hover:text-primary py-2 px-1"
                            onclick="showTab('shipping')">
                            Shipping Info
                        </button>
                    </nav>
                </div>

                <div class="py-8">
                    <!-- Description Tab -->
                    <div id="description" class="tab-content">
                        <div class="prose max-w-none">
                            <h3 class="text-xl font-semibold mb-4">About This Book</h3>
                            <p class="text-gray-700 mb-4">
                                The Midnight Library tells the story of Nora Seed, who finds herself faced with the
                                possibility of changing her life for a completely different one, following a different
                                career, undoing old breakups, realizing her dreams of becoming a glaciologist; she must
                                search within herself as she travels through the Midnight Library to decide what is truly
                                fulfilling in life, and what makes it worth living in the first place.
                            </p>
                            <p class="text-gray-700 mb-4">
                                Between life and death there is a library, and within that library, the shelves go on
                                forever. Every book provides a chance to try another life you could have lived. To see how
                                things would be if you had made other choices... Would you have done anything different, if
                                you had the chance to undo your regrets?
                            </p>

                            <h4 class="text-lg font-semibold mb-3">Product Details</h4>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700">
                                <li><strong>Publisher:</strong> Penguin Random House</li>
                                <li><strong>Publication Date:</strong> August 13, 2020</li>
                                <li><strong>Pages:</strong> 288</li>
                                <li><strong>Language:</strong> English</li>
                                <li><strong>ISBN-10:</strong> 0525559477</li>
                                <li><strong>ISBN-13:</strong> 978-0525559474</li>
                                <li><strong>Dimensions:</strong> 5.2 x 0.7 x 8 inches</li>
                                <li><strong>Weight:</strong> 7.2 ounces</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div id="reviews" class="tab-content hidden">
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div class="mr-8">
                                    <div class="text-4xl font-bold">4.8</div>
                                    <div class="flex text-yellow-400 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="text-sm text-gray-600">324 reviews</div>
                                </div>

                                <div class="flex-1">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="flex items-center mb-1">
                                            <span class="w-3 text-sm">{{ $i }}</span>
                                            <svg class="w-4 h-4 text-yellow-400 mx-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                            <div class="w-32 bg-gray-200 rounded-full h-2 mx-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ 85 - ($i * 5) }}%">
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ rand(20, 80) }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Sample Reviews -->
                            <div class="space-y-6">
                                @for($i = 1; $i <= 3; $i++)
                                    <div class="border-b pb-6">
                                        <div class="flex items-start mb-3">
                                            <img src="{{ asset('template/images/commentor-item' . $i . '.jpg') }}"
                                                alt="Reviewer" class="w-12 h-12 rounded-full mr-4">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-1">
                                                    <h5 class="font-semibold mr-3">Book Lover {{ $i }}</h5>
                                                    <div class="flex text-yellow-400 mr-2">
                                                        @for($j = 1; $j <= 5; $j++)
                                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span
                                                        class="text-sm text-gray-500">{{ now()->subDays($i * 5)->format('M d, Y') }}</span>
                                                </div>
                                                <p class="text-gray-700">
                                                    @if($i == 1)
                                                        This book completely changed my perspective on life and the choices we make.
                                                        Matt Haig's writing is both profound and accessible. A must-read for anyone
                                                        questioning their path in life.
                                                    @elseif($i == 2)
                                                        Beautiful, thought-provoking, and emotionally resonant. The concept is
                                                        brilliant and the execution is flawless. I couldn't put it down!
                                                    @else
                                                        One of the most unique and moving books I've read this year. The
                                                        philosophical themes are handled with care and wisdom. Highly recommend!
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Tab -->
                    <div id="shipping" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h3 class="text-xl font-semibold mb-4">Shipping Options</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center p-4 border rounded">
                                        <div>
                                            <h4 class="font-medium">Standard Shipping</h4>
                                            <p class="text-sm text-gray-600">5-7 business days</p>
                                        </div>
                                        <span class="font-semibold">Free</span>
                                    </div>
                                    <div class="flex justify-between items-center p-4 border rounded">
                                        <div>
                                            <h4 class="font-medium">Express Shipping</h4>
                                            <p class="text-sm text-gray-600">2-3 business days</p>
                                        </div>
                                        <span class="font-semibold">$5.99</span>
                                    </div>
                                    <div class="flex justify-between items-center p-4 border rounded">
                                        <div>
                                            <h4 class="font-medium">Next Day Delivery</h4>
                                            <p class="text-sm text-gray-600">1 business day</p>
                                        </div>
                                        <span class="font-semibold">$12.99</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xl font-semibold mb-4">Return Policy</h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li>• 30-day return window</li>
                                    <li>• Items must be in original condition</li>
                                    <li>• Free return shipping on defective items</li>
                                    <li>• Refunds processed within 3-5 business days</li>
                                    <li>• Digital products are non-returnable</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center mb-12">Related Products</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @for($i = 2; $i <= 5; $i++)
                        <div class="group">
                            <div class="relative overflow-hidden rounded-lg mb-4">
                                <img src="{{ asset('template/images/item-image' . $i . '.jpg') }}" alt="Related Book {{ $i }}"
                                    class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="bg-white p-2 rounded-full shadow hover:bg-primary hover:text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <h3 class="font-semibold mb-2 group-hover:text-primary">Related Book {{ $i }}</h3>
                            <p class="text-gray-600 text-sm mb-2">by Author Name</p>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-primary">${{ 15 + $i }}.99</span>
                                <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-primary/90">Add to
                                    Cart</button>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <script>
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(img => {
                img.classList.remove('border-primary');
                img.classList.add('border-transparent');
            });
            // Add active class to clicked thumbnail
            event.target.classList.remove('border-transparent');
            event.target.classList.add('border-primary');
        }

        function increaseQuantity() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-primary', 'text-primary');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName).classList.remove('hidden');

            // Add active class to clicked button
            event.target.classList.add('active', 'border-primary', 'text-primary');
            event.target.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>
@endsection