@extends('layouts.box-app')

@section('box-title')
    {{ __('Reviews') }}
@endsection

@php
    $cols = [
        "id",
        "content",
        "created_at",
        "updated_at"
    ];
@endphp

@section('box-content')
    <!-- Results -->
    <x-table-index :cols=$cols :rows=$reviews 
        :enableActions=true parentRoute='reviews'
        :enableSearch=true :search=$search />
    <!-- Pagination -->
    <div class="mt-8">
        {{ $reviews->links() }}
    </div>
    <!-- Buttons -->
    <div class="mt-8">
        @can('create', App\Models\Review::class)
        <x-primary-button href="{{ route('reviews.create') }}">
            {{ __('Add new review') }}
        </x-primary-button>
        @endcan
        <x-secondary-button href="{{ route('dashboard') }}">
            {{ __('Back to dashboard') }}
        </x-secondary-button>
    </div>
@endsection
