<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ $company->name }}
        </h2>
    </x-slot>   

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        @if (auth()->user()->role == 'admin')
        <!-- Back Button -->
         <div class="mb-6">
             <a href="{{ route('companies.index') }}" class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded-md">← Back</a>
            </div>
        @endif 
            
        <!-- Wrapper -->    
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
          <!-- Company Details -->
            <div>
                <h3 class="text-lg font-bold">Company Information</h3>
                <p><strong>Owner:</strong>{{ $company->owner->name }}</p>
                <p><strong>Owner:</strong>{{ $company->owner->email }}</p>
                <p><strong>Address:</strong>{{ $company->address }}</p>
                <p><strong>Industry:</strong>{{ $company->industry }}</p>
                <p><strong>Website:</strong><a href="{{ $company->website }}" 
                target="_blank" class="text-blue-500 hover:text-blue-700 ">{{ $company->website }}</a></p>

            </div>
            <!-- Edit and Archive Buttons -->
            <div class="flex justify-end space-x-4 mb-6">
                <!-- Edit Button -->
                 @if (auth()->user()->role == 'admin')
           <a href="{{ route('companies.edit',['company'=>$company->id,'redirectToList'=>'false']) }}" class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition duration-200">Edit</a>
           @else
           <a href="{{ route('my-company.edit') }}" class="inline-flex items-center px-6 py-2.5 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition duration-200">Edit</a>
            @endif

           @if (auth()->user()->role == 'admin')
              <!-- Delete Button -->
             <form action="{{ route('companies.destroy',$company->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition duration-200">Archive</button>
                                    </form> 
            @endif                                                                     
            </div>
            @if (auth()->user()->role == 'admin')
            <!-- Tabs Navigation -->
             <div class="mb-6">
                <ul class="flex space-x-4">
                    <li>
                        <a href="{{ route('companies.show',['company' => $company->id, 'tab'=>'jobs']) }}" class="px-4 py-2 text-gray-800 font-semibold
                        {{ request('tab') == 'jobs' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Jobs</a>
                    </li>
                    <li>
                        <a href="{{ route('companies.show',['company' => $company->id, 'tab'=>'applications']) }}" class="px-4 py-2 text-gray-800 font-semibold
                        {{ request('tab') == 'applications'  ? 'border-b-2 border-blue-500' : '' }}">Applications</a>
                    </li>
                </ul>
             </div>

            <!-- Tabs Content -->
             <div>
                <!-- Jobs Tab -->
                 <div id="jobs" class="{{ request('tab') == 'jobs' || request('tab') == '' ? 'block' : 'hidden' }}">
                    
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Title</th>
                                <th class="py-2 px-4 text-left bg-gray-100">Type</th>
                                <th class="py-2 px-4 text-left bg-gray-100">Location</th>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->jobVacancies as $job )
                                <tr>
                                    <td class="py-2 px-4">{{ $job->title }}</td>
                                    <td class="py-2 px-4">{{ $job->type }}</td>
                                    <td class="py-2 px-4">{{ $job->location }}</td>
                                    <td class="py-2 px-4">
                                        <a href="{{ route('job-vacancies.show',$job->id) }}" class="text-blue-500 hover:text-blue-700 underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                 </div>
                <!-- Applications Tab -->
                 <div id="applications" class="{{ request('tab') == 'applications'  ? 'block' : 'hidden' }}">
                    
                        <table class="min-w-full  bg-gray-50 rounded-lg shadow">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Application Name</th>
                                    <th class="py-2 px-4 text-left bg-gray-100">Job Title</th>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Status</th>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $company->jobApplications as $application )
                                    <tr>
                                        <td class="py-2 px-4">{{ $application->user->name }}</td>
                                        <td class="py-2 px-4">{{ $application->jobVacancy->title }}</td>
                                        <td class="py-2 px-4">{{ $application->status }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('job-applications.show', $application->id) }}" class="text-blue-500 hover:text-blue-700 underline">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                 </div>
             </div>

            </div>
            @endif 
            </div>
</x-app-layout>


