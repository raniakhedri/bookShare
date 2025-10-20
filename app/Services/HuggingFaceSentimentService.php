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
                Log::error('HuggingFace API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'model' => $this->modelUrl
                ]);
                return 'neutral';
            }

            $result = $response->json();
            
            // Log the actual response for debugging
            Log::info('HuggingFace API Response', ['result' => $result]);

            // Handle the nlptown/bert-base-multilingual-uncased-sentiment model
            // This model returns star ratings: "1 star", "2 stars", "3 stars", "4 stars", "5 stars"
            if (isset($result[0]) && is_array($result[0])) {
                // Get the highest scored label
                $maxScore = 0;
                $topLabel = null;
                
                foreach ($result[0] as $prediction) {
                    if (isset($prediction['score']) && $prediction['score'] > $maxScore) {
                        $maxScore = $prediction['score'];
                        $topLabel = $prediction['label'];
                    }
                }
                
                if ($topLabel) {
                    // Convert star ratings to sentiment
                    // 1-2 stars = negative, 3 stars = neutral, 4-5 stars = positive
                    if (str_contains($topLabel, '1 star') || str_contains($topLabel, '2 stars')) {
                        return 'negative';
                    } elseif (str_contains($topLabel, '3 stars')) {
                        return 'neutral';
                    } elseif (str_contains($topLabel, '4 stars') || str_contains($topLabel, '5 stars')) {
                        return 'positive';
                    }
                }
            }

        } catch (\Throwable $e) {
            Log::error('HuggingFace sentiment error: ' . $e->getMessage(), [
                'text_length' => strlen($text),
                'exception' => get_class($e)
            ]);
            return 'neutral';
        }

        return 'neutral';
    }
}
