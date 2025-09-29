@extends('marketplace.layout')

@section('title', 'Add New Book')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-8">
        <div class="container mx-auto px-4">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="inline-block p-2 bg-white rounded-2xl shadow-lg mb-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Share Your <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Book</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Add your book to the marketplace and connect with fellow readers in the community
                </p>
            </div>

            <!-- Form Section -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-8 md:p-12">
                        <form action="{{ route('marketplace.books.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-8">
                            @csrf

                            <!-- Book Image Upload Section -->
                            <div class="text-center">
                                <label class="block text-sm font-bold text-gray-700 mb-4">Book Cover Image</label>
                                <div class="relative inline-block">
                                    <div
                                        class="w-48 h-64 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-lg overflow-hidden border-4 border-dashed border-gray-300 hover:border-blue-400 transition-all duration-300">
                                        <img id="image-preview" src="#" alt="Preview"
                                            class="w-full h-full object-cover hidden">
                                        <div id="upload-placeholder"
                                            class="flex flex-col items-center justify-center h-full">
                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 font-medium">Click to upload</p>
                                            <p class="text-gray-400 text-sm">or drag and drop</p>
                                        </div>
                                    </div>
                                    <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        id="image" name="image" accept="image/*">
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Book Details Grid -->
                            <div class="grid md:grid-cols-2 gap-8">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <!-- Book Title -->
                                    <div>
                                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">
                                            Book Title <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('title') border-red-500 @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Enter the book title" required>
                                        @error('title')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Author -->
                                    <div>
                                        <label for="author" class="block text-sm font-bold text-gray-700 mb-2">
                                            Author <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('author') border-red-500 @enderror"
                                            id="author" name="author" value="{{ old('author') }}"
                                            placeholder="Enter the author's name" required>
                                        @error('author')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Condition -->
                                    <div>
                                        <label for="condition" class="block text-sm font-bold text-gray-700 mb-2">
                                            Book Condition <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('condition') border-red-500 @enderror"
                                            id="condition" name="condition" required>
                                            <option value="">Select condition</option>
                                            <option value="New" {{ old('condition') === 'New' ? 'selected' : '' }}>New -
                                                Pristine condition</option>
                                            <option value="Good" {{ old('condition') === 'Good' ? 'selected' : '' }}>Good -
                                                Minor wear</option>
                                            <option value="Fair" {{ old('condition') === 'Fair' ? 'selected' : '' }}>Fair -
                                                Some wear</option>
                                            <option value="Poor" {{ old('condition') === 'Poor' ? 'selected' : '' }}>Poor -
                                                Heavy wear</option>
                                        </select>
                                        @error('condition')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <label for="price" class="block text-sm font-bold text-gray-700 mb-2">
                                            Reference Price (Optional)
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold">$</span>
                                            <input type="number"
                                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('price') border-red-500 @enderror"
                                                id="price" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                                placeholder="0.00">
                                        </div>
                                        @error('price')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-gray-500 text-sm mt-1">Optional reference price for exchanges</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors resize-none @error('description') border-red-500 @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="Describe the book's content, condition details, or any other relevant information...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                <a href="{{ route('marketplace.my-books') }}"
                                    class="flex-1 text-center px-8 py-4 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors font-semibold">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="flex-1 px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl font-bold">
                                    Add Book to Marketplace
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });

        // Drag and drop functionality
        const uploadArea = document.querySelector('.relative.inline-block');
        const fileInput = document.getElementById('image');

        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('border-blue-400', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', function (e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        });
    </script>
@endsection