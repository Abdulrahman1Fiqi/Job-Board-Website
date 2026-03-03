<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ $jobApplication->user->name }} | Applied to {{ $jobApplication->jobVacancy->title }}
        </h2>
    </x-slot>   

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <!-- Back Button -->
         <div class="mb-6">
             <a href="{{ route('job-applications.index') }}" class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded-md">← Back</a>
            </div>
            
        <!-- Wrapper -->    
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
          <!-- Application Details -->
            <div>
                <h3 class="text-lg font-bold">Application Information</h3>
                <p><strong>Applicant:</strong>{{ $jobApplication->user->name }}</p>
                <p><strong>Job Vacancy:</strong>{{ $jobApplication->jobVacancy->title }}</p>
                <p><strong>Company:</strong>{{ $jobApplication->jobVacancy->company->name }}</p>
                <p><strong>Status:</strong>{{ $jobApplication->status }}</p>
                <p><strong>Resume:</strong><a href="{{ $jobApplication->resume->fileUrl}}" 
                target="_blank" class="text-blue-500 hover:text-blue-700 ">{{ $jobApplication->resume->fileUrl }}</a></p>

            </div>
            <!-- Edit and Archive Buttons -->
            <div class="flex justify-end space-x-4 mb-6">
                <!-- Edit Button -->
           <a href="{{ route('job-applications.edit',['job_application'=>$jobApplication->id,'redirectToList'=>'false']) }}" class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition duration-200">Edit</a>
              <!-- Delete Button -->
             <form action="{{ route('job-applications.destroy',$jobApplication->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition duration-200">Archive</button>
                                    </form>                                                                      
            </div>
            <!-- Tabs Navigation -->
             <div class="mb-6">
                <ul class="flex space-x-4">
                    <li>
                        <a href="{{ route('job-applications.show',['job_application' => $jobApplication->id, 'tab'=>'resume']) }}" class="px-4 py-2 text-gray-800 font-semibold
                        {{ request('tab') == 'resume' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Resume</a>
                    </li>
                    <li>
                        <a href="{{ route('job-applications.show',['job_application' => $jobApplication->id, 'tab'=>'AIFeedback']) }}" class="px-4 py-2 text-gray-800 font-semibold
                        {{ request('tab') == 'AIFeedback'  ? 'border-b-2 border-blue-500' : '' }}">AI Feedback</a>
                    </li>
                </ul>
             </div>

            <!-- Tabs Content -->
             <div>
                <!-- Resume Tab -->
                 <div id="resume" class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block' : 'hidden' }}">
                    
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Summary</th>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Skills</th>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Experience</th>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Education</th>
                                
                            </tr>
                        </thead>
                        <tbody>
    
                                <tr>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->summary }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->skills }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->experience }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->education }}</td>
                                    
                                     
                                </tr>
                          
                        </tbody>
                    </table>
                 </div>
                <!-- AI Feedback Tab -->
                 <div id="AIFeedback" class="{{ request('tab') == 'AIFeedback'  ? 'block' : 'hidden' }}">
                    
                        <table class="min-w-full  bg-gray-50 rounded-lg shadow">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">AI Score</th>
                                    <th class="py-2 px-4 text-left bg-gray-100">Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <tr>
                                        <td class="py-2 px-4">{{ $jobApplication->aiGeneratedScore }}</td>
                                        <td class="py-2 px-4">{{ $jobApplication->aiGeneratedFeedback }}</td>
                                    </tr>
                               
                            </tbody>
                        </table>
                 </div>
             </div>


            </div>
            </div>
</x-app-layout>


