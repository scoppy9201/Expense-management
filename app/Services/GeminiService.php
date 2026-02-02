<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeminiService
{
    protected array $apiKeys;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1/';
    protected int $currentKeyIndex = 0;

    public function __construct()
    {
        // Lấy nhiều API keys từ .env
        $keys = [
            env('GEMINI_API_KEY_1'),
            env('GEMINI_API_KEY_2'),
            env('GEMINI_API_KEY_3'),
            // Thêm nhiều keys nếu cần
        ];

        // Lọc bỏ key null/empty
        $this->apiKeys = array_filter($keys);

        if (empty($this->apiKeys)) {
            throw new \Exception('Không có GEMINI_API_KEY nào được cấu hình');
        }

        // Load index từ cache (để rotate giữa các requests)
        $this->currentKeyIndex = Cache::get('gemini_key_index', 0);
    }

    /**
     * Lấy API key tiếp theo (rotation)
     */
    protected function getNextApiKey(): string
    {
        $key = $this->apiKeys[$this->currentKeyIndex];
        
        // Chuyển sang key tiếp theo
        $this->currentKeyIndex = ($this->currentKeyIndex + 1) % count($this->apiKeys);
        
        // Lưu index mới
        Cache::put('gemini_key_index', $this->currentKeyIndex, 3600);
        
        return $key;
    }

    /**
     * Thử gọi API với retry logic (tự động đổi key nếu lỗi quota)
     */
    public function generateContent(array $payload): array
    {
        $model = $payload['model'] ?? 'models/gemini-2.5-flash';
        unset($payload['model']);

        $maxRetries = count($this->apiKeys);
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $apiKey = $this->getNextApiKey();
                $url = $this->baseUrl . $model . ':generateContent?key=' . $apiKey;

                $response = Http::timeout(30)->post($url, $payload);

            /** @var \Illuminate\Http\Client\Response $response */
                if ($response->successful()) {
                    return $response->json();
                }

                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? '';

                // Nếu lỗi quota/rate limit → thử key khác
                if ($response->status() === 429 || 
                    str_contains($errorMessage, 'quota') || 
                    str_contains($errorMessage, 'rate limit')) {
                    
                    Log::warning("Gemini quota exceeded on key " . substr($apiKey, 0, 10) . "..., trying next key");
                    $attempt++;
                    sleep(1); // Đợi 1 giây trước khi thử key khác
                    continue;
                }

                // Lỗi khác → throw ngay
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $errorBody
                ]);

                throw new \Exception($errorMessage ?: 'Lỗi Gemini API');

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::error('Gemini connection error: ' . $e->getMessage());
                throw new \Exception('Không thể kết nối đến Gemini API');
            }
        }

        // Hết tất cả keys
        throw new \Exception('Tất cả API keys đều đã vượt quota. Vui lòng đợi hoặc thêm key mới.');
    }

    public function simpleGenerate(string $prompt, string $model = 'models/gemini-2.5-flash'): string
    {
        $payload = [
            'model' => $model,
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 300,
            ]
        ];

        try {
            $response = $this->generateContent($payload);
            return $response['candidates'][0]['content']['parts'][0]['text']
                ?? 'Không có phản hồi từ Gemini';
        } catch (\Exception $e) {
            Log::error('SimpleGenerate error: ' . $e->getMessage());
            return 'Xin lỗi, không thể tạo phản hồi. Vui lòng thử lại.';
        }
    }

    public function testConnection(): bool
    {
        try {
            $payload = [
                'model' => 'models/gemini-2.5-flash',
                'contents' => [['role' => 'user', 'parts' => [['text' => 'Hello']]]]
            ];
            $response = $this->generateContent($payload);
            return isset($response['candidates']);
        } catch (\Exception $e) {
            Log::error('Connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra quota còn lại của từng key
     */
    public function checkQuotas(): array
    {
        $results = [];
        
        foreach ($this->apiKeys as $index => $key) {
            try {
                $url = $this->baseUrl . 'models/gemini-2.5-flash:generateContent?key=' . $key;
                $response = Http::timeout(5)->post($url, [
                    'contents' => [['role' => 'user', 'parts' => [['text' => 'test']]]]
                ]);

                /** @var \Illuminate\Http\Client\Response $response */
                $results["key_" . ($index + 1)] = [
                    'prefix' => substr($key, 0, 10) . '...',
                    'status' => $response->successful() ? 'OK' : 'QUOTA_EXCEEDED',
                    'http_code' => $response->status()
                ];
            } catch (\Exception $e) {
                $results["key_" . ($index + 1)] = [
                    'prefix' => substr($key, 0, 10) . '...',
                    'status' => 'ERROR',
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }
}
