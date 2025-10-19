<?php
// Test Imagen 3 book cover generation endpoint

$apiKey = 'AIzaSyCzqoIaXg-BqGnWgBFwHDpS3TZ67ykfh8E';
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/imagen-3:generateImage';

$payload = [
    'prompt' => 'Create a professional book cover for "The Great Gatsby" by F. Scott Fitzgerald. Design should be eye-catching, professional, and suitable for a book marketplace. Include the title and author name.',
    'number_of_images' => 1,
    'aspectRatio' => '3:4',
];

echo "Testing Imagen 3 API for book cover generation...\n";
echo "Endpoint: " . $endpoint . "\n";
echo "Payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint . '?key=' . $apiKey);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: " . $http_code . "\n";
echo "Response:\n";

if ($http_code === 200) {
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

    if (isset($data['images']) && !empty($data['images'])) {
        echo "\n✓ SUCCESS! Generated image data received\n";
        echo "Image size: " . strlen($data['images'][0]) . " bytes\n";
    }
} else {
    echo "✗ FAILED with status " . $http_code . "\n";
    echo $response . "\n";
    if ($error) {
        echo "cURL Error: " . $error . "\n";
    }
}
