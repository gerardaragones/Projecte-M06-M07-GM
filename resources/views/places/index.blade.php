<x-geomir-layout>

<x-slot:header>
    {{ __('Places') }}
</x-slot>

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
    <x-table-index :cols=$cols :rows=$places 
        :enableActions=true parentRoute='places'
        :enableSearch=true :search=$search />
    <!-- Pagination -->
    <div class="mt-8">
        {{ $places->links() }}
    </div>
    <!-- Buttons -->
    <div class="mt-8">
        @can('create', App\Models\Places::class)
            <x-primary-button href="{{ route('places.create') }}">
                {{ __('Add new place') }}
            </x-primary-button>
        @endcan
            <x-secondary-button href="{{ route('dashboard') }}">
                {{ __('Back to dashboard') }}
            </x-secondary-button>
    </div>
</x-geomir-layout>