<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OpenAIController extends Controller
{
    protected $openAI;

    public function __construct(Client $openAI)
    {
        $this->openAI = $openAI;
    }

    public function jobDissatisfaction(Request $request)
    { 
        return view('open_ai.job_des');
    }

    public function generateText(Request $request)
    {
        $content = $request->input('content'); // Fetch the raw JSON payload


        $quenstion = 'title: '.$content.'. I am looking for key specific questions are related to the title to solve my problem. Which questions are you going to ask for me to know what is he wants.
        Provide me a list of three questions that would be most relevant to ask before starting my work on this project.
        Each question should be asked as a question and has three alternatives to pick from as a list.
        The questions and answers to choose from need to be specific to the title and only display features separating this project title from other projects. specific to the project title. So that we do not mix features that can be in any project with this very specific one.
        Keep the phrasing simple. Each answer should have a maximum length of 5 words.';

        

        // Prepare data in the required format
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $quenstion,
                ],
            ],
            'max_tokens' => 300,
            'temperature' => 0,
        ];
       

        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->post('chat/completions', [
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return response()->json(['text' => $responseBody['choices'][0]['message']['content']]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse()->getBody()->getContents();
                return response()->json(['error' => $errorResponse], $e->getResponse()->getStatusCode());
            }

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function submitAnswers(Request $request)
    {
        $questions = $request->input('questions');
       
        // Prepare the prompt to send to OpenAI
        $prompt = "Here are the selected answers:\n\n";
        foreach ($questions as $q) {
            $prompt .= "{$q['question']}: {$q['answer']}\n";
        }
        $prompt .= "\nProvide a job Description based on these answers.";

        // Call OpenAI API
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => 300,
            'temperature' => 0,
        ];

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->post('chat/completions', [
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return response()->json(['text' => $responseBody['choices'][0]['message']['content']]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse()->getBody()->getContents();
                return response()->json(['error' => $errorResponse], $e->getResponse()->getStatusCode());
            }

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
