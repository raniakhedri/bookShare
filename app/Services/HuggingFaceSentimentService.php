<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceSentimentService
{
    protected string $apiKey;
    protected string $modelUrl;

    public function __construct()
    {
        $this->apiKey = env('HUGGINGFACE_API_KEY');
        $model = env('HUGGINGFACE_MODEL');
        $this->modelUrl = "https://api-inference.huggingface.co/models/{$model}";
    }

    public function analyze(string $text): string
    {
        if (empty(trim($text))) {
            return 'neutral';
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
            ])->timeout(20)
              ->post($this->modelUrl, ['inputs' => $text]);

            if ($response->failed()) {
                Log::error('HuggingFace API failed', ['body' => $response->body()]);
                return 'neutral';
            }

            $result = $response->json();

            // New HF API sometimes returns nested arrays
            if (isset($result[0]) && is_array($result[0])) {
                $data = $result[0][0] ?? $result[0];
                if (isset($data['label'])) {
                    $label = strtolower($data['label']);
                    if (in_array($label, ['positive', 'negative', 'neutral'])) {
                        return $label;
                    }
                }
            }

        } catch (\Throwable $e) {
            Log::error('HuggingFace sentiment error: ' . $e->getMessage());
            return 'neutral';
        }

        return 'neutral';
    }
}
