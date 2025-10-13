@extends('frontoffice.layouts.app')

@section('title', 'Unlock Journal - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-10">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-[#f53003]/10 dark:bg-[#FF4433]/10 rounded-2xl flex items-center justify-center mb-5">
                    <i class="bi bi-lock text-[#f53003] dark:text-[#FF4433] text-2xl"></i>
                </div>
                <h1 class="text-3xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Unlock Journal
                </h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    "{{ $journal->name }}" is locked. Enter the password to access it.
                </p>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
                <form action="{{ route('journals.unlock.attempt', $journal->id) }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            required
                            class="w-full px-4 py-3 bg-transparent border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-transparent"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-lg hover:opacity-90 transition">
                        <i class="bi bi-unlock"></i>
                        Unlock Journal
                    </button>

                    <div class="mt-4 text-center">
                        <a href="{{ route('journals.index') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">
                            ← Back to Journals
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection