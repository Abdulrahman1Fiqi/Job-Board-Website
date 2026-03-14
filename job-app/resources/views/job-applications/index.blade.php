<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

<!-- Validate Session -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif



</x-app-layout>