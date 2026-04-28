<?php

namespace App\Services;

use App\Interfaces\openAi;
use App\Traits\Upload;


class OpenAiService implements openAi
{
    use Upload;

    protected $OPENAI_API_KEY;
    protected $OPENAI_MODEL;
    protected $MAX_TOKENS;

    public function __construct()
    {
        $this->OPENAI_API_KEY = basicControl()->open_ai_key;
        $this->OPENAI_MODEL = basicControl()->open_ai_model;
        $this->MAX_TOKENS = basicControl()->open_ai_max_token;
    }

    public function generateRes($request): array
    {

        $packageTitle = $request['title'];
        $length = $request['length'];

        $prompt = "I need to generate a description for my package titled '$packageTitle'. Please ensure the description does not exceed $length words. Thank you!";

        $postParams = [
            'model' => $this->OPENAI_MODEL,
            'messages' => [
                [
                    "role" => "system",
                    "content" => 'You are a helpful assistant.',
                ],
                [
                    "role" => "user",
                    "content" => $prompt,
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, openAi::OPENAI_CHAT_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->OPENAI_API_KEY ?? '',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));

        $res = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($res);

        if (isset($response->choices)) {
            return [
                'status' => 'success',
                'ai_response' => $response->choices[0]->message->content,
                'total_token' => $response->usage->total_tokens,
            ];
        } else {
            return [
                'status' => 'error',
                'message' => $response->error->message ?? 'Please Contact with admin',
            ];
        }
    }
    public function generateImage($request): array
    {
        $imageDescription = $request['title'] ?? 'default description';
        $image_type = $request['image_type'] ?? 'image';
        $image_count = (int) ($request['image_count'] ?? 1);

        $prompt = "I need to generate a {$image_type} for my package. Image description is: {$imageDescription}. Please ensure a total of {$image_count} images are generated. Thank you!";

        $postData = [
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => $image_count,
            'size' => '1024x1024',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, openAi::OPENAI_IMAGE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . 'Bearer ' . $this->OPENAI_API_KEY,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['error' => curl_error($ch)];
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
