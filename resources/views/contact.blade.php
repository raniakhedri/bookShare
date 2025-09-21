@extends('layouts.app')

@section('title', 'Contact Us - Bookly Bookstore')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">Contact Us</h1>
                <p class="text-xl text-gray-600">
                    We'd love to hear from you. Get in touch with our team.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <!-- Contact Form -->
                <div class="w-full lg:w-2/3 pr-8 mb-8 lg:mb-0">
                    <h2 class="text-3xl font-semibold mb-6">Send us a Message</h2>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                                <input type="text" id="name" name="name"
                                    class="w-full px-4 py-3 border rounded-lg focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                    Address</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-3 border rounded-lg focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="subject" name="subject"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-primary focus:border-primary" required>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="6"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-primary focus:border-primary"
                                required></textarea>
                        </div>
                        <button type="submit"
                            class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-dark transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="w-full lg:w-1/3">
                    <h2 class="text-3xl font-semibold mb-6">Get in Touch</h2>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div
                                class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Email</h3>
                                <p class="text-gray-600">contact@bookly.com</p>
                                <p class="text-gray-600">support@bookly.com</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Phone</h3>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                                <p class="text-gray-600">+1 (555) 987-6543</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Address</h3>
                                <p class="text-gray-600">123 Book Street<br>Literary District<br>Reading City, RC 12345</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div
                                class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Business Hours</h3>
                                <p class="text-gray-600">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                <p class="text-gray-600">Saturday: 10:00 AM - 4:00 PM</p>
                                <p class="text-gray-600">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="bg-gray-100 p-3 rounded-full hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-5 h-5">
                                    <use xlink:href="#facebook"></use>
                                </svg>
                            </a>
                            <a href="#"
                                class="bg-gray-100 p-3 rounded-full hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-5 h-5">
                                    <use xlink:href="#twitter"></use>
                                </svg>
                            </a>
                            <a href="#"
                                class="bg-gray-100 p-3 rounded-full hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-5 h-5">
                                    <use xlink:href="#instagram"></use>
                                </svg>
                            </a>
                            <a href="#"
                                class="bg-gray-100 p-3 rounded-full hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-5 h-5">
                                    <use xlink:href="#linkedin"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center mb-8">Find Us</h2>
            <div class="bg-gray-300 h-96 rounded-lg flex items-center justify-center">
                <p class="text-gray-600">Interactive map would be integrated here</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center mb-12">Frequently Asked Questions</h2>

            <div class="max-w-3xl mx-auto space-y-6">
                <div class="border rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">How long does shipping take?</h3>
                    <p class="text-gray-600">We typically process orders within 1-2 business days and shipping takes 2-5
                        business days depending on your location.</p>
                </div>

                <div class="border rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">What is your return policy?</h3>
                    <p class="text-gray-600">We offer a 30-day return policy on all books in original condition. Simply
                        contact us to initiate a return.</p>
                </div>

                <div class="border rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Do you offer international shipping?</h3>
                    <p class="text-gray-600">Yes, we ship to most countries worldwide. Shipping costs and delivery times
                        vary by destination.</p>
                </div>

                <div class="border rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Can I track my order?</h3>
                    <p class="text-gray-600">Absolutely! Once your order ships, you'll receive a tracking number via email
                        to monitor your package's progress.</p>
                </div>
            </div>
        </div>
    </section>
@endsection