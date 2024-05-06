<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateFaqs;
use App\Models\Chat;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OpenAiController extends Controller
{
    public function question(Request $request)
    {
        // Create a new Guzzle client instance
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
        ]);

        // Make an HTTP GET request to gpt
        $response = $client->request('POST', 'chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPEN_AI_KEY'),
            ],
            'json' => [
                'model' => 'gpt-4-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $request->question . ' your answer should be very brief and straight to the point and should not exceed 100 words',
                    ]
                ],
                'temperature' => 0.7
            ],
        ]);

        // Get the response body as a string
        $body = $response->getBody()->getContents();

        // Parse the JSON response
        $responseData = json_decode($body, true);

        // Extract the content message content
        $gptResponse = $responseData['choices'][0]['message']['content'];

        $chat =  Chat::create([
            'prompt' => $request->question,
            'response' => $gptResponse,
        ]);

        $return = [
            'response' => $gptResponse,
            'chatId' => $chat->id
        ];

        GenerateFaqs::dispatch();

        return $return;
    }
}
