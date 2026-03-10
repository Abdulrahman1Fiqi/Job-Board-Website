<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;

use OpenAI\Laravel\Facades\OpenAI;
use GuzzleHttp\Client;

class JobVacancyController extends Controller
{
    public function show(string $id){
        $jobVacancy = JobVacancy::findOrFail($id);
        return view('job-vacancies.show',compact('jobVacancy'));
    }
    public function apply(string $id){
        $jobVacancy = JobVacancy::findOrFail($id);
        return view('job-vacancies.apply',compact('jobVacancy'));
    }

    public function processApplication(Request $request,string $id){
        
    }

    public function testClaude(){
           
          try {
        $client = new Client([
            'base_uri' => 'https://api.anthropic.com/',
            'headers' => [
                'x-api-key' => env('CLAUDE_API_KEY'),
                'Content-Type' => 'application/json',
                'Anthropic-Version' => '2023-06-01',
            ],
        ]);

        $response = $client->post('v1/messages', [
            'json' => [
                'model' => 'claude-sonnet-4-6', // الموديل الموجود فعليًا
                'messages' => [
                    ["role" => "user", "content" => "Hello Claude, tell me a joke!"]
                ],
                'max_tokens' => 200
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        // اطبع الرد
        echo $data['completion'] ?? ($data['content'][0]['text'] ?? 'No text returned');

    } catch (\Exception $e) {
        dd($e->getMessage());
    }

    }

    
}
