@extends('frontoffice.layouts.app')

@section('title', 'Edit Book - BookShare Marketplace')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="container mx-auto px-4 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    ✏️ Edit Book
                </h1>
                <p class="text-lg text-gray-600">
                    Update your book details and keep your listing fresh
                </p>
            </div>

            <!-- Edit Form -->
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-purple-600 p-8 text-white">
                    <h2 class="text-2xl font-bold mb-2">Book Information</h2>
                    <p class="text-white/80">Update the details for "{{ $book->title }}"</p>
                </div>

                <form action="{{ route('marketplace.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">
                                    Book Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $book->title) }}"
                                       required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors @error('title') border-red-500 @enderror"
                                       placeholder="Enter the book title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Author -->
                            <div>
                                <label for="author" class="block text-sm font-bold text-gray-700 mb-2">
                                    Author <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="author" 
                                       name="author" 
                                       value="{{ old('author', $book->author) }}"
                                       required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors @error('author') border-red-500 @enderror"
                                       placeholder="Enter the author's name">
                                @error('author')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Condition -->
                            <div>
                                <label for="condition" class="block text-sm font-bold text-gray-700 mb-2">
                                    Book Condition <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="condition" value="New" class="sr-only peer" {{ old('condition', $book->condition) == 'New' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <div class="text-2xl mb-1">✨</div>
                                                <div class="font-semibold text-gray-900">Like New</div>
                                                <div class="text-sm text-gray-600">Perfect condition</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="condition" value="Good" class="sr-only peer" {{ old('condition', $book->condition) == 'Good' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <div class="text-2xl mb-1">📖</div>
                                                <div class="font-semibold text-gray-900">Good</div>
                                                <div class="text-sm text-gray-600">Minor wear</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="condition" value="Fair" class="sr-only peer" {{ old('condition', $book->condition) == 'Fair' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <div class="text-2xl mb-1">📚</div>
                                                <div class="font-semibold text-gray-900">Fair</div>
                                                <div class="text-sm text-gray-600">Some wear</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="condition" value="Poor" class="sr-only peer" {{ old('condition', $book->condition) == 'Poor' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                            <div class="text-center">
                                                <div class="text-2xl mb-1">📜</div>
                                                <div class="font-semibold text-gray-900">Well-Used</div>
                                                <div class="text-sm text-gray-600">Readable</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('condition')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price (Optional) -->
                            <div>
                                <label for="price" class="block text-sm font-bold text-gray-700 mb-2">
                                    Reference Price (Optional)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">$</span>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $book->price) }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors @error('price') border-red-500 @enderror"
                                           placeholder="0.00">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    💡 This helps others understand the book's value for exchanges
                                </p>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Current Image Preview -->
                            @if($book->image_path || $book->image)
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Current Image</label>
                                    <div class="w-48 h-64 mx-auto relative rounded-xl overflow-hidden shadow-lg">
                                        @if($book->image_path)
                                            <img src="{{ asset('storage/' . $book->image_path) }}" 
                                                 alt="{{ $book->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $book->image) }}" 
                                                 alt="{{ $book->title }}" 
                                                 class="w-full h-full object-cover">
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">
                                    {{ ($book->image_path || $book->image) ? 'Update' : 'Add' }} Book Cover Image
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary transition-colors">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <label for="image" class="cursor-pointer">
                                        <span class="text-primary font-semibold">Click to upload</span>
                                        <span class="text-gray-600"> or drag and drop</span>
                                        <input type="file" 
                                               id="image" 
                                               name="image" 
                                               accept="image/*"
                                               class="hidden">
                                    </label>
                                    <p class="text-sm text-gray-500 mt-2">PNG, JPG up to 2MB</p>
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Availability Toggle -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Availability Status</label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="hidden" name="is_available" value="0">
                                    <input type="checkbox" 
                                           name="is_available" 
                                           value="1"
                                           {{ old('is_available', $book->is_available) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="relative w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-green-500"></div>
                                    <span class="ml-3 text-gray-700 font-medium">
                                        Make this book available for requests
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="mt-8">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors resize-none @error('description') border-red-500 @enderror"
                                  placeholder="Share more details about your book, its condition, or why you love it...">{{ old('description', $book->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-200">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-primary to-red-500 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            💾 Update Book
                        </button>
                        <a href="{{ route('marketplace.books.index') }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-colors text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add image preview logic here if needed
                    console.log('Image selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection