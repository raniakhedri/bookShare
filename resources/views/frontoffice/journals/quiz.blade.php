@extends('frontoffice.layouts.app')

@section('title', $journal->name . ' — Gérer les Quiz')

@section('content')
<div class="min-h-screen py-12 relative bg-gradient-to-br from-[#fdf6f0] via-[#f7faff] to-[#fff]">

    <!-- Background Blur -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="w-full h-full bg-gradient-to-br from-[#f53003]/5 via-[#FF4433]/10 to-[#1b1b18]/5 blur-3xl opacity-50"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">

        <!-- Header -->
        <div class="flex flex-col items-center justify-center mb-12 animate-fade-in-up">
            <div class="w-24 h-24 flex items-center justify-center rounded-full bg-gradient-to-tr from-[#f53003]/20 to-[#FF4433]/20 shadow-xl mb-5 transform transition hover:scale-110 hover:rotate-6">
                <i class="bi bi-patch-question-fill text-[#f53003] dark:text-[#FF4433] text-4xl"></i>
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 tracking-tight text-center">
                 Gérer les Quiz — {{ $journal->name }}
            </h1>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg text-center">
                {{ $books->count() }} {{ Str::plural('livre', $books->count()) }} disponibles
            </p>
        </div>

        <!-- Messages -->
        @if(session('error'))
            <div class="bg-red-100 text-red-700 border border-red-300 px-6 py-3 rounded-2xl mb-6 text-center shadow">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 text-green-700 border border-green-300 px-6 py-3 rounded-2xl mb-6 text-center shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulaire Générer Quiz -->
        <div class="bg-white/80 dark:bg-[#161615]/80 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-3xl shadow-xl p-10 mb-12 backdrop-blur-md animate-fade-in-up">
            @if($books->count() > 0)
                <form action="{{ route('journals.generateQuiz', $journal->id) }}" method="POST" class="flex flex-col items-center gap-6">
                    @csrf
                    <div class="w-full md:w-2/3">
                        <label for="book_id" class="block text-left text-[#1b1b18] dark:text-[#EDEDEC] font-semibold mb-2">
                             Choisir un livre :
                        </label>
                        <select name="book_id" id="book_id"
                            class="w-full rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] px-4 py-3 text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#1D1D1B] focus:ring-2 focus:ring-[#f53003] transition">
                            <option value="">-- Sélectionner un livre --</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#f53003] to-[#FF4433] text-white rounded-full font-semibold shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-200">
                         Générer un Quiz
                    </button>
                </form>
            @else
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-center italic">
                    Aucun livre actif dans ce journal.
                </p>
            @endif
        </div>

        <!-- Quiz -->
        @isset($question)
        <div class="bg-white/90 dark:bg-[#161615]/90 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-3xl shadow-2xl p-10 mx-auto max-w-3xl text-left backdrop-blur-md animate-fade-in-up">
            <h2 class="text-2xl font-semibold mb-6 text-[#1b1b18] dark:text-[#EDEDEC]">
                {{ $question }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($options as $opt)
                    @php $letter = substr(trim($opt), 0, 1); @endphp
                    <button 
                        class="option-btn px-4 py-3 border border-[#f53003] rounded-xl text-[#f53003] hover:bg-[#f53003] hover:text-white font-medium transition hover:scale-105 duration-200"
                        data-letter="{{ $letter }}"
                        data-correct="{{ $correct }}">
                        {{ $opt }}
                    </button>
                @endforeach
            </div>
            <div id="quiz-feedback" class="mt-6 font-semibold text-lg text-center"></div>
        </div>

        <script>
        document.querySelectorAll('.option-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const correct = btn.getAttribute('data-correct');
                const chosen = btn.getAttribute('data-letter');
                const feedback = document.getElementById('quiz-feedback');

                document.querySelectorAll('.option-btn').forEach(b => b.disabled = true);

                if (chosen === correct) {
                    btn.classList.remove('border-[#f53003]', 'text-[#f53003]');
                    btn.classList.add('bg-green-500', 'text-white', 'border-green-500');
                    feedback.textContent = '✅ Bonne réponse !';
                    feedback.classList.remove('text-red-600');
                    feedback.classList.add('text-green-600');
                } else {
                    btn.classList.remove('border-[#f53003]', 'text-[#f53003]');
                    btn.classList.add('bg-red-500', 'text-white', 'border-red-500');
                    feedback.textContent = `❌ Mauvaise réponse. La bonne réponse était ${correct}.`;
                    feedback.classList.remove('text-green-600');
                    feedback.classList.add('text-red-600');
                }
            });
        });
        </script>
        @endisset
    </div>
</div>

@push('styles')
<style>
@keyframes fade-in-up {
  0% { opacity: 0; transform: translateY(40px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
  animation: fade-in-up 0.8s cubic-bezier(.4,0,.2,1) both;
}
.option-btn {
  transition: all 0.25s ease-in-out;
}
.option-btn:hover {
  transform: scale(1.05);
}
</style>
@endpush
@endsection
