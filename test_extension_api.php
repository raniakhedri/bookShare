<?php

// Test script for BookShare Browser Extension API
// Run: php test_extension_api.php

$apiUrl = 'http://127.0.0.1:8000/api/public';

echo "=== Testing BookShare Extension API ===\n\n";

// Test 1: Check availability with title only
echo "Test 1: Checking availability with title 'Test Book'\n";
$params = http_build_query(['title' => 'Test Book']);
$response1 = file_get_contents("$apiUrl/books/check-availability?$params");
$data1 = json_decode($response1, true);
echo "Response: " . json_encode($data1, JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Check availability with title and author
echo "Test 2: Checking availability with title and author\n";
$params = http_build_query(['title' => 'Sample Book', 'author' => 'John Doe']);
$response2 = file_get_contents("$apiUrl/books/check-availability?$params");
$data2 = json_decode($response2, true);
echo "Response: " . json_encode($data2, JSON_PRETTY_PRINT) . "\n\n";

echo "=== Tests Complete ===\n";
echo "\nNote: If you see errors, make sure:\n";
echo "1. Laravel server is running (php artisan serve)\n";
echo "2. You have books in the market_books table\n";
echo "3. Database connection is working\n";
