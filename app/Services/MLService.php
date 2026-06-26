<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MLService
{
    private static $instance = null;

    private $apiUrl;

    private function __construct()
    {
        $this->apiUrl = env('ML_SERVICE_URL', 'http://127.0.0.1:5000');
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new MLService();
        }

        return self::$instance;
    }

    public function classifyTransaction($description) 
    {
        try {
            $response = Http::timeout(3)->post($this->apiUrl . '/classify', [
                'text' => $description,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('ML Service: Eroare la clasificare. Fallback la Diverse.');
            return ['category' => 'Diverse', 'confidence' => 0];

        } catch (\Exception $e) {
            Log::error('ML Service: Conexiunea la clasificare a eșuat: ' . $e->getMessage());
            return ['category' => 'Diverse', 'confidence' => 0];
        }
    }

    public function predictSavings($familyData)
    {
        try {
            $response = Http::timeout(5)->post($this->apiUrl . '/predict_savings', $familyData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('ML Service: Eroare la predicția economiilor.');
            return null;

        } catch (\Exception $e) {
            Log::error('ML Service: Conexiunea la predicție a eșuat: ' . $e->getMessage());
            return null;
        }
    }
}