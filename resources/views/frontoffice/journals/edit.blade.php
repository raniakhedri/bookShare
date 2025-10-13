@extends('frontoffice.layouts.app')

@section('title', 'Edit ' . $journal->name . ' - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-10">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 transition-all duration-700 starting:opacity-0 starting:translate-y-6">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Edit Journal
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                Update your reading companion.
            </p>
        </div>

        <!-- Form Card -->
        <div class="max-w-lg mx-auto">
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 shadow-sm">
                <form action="{{ route('journals.update', $journal) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Journal Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Journal Name <span class="text-[#f53003] dark:text-[#FF4433]">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#706f6c] dark:text-[#A1A09A]">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="w-full pl-10 pr-4 py-3 bg-transparent border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#A1A09A] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent"
                                placeholder="Enter journal name"
                                value="{{ old('name', $journal->name) }}"
                                required
                                autofocus
                                maxlength="100"
                            >
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            <span id="charCount">{{ strlen($journal->name) }}</span>/100 characters
                        </div>

                        @error('name')
                            <div class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433] flex items-center gap-1">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg hover:opacity-90 transition">
                            <i class="bi bi-check-circle"></i>
                            Update Journal
                        </button>
                        <a href="{{ route('journals.show', $journal) }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#d0d0cc] dark:hover:bg-[#2A2A28] transition text-center">
                            <i class="bi bi-arrow-left"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    /* Nothing extra needed if using Tailwind classes */
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const charCount = document.getElementById('charCount');
    
    nameInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
});
</script>
@endpush