<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\PdfTextExtractorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AudioBookController extends Controller
{
    private PdfTextExtractorService $pdfExtractor;

    public function __construct(PdfTextExtractorService $pdfExtractor)
    {
        $this->pdfExtractor = $pdfExtractor;
    }

    /**
     * Extrait le texte d'un livre PDF pour la lecture audio
     * 
     * @param Book $book
     * @return JsonResponse
     */
    public function extractText(Book $book): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur a accès au livre
            if (!$this->canAccessBook($book)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé à ce livre'
                ], 403);
            }

            // Vérifier qu'il y a un fichier PDF
            if (!$book->file) {
                return response()->json([
                    'success' => false,
                    'error' => 'Aucun fichier PDF associé à ce livre'
                ], 404);
            }

            // Utiliser le cache pour éviter de re-extraire le même texte
            $cacheKey = "book_text_extraction_{$book->id}";
            $cachedResult = Cache::get($cacheKey);

            if ($cachedResult) {
                Log::info('Utilisation du cache pour l\'extraction de texte', [
                    'book_id' => $book->id,
                    'cache_key' => $cacheKey
                ]);

                return response()->json([
                    'success' => true,
                    'cached' => true,
                    ...$cachedResult
                ]);
            }

            // Extraire le texte du PDF
            $result = $this->pdfExtractor->extractText($book->file);

            if ($result['success']) {
                // Préparer le texte pour l'audio
                $audioText = $this->pdfExtractor->prepareForAudio($result['text']);
                
                $response = [
                    'success' => true,
                    'cached' => false,
                    'book' => [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                    ],
                    'extraction' => [
                        'method' => $result['method'],
                        'text' => $audioText,
                        'original_text' => $result['text'],
                        'chunks' => $result['chunks'] ?? [],
                        'stats' => $result['stats'] ?? []
                    ],
                    'audio_settings' => $this->getRecommendedAudioSettings($result['text'])
                ];

                // Mettre en cache le résultat (24h)
                Cache::put($cacheKey, $response, 86400);

                Log::info('Extraction de texte PDF réussie', [
                    'book_id' => $book->id,
                    'method' => $result['method'],
                    'characters' => $result['stats']['total_characters'] ?? 0
                ]);

                return response()->json($response);

            } else {
                Log::warning('Échec de l\'extraction de texte PDF', [
                    'book_id' => $book->id,
                    'error' => $result['error'] ?? 'Erreur inconnue'
                ]);

                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Erreur lors de l\'extraction',
                    'fallback' => $this->getFallbackContent($book)
                ], 422);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'extraction de texte', [
                'book_id' => $book->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur serveur lors de l\'extraction',
                'fallback' => $this->getFallbackContent($book)
            ], 500);
        }
    }

    /**
     * Obtient les informations d'un PDF sans extraire le texte
     * 
     * @param Book $book
     * @return JsonResponse
     */
    public function getPdfInfo(Book $book): JsonResponse
    {
        try {
            if (!$this->canAccessBook($book)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé'
                ], 403);
            }

            if (!$book->file) {
                return response()->json([
                    'success' => false,
                    'error' => 'Aucun fichier PDF'
                ], 404);
            }

            $info = $this->pdfExtractor->getPdfInfo($book->file);

            return response()->json([
                'success' => true,
                'book_id' => $book->id,
                'pdf_info' => $info
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'obtention des infos PDF', [
                'book_id' => $book->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'obtention des informations PDF'
            ], 500);
        }
    }

    /**
     * Sauvegarde la position de lecture d'un utilisateur
     * 
     * @param Request $request
     * @param Book $book
     * @return JsonResponse
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

            if (!$this->canAccessBook($book)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé'
                ], 403);
            }

            $userId = auth()->id();
            $position = [
                'chunk_index' => $request->chunk_index,
                'position' => $request->position,
                'total_chunks' => $request->total_chunks,
                'timestamp' => $request->timestamp,
                'updated_at' => now()->toISOString()
            ];

            // Sauvegarder en cache et/ou en base de données
            $cacheKey = "reading_position_{$userId}_{$book->id}";
            Cache::put($cacheKey, $position, 86400 * 30); // 30 jours

            // Optionnel: sauvegarder aussi en base de données
            if (config('app.save_reading_positions_db', false)) {
                \DB::table('reading_positions')->updateOrInsert(
                    ['user_id' => $userId, 'book_id' => $book->id],
                    array_merge($position, ['created_at' => now(), 'updated_at' => now()])
                );
            }

            Log::info('Position de lecture sauvegardée', [
                'user_id' => $userId,
                'book_id' => $book->id,
                'chunk_index' => $request->chunk_index
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Position sauvegardée'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde de position', [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la sauvegarde'
            ], 500);
        }
    }

    /**
     * Récupère la position de lecture d'un utilisateur
     * 
     * @param Book $book
     * @return JsonResponse
     */
    public function getReadingPosition(Book $book): JsonResponse
    {
        try {
            if (!$this->canAccessBook($book)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé'
                ], 403);
            }

            $userId = auth()->id();
            $cacheKey = "reading_position_{$userId}_{$book->id}";
            
            $position = Cache::get($cacheKey);

            // Fallback vers la base de données si pas en cache
            if (!$position && config('app.save_reading_positions_db', false)) {
                $dbPosition = \DB::table('reading_positions')
                    ->where('user_id', $userId)
                    ->where('book_id', $book->id)
                    ->first();

                if ($dbPosition) {
                    $position = [
                        'chunk_index' => $dbPosition->chunk_index,
                        'position' => $dbPosition->position,
                        'total_chunks' => $dbPosition->total_chunks,
                        'timestamp' => $dbPosition->timestamp,
                        'updated_at' => $dbPosition->updated_at
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'position' => $position,
                'has_position' => $position !== null
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de position', [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération'
            ], 500);
        }
    }

    /**
     * Obtient les paramètres audio recommandés pour un texte
     * 
     * @param string $text
     * @return array
     */
    private function getRecommendedAudioSettings(string $text): array
    {
        $wordCount = str_word_count($text);
        $avgWordsPerSentence = $this->calculateAvgWordsPerSentence($text);
        
        return [
            'recommended_speed' => $avgWordsPerSentence > 20 ? 0.9 : 1.0,
            'recommended_pause_duration' => $avgWordsPerSentence > 25 ? 1.2 : 0.8,
            'complexity_score' => min(10, $avgWordsPerSentence / 2),
            'estimated_duration_minutes' => ceil($wordCount / 180), // 180 mots/minute moyenne
            'suggested_voice_type' => $wordCount > 5000 ? 'narrative' : 'conversational'
        ];
    }

    /**
     * Calcule le nombre moyen de mots par phrase
     */
    private function calculateAvgWordsPerSentence(string $text): float
    {
        $sentences = preg_split('/[.!?]+/', $text);
        $sentences = array_filter($sentences, function($s) { return trim($s) !== ''; });
        
        if (count($sentences) === 0) return 15;
        
        $totalWords = 0;
        foreach ($sentences as $sentence) {
            $totalWords += str_word_count(trim($sentence));
        }
        
        return $totalWords / count($sentences);
    }

    /**
     * Contenu de fallback si l'extraction échoue
     */
    private function getFallbackContent(Book $book): array
    {
        return [
            'text' => "Bienvenue dans la lecture audio de \"{$book->title}\" par {$book->author}. " .
                     "L'extraction automatique du texte n'est pas disponible, mais vous pouvez " .
                     "utiliser cette fonctionnalité de démonstration pour tester les contrôles audio.",
            'chunks' => [
                "Bienvenue dans la lecture audio de \"{$book->title}\" par {$book->author}.",
                "Cette fonctionnalité vous permet d'écouter vos livres au lieu de les lire.",
                "Vous pouvez contrôler la vitesse, changer de voix, et suivre votre progression.",
                "L'extraction automatique du texte nécessite une configuration serveur spéciale.",
                "Profitez de cette démonstration des fonctionnalités audio avancées."
            ],
            'stats' => [
                'total_characters' => 400,
                'total_words' => 80,
                'total_chunks' => 5,
                'estimated_reading_time' => 1
            ]
        ];
    }

    /**
     * Vérifie si l'utilisateur peut accéder au livre
     */
    private function canAccessBook(Book $book): bool
    {
        // Pour l'instant, tous les livres publics sont accessibles
        // Vous pouvez ajouter ici votre logique d'autorisation
        return $book->availability || auth()->check();
    }
}