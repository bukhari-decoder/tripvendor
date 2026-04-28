<?php

namespace App\Services;

use App\Interfaces\gemini;
use App\Traits\Upload;
use Illuminate\Support\Facades\File;


class GeminiService implements gemini
{
    use Upload;

    protected $GEMINI_API_KEY;
    protected $GEMINI_MODEL;
    protected $MAX_TOKENS;

    public function __construct()
    {
        $this->GEMINI_API_KEY = basicControl()->gemini_key;
        $this->GEMINI_MODEL = basicControl()->gemini_model;
        $this->MAX_TOKENS = basicControl()->gemini_max_token;
    }

    public function generateRes($request): array
    {
        $packageTitle = $request['title'];
        $length = $request['length'];

        $prompt = "I need to generate a description for my package titled '$packageTitle'. Please ensure the description does not exceed $length words. Thank you!";

        $apiKey = $this->GEMINI_API_KEY;
        $apiUrl = gemini::GEMINI_BASE_URL . $this->GEMINI_MODEL . ':generateContent';

        $data = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.9,
                "topK" => 1,
                "topP" => 1,
                "maxOutputTokens" => $this->MAX_TOKENS ?? 1000,
                "stopSequences" => []
            ],
        ];

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-goog-api-key: ' . $apiKey
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['candidates']) && isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            return [
                'status' => 'success',
                'ai_response' => $responseData['candidates'][0]['content']['parts'][0]['text'],
                'total_token' => $responseData['usageMetadata']['totalTokenCount'],
            ];
        } else {
            return [
                'status' => 'error',
                'message' => curl_error($ch) ?? 'Please Contact with admin',
            ];
        }
    }

    public function generateImage($request): array
    {
        $imageDescription = $request['imageDescription'];
        $image_count = $request['image_count'];
        $prompt = "Hi, can you create $image_count image(s) based on the following description: $imageDescription? Please make them 1024x1024 in size. Thank you!";

        $imageModel = 'gemini-2.0-flash-preview-image-generation';
        $apiKey = $this->GEMINI_API_KEY;
        $apiUrl = Gemini::GEMINI_BASE_URL . $imageModel . ":generateContent?key={$apiKey}";

        $postData = [

            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "responseModalities" => ["IMAGE","TEXT"]
            ]
        ];

        $headers = [
            "Content-Type: application/json"
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new \Exception("Curl error while calling Gemini API: {$error_msg}");
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        $imageDataUris = [];

        $parts = $responseData['candidates'][0]['content']['parts'] ?? [];

        foreach ($parts as $index => $part) {
            if (isset($part['inlineData'])) {
                $base64Image = $part['inlineData']['data'] ?? null;
                $mimeType = $part['inlineData']['mimeType'] ?? 'image/png';

                if ($base64Image) {
                    $imageDataUris[] = "data:{$mimeType};base64,{$base64Image}";
                }
            }
        }

        if (count($imageDataUris)) {
            return [
                'status' => 'success',
                'message' => count($imageDataUris) . ' image(s) generated successfully.',
                'image_data_uris' => $imageDataUris
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'No image data found in Gemini API response.',
                'raw_response' => $responseData,
            ];
        }
    }

}
