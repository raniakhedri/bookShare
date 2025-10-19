<?php
// Test different Imagen 3 endpoint formats

$apiKey = 'AIzaSyCzqoIaXg-BqGnWgBFwHDpS3TZ67ykfh8E';

$endpoints = [
    'imagen-3-generate-001' => 'https://generativelanguage.googleapis.com/v1beta/models/imagen-3-generate-001:generateImage',
    'imagen-3' => 'https://generativelanguage.googleapis.com/v1beta/models/imagen-3:generateImage',
    'imagen3' => 'https://generativelanguage.googleapis.com/v1/projects/default/locations/us-central1/publishers/google/models/imagegeneration@006',
];

$payload = [
    'prompt' => 'A professional book cover',
    'number_of_images' => 1,
];

foreach ($endpoints as $name => $endpoint) {
    echo "\n=== Testing endpoint: " . $name . " ===\n";
    echo "URL: " . $endpoint . "\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint . '?key=' . $apiKey);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "Status: " . $http_code . "\n";

    if ($http_code === 200) {
        echo "✓ SUCCESS\n";
        $data = json_decode($response, true);
        if (isset($data['images'])) {
            echo "Images returned: " . count($data['images']) . "\n";
        }
    } elseif ($http_code === 404) {
        echo "✗ Not found (404)\n";
    } else {
        echo "Response: " . substr($response, 0, 200) . "\n";
    }
}
