@extends('frontoffice.layouts.app')

@section('title', 'Share Your Book - BookShare Marketplace')

@section('content')
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-blue-50 to-purple-50">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                📖 Share Your Book
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Add your book to the community library and connect with fellow readers
            </p>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">

            <!-- Step Indicator -->
            <div class="mb-12">
                <div class="flex items-center justify-center space-x-8">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                            1</div>
                        <span class="ml-3 text-gray-900 font-medium">Book Details</span>
                    </div>
                    <div class="h-px bg-gray-300 w-16"></div>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold">
                            2</div>
                        <span class="ml-3 text-gray-500">Review & Share</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('marketplace.books.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Left Column: Book Image -->
                    <div class="order-2 md:order-1">
                        <div class="sticky top-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">📸 Book Cover</h3>

                            <!-- Image Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors group cursor-pointer"
                                onclick="document.getElementById('image').click()">
                                <div id="uploadArea" class="space-y-4">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto group-hover:text-primary transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <div>
                                        <p
                                            class="text-lg font-medium text-gray-700 group-hover:text-primary transition-colors">
                                            Drop your book cover here</p>
                                        <p class="text-gray-500">or click to browse</p>
                                        <p class="text-sm text-gray-400 mt-2">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="hidden">
                                    <img id="previewImg" class="max-w-full max-h-80 rounded-lg shadow-md mx-auto"
                                        alt="Book cover preview">
                                    <p class="text-sm text-gray-600 mt-4">Click to change image</p>
                                </div>
                            </div>

                            <input id="image" name="image" type="file" accept="image/*" class="hidden"
                                onchange="previewImage(event)">

                            @error('image')
                                <p class="mt-3 text-red-600 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <!-- Quick Tips -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-blue-900 mb-2">📱 Photo Tips</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Use good lighting for clear photos</li>
                                    <li>• Capture the front cover clearly</li>
                                    <li>• Avoid shadows and glare</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Book Information -->
                    <div class="order-1 md:order-2 space-y-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">📚 Book Information</h3>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Book Title *</label>
                            <input type="text" id="title" name="title" required value="{{ old('title') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('title') border-red-500 @enderror"
                                placeholder="e.g., The Great Gatsby">
                            @error('title')
                                <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div>
                            <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">Author *</label>
                            <input type="text" id="author" name="author" required value="{{ old('author') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('author') border-red-500 @enderror"
                                placeholder="e.g., F. Scott Fitzgerald">
                            @error('author')
                                <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Condition -->
                        <div>
                            <label for="condition" class="block text-sm font-semibold text-gray-700 mb-2">Book Condition
                                *</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="New" class="sr-only peer" {{ old('condition') == 'New' ? 'checked' : '' }}>
                                    <div
                                        class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">✨</div>
                                            <div class="font-semibold text-gray-900">Like New</div>
                                            <div class="text-sm text-gray-600">Perfect condition</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="Good" class="sr-only peer" {{ old('condition') == 'Good' ? 'checked' : '' }}>
                                    <div
                                        class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">📖</div>
                                            <div class="font-semibold text-gray-900">Good</div>
                                            <div class="text-sm text-gray-600">Minor wear</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="Fair" class="sr-only peer" {{ old('condition') == 'Fair' ? 'checked' : '' }}>
                                    <div
                                        class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">📚</div>
                                            <div class="font-semibold text-gray-900">Fair</div>
                                            <div class="text-sm text-gray-600">Some wear</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="Poor" class="sr-only peer" {{ old('condition') == 'Poor' ? 'checked' : '' }}>
                                    <div
                                        class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">📜</div>
                                            <div class="font-semibold text-gray-900">Well-Used</div>
                                            <div class="text-sm text-gray-600">Readable</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('condition')
                                <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Tell us about
                                this book</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('description') border-red-500 @enderror"
                                placeholder="What did you love about this book? Any special notes about its condition or history?">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ISBN (Optional) -->
                            <div>
                                <label for="isbn" class="block text-sm font-semibold text-gray-700 mb-2">ISBN <span
                                        class="font-normal text-gray-500">(Optional)</span></label>
                                <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('isbn') border-red-500 @enderror"
                                    placeholder="978-0-123456-78-9">
                                @error('isbn')
                                    <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price (Reference) -->
                            <div>
                                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Reference Price
                                    <span class="font-normal text-gray-500">(Optional)</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                                    <input type="number" id="price" name="price" step="0.01" min="0"
                                        value="{{ old('price') }}"
                                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('price') border-red-500 @enderror"
                                        placeholder="0.00">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">For reference only - not for sale</p>
                                @error('price')
                                    <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Availability Toggle -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Make Available for Sharing</h4>
                                    <p class="text-sm text-gray-600">Others can request this book immediately</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_available" value="1" class="sr-only peer" {{ old('is_available', true) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="border-t border-gray-200 pt-8">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit"
                            class="px-8 py-4 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            🎉 Share Your Book
                        </button>
                        <a href="{{ route('marketplace') }}"
                            class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-lg text-center">
                            Cancel
                        </a>
                    </div>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500">
                            By sharing your book, you agree to our
                            <a href="#" class="text-primary hover:underline">Community Guidelines</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const uploadArea = document.getElementById('uploadArea');
            const previewDiv = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    uploadArea.classList.add('hidden');
                    previewDiv.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                uploadArea.classList.remove('hidden');
                previewDiv.classList.add('hidden');
            }
        }

        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            // Add loading state on form submit
            form.addEventListener('submit', function () {
                submitBtn.innerHTML = '⏳ Sharing Your Book...';
                submitBtn.disabled = true;
            });

            // Auto-format ISBN input
            const isbnInput = document.getElementById('isbn');
            if (isbnInput) {
                isbnInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/[^\d]/g, '');
                    if (value.length >= 3) {
                        value = value.substring(0, 3) + '-' + value.substring(3);
                    }
                    if (value.length >= 5) {
                        value = value.substring(0, 5) + '-' + value.substring(5);
                    }
                    if (value.length >= 12) {
                        value = value.substring(0, 12) + '-' + value.substring(12);
                    }
                    if (value.length >= 15) {
                        value = value.substring(0, 15) + '-' + value.substring(15);
                    }
                    e.target.value = value.substring(0, 17);
                });
            }
        });
    </script>
@endsection