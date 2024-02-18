@extends('layouts.box-app')

@section('box-title')
    {{ __('Edit Review') . " " . $review->id }}
@endsection

@section('box-content')
<x-columns columns=2>
    @section('column-1')
        <!-- Aquí puedes mostrar cualquier contenido relacionado con la reseña que deseas editar -->
        <p>{{ __('Review ID') }}: {{ $review->id }}</p>
        <!-- Puedes añadir más campos si es necesario -->
    @endsection
    @section('column-2')
        <form method="POST" action="{{ route('reviews.update', $review) }}">
            @csrf
            @method("PUT")
            <div>
                <x-input-label for="content" :value="__('Content')" />
                <x-textarea name="content" id="content" class="block mt-1 w-full">{{ $review->content }}</x-textarea>
            </div>
            <!-- Aquí puedes añadir más campos de edición si es necesario -->
            <div class="mt-8">
                <x-primary-button>
                    {{ __('Update') }}
                </x-primary-button>
                <x-secondary-button type="reset">
                    {{ __('Reset') }}
                </x-secondary-button>
                <x-secondary-button href="{{ route('reviews.index') }}">
                    {{ __('Back to list') }}
                </x-secondary-button>
            </div>
        </form>
    @endsection
</x-columns>
@endsection
