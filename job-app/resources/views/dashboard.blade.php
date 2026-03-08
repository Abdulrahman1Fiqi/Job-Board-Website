<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">

    <h3 class="text-white text-2xl font-bold mb-6">
        {{ __('Welcome back, ') }} {{ Auth::user()->name }}
    </h3>

    <!-- Search & Filters -->
    <div class="flex items-center justify-between">

        <!-- Search Bar -->
        <form action="" class="flex items-center justify-center w-1/4">
            <input type="text" class="w-full p-2 rounded-l-lg bg-gray-800 text-white" placeholder="Search for a job" >
            <button type="submit" class="bg-indigo-500 text-white p-2 rounded-r-lg border border-indigo-500">
                Search
            </button>
        </form>

        <!-- Filters -->
        <div class="flex space-x-2">
            <a class="bg-indigo-500 text-white p-2 rounded-lg">Full-Time</a>
            <a class="bg-indigo-500 text-white p-2 rounded-lg">Remote</a>
            <a class="bg-indigo-500 text-white p-2 rounded-lg">Hybrid</a>
            <a class="bg-indigo-500 text-white p-2 rounded-lg">Contract</a>
        </div>

    </div>

    <!-- Job List -->
     <div class="space-y-4 mt-6">
        @foreach ($jobs as $job )
        <!-- Job item -->
        <div class="border-b border-white/10 pb-4 flex justify-between items-center">
             <div>
                <a class="text-lg font-semibold text-blue-400 hover:underline">{{ $job->title }}</a>
                <p class="text-sm text-white">{{ $job->company->name }} - {{ $job->location }}</p>
                <p class="text-sm text-white">{{'$'. number_format($job-> salary) }} / Year</p>
             </div>
             <span class="bg-blue-500 text-white p-2 rounded-lg">{{ $job->type }}</span>
        </div>
        @endforeach
     </div>

        <div class="mt-6">
            {{ $jobs->links() }}
        </div>

    </div>
    </div>
</x-app-layout>
