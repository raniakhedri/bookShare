<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Book;
use App\Models\UserInteraction;
use App\Services\AIRecommendationService;

echo "=== DIAGNOSTIC IA ===\n";

// 1. Vérifier les livres
$totalBooks = Book::count();
$availableBooks = Book::where('availability', true)->count();

echo "Total des livres: $totalBooks\n";
echo "Livres disponibles: $availableBooks\n";

if ($availableBooks === 0) {
    echo "❌ PROBLÈME: Aucun livre disponible!\n";
    echo "Activation de tous les livres...\n";
    Book::query()->update(['availability' => true]);
    echo "✅ Tous les livres sont maintenant disponibles\n";
}

// 2. Vérifier les interactions
$totalInteractions = UserInteraction::count();
echo "Total des interactions: $totalInteractions\n";

// 3. Test du service IA
try {
    $aiService = new AIRecommendationService();
    $recommendations = $aiService->generateRecommendations(3, 5); // User ID 3 (notre utilisateur de test)
    
    echo "Recommandations générées: " . $recommendations->count() . "\n";
    
    if ($recommendations->count() > 0) {
        echo "✅ IA fonctionne!\n";
        foreach ($recommendations->take(2) as $rec) {
            echo "- " . $rec['book']->title . " (Score: " . $rec['score'] . ")\n";
        }
    } else {
        echo "❌ Aucune recommandation générée\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur IA: " . $e->getMessage() . "\n";
}