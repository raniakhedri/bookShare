<?php

namespace App\Http\Controllers\Frontoffice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Journal;
use App\Models\Quiz;
class QuizController extends Controller
{
    // Affiche la vue vide du quiz
    public function showQuiz($id)
    {
        $journal = Journal::findOrFail($id);
        return view('frontoffice.journals.quiz', compact('journal'));
    }


    // Génère un quiz via Ollama et renvoie la vue avec la question
    public function generateQuiz(Request $request, $id)
    {
        // Augmente le temps maximum d'exécution (5 minutes)
        ini_set('max_execution_time', 300);

        $journal = Journal::findOrFail($id);

        // Vérifie que l'utilisateur est le propriétaire
        if ($journal->user_id !== auth()->id()) {
            return back()->with('error', 'Seul le propriétaire peut générer un quiz.');
        }

        // Vérifie que le journal est partagé
        if (!\App\Models\JournalShare::where('journal_id', $journal->id)->exists()) {
            return back()->with('error', 'Le quiz ne peut être généré que pour un journal partagé.');
        }

        // Validation du livre choisi
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);
        $book = \App\Models\Book::findOrFail($request->book_id);

        // Lire le contenu du livre (ou fallback sur $book->content)
        $fileContent = '';
        if ($book->file && file_exists(storage_path('app/public/' . $book->file))) {
            $fileContent = file_get_contents(storage_path('app/public/' . $book->file));

            // Convertit en UTF-8 en ignorant les caractères invalides
            $fileContent = @iconv(mb_detect_encoding($fileContent, mb_detect_order(), true), 'UTF-8//IGNORE', $fileContent);

            // Limite la taille du texte pour éviter les timeouts
            $maxLength = 100000; // caractères maximum à envoyer à Ollama
            $fileContent = mb_substr($fileContent, 0, $maxLength);
        } else {
            $fileContent = $book->content ?? '';
            $fileContent = mb_substr($fileContent, 0, 10000);
        }

        // Préparer le prompt pour Ollama
       $prompt = "Generate one multiple-choice question (A, B, C, D) strictly based ONLY on the content of this book. Do NOT include any general knowledge questions or information not present in the book:\n\n" . $fileContent . 
       "\n\nPlease include the correct answer letter at the end.";
        try {
            // Appel à Ollama
            $response = Http::timeout(300)->asJson()->post('http://127.0.0.1:11434/api/generate', [
                'model' => 'llama3',
                'prompt' => $prompt,
                'stream' => false,
            ]);

            $data = $response->json();
            $text = $data['response'] ?? $data['output'] ?? $data['data'][0]['text'] ?? 'Aucune question générée.';

            // Extraire question, options et réponse correcte
            preg_match('/question[:\-]?\s*(.+?)(?:\n|$)/i', $text, $qMatch);
            preg_match_all('/([A-D])\)\s*(.+)/i', $text, $optLines, PREG_SET_ORDER);
            preg_match('/Correct answer[:\-]?\s*([A-D])/i', $text, $cMatch);

            $question = $qMatch[1] ?? "Question non détectée.";
            $options = [];
            foreach ($optLines as $line) {
                $options[] = $line[1] . ') ' . trim($line[2]);
            }
            $correct = isset($cMatch[1]) ? strtoupper($cMatch[1]) : null;

            // Récupère les livres actifs pour la liste déroulante
            $books = $journal->books()->wherePivot('archived', false)->get();

            // Sauvegarder le quiz dans la base de données
            $quiz = Quiz::create([
            'journal_id' => $journal->id,
            'question' => $question,
            'options' => json_encode($options),
            'correct_option' => $correct,
        ]);

       // Récupérer les participants du journal
        $participants = \App\Models\JournalShare::where('journal_id', $journal->id)->get();

        // Créer un lien entre le quiz et chaque participant
        foreach ($participants as $participant) {
            \App\Models\ParticipantQuiz::create([
                'quiz_id' => $quiz->id,
                'user_id' => $participant->user_id, // ✅ correction ici
            ]);
        }


        // Retourne la vue avec les données du quiz
        return view('frontoffice.journals.quiz', compact('journal', 'books', 'question', 'options', 'correct', 'quiz'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de connexion à Ollama : ' . $e->getMessage());
        }
    }


    // Affiche les quiz associés à un journal
    public function showQuizzes($journalId)
    {
        $journal = Journal::findOrFail($journalId);

        // Vérifie que le journal appartient au propriétaire (ou adapte selon ta logique)
        if ($journal->user_id !== auth()->id()) {
            return redirect()->route('journals.show', $journalId)
                            ->with('error', '⛔ Vous n’êtes pas autorisé à gérer les quiz de ce journal.');
        }

        // Vérifie qu'il y a au moins un partage (si tu veux cette règle)
        if (! $journal->shares()->exists()) {
            return redirect()->route('journals.show', $journalId)
                            ->with('error', '⚠️ Le journal doit être partagé pour gérer les quiz.');
        }

        // Récupère les livres liés au journal (pour la liste déroulante)
        $books = $journal->books()->wherePivot('archived', false)->get();

        // Si tu veux aussi l'historique des quizzes :
        $quizzes = \App\Models\InteractiveSession::where('journal_id', $journalId)
            ->where('type', 'quiz')
            ->get();

        // Passe journal, books et quizzes à la vue
        return view('frontoffice.journals.quiz', compact('journal', 'books', 'quizzes'));
    }


 public function submitAnswer(Request $request, $id)
{
    $quiz = \App\Models\Quiz::findOrFail($id);

    $request->validate([
        'answer' => 'required|in:A,B,C,D',
    ]);

    $isCorrect = strtoupper($request->answer) === strtoupper($quiz->correct_option);

    // ✅ Enregistre la réponse du participant (optionnel)
    \App\Models\QuizResponse::create([
        'quiz_id' => $quiz->id,
        'user_id' => auth()->id(),
        'selected_option' => strtoupper($request->answer),
        'is_correct' => $isCorrect,
    ]);

    // Message de feedback
    $message = $isCorrect ? '✅ Bonne réponse !' : "❌ Mauvaise réponse. La bonne réponse était : {$quiz->correct_option}";

    // Retour à la page précédente avec feedback uniquement pour ce quiz
    return back()->with('quiz_result_'.$quiz->id, [
        'correct' => $isCorrect,
        'message' => $message,
    ]);
}


public function showForParticipant($journalId)
{
    $journal = \App\Models\Journal::findOrFail($journalId);

    // Vérifier que l'utilisateur est bien un participant
    if ($journal->user_id !== auth()->id() && !$journal->isSharedWith(auth()->user())) {
        abort(403, 'Accès refusé');
    }

    // Récupérer les quiz du journal
    $quizzes = $journal->quizzes()->get(); // suppose que tu as une relation quizzes

    return view('frontoffice.journals.participant-quizzes', compact('journal', 'quizzes'));
}


}
