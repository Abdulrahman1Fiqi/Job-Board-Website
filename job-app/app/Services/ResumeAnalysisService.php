<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

use Illuminate\Support\Facades\Log;

class ResumeAnalysisService 
{

    public function extractResumeInformation(string $fileUrl){

        // Extract raw text from the resume pdf file (read pdf file, and get the text)
        $rawText = $this->extractTextFromPdf($fileUrl);

        Log::debug('successfully'.strlen($rawText).'Characters');   

        // Use Claude API to organize the text into a structured format
        // Output: summary, skills, experience, education -> JSON

        // Return the JSON object
        return [
                'summary'=>'',
                'skills'=>'',
                'experience'=>'',
                'education'=>'',
            ];

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