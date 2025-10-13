@extends('frontoffice.layouts.app')

@section('title', 'My Journals - Bookly')

@section('content')

<div class="min-h-screen py-10 relative bg-[#FDFDFC]">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="w-full h-full bg-gradient-to-br from-[#f53003]/5 via-[#FF4433]/10 to-[#1b1b18]/5 blur-2xl opacity-60"></div>
    </div>
    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Section d'introduction avec illustration -->
        <div class="flex flex-col items-center justify-center mb-12 animate-fade-in-up">
            <div class="mx-auto w-16 h-16 bg-[#f53003]/10 dark:bg-[#FF4433]/10 rounded-2xl flex items-center justify-center mb-5">
                <i class="bi bi-journal-bookmark text-[#f53003] dark:text-[#FF4433] text-2xl"></i>
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 tracking-tight drop-shadow-sm">
                My Reading Journals
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                Curate your literary journey with beautifully organized journals.
            </p>
            <!-- Stats -->
            <div class="flex justify-center items-center gap-8 mt-8">
                <div class="text-center">
                    <span class="text-3xl font-bold text-[#f53003] dark:text-[#FF4433] animate-bounce">{{ $journals->count() }}</span>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Journals</p>
                </div>
                <div class="w-px h-10 bg-[#e3e3e0] dark:bg-[#3E3E3A]"></div>
                <div class="text-center">
                    <span class="text-3xl font-bold text-[#f53003] dark:text-[#FF4433] animate-bounce delay-150">{{ $totalBooks ?? 0 }}</span>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Books Total</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div>
            @if($journals->count() > 0)
                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($journals as $journal)
    <div class="relative bg-white/80 dark:bg-[#161615]/80 rounded-3xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-xl hover:shadow-2xl hover:scale-[1.025] transition-all duration-300 backdrop-blur-md animate-fade-in-up" style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);">
        <!-- Menu à 3 points -->
    <div class="absolute top-3 right-3 z-20">
            <div class="dropdown">
                <button 
                    class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-[#f53003]/10 dark:hover:bg-[#FF4433]/10 transition shadow"
                    onclick="toggleDropdown({{ $journal->id }})"
                    aria-label="Journal options"
                >
                    <i class="bi bi-three-dots text-[#706f6c] dark:text-[#A1A09A]"></i>
                </button>

                <div id="dropdown-{{ $journal->id }}" class="hidden absolute right-0 mt-1 w-48 bg-white/95 dark:bg-[#1D1D1B]/95 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl shadow-2xl z-30 backdrop-blur-md">
                    @if($journal->is_locked)
                        <form action="{{ route('journals.unlock', $journal->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#f8f9fa] dark:hover:bg-[#2A2A28] flex items-center gap-2">
                                <i class="bi bi-unlock"></i> Unlock Journal
                            </button>
                        </form>
                    @else
                        <form action="{{ route('journals.lock', $journal->id) }}" method="POST" class="lock-form" data-journal-id="{{ $journal->id }}">
                            @csrf
                            <button type="button" class="w-full text-left px-4 py-2 text-sm text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#f8f9fa] dark:hover:bg-[#2A2A28] flex items-center gap-2">
                                <i class="bi bi-lock"></i> Lock Journal
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
    <a href="{{ route('journals.show', $journal) }}" class="block p-8 pt-14 group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 flex items-center justify-center 
                        {{ $journal->is_locked 
                            ? 'bg-gray-200 dark:bg-gray-700' 
                            : 'bg-[#f53003]/10 dark:bg-[#FF4433]/10' }} rounded-2xl shadow-lg group-hover:scale-110 transition">
                        <i class="bi 
                            {{ $journal->is_locked ? 'bi-lock' : 'bi-journal-text' }} 
                            {{ $journal->is_locked 
                                ? 'text-gray-500 dark:text-gray-400' 
                                : 'text-[#f53003] dark:text-[#FF4433]' }} 
                            text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center gap-2">
    {{ $journal->name }}

    {{-- Badge Secret --}}
    @if($journal->is_locked)
        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded">
            🔒 Secret
        </span>
    @endif

    {{-- Badge type de journal --}}
    @if($journal->is_owner)
        <span class="text-xs bg-[#f53003]/10 dark:bg-[#FF4433]/10 text-[#f53003] dark:text-[#FF4433] px-2 py-0.5 rounded">
            🧍‍♂️ My Journal
        </span>
    @else
        <span class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded">
            🤝 Shared with me
        </span>
    @endif
</h3>

                </div>
                <div class="px-4 py-1 bg-gradient-to-r from-[#f53003]/20 to-[#FF4433]/20 dark:from-[#FF4433]/20 dark:to-[#f53003]/20 rounded-full text-sm text-[#f53003] dark:text-[#FF4433] font-semibold shadow">
                    {{ $journal->books->count() }} books
                </div>
            </div>

            @if($journal->description)
                <p class="text-base text-[#706f6c] dark:text-[#A1A09A] mb-4 italic">
                    {{ Str::limit($journal->description, 100) }}
                </p>
            @endif

            <div class="flex justify-between items-center text-sm text-[#706f6c] dark:text-[#A1A09A] mt-6">
                <span class="flex items-center gap-2">
                    <i class="bi bi-clock text-lg"></i>
                    Updated {{ $journal->updated_at->diffForHumans() }}
                </span>
                <i class="bi bi-arrow-right text-[#f53003] dark:text-[#FF4433] text-xl animate-pulse"></i>
            </div>
        </a>
    </div>
@endforeach
                </div>

                <!-- Create New -->
                <div class="text-center mt-10 animate-fade-in-up">
                    <div class="bg-[#fff2f2]/80 dark:bg-[#1D0002]/80 rounded-2xl p-10 max-w-xl mx-auto shadow-lg backdrop-blur-md">
                        <h3 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                            <i class="bi bi-journal-plus text-[#f53003] dark:text-[#FF4433] text-2xl mr-2"></i>
                            Create New Journal
                        </h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                            Organize your books by theme, project, or reading goals.
                        </p>
                        <a href="{{ route('journals.create') }}" 
                           class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-semibold text-lg shadow-lg hover:opacity-90 transition-all duration-200">
                            <i class="bi bi-plus-circle text-xl"></i>
                            Create
                        </a>
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center mt-20 animate-fade-in-up">
                    <div class="bg-white/80 dark:bg-[#161615]/80 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl p-12 max-w-xl mx-auto shadow-xl backdrop-blur-md">
                        <i class="bi bi-journal-plus text-[#f53003] dark:text-[#FF4433] text-6xl mb-4 animate-bounce"></i>
                        <h3 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                            Start Your Reading Journey
                        </h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">
                            Create your first journal to organize books by theme, project, or reading goals.
                        </p>
                        <a href="{{ route('journals.create') }}" 
                           class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-semibold text-lg shadow-lg hover:opacity-90 transition-all duration-200">
                            <i class="bi bi-plus-circle text-xl"></i>
                            Create First Journal
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<style>
@keyframes fade-in-up {
  0% { opacity: 0; transform: translateY(40px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
  animation: fade-in-up 0.8s cubic-bezier(.4,0,.2,1) both;
}
</style>
<script>
function toggleDropdown(journalId) {
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el.id !== `dropdown-${journalId}`) {
            el.classList.add('hidden');
        }
    });
    const dropdown = document.getElementById(`dropdown-${journalId}`);
    dropdown.classList.toggle('hidden');
}

// Fermer les dropdowns au clic extérieur
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

// Gestion du verrouillage avec mot de passe
document.querySelectorAll('.lock-form').forEach(form => {
    form.addEventListener('click', function(e) {
        const journalId = this.dataset.journalId;
        const password = prompt('🔒 Enter a password to lock this journal:');
        
        if (password === null) return; // Annulé
        
        if (password.trim() === '') {
            alert('Password cannot be empty.');
            return;
        }
        
        // Ajouter le champ password au formulaire
        let input = this.querySelector('input[name="password"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'password';
            this.appendChild(input);
        }
        input.value = password;
        
        // Soumettre
        this.submit();
    });
});
</script>
@endpush