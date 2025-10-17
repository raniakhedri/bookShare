<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfTextExtractorService
{
    /**
     * Extrait le texte d'un fichier PDF
     * 
     * @param string $pdfPath Chemin vers le fichier PDF
     * @return array
     */
    public function extractText(string $pdfPath): array
    {
        try {
            // Vérifier si le fichier existe
            if (!Storage::exists($pdfPath)) {
                throw new Exception("Le fichier PDF n'existe pas: {$pdfPath}");
            }

            $fullPath = Storage::path($pdfPath);
            $extractedText = '';
            $method = 'unknown';

            // Méthode 1: Utiliser pdftotext (si disponible)
            if ($this->commandExists('pdftotext')) {
                $extractedText = $this->extractWithPdftotext($fullPath);
                $method = 'pdftotext';
            }
            // Méthode 2: Utiliser une librairie PHP (ex: smalot/pdfparser)
            elseif (class_exists('\Smalot\PdfParser\Parser')) {
                $extractedText = $this->extractWithSmalotParser($fullPath);
                $method = 'smalot';
            }
            // Méthode 3: Fallback - retourner des métadonnées
            else {
                return $this->getFallbackData($pdfPath);
            }

            // Nettoyer et structurer le texte
            $cleanedText = $this->cleanExtractedText($extractedText);
            $chunks = $this->createTextChunks($cleanedText);

            return [
                'success' => true,
                'method' => $method,
                'text' => $cleanedText,
                'chunks' => $chunks,
                'stats' => [
                    'total_characters' => strlen($cleanedText),
                    'total_words' => str_word_count($cleanedText),
                    'total_chunks' => count($chunks),
                    'estimated_reading_time' => $this->estimateReadingTime($cleanedText)
                ]
            ];

        } catch (Exception $e) {
            Log::error('Erreur extraction PDF', [
                'file' => $pdfPath,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'method' => 'error'
            ];
        }
    }

    /**
     * Extraction avec pdftotext (meilleure qualité)
     */
    private function extractWithPdftotext(string $filePath): string
    {
        $command = "pdftotext -layout -nopgbrk " . escapeshellarg($filePath) . " -";
        $output = shell_exec($command);
        
        if ($output === null) {
            throw new Exception('Erreur lors de l\'exécution de pdftotext');
        }

        return $output;
    }

    /**
     * Extraction avec Smalot PDF Parser
     */
    private function extractWithSmalotParser(string $filePath): string
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($filePath);
        
        $text = $pdf->getText();
        
        if (empty($text)) {
            throw new Exception('Impossible d\'extraire le texte du PDF');
        }

        return $text;
    }

    /**
     * Données de fallback si l'extraction échoue
     */
    private function getFallbackData(string $pdfPath): array
    {
        return [
            'success' => false,
            'method' => 'fallback',
            'text' => $this->generateFallbackText($pdfPath),
            'chunks' => [],
            'stats' => [
                'total_characters' => 0,
                'total_words' => 0,
                'total_chunks' => 0,
                'estimated_reading_time' => 0
            ],
            'message' => 'Extraction automatique non disponible. Utilisez le mode de démonstration.'
        ];
    }

    /**
     * Génère un texte de remplacement
     */
    private function generateFallbackText(string $pdfPath): string
    {
        $fileName = basename($pdfPath);
        
        return "Bienvenue dans la lecture audio de ce document PDF ({$fileName}). " .
               "L'extraction automatique du texte n'est pas disponible sur ce serveur. " .
               "Pour une expérience complète, configurez les outils d'extraction PDF ou utilisez le mode de démonstration. " .
               "Cette fonctionnalité permet néanmoins de tester toutes les options audio disponibles.";
    }

    /**
     * Nettoie le texte extrait
     */
    private function cleanExtractedText(string $text): string
    {
        // Supprimer les caractères de contrôle
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // Normaliser les espaces et nouvelles lignes
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n\s*\n/', "\n\n", $text);
        
        // Supprimer les lignes très courtes (probablement des artefacts)
        $lines = explode("\n", $text);
        $cleanLines = array_filter($lines, function($line) {
            return strlen(trim($line)) > 10; // Garder seulement les lignes de plus de 10 caractères
        });
        
        // Reconstituer le texte
        $text = implode("\n", $cleanLines);
        
        // Normaliser la ponctuation
        $text = preg_replace('/[.]{2,}/', '.', $text);
        $text = preg_replace('/[!]{2,}/', '!', $text);
        $text = preg_replace('/[?]{2,}/', '?', $text);
        
        return trim($text);
    }

    /**
     * Divise le texte en chunks pour la lecture
     */
    private function createTextChunks(string $text, int $maxWordsPerChunk = 200): array
    {
        // Diviser en paragraphes
        $paragraphs = explode("\n\n", $text);
        $chunks = [];
        $currentChunk = '';
        $currentWordCount = 0;

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (empty($paragraph)) continue;

            $wordCount = str_word_count($paragraph);

            // Si le paragraphe seul dépasse la limite, le diviser en phrases
            if ($wordCount > $maxWordsPerChunk) {
                // Sauvegarder le chunk actuel s'il n'est pas vide
                if (!empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                    $currentWordCount = 0;
                }

                // Diviser le paragraphe long en phrases
                $sentences = $this->splitIntoSentences($paragraph);
                foreach ($sentences as $sentence) {
                    $sentenceWordCount = str_word_count($sentence);
                    
                    if ($currentWordCount + $sentenceWordCount > $maxWordsPerChunk && !empty($currentChunk)) {
                        $chunks[] = trim($currentChunk);
                        $currentChunk = $sentence;
                        $currentWordCount = $sentenceWordCount;
                    } else {
                        $currentChunk .= ($currentChunk ? ' ' : '') . $sentence;
                        $currentWordCount += $sentenceWordCount;
                    }
                }
            } else {
                // Le paragraphe tient dans un chunk
                if ($currentWordCount + $wordCount > $maxWordsPerChunk && !empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = $paragraph;
                    $currentWordCount = $wordCount;
                } else {
                    $currentChunk .= ($currentChunk ? "\n\n" : '') . $paragraph;
                    $currentWordCount += $wordCount;
                }
            }
        }

        // Ajouter le dernier chunk
        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return array_filter($chunks, function($chunk) {
            return strlen(trim($chunk)) > 0;
        });
    }

    /**
     * Divise un texte en phrases
     */
    private function splitIntoSentences(string $text): array
    {
        // Expression régulière pour détecter la fin des phrases
        $pattern = '/(?<=[.!?])\s+(?=[A-Z])/';
        $sentences = preg_split($pattern, $text, -1, PREG_SPLIT_NO_EMPTY);
        
        return array_map('trim', $sentences);
    }

    /**
     * Estime le temps de lecture en minutes
     */
    private function estimateReadingTime(string $text, int $wordsPerMinute = 200): int
    {
        $wordCount = str_word_count($text);
        return ceil($wordCount / $wordsPerMinute);
    }

    /**
     * Vérifie si une commande existe
     */
    private function commandExists(string $command): bool
    {
        $return = shell_exec("which {$command}");
        return !empty($return);
    }

    /**
     * Obtient des informations sur un PDF sans extraire le texte
     */
    public function getPdfInfo(string $pdfPath): array
    {
        try {
            $fullPath = Storage::path($pdfPath);
            
            if (!file_exists($fullPath)) {
                throw new Exception("Fichier non trouvé: {$pdfPath}");
            }

            $info = [
                'file_size' => filesize($fullPath),
                'file_name' => basename($pdfPath),
                'mime_type' => mime_content_type($fullPath),
                'created_at' => date('Y-m-d H:i:s', filectime($fullPath)),
                'modified_at' => date('Y-m-d H:i:s', filemtime($fullPath))
            ];

            // Essayer d'obtenir plus d'infos avec pdfinfo (si disponible)
            if ($this->commandExists('pdfinfo')) {
                $command = "pdfinfo " . escapeshellarg($fullPath);
                $output = shell_exec($command);
                
                if ($output) {
                    $lines = explode("\n", $output);
                    foreach ($lines as $line) {
                        if (strpos($line, ':') !== false) {
                            [$key, $value] = explode(':', $line, 2);
                            $key = strtolower(trim(str_replace(' ', '_', $key)));
                            $info[$key] = trim($value);
                        }
                    }
                }
            }

            return [
                'success' => true,
                'info' => $info
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Génère une version audio-friendly du texte
     */
    public function prepareForAudio(string $text): string
    {
        // Remplacer les abréviations communes
        $replacements = [
            'Dr.' => 'Doctor',
            'Mr.' => 'Mister',
            'Mrs.' => 'Misses',
            'Ms.' => 'Miss',
            'Prof.' => 'Professor',
            'etc.' => 'et cetera',
            'i.e.' => 'that is',
            'e.g.' => 'for example',
            '&' => 'and',
            '%' => 'percent',
            '@' => 'at',
            '#' => 'number',
            '$' => 'dollars',
            '€' => 'euros',
            '£' => 'pounds'
        ];

        foreach ($replacements as $search => $replace) {
            $text = str_replace($search, $replace, $text);
        }

        // Améliorer la prononciation des nombres
        $text = preg_replace_callback('/\b\d{4}\b/', function($matches) {
            $year = $matches[0];
            if ($year >= 1000 && $year <= 2100) {
                // Prononcer comme une année
                return $year;
            }
            return $year;
        }, $text);

        // Ajouter des pauses pour les paragraphes
        $text = str_replace("\n\n", ". ... ", $text);
        
        return $text;
    }
}