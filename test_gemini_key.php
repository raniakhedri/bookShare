<?php
// Test the Gemini API key

$apiKey = 'AIzaSyCzqoIaXg-BqGnWgBFwHDpS3TZ67ykfh8E';
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint . '?key=' . $apiKey);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'contents' => [
        [
            'parts' => [['text' => 'Say hello briefly']]
        ]
    ],
    'generationConfig' => [
        'maxOutputTokens' => 100,
        'temperature' => 0.7,
    ]
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: " . $http_code . "\n";
echo "Response:\n";
if ($http_code === 200) {
    $data = json_decode($response, true);
    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        echo "✓ SUCCESS! Gemini API responded:\n";
        echo $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
    } else {
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
} else {
    echo "✗ FAILED with status " . $http_code . "\n";
    echo $response . "\n";
    if ($error) {
        echo "cURL Error: " . $error . "\n";
    }
}
