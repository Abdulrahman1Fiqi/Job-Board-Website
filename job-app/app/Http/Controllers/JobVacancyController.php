<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;

use OpenAI\Laravel\Facades\OpenAI;

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

    public function testOpenAI(){
            $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => 'Hello!',
        ]);

        echo $response->outputText; // Hello! How can I assist you today?
    }
}
