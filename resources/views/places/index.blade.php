@extends('layouts.geomir')

@section('header-content')
    @include('layouts.navigation')
@endsection

@section('box-content')
<div class="h-screen container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @php
        $cols = [
            "id",
            "name",
            "description",
            "file_id",
            "latitude",
            "longitude",
            "created_at",
            "updated_at"
        ];
    @endphp

    <!-- Results -->
    <div class="bg-red rounded-lg shadow overflow-x-auto">
        <x-table-index class="w-full whitespace-nowrap" :cols="$cols" :rows="$places" 
            :enableActions="true" parentRoute="places"
            :enableSearch="true" :search="$search" />
    </div>

    <!-- Buttons -->
    <div class="mt-8 flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-4">
        <x-primary-button href="{{ route('places.create') }}" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700">
            {{ __('Add new place') }}
        </x-primary-button>
        <x-secondary-button href="{{ route('dashboard') }}" class="w-full md:w-auto bg-gray-500 hover:bg-gray-700">
            {{ __('Back to dashboard') }}
        </x-secondary-button>
    </div>

</div>
@endsection