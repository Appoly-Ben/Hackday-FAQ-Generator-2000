<?php

namespace App\Jobs;

use App\Models\Chat;
use App\Models\Faq;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateFaqs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chats;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->chats = Chat::all()->toJson();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $prompt = $this->chats . ' Using this data generate me some faqs by looking at the most frequently occurring similar prompts and then using the response_ratings to generate the best answer. You should generally favour more recent questions over old ones. There should be exactly 8 faqs. You should only return me the faqs in the following format: [{question: "", answer: ""}] and nothing else';

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
                        'content' => $prompt,
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
        $gptResponse = json_decode($responseData['choices'][0]['message']['content']);

        Log::info($gptResponse);

        if ($gptResponse) {

            Faq::truncate();

            foreach ($gptResponse as $faq) {
                Faq::create(['question' => $faq->question, 'answer' => $faq->answer]);
            }
        }
    }
}
