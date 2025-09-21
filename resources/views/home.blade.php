@extends('layouts.app')

@section('title', 'Bookly - Bookstore eCommerce TailwindCSS Website Template')

@section('content')
    <section id="billboard" class="relative flex items-center py-12 bg-gray-100 bg-banner bg-cover bg-no-repeat bg-center ">

        <!-- Next Button -->
        <div class="absolute right-0 pe-0 xl:pe-5 me-0 xl:me-5 swiper-next main-slider-button-next z-10">
            <svg class="chevron-forward-circle flex justify-center items-center p-2 w-20 h-20">
                <use xlink:href="#alt-arrow-right-outline"></use>
            </svg>
        </div>

        <!-- Prev Button -->
        <div class="absolute left-0 ps-0 xl:ps-5 ms-0 xl:ms-5 swiper-prev main-slider-button-prev z-10">
            <svg class="chevron-back-circle flex justify-center items-center p-2 w-20 h-20">
                <use xlink:href="#alt-arrow-left-outline"></use>
            </svg>
        </div>

        <!-- Swiper Container -->
        <div class="swiper main-swiper w-full h-full">
            <div class="swiper-wrapper flex items-center md:mx-32">
                <!-- Slide 1 -->
                <div class="swiper-slide content-center">
                    <div class="container mx-auto px-4">
                        <div class="flex flex-col-reverse md:flex-row items-center">
                            <div class="md:w-6/12 md:ml-8 mt-10 md:mt-0 text-center md:text-left">
                                <div class="banner-content">
                                    <h2 class="text-4xl md:text-6xl font-semibold mb-4">The Fine Print Book Collection</h2>
                                    <p class="text-xl mb-6">Best Offer Save 30%. Grab it now!</p>
                                    <a 
                                        class="btn inline-block px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                        Shop Collection
                                    </a>
                                </div>
                            </div>
                            <div class="md:w-5/12 text-center">
                                <div class="image-holder">
                                    <img src="{{ asset('template/images/banner-image2.png') }}"
                                        class="w-full max-w-md mx-auto" alt="banner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide content-center">
                    <div class="container mx-auto px-4">
                        <div class="flex flex-col-reverse md:flex-row items-center">
                            <div class="md:w-6/12 md:ml-8 mt-10 md:mt-0 text-center md:text-left">
                                <div class="banner-content">
                                    <h2 class="text-4xl md:text-6xl font-semibold mb-4">How Innovation works</h2>
                                    <p class="text-xl mb-6">Discount available. Grab it now!</p>
                                    <a 
                                        class="btn inline-block px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                        Shop Product
                                    </a>
                                </div>
                            </div>
                            <div class="md:w-5/12 text-center">
                                <div class="image-holder">
                                    <img src="{{ asset('template/images/banner-image1.png') }}"
                                        class="w-full max-w-md mx-auto" alt="banner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide content-center">
                    <div class="container mx-auto px-4">
                        <div class="flex flex-col-reverse md:flex-row items-center">
                            <div class="md:w-6/12 md:ml-8 mt-10 md:mt-0 text-center md:text-left">
                                <div class="banner-content">
                                    <h2 class="text-4xl md:text-6xl font-semibold mb-4">Your Heart is the Sea</h2>
                                    <p class="text-xl mb-6">Limited stocks available. Grab it now!</p>
                                    <a 
                                        class="btn inline-block px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                        Shop Collection
                                    </a>
                                </div>
                            </div>
                            <div class="md:w-5/12 text-center">
                                <div class="image-holder">
                                    <img src="{{ asset('template/images/banner-image.png') }}"
                                        class="w-full max-w-md mx-auto" alt="banner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="company-services" class="pt-28 pb-0">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-2">
                <!-- Service 1 -->
                <div class="w-full lg:w-1/4 md:w-1/2 px-2 pb-6 lg:pb-0">
                    <div class="icon-box flex">
                        <div class="icon-box-icon pr-3 pb-3">
                            <svg class="cart-outline w-8 h-8 text-primary">
                                <use xlink:href="#cart-outline" />
                            </svg>
                        </div>
                        <div class="icon-box-content">
                            <h4 class="text-lg font-medium capitalize text-dark mb-1">Free delivery</h4>
                            <p class="text-gray-600">Consectetur adipi elit lorem ipsum dolor sit amet.</p>
                        </div>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="w-full lg:w-1/4 md:w-1/2 px-2 pb-6 lg:pb-0">
                    <div class="icon-box flex">
                        <div class="icon-box-icon pr-3 pb-3">
                            <svg class="quality w-8 h-8 text-primary">
                                <use xlink:href="#quality" />
                            </svg>
                        </div>
                        <div class="icon-box-content">
                            <h4 class="text-lg font-medium capitalize text-dark mb-1">Quality guarantee</h4>
                            <p class="text-gray-600">Dolor sit amet orem ipsu mcons ectetur adipi elit.</p>
                        </div>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="w-full lg:w-1/4 md:w-1/2 px-2 pb-6 lg:pb-0">
                    <div class="icon-box flex">
                        <div class="icon-box-icon pr-3 pb-3">
                            <svg class="price-tag w-8 h-8 text-primary">
                                <use xlink:href="#price-tag" />
                            </svg>
                        </div>
                        <div class="icon-box-content">
                            <h4 class="text-lg font-medium capitalize text-dark mb-1">Daily offers</h4>
                            <p class="text-gray-600">Amet consectetur adipi elit loreme ipsum dolor sit.</p>
                        </div>
                    </div>
                </div>

                <!-- Service 4 -->
                <div class="w-full lg:w-1/4 md:w-1/2 px-2 pb-6 lg:pb-0">
                    <div class="icon-box flex">
                        <div class="icon-box-icon pr-3 pb-3">
                            <svg class="shield-plus w-8 h-8 text-primary">
                                <use xlink:href="#shield-plus" />
                            </svg>
                        </div>
                        <div class="icon-box-content">
                            <h4 class="text-lg font-medium capitalize text-dark mb-1">100% secure payment</h4>
                            <p class="text-gray-600">Rem Lopsum dolor sit amet, consectetur adipi elit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="best-selling-items" class="relative py-28">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h3 class="text-4xl font-semibold flex items-center">Best selling items</h3>
                <a 
                    class="mt-4 md:mt-0 px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    View All
                </a>
            </div>

            <!-- Navigation Arrows -->
            <div
                class="absolute top-1/2 right-0 pe-0 xl:pe-5 me-0 xl:me-5 swiper-next product-slider-button-next z-10 -translate-y-1/2">
                <svg
                    class="flex justify-center items-center p-2 w-16 h-16 bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors">
                    <use xlink:href="#alt-arrow-right-outline"></use>
                </svg>
            </div>
            <div
                class="absolute top-1/2 left-0 ps-0 xl:ps-5 ms-0 xl:ms-5 swiper-prev product-slider-button-prev z-10 -translate-y-1/2">
                <svg
                    class="flex justify-center items-center p-2 w-16 h-16 bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors">
                    <use xlink:href="#alt-arrow-left-outline"></use>
                </svg>
            </div>

            <!-- Product Slider -->
            <div class="swiper product-swiper">
                <div class="swiper-wrapper">
                    <!-- Product 1 -->
                    <div class="swiper-slide">
                        <div class="card relative p-6 border rounded-xl hover:shadow-lg transition-shadow">
                            <div class="absolute top-4 left-4">
                                <p class="bg-primary py-1 px-3 text-sm text-white rounded-lg">10% off</p>
                            </div>
                            <img src="{{ asset('template/images/product-item1.png') }}" class="w-full shadow-sm"
                                alt="House of Sky Breath">
                            <h6 class="mt-4 mb-1 font-bold text-lg"><a href="#" class="hover:text-primary">House of
                                    Sky Breath</a></h6>
                            <div class="flex items-center">
                                <p class="my-2 mr-2 text-sm text-gray-500">Lauren Asher</p>
                                <div class="rating text-yellow-400 flex items-center">
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                </div>
                            </div>
                            <span class="price text-primary font-bold text-lg">$870</span>
                            <div class="card-concern absolute left-0 right-0 flex justify-center gap-2 opacity-0">
                                <button type="button" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div class="swiper-slide">
                        <div class="card relative p-6 border rounded-xl hover:shadow-lg transition-shadow">
                            <img src="{{ asset('template/images/product-item2.png') }}" class="w-full shadow-sm"
                                alt="Heartland Stars">
                            <h6 class="mt-4 mb-1 font-bold text-lg"><a href="#" class="hover:text-primary">Heartland
                                    Stars</a></h6>
                            <div class="flex items-center">
                                <p class="my-2 mr-2 text-sm text-gray-500">Lauren Asher</p>
                                <div class="rating text-yellow-400 flex items-center">
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                </div>
                            </div>
                            <span class="price text-primary font-bold text-lg">$870</span>
                            <div class="card-concern absolute left-0 right-0 flex justify-center gap-2 opacity-0">
                                <button type="button" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div class="swiper-slide">
                        <div class="card relative p-6 border rounded-xl hover:shadow-lg transition-shadow">
                            <img src="{{ asset('template/images/product-item3.png') }}" class="w-full shadow-sm"
                                alt="Heavenly Bodies">
                            <h6 class="mt-4 mb-1 font-bold text-lg"><a href="#" class="hover:text-primary">Heavenly
                                    Bodies</a></h6>
                            <div class="flex items-center">
                                <p class="my-2 mr-2 text-sm text-gray-500">Lauren Asher</p>
                                <div class="rating text-yellow-400 flex items-center">
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                </div>
                            </div>
                            <span class="price text-primary font-bold text-lg">$870</span>
                            <div class="card-concern absolute left-0 right-0 flex justify-center gap-2 opacity-0">
                                <button type="button" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div class="swiper-slide">
                        <div class="card relative p-6 border rounded-xl hover:shadow-lg transition-shadow">
                            <div class="absolute top-4 left-4">
                                <p class="bg-primary py-1 px-3 text-sm text-white rounded-lg">10% off</p>
                            </div>
                            <img src="{{ asset('template/images/product-item4.png') }}" class="w-full shadow-sm"
                                alt="His Saving Grace">
                            <h6 class="mt-4 mb-1 font-bold text-lg"><a href="#" class="hover:text-primary">His
                                    Saving Grace</a></h6>
                            <div class="flex items-center">
                                <p class="my-2 mr-2 text-sm text-gray-500">Lauren Asher</p>
                                <div class="rating text-yellow-400 flex items-center">
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                </div>
                            </div>
                            <span class="price text-primary font-bold text-lg">$870</span>
                            <div class="card-concern absolute left-0 right-0 flex justify-center gap-2 opacity-0">
                                <button type="button" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product 5 -->
                    <div class="swiper-slide">
                        <div class="card relative p-6 border rounded-xl hover:shadow-lg transition-shadow">
                            <img src="{{ asset('template/images/product-item5.png') }}" class="w-full shadow-sm"
                                alt="My Dearest Darkest">
                            <h6 class="mt-4 mb-1 font-bold text-lg"><a href="#" class="hover:text-primary">My
                                    Dearest Darkest</a></h6>
                            <div class="flex items-center">
                                <p class="my-2 mr-2 text-sm text-gray-500">Lauren Asher</p>
                                <div class="rating text-yellow-400 flex items-center">
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                </div>
                            </div>
                            <span class="price text-primary font-bold text-lg">$870</span>
                            <div class="card-concern absolute left-0 right-0 flex justify-center gap-2 opacity-0">
                                <button type="button" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Product 6 -->
                    <div class="swiper-slide">
                        <div class="card relative p-6 border rounded-xl hover:shadow-lg transition-shadow">
                            <img src="{{ asset('template/images/product-item6.png') }}" class="w-full shadow-sm"
                                alt="The Story of Success">
                            <h6 class="mt-4 mb-1 font-bold text-lg"><a href="#" class="hover:text-primary">The Story
                                    of Success</a></h6>
                            <div class="flex items-center">
                                <p class="my-2 mr-2 text-sm text-gray-500">Lauren Asher</p>
                                <div class="rating text-yellow-400 flex items-center">
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current">
                                        <use xlink:href="#star-fill"></use>
                                    </svg>
                                </div>
                            </div>
                            <span class="price text-primary font-bold text-lg">$870</span>
                            <div class="card-concern absolute left-0 right-0 flex justify-center gap-2 opacity-0">
                                <button type="button" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#cart"></use>
                                    </svg>
                                </button>
                                <a href="#" class="p-2 bg-gray-800 text-white rounded-full hover:bg-gray-700">
                                    <svg class="w-8 h-8 p-1">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection