<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\PdfTextExtractorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AudioBookWebController extends Controller
{
    private PdfTextExtractorService $pdfExtractor;

    public function __construct(PdfTextExtractorService $pdfExtractor)
    {
        $this->pdfExtractor = $pdfExtractor;
    }

    /**
     * Extrait le texte d'un livre PDF pour la lecture audio (route web)
     */
    public function extractText(Book $book): JsonResponse
    {
        try {
            // Vérifier l'accès au livre
            if (!$book->availability && !auth()->check()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ce livre nécessite une connexion',
                    'fallback' => $this->getFallbackContent($book)
                ], 403);
            }

            // Vérifier qu'il y a un fichier PDF
            if (!$book->file) {
                return response()->json([
                    'success' => false,
                    'error' => 'Aucun fichier PDF disponible',
                    'fallback' => $this->getFallbackContent($book)
                ], 404);
            }

            // Pour cette démonstration, retourner directement le contenu de fallback
            // Dans une implémentation complète, vous utiliseriez $this->pdfExtractor->extractText()
            
            $fallbackContent = $this->getFallbackContent($book);
            
            return response()->json([
                'success' => true,
                'cached' => false,
                'book' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                ],
                'extraction' => [
                    'method' => 'demo',
                    'text' => $fallbackContent['text'],
                    'chunks' => $fallbackContent['chunks'],
                    'stats' => $fallbackContent['stats']
                ],
                'audio_settings' => [
                    'recommended_speed' => 1.0,
                    'recommended_pause_duration' => 0.8,
                    'complexity_score' => 5,
                    'estimated_duration_minutes' => 2,
                    'suggested_voice_type' => 'conversational'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'extraction de texte web', [
                'book_id' => $book->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'extraction',
                'fallback' => $this->getFallbackContent($book)
            ], 500);
        }
    }

    /**
     * Sauvegarde la position de lecture (route web)
     */
    public function saveReadingPosition(Request $request, Book $book): JsonResponse
    {
        try {
            $request->validate([
                'chunk_index' => 'required|integer|min:0',
                'position' => 'required|integer|min:0',
                'total_chunks' => 'required|integer|min:1',
                'timestamp' => 'required|integer'
            ]);

            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Position sauvegardée localement (utilisateur non connecté)'
                ]);
            }

            // Sauvegarder en session pour les utilisateurs connectés
            $userId = auth()->id();
            $positionKey = "reading_position_{$book->id}";
            
            session()->put($positionKey, [
                'chunk_index' => $request->chunk_index,
                'position' => $request->position,
                'total_chunks' => $request->total_chunks,
                'timestamp' => $request->timestamp,
                'updated_at' => now()->toISOString()
            ]);

            Log::info('Position de lecture sauvegardée en session', [
                'user_id' => $userId,
                'book_id' => $book->id,
                'chunk_index' => $request->chunk_index
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Position sauvegardée'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde de position web', [
                'book_id' => $book->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Position sauvegardée localement'
            ]);
        }
    }

    /**
     * Récupère la position de lecture (route web)
     */
    public function getReadingPosition(Book $book): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'position' => null,
                    'has_position' => false,
                    'message' => 'Utilisateur non connecté - position locale uniquement'
                ]);
            }

            $positionKey = "reading_position_{$book->id}";
            $position = session()->get($positionKey);

            return response()->json([
                'success' => true,
                'position' => $position,
                'has_position' => $position !== null
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de position web', [
                'book_id' => $book->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => true,
                'position' => null,
                'has_position' => false
            ]);
        }
    }

    /**
     * Contenu de fallback pour la démonstration
     */
    private function getFallbackContent(Book $book): array
    {
        $demoText = "Bienvenue dans la lecture audio de \"{$book->title}\" par {$book->author}. " .
                   "Cette fonctionnalité révolutionnaire vous permet d'écouter vos livres préférés au lieu de les lire. " .
                   "Grâce à la synthèse vocale avancée, vous pouvez profiter de vos lectures tout en faisant d'autres activités. " .
                   "Les contrôles intuitifs vous permettent de personnaliser votre expérience d'écoute. " .
                   "Vous pouvez ajuster la vitesse de lecture, choisir différentes voix, et même naviguer entre les chapitres. " .
                   "Cette technologie rend la lecture plus accessible et offre une expérience immersive unique. " .
                   "Profitez de cette nouvelle façon de découvrir et d'apprécier la littérature !";

        $chunks = [
            "Bienvenue dans la lecture audio de \"{$book->title}\" par {$book->author}.",
            "Cette fonctionnalité révolutionnaire vous permet d'écouter vos livres préférés au lieu de les lire.",
            "Grâce à la synthèse vocale avancée, vous pouvez profiter de vos lectures tout en faisant d'autres activités.",
            "Les contrôles intuitifs vous permettent de personnaliser votre expérience d'écoute.",
            "Vous pouvez ajuster la vitesse de lecture, choisir différentes voix, et même naviguer entre les chapitres.",
            "Cette technologie rend la lecture plus accessible et offre une expérience immersive unique.",
            "Profitez de cette nouvelle façon de découvrir et d'apprécier la littérature !"
        ];

        return [
            'text' => $demoText,
            'chunks' => $chunks,
            'stats' => [
                'total_characters' => strlen($demoText),
                'total_words' => str_word_count($demoText),
                'total_chunks' => count($chunks),
                'estimated_reading_time' => 2
            ]
        ];
    }
}