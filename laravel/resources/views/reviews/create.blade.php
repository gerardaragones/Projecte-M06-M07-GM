@extends('layouts.box-app')

@section('box-title')
    {{ __('Add review') }}
@endsection

@section('box-content')
    <form method="POST" action="{{ route('places.reviews.store', $place) }}">
        @csrf
        <div>
            <x-input-label for="content" :value="__('Content')" />
            <x-textarea name="content" id="content" class="block mt-1 w-full" :value="old('content')" />
        </div>
        <div class="mt-8">
            <x-primary-button>
                {{ __('Create') }}
            </x-primary-button>
            <x-secondary-button type="reset">
                {{ __('Reset') }}
            </x-secondary-button>
            <x-secondary-button href="{{ route('places.reviews.index', $place) }}">
                {{ __('Back to list') }}
            </x-secondary-button>
        </div>
    </form>
@endsection
