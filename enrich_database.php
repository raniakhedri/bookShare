<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Book;
use App\Models\Category;

echo "=== CRÉATION DE LIVRES POUR L'IA ===\n";

// Activer tous les livres existants
Book::query()->update(['availability' => true]);
echo "✅ Tous les livres existants sont maintenant disponibles\n";

// Créer quelques catégories si elles n'existent pas
$categories = [
    'Fiction' => 'Livres de fiction et romans',
    'Science' => 'Livres scientifiques et techniques', 
    'Histoire' => 'Livres d\'histoire et biographies',
    'Art' => 'Livres d\'art et de culture',
    'Philosophie' => 'Livres de philosophie et pensée'
];

foreach ($categories as $name => $description) {
    $category = Category::firstOrCreate(['name' => $name], ['description' => $description]);
    echo "Catégorie: $name (ID: {$category->id})\n";
}

// Créer des livres supplémentaires
$books = [
    ['title' => 'Les Misérables', 'author' => 'Victor Hugo', 'category' => 'Fiction', 'description' => 'Chef-d\'œuvre de la littérature française'],
    ['title' => 'Une Brève Histoire du Temps', 'author' => 'Stephen Hawking', 'category' => 'Science', 'description' => 'Exploration de l\'univers et du temps'],
    ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'category' => 'Histoire', 'description' => 'Histoire de l\'humanité'],
    ['title' => 'L\'Art de la Guerre', 'author' => 'Sun Tzu', 'category' => 'Philosophie', 'description' => 'Traité de stratégie militaire'],
    ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'category' => 'Fiction', 'description' => 'Conte philosophique'],
    ['title' => '1984', 'author' => 'George Orwell', 'category' => 'Fiction', 'description' => 'Dystopie totalitaire'],
    ['title' => 'L\'Histoire de l\'Art', 'author' => 'Ernst Gombrich', 'category' => 'Art', 'description' => 'Histoire de l\'art occidental'],
    ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'category' => 'Science', 'description' => 'Exploration de l\'univers'],
];

foreach ($books as $bookData) {
    $category = Category::where('name', $bookData['category'])->first();
    
    $book = Book::firstOrCreate(
        ['title' => $bookData['title']],
        [
            'author' => $bookData['author'],
            'category_id' => $category->id,
            'description' => $bookData['description'],
            'availability' => true,
            'user_id' => 1, // Admin user
            'condition' => 'Good'
        ]
    );
    
    echo "Livre: {$book->title} par {$book->author} (ID: {$book->id})\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "Total des livres: " . Book::count() . "\n";
echo "Livres disponibles: " . Book::where('availability', true)->count() . "\n";
echo "Total des catégories: " . Category::count() . "\n";

echo "\n✅ Base de données enrichie pour l'IA!\n";