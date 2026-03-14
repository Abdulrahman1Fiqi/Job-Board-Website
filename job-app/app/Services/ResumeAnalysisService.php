<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ResumeAnalysisService 
{

    public function extractResumeInformation(string $fileUrl){

        try {
        // Extract raw text from the resume pdf file (read pdf file, and get the text)
        $rawText = $this->extractTextFromPdf($fileUrl);

        Log::debug('successfully'.strlen($rawText).'Characters');   

        // Use Claude API to organize the text into a structured format

      
            $client = new Client([
                'base_uri' => 'https://api.anthropic.com/',
                'headers' => [
                    'x-api-key' => env('CLAUDE_API_KEY'),
                    'Content-Type' => 'application/json',
                    'anthropic-version' => '2023-06-01',
                ],
            ]);

            $response = $client->post('v1/messages', [
                'json' => [
                    'model' => 'claude-sonnet-4-6',
                    'max_tokens' => 1024,
                    'temperature' => 0.1, 
                    'system' => 'You are a precise resume parser. Extract information exactly as it appears in the resume without adding any interpretation or additional information. Return ONLY a valid JSON object, no extra text.', // ✅ system منفصل
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => "Parse the following resume content and extract the information as a JSON object with the exact keys: 'summary', 'skills', 'experience', 'education'. Return an empty string for any key not found. Resume content: {$rawText}"
                        ],
                    ],
                
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            
            $text = $data['content'][0]['text'] ?? 'No text returned';

            if (empty($text)) {
                throw new \Exception('Claude returned empty content');
            }

            $text = preg_replace('/^```json\s*|\s*```$/s', '', trim($text));

            $parsed = json_decode($text, true);

            if (!is_array($parsed)) {
                Log::error('Parsed JSON is invalid', ['raw' => $text]);
                throw new \Exception('Invalid JSON returned from Claude');
            }
            
            if (json_last_error() === JSON_ERROR_NONE) {
            
                $requiredKeys = ['summary', 'skills', 'experience', 'education'];
                $missingKeys = array_diff($requiredKeys, array_keys($parsed));

                if (count($missingKeys) > 0) {
                    Log::error('Missing required keys: ' . implode(', ', $missingKeys));
                    throw new \Exception('Missing required keys in the parsed result');
                }

            
            }

            Log::debug('Claude response: ', $parsed ?? ['raw' => $text]);
        
        // Output: summary, skills, experience, education -> JSON

        // Return the JSON object
        return [
                'summary'    => is_array($parsed['summary'] ?? '') ? json_encode($parsed['summary']) : ($parsed['summary'] ?? ''),
                'skills'     => is_array($parsed['skills'] ?? '') ? json_encode($parsed['skills']) : ($parsed['skills'] ?? ''),
                'experience' => is_array($parsed['experience'] ?? '') ? json_encode($parsed['experience']) : ($parsed['experience'] ?? ''),
                'education'  => is_array($parsed['education'] ?? '') ? json_encode($parsed['education']) : ($parsed['education'] ?? ''),
            ];

        } catch (\Exception $e) {
            Log::error('Error extracting resume information: '.$e->getMessage());
            
            return [
                'summary'=>'',
                'skills'=>'',
                'experience'=>'',
                'education'=>'',
            ];

        }

    }




    

    private function extractTextFromPdf(string $fileUrl):string{
        // Reading the file from the cloud to local disk storage in temp file
        $tempFile = tempnam( sys_get_temp_dir(),'resume');

        $filePath = parse_url($fileUrl, PHP_URL_PATH);
        if (!$filePath) {
            throw new \Exception('Invalid file URL');
        }

        $filename = basename($filePath);

        $storagePath = "resumes/{$filename}";

        if (!Storage::disk('cloud')->exists($storagePath)) {
            throw new \Exception('File not found');
        }

        $pdfContent = Storage::disk('cloud')->get($storagePath);
        if (!$pdfContent) {
            throw new \Exception('Failed to read file');
        }

        file_put_contents($tempFile,$pdfContent);

        // Check if pdf-to-text is installed
        $pdfToTextPath = ['C:/poppler-25.12.0/Library/bin/pdftotext.exe'];
        $pdfToTextAvailable = false;

        foreach($pdfToTextPath as $path){
            if(file_exists($path)){
                $pdfToTextAvailable = true;
                break;
            }
        }

        if(!$pdfToTextAvailable){
            throw new \Exception('pdf-to-text is not installed.');
        }

        //Extract text from the pdf file
        $instance = new Pdf('C:/poppler-25.12.0/Library/bin/pdftotext.exe');
        $instance->setPdf($tempFile);
        $text = $instance->text();

        // Clean up the temp file
        unlink($tempFile);

        return $text;

    }


}