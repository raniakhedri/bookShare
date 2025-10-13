@extends('frontoffice.layouts.app')

@section('title', 'Create a Journal - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-10">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 transition-all duration-700 starting:opacity-0 starting:translate-y-6">
            <h1 class="text-4xl lg:text-5xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                Create New Journal
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                Craft your perfect reading companion.
            </p>
        </div>

        <!-- Form Card -->
        <div class="max-w-lg mx-auto">
            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 shadow-sm">
                <form action="{{ route('journals.store') }}" method="POST" id="journalForm">
                    @csrf

                    <!-- Journal Name -->
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
                                placeholder="Ex: My 2024 Reading Challenge..."
                                required
                                autofocus
                                maxlength="100"
                            >
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                            <span id="charCount">0</span>/100 characters
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
                        <button 
                            type="submit" 
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg hover:opacity-90 transition"
                            id="submitBtn"
                        >
                            <i class="bi bi-plus-circle"></i>
                            <span id="btnText">Create Journal</span>
                            <span id="spinner" class="hidden">
                                <i class="bi bi-arrow-clockwise animate-spin"></i>
                            </span>
                        </button>
                        <a 
                            href="{{ url('/book') }}" 
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-lg hover:bg-[#d0d0cc] dark:hover:bg-[#2A2A28] transition text-center"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Back to Books
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
    .color-btn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .color-btn[data-active="true"] {
        border-color: rgba(0,0,0,0.2);
    }
    .color-btn:not([data-active="true"]) i {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counters
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const descCharCount = document.getElementById('descCharCount');

    if (nameInput) {
        nameInput.addEventListener('input', () => {
            charCount.textContent = nameInput.value.length;
        });
    }

    if (descInput) {
        descInput.addEventListener('input', () => {
            descCharCount.textContent = descInput.value.length;
        });
    }

    // Color selection
    const colorButtons = document.querySelectorAll('.color-btn');
    const journalColorInput = document.getElementById('journalColor');

    colorButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active state
            colorButtons.forEach(b => {
                b.setAttribute('data-active', 'false');
                b.querySelector('i').style.display = 'none';
            });
            // Set new active
            btn.setAttribute('data-active', 'true');
            btn.querySelector('i').style.display = 'block';
            journalColorInput.value = btn.getAttribute('data-color');
        });
    });

    // Form submission loading
    const form = document.getElementById('journalForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');

    if (form) {
        form.addEventListener('submit', () => {
            btnText.textContent = 'Creating...';
            spinner.classList.remove('hidden');
            submitBtn.disabled = true;
        });
    }
});
</script>
@endpush