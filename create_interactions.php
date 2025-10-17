<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UserInteraction;
use App\Models\Book;

echo "=== CRÉATION D'INTERACTIONS POUR L'UTILISATEUR TEST ===\n";

$userId = 3; // Notre utilisateur de test
$books = Book::where('availability', true)->get();

echo "Création d'interactions pour l'utilisateur $userId avec " . $books->count() . " livres...\n";

// Créer des interactions variées
$interactionTypes = ['view', 'like', 'share', 'read_time'];

foreach ($books->take(8) as $book) {
    // Créer quelques interactions aléatoires pour chaque livre
    foreach ($interactionTypes as $type) {
        $value = match($type) {
            'view' => 1.0,
            'like' => rand(0, 1) ? 1.0 : 0, // 50% de chance de liker
            'share' => rand(0, 2) ? 0 : 1.0, // 33% de chance de partager
            'read_time' => rand(5, 60) / 10.0 // 0.5 à 6.0 minutes
        };
        
        if ($value > 0) {
            UserInteraction::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'interaction_type' => $type,
                'interaction_value' => $value,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
            
            echo "Interaction $type pour '{$book->title}' (valeur: $value)\n";
        }
    }
}

echo "\n=== RÉSUMÉ ===\n";
$userInteractions = UserInteraction::where('user_id', $userId)->count();
echo "Total des interactions pour l'utilisateur $userId: $userInteractions\n";

echo "\n✅ Interactions créées pour l'utilisateur de test!\n";