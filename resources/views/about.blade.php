@extends('layouts.app')

@section('title', 'About Us - Bookly Bookstore')

@section('content')
    <!-- Hero Section -->
    <section class="py-28 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-6xl font-bold mb-4">About Bookly</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Your premier destination for books of all genres, serving passionate readers since 2013.
                </p>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-28">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center mb-16">
                <div class="w-full lg:w-1/2 mb-8 lg:mb-0">
                    <h2 class="text-4xl font-semibold mb-6">Our Story</h2>
                    <p class="text-gray-600 mb-6">
                        Founded in 2013, Bookly began as a small independent bookstore with a simple mission: to connect
                        readers with the books they love. What started as a passion project has grown into a comprehensive
                        online platform serving book enthusiasts worldwide.
                    </p>
                    <p class="text-gray-600 mb-6">
                        We believe that books have the power to transform lives, broaden perspectives, and build
                        communities. Our carefully curated selection includes everything from timeless classics to
                        contemporary bestsellers, ensuring there's something for every reader.
                    </p>
                </div>
                <div class="w-full lg:w-1/2 lg:pl-12">
                    <img src="{{ asset('template/images/single-image-about.jpg') }}" alt="Our Story"
                        class="w-full h-auto rounded-lg shadow-lg">
                </div>
            </div>

            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 lg:pr-12 order-2 lg:order-1">
                    <img src="{{ asset('template/images/single-image2.jpg') }}" alt="Our Mission"
                        class="w-full h-auto rounded-lg shadow-lg">
                </div>
                <div class="w-full lg:w-1/2 mb-8 lg:mb-0 order-1 lg:order-2">
                    <h2 class="text-4xl font-semibold mb-6">Our Mission</h2>
                    <p class="text-gray-600 mb-6">
                        At Bookly, we're committed to making quality literature accessible to everyone. We work directly
                        with publishers and authors to bring you the best books at competitive prices, while supporting the
                        literary community.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Our team of book lovers carefully reviews and recommends titles across all genres, helping you
                        discover your next favorite read. We also provide detailed reviews, author interviews, and reading
                        guides to enhance your literary journey.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-28 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-semibold mb-4">Our Values</h2>
                <p class="text-gray-600">The principles that guide everything we do</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8">
                            <use xlink:href="#quality"></use>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Quality</h3>
                    <p class="text-gray-600">We carefully curate our collection to ensure only the finest books make it to
                        our shelves.</p>
                </div>

                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8">
                            <use xlink:href="#price-tag"></use>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Value</h3>
                    <p class="text-gray-600">Great books at fair prices, with regular discounts and special offers for our
                        customers.</p>
                </div>

                <div class="text-center">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8">
                            <use xlink:href="#shield-plus"></use>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Service</h3>
                    <p class="text-gray-600">Exceptional customer service with fast shipping and easy returns on all orders.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-28">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-semibold mb-4">Meet Our Team</h2>
                <p class="text-gray-600">The passionate book lovers behind Bookly</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $team = [
                        ['name' => 'Sarah Johnson', 'role' => 'Founder & CEO', 'image' => 'commentor-item1.jpg'],
                        ['name' => 'Michael Chen', 'role' => 'Head of Curation', 'image' => 'commentor-item2.jpg'],
                        ['name' => 'Emily Rodriguez', 'role' => 'Customer Experience', 'image' => 'commentor-item3.jpg'],
                        ['name' => 'David Kim', 'role' => 'Technology Lead', 'image' => 'commentor-item1.jpg']
                    ];
                @endphp

                @foreach($team as $member)
                    <div class="text-center">
                        <img src="{{ asset('template/images/' . $member['image']) }}" alt="{{ $member['name'] }}"
                            class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                        <h3 class="text-xl font-semibold mb-2">{{ $member['name'] }}</h3>
                        <p class="text-gray-600">{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-28 bg-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-semibold mb-4">Ready to Start Reading?</h2>
            <p class="text-xl mb-8 opacity-90">
                Join thousands of satisfied customers and discover your next favorite book today.
            </p>
            <a class="inline-block bg-white text-primary px-8 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                Browse Our Collection
            </a>
        </div>
    </section>
@endsection