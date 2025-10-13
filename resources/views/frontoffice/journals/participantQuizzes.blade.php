@extends('frontoffice.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">

    <!-- En-tête avec image de journal -->
    <div class="text-center mb-10">
        <div class="mx-auto mb-6 w-28 h-28 flex items-center justify-center rounded-full border-4 border-white dark:border-gray-800 bg-white dark:bg-gray-900 shadow-lg transition transform hover:scale-105 hover:rotate-3 duration-300 ease-out">
            <i class="bi bi-patch-question-fill text-[#f53003] text-5xl"></i>
        </div>

        <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">
            {{ $journal->name }}
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Quiz disponibles</p>
    </div>

    <!-- Section des quiz -->
    @if($quizzes->count() > 0)
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($quizzes as $quiz)
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-md hover:shadow-lg transition duration-300 border border-gray-100 dark:border-gray-800 transform hover:-translate-y-1">
                    <p class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4 leading-relaxed">
                        {{ $quiz->question }}
                    </p>

                    <form method="POST" action="{{ route('quizzes.answer', $quiz->id) }}" class="space-y-3">
                        @csrf
                        @foreach(json_decode($quiz->options, true) as $option)
                            <label class="flex items-start gap-2 cursor-pointer text-gray-700 dark:text-gray-300 hover:text-[#f53003] dark:hover:text-[#FF4433] transition">
                                <input 
                                    type="radio" 
                                    name="answer" 
                                    value="{{ substr($option, 0, 1) }}" 
                                    class="mt-1 text-[#f53003] focus:ring-[#f53003] border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach

                        <button 
                            type="submit"
                            class="w-full mt-4 px-5 py-2.5 bg-[#f53003] text-white font-medium rounded-lg hover:bg-[#FF4433] focus:ring-4 focus:ring-[#f53003]/50 dark:focus:ring-[#FF4433]/50 transition-all">
                            Soumettre la réponse
                        </button>
                    </form>

                    @if(session('quiz_result_'.$quiz->id))
                        <div class="mt-4 px-4 py-3 rounded-lg text-center font-medium
                            {{ session('quiz_result_'.$quiz->id)['correct'] 
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' 
                                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }} transition-all">
                            {{ session('quiz_result_'.$quiz->id)['message'] }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
            <i class="bi bi-journal-x text-5xl mb-3 text-gray-400 dark:text-gray-500"></i>
            <p class="text-lg">Aucun quiz disponible pour ce journal pour le moment.</p>
        </div>
    @endif
</div>
@endsection
