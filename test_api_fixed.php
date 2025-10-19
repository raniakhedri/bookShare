<?php

// Test the API endpoint directly
// Run: php test_api_fixed.php

$url = 'http://127.0.0.1:8000/api/public/books/check-availability?title=It+Ends+with+Us&author=Colleen+Hoover';

echo "Testing API endpoint:\n";
echo "$url\n\n";

$response = @file_get_contents($url);

if ($response === false) {
    echo "❌ ERROR: Could not connect to server!\n";
    echo "Make sure the Laravel server is running: php artisan serve\n\n";
    exit(1);
}

$data = json_decode($response, true);

echo "Response:\n";
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n\n";

if ($data['available']) {
    echo "✅ SUCCESS! Book found in marketplace!\n";
    echo "Count: " . $data['count'] . "\n";
    foreach ($data['books'] as $book) {
        echo "  - {$book['title']} by {$book['author']} ({$book['condition']}) - \${$book['price']}\n";
    }
} else {
    echo "❌ Book not found\n";
    echo "Message: " . $data['message'] . "\n";
}

echo "\n";
