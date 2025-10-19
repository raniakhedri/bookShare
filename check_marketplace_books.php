<?php

// Quick script to check what books are in the marketplace
// Run: php check_marketplace_books.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MarketBook;

echo "=== Books in Marketplace ===\n\n";

$books = MarketBook::where('is_available', true)->get();

if ($books->isEmpty()) {
    echo "❌ No books found in marketplace!\n";
    echo "Please add some books first.\n\n";
} else {
    echo "✅ Found " . $books->count() . " book(s):\n\n";

    foreach ($books as $book) {
        echo "ID: {$book->id}\n";
        echo "Title: {$book->title}\n";
        echo "Author: {$book->author}\n";
        echo "Price: {$book->price}\n";
        echo "Condition: {$book->condition}\n";
        echo "---\n";
    }
}

echo "\n=== Testing API Search ===\n\n";

// Test with the Amazon book
$searchTitle = "It Ends with Us";
$searchAuthor = "Colleen Hoover";

echo "Searching for:\n";
echo "  Title: {$searchTitle}\n";
echo "  Author: {$searchAuthor}\n\n";

$results = MarketBook::where('is_available', true)
    ->where(function ($query) use ($searchTitle, $searchAuthor) {
        $query->where('title', 'LIKE', "%{$searchTitle}%")
            ->orWhere('author', 'LIKE', "%{$searchAuthor}%");
    })
    ->get();

if ($results->isEmpty()) {
    echo "❌ No matches found!\n\n";
    echo "Possible reasons:\n";
    echo "1. Title doesn't match: '{$searchTitle}'\n";
    echo "2. Author doesn't match: '{$searchAuthor}'\n";
    echo "3. Book not marked as available (is_available = 0)\n\n";

    echo "Try searching with partial match:\n";
    $partial = MarketBook::where('is_available', true)
        ->where(function ($query) use ($searchTitle) {
            $query->where('title', 'LIKE', "%Ends%")
                ->orWhere('author', 'LIKE', "%Hoover%");
        })
        ->get();

    if ($partial->count() > 0) {
        echo "✅ Found with partial search:\n";
        foreach ($partial as $book) {
            echo "  - {$book->title} by {$book->author}\n";
        }
    }
} else {
    echo "✅ Found {$results->count()} match(es):\n";
    foreach ($results as $book) {
        echo "  - {$book->title} by {$book->author}\n";
    }
}

echo "\n";
