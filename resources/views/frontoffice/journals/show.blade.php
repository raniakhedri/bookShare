@extends('frontoffice.layouts.app')

@section('title', $journal->name . ' - Bookly')

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-10">
    <div class="container mx-auto px-4 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12 transition-all duration-700 starting:opacity-0 starting:translate-y-6">
            <div class="mx-auto w-16 h-16 bg-[#f53003]/10 dark:bg-[#FF4433]/10 rounded-2xl flex items-center justify-center mb-5">
                <i class="bi bi-journal-text text-[#f53003] dark:text-[#FF4433] text-2xl"></i>
            </div>

            <h1 class="text-4xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">{{ $journal->name }}</h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                {{ $books->count() }} {{ Str::plural('book', $books->count()) }}
                @if($archivedCount > 0)
                    • {{ $archivedCount }} archived
                @endif
            </p>

            <!-- 🔘 Boutons uniformes avec effet de hover -->
            <div class="flex justify-center flex-wrap gap-4 mt-8 mb-12">

                @if($journal->user_id === auth()->id())
                    <!-- Edit -->
                    <a href="{{ route('journals.edit', $journal) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#f53003] text-[#f53003] bg-white dark:bg-[#161615] hover:bg-[#f53003] hover:text-white hover:shadow-lg hover:-translate-y-0.5 transition duration-300 dark:border-[#FF4433] dark:text-[#FF4433] dark:hover:bg-[#FF4433]">
                        <i class="bi bi-pencil"></i> Edit Journal
                    </a>
                @endif

                <!-- View Archived -->
                <a href="{{ route('journals.archived', $journal) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#706f6c] text-[#706f6c] bg-white dark:bg-[#161615] hover:bg-[#706f6c] hover:text-white hover:shadow-lg hover:-translate-y-0.5 transition duration-300 dark:border-[#A1A09A] dark:text-[#A1A09A]">
                    <i class="bi bi-archive"></i> View Archived
                    @if($archivedCount > 0)
                        <span class="ml-2 bg-[#f53003] dark:bg-[#FF4433] text-white text-xs font-semibold rounded-full px-2 py-0.5">{{ $archivedCount }}</span>
                    @endif
                </a>

                <!-- Leave Journal -->
                @if($journal->user_id !== auth()->id() && $journal->isSharedWith(auth()->user()))
                    <form action="{{ route('journals.leave', $journal->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#dc2626] text-[#dc2626] bg-white dark:bg-[#161615] hover:bg-[#dc2626] hover:text-white hover:shadow-lg hover:-translate-y-0.5 transition duration-300 dark:border-[#f87171] dark:text-[#f87171] dark:hover:bg-[#dc2626]"
                                onclick="return confirm('Are you sure you want to leave this journal? You will lose access immediately.')">
                            <i class="bi bi-box-arrow-left"></i> Leave Journal
                        </button>
                    </form>
                @endif

                <!-- Delete -->
                @if($journal->user_id === auth()->id())
                    <form action="{{ route('journals.destroy', $journal->id) }}" method="POST" 
                          onsubmit="return confirm('⚠️ Are you sure?\n\nThis will permanently delete the journal \"{{ $journal->name }}\" and remove all books from it. This action cannot be undone.')"
                          class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#dc2626] text-[#dc2626] bg-white dark:bg-[#161615] hover:bg-[#dc2626] hover:text-white hover:shadow-lg hover:-translate-y-0.5 transition duration-300 dark:border-[#f87171] dark:text-[#f87171] dark:hover:bg-[#dc2626]">
                            <i class="bi bi-trash"></i> Delete Journal
                        </button>
                    </form>
                @endif

                <!-- Share -->
                <button type="button" 
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#4f46e5] text-[#4f46e5] bg-white hover:bg-[#4f46e5] hover:text-white transition"
                        onclick="document.getElementById('share-modal').classList.remove('hidden')">
                    <i class="bi bi-share"></i> Share Journal
                </button>


                <!-- Manage Quiz -->
                @if($journal->user_id === auth()->id() && $journal->shares()->exists())
                    <a href="{{ route('journals.quizzes', $journal->id) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#16a34a] text-[#16a34a] bg-white dark:bg-[#161615] hover:bg-[#16a34a] hover:text-white hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                        <i class="bi bi-lightbulb"></i> Manage Quizzes
                    </a>
                @endif

                <!-- View Quiz -->
                @if($journal->user_id !== auth()->id() && $journal->isSharedWith(auth()->user()))
                    <a href="{{ route('journals.participantQuizzes', $journal->id) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium rounded-md border border-[#4f46e5] text-[#4f46e5] bg-white dark:bg-[#161615] hover:bg-[#4f46e5] hover:text-white hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                        <i class="bi bi-lightbulb"></i> View Quiz
                    </a>
                @endif
            </div>
        </div>

        <!-- Books Grid -->
        @if($books->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($books as $book)
                <div class="p-6 bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        @if($book->pivot->archived)
                            <span class="px-3 py-1 text-xs rounded-full bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A]">
                                <i class="bi bi-archive me-1"></i> Archived
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs rounded-full bg-[#f53003]/10 dark:bg-[#FF4433]/10 text-[#f53003] dark:text-[#FF4433]">
                                <i class="bi bi-book me-1"></i> Active
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        <a href="{{ route('journals.showBook', ['journal' => $journal->id, 'book' => $book->id]) }}" 
                           class="hover:text-[#f53003] dark:hover:text-[#FF4433] transition">
                            {{ $book->title }}
                        </a>
                    </h3>

                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">{{ Str::limit($book->description, 120) }}</p>

                    <div class="flex gap-3">
                        @if(!$book->pivot->archived)
                            <form action="{{ route('journals.archive-book', [$journal->id, $book->id]) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" 
                                        class="w-full px-3 py-2 text-sm font-medium rounded-md bg-[#fef5e6] text-[#b86e00] hover:bg-[#b86e00] hover:text-white hover:shadow-md transition duration-300">
                                    <i class="bi bi-archive"></i> Archive
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('journals.detach-book', [$journal->id, $book->id]) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to remove this book from the journal?');" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-3 py-2 text-sm font-medium rounded-md bg-[#fdeaea] text-[#b80000] hover:bg-[#b80000] hover:text-white hover:shadow-md transition duration-300">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- Navigation -->
        <div class="text-center mt-16">
            <a href="{{ route('books.index') }}" 
               class="inline-flex items-center gap-2 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433] transition">
                <i class="bi bi-arrow-left"></i> Back to Library
            </a>
        </div>
    </div>
</div>
<!-- 🔘 Modal Share -->
<div id="share-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 max-w-md w-full mx-4 relative">
        <button onclick="document.getElementById('share-modal').classList.add('hidden')" 
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="bi bi-x-lg"></i>
        </button>
        
        <h3 class="text-xl font-semibold text-[#1b1b18] mb-4">Share Journal</h3>
        
        <form action="{{ route('journals.share', $journal->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#706f6c] mb-2">
                    Share with (email address)
                </label>
                <input type="email" name="email" id="email" required
                       class="w-full px-4 py-2 rounded-lg border border-[#e3e3e0] bg-white text-[#1b1b18] focus:outline-none focus:ring-2 focus:ring-[#f53003]"
                       placeholder="Enter email address">
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" 
                        onclick="document.getElementById('share-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium rounded-md border border-[#e3e3e0] text-[#706f6c] hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-md bg-[#f53003] text-white hover:bg-[#d42a03] transition">
                    Share
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fermer le modal quand on clique en dehors
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('share-modal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.getElementById('share-modal').classList.add('hidden');
        }
    });

    // Animation du modal
    const shareModal = document.getElementById('share-modal');
    const modalContent = shareModal.querySelector('div');
    
    // Ajouter une transition douce
    shareModal.addEventListener('transitionend', function() {
        if (shareModal.classList.contains('hidden')) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
    });

    // Animer l'ouverture
    const originalOnClick = shareModal.previousElementSibling.onclick;
    shareModal.previousElementSibling.onclick = function(e) {
        originalOnClick.call(this, e);
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    };
</script>
@endsection
